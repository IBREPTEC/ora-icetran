<?php
if ((int) $url[6]) {
    $objeto = $matriculaObj->retornarObjetoDaRotaDeAprendizagem($ava['idava'], (int) $url[6]);
    $downloadsEbooksFeitos = $matriculaObj->verificarTodosDownloadsEbooksFeitos($matricula['idmatricula'], $ava['idava']);
    $idAvas = $matriculaObj->retornaIdAvas($url[3]);
    $ultimoConteudoRota = $matriculaObj->ultimoConteudoRotaAprendizagem($ava['idava']);

    if ($objeto['idobjeto']) {

        if ($ava['pre_requisito'] == 'S' && $objeto['objeto_anterior']['idobjeto']) {
            $anteriorContabilizado = $matriculaObj->verificaContabilizado($ava['idava'], 'objeto', $objeto['objeto_anterior']['idobjeto']);

            if (!$anteriorContabilizado) {
                header('Location: /'.$url[0].'/academico/curso/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] - 1));
                exit;
            }
        }

        ## Pré requisito do reconhecimento
        $rotaDeAprendizagem = $matriculaObj->retornarRotaDeAprendizagem($ava['idava']);
        $preRequisitoReconhecimento = $conteudoObj->verificaPreRequisito($rotaDeAprendizagem, $url[4], $url[6]);

        if(!$preRequisitoReconhecimento['sucesso'] && !empty($preRequisitoReconhecimento['ordem']) && $preRequisitoReconhecimento['ordem'] < ($url[6])){
            header('Location: /' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($preRequisitoReconhecimento['ordem']) . '#conteudo');
            exit;
        } elseif($preRequisitoReconhecimento['sucesso'] && $preRequisitoReconhecimento['ordem'] == ($url[6] + 1)) {
            header('Location: /' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo');
            exit;
        }

        ## /Pré requisito do reconhecimento

        ## Pré requisito dos links e ações
        $preRequisitoLinkAcao = $conteudoObj->verificaNaoClicadosUrl($rotaDeAprendizagem, $url[4], $url[6], $url[3]);

        if(!$preRequisitoLinkAcao['sucesso'] && !empty($preRequisitoLinkAcao['ordem']) && $preRequisitoLinkAcao['redirecionar'] != $url[6]){
            header('Location: /' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($preRequisitoLinkAcao['redirecionar']) . '#conteudo');
            exit;
        }

        ## /Pré requisito dos links e ações

        ## Pré requisito dos dias
        $preRequisitoDias = $conteudoObj->verificarDias($rotaDeAprendizagem, $url[4], $url[6], $matricula);

        if(!$preRequisitoDias['sucesso'] && !empty($preRequisitoDias['ordem']) && $preRequisitoDias['ordem'] != $url[6]){
            header('Location: /' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($preRequisitoDias['ordem']) . '#conteudo');
            exit;
        }

        ## /Pré requisito dos dias

        ## Pre requisito
        $preRequisito = $matriculaObj->verificaTodosPreRequisito($ava['idava'], $objeto['ordem']);
        if(!$preRequisito) {
            header('Location: /'.$url[0].'/academico/curso/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] - 1));
            exit;
        } elseif($objeto['objeto_proximo']['idobjeto_pre_requisito']) {
            $preRequisito = $matriculaObj->verificaPreRequisito($objeto['objeto_proximo']['idobjeto_pre_requisito']);
        }

        ## /Pre requisito

        ## Verificando se ele fez um simulado
        $simuladosRealizados = count($matriculaObj->retornarSimuladosRealizadosPorAva($ava['idava']));
        $simuladoObj = new Simulados();
        $simuladoObj->set('campos', '*');
        $simuladoObj->set('idava', $ava['idava']);
        $simulados = count($simuladoObj->ListarTodasSimulado());
        if($ultimoConteudoRota == $objeto['idobjeto'] && $simulados > 0 && $simuladosRealizados == 0) {
            header('Location: /' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] - 1) . '?alert=verificarSimulados#conteudo');
            exit;
        }
        ## /Verificando se ele fez um simulado

        ## Informações para a lib topo_curso
        $informacoesTopoCurso['link_pagina_anterior'] = '/'.$url[0].'/academico/curso/'.$matricula['idmatricula'].'/'.$ava['idava'];
        $informacoesTopoCurso['pagina_anterior'] = 'rota_aprendezagem';
        $informacoesTopoCurso['pagina'] = 'conteudo';
        ## /Informações para a lib topo_curso

        ## Verifica se foi contabilizado
        $contabilizado = $matriculaObj->verificaContabilizado($ava['idava'], 'objeto', $objeto['idobjeto']);
        ## /Verifica se foi contabilizado

        ## Verifica se foi favoritado
        $favorito = $matriculaObj->verificaFavorito($ava['idava'], $objeto['idobjeto']);
        ## /Verifica se foi favoritado

        ## Anotações
        $anotacoes = $matriculaObj->retornarAnotacoes($ava['idava'], $objeto['idobjeto']);
        ## /Anotações

        ## Contabilizando porcentagem ava se o mesmo tiver tempo 0
        if($objeto["tempo"] === '00:00:00' or $objeto["tempo"] === null){
            $post=array();
            $post['matricula'] = $matricula['idmatricula'];
            $post['ava'] = $ava['idava'];
            $post['objeto'] = $objeto['idobjeto'];
            $post['idmatricula'] = senhaSegura($matricula['idmatricula'],$config['chaveLogin']);
            $post['idava'] = senhaSegura($ava['idava'],$config['chaveLogin']);
            $post['idobjeto'] = senhaSegura($objeto['idobjeto'],$config['chaveLogin']);
            $matriculaObj->set('post', $post);
            $ok = $matriculaObj->contabilizarRota();
            $contabilizado = true;
        }
        ## /Contabilizando porcentagem ava se o mesmo tiver tempo 0
        switch ($objeto['tipo']) {
            case 'conteudo':
                if($objeto['objeto_anterior']['idobjeto']) {
                    $variaveisConteudo["linkAnterior"] = '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] - 1);
                }
                if($objeto['objeto_proximo']['idobjeto']) {
                    if ($preRequisito) { $variaveisConteudo["linkProximo"] = '/'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.($url[6] + 1); } else { $variaveisConteudo["linkProximo"] = 'javascript:preRequisito();'; }
                }
                $objeto['objeto']['conteudo'] = $matriculaObj->substituiVariaveisConteudo($objeto['objeto']['conteudo'], $variaveisConteudo);
                $filtros = new FiltrarVariaveis($objeto['objeto']['conteudo']);
                $filtros->registrarFiltro(new Video);
                $objeto['objeto']['conteudo'] = $filtros->aplicar();
                $filtrosLinksAcoes = new LinkAcao();
                $objeto['objeto']['conteudo'] = $filtrosLinksAcoes->renderizar($objeto['objeto']);
                ## Cadastra historico do aluno
                $matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', "objeto_rota", $objeto['idobjeto']);
                ## /Cadastra historico do aluno
                if(intval($objeto['gerar_data_final']) === 1)
                    $matriculaObj->mudarDataFim($ava['idava']);
                break;
            case 'exercicio':
                if($_POST['acao'] == 'salvar_exercicio') {
                    $matriculaObj->set('post',$_POST);
                    $salvar = $matriculaObj->salvarExercicio();

                    if($salvar['sucesso']){
                        $matriculaObj->set('id', (int) $matricula['idmatricula']);
                        $matriculaObj->cadastrarHistorioAluno($ava['idava'], 'respondeu', 'exercicio', $_POST['idmatricula_exercicio']);
                        $matriculaObj->set('url','/'.$url[0].'/academico/curso/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.$url[6].'/resultado/'.$salvar['id']);
                        $matriculaObj->Processando();
                        if(intval($objeto['gerar_data_final']) === 1)
                            $matriculaObj->mudarDataFim($ava['idava']);
                    }
                } elseif($url[7] == 'resultado' && (int) $url[8]) {
                    $exercicio = $matriculaObj->retornarMatriculaExercicio((int) $url[8]);
                    $exercicio['acao'] = 'retornar';
                } else {
                    $refazer = false;
                    if($url[7] == 'refazer')
                        $refazer = true;

                    $exercicio = $matriculaObj->gerarRefazerRetornarExercicio($objeto['idexercicio'], $refazer);
                }
            break;
            case 'enquete':
                if($_POST['acao'] == 'votar_enquete') {
                    $matriculaObj->set("post",$_POST);
                    $salvar = $matriculaObj->votarEnquete($ava['idava']);

                    if($salvar["sucesso"]){
                        $matriculaObj->set('id', (int) $matricula['idmatricula']);
                        $matriculaObj->cadastrarHistorioAluno($ava['idava'], 'respondeu', 'enquete', $objeto['objeto']['idenquete']);
                        $matriculaObj->set('url','/'.$url[0].'/academico/curso/'.$matricula['idmatricula'].'/'.$ava['idava'].'/rota/'.$url[6]);
                        $matriculaObj->Processando();
                        if(intval($objeto['gerar_data_final']) === 1)
                            $matriculaObj->mudarDataFim($ava['idava']);
                    }
                } else {
                    $enquete = $matriculaObj->retornaOpcoesVerificaVotoEnquete($objeto['objeto']['idenquete'], $ava['idava']);
                }
            break;
            case 'video':
                require_once('../classes/videoteca/videoteca.pastas.class.php');
                $videotecaPastas = new VideotecaPastas(new Core);
                $caminho = $videotecaPastas->getPathNameById($objeto['objeto']['idpasta']);
                $objeto['objeto']['conteudo'] = $objeto['objeto']['descricao'];
                if(intval($objeto['gerar_data_final']) === 1)
                    $matriculaObj->mudarDataFim($ava['idava']);
            break;
        }

        require 'idiomas/'.$config['idioma_padrao'].'/rota.objeto.php';
        require 'telas/'.$config['tela_padrao'].'/rota.objeto.php';
        exit;
    } else {
        header('Location: /'.$url[0].'/academico/curso/'.$matricula['idmatricula'].'/'.$ava['idava']);
        exit;
    }
} else {
    header('Location: /'.$url[0].'/academico/curso/'.$matricula['idmatricula'].'/'.$ava['idava']);
    exit;
}
