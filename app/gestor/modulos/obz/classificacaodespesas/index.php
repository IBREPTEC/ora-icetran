<?php
include('../classes/obz.classificacaodespesas.class.php');
include('config.php');
include('config.formulario.php');
include('config.listagem.php');

//Incluimos o arquivo com variaveis padrão do sistema.
include('idiomas/' . $config['idioma_padrao'] . '/idiomapadrao.php');

$linhaObj = new ClassificacaoDespesasOBZ();
$linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');
$linhaObj->Set('idusuario', $usuario['idusuario']);
$linhaObj->Set('monitora_onde', $config['monitoramento']['onde']);

if ($_POST['acao'] == 'salvar') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|2');

    $linhaObj->Set('post', $_POST);
    if ($_POST[$config['banco']['primaria']]){
        $salvar = $linhaObj->modificar();
    } else {
        $salvar = $linhaObj->cadastrar();
    }
    
    if ($salvar['sucesso']) {
        if ($_POST[$config['banco']['primaria']]) {
            $linhaObj->Set('pro_mensagem_idioma', 'modificar_sucesso');
            $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        } else {
            $linhaObj->Set('pro_mensagem_idioma', 'cadastrar_sucesso');
            $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2]);
        }
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|3');
    
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->remover();
    
    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2]);
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'salvar_regra') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|5');

    $_POST['idclassificacao'] = (int)$url[3];

    $linhaObj->Set('post', $_POST);
    $salvar = $linhaObj->cadastrarRegra();

    if ($salvar['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'cadastrar_regra_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        $linhaObj->Processando();
    }
} elseif ($_POST['acao'] == 'remover_regra') {
    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|6');
    
    $linhaObj->Set('post', $_POST);
    $remover = $linhaObj->removerRegra();
    
    if ($remover['sucesso']) {
        $linhaObj->Set('pro_mensagem_idioma', 'remover_regra_sucesso');
        $linhaObj->Set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4]);
        $linhaObj->Processando();
    }
}

if (isset($url[3])) {
    if($url[3] == 'cadastrar') {
        $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');

        include('idiomas/'.$config['idioma_padrao'].'/formulario.php');
        include('telas/'.$config['tela_padrao'].'/formulario.php');
        exit();
    } else {
        $linhaObj->Set('id',intval($url[3]));
        $linhaObj->Set('campos','*');   
        $linha = $linhaObj->retornar();

        if ($linha) {
            switch ($url[4]) {
                case 'editar':      
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|2');

                    include('idiomas/'.$config['idioma_padrao'].'/formulario.php');
                    include('telas/'.$config['tela_padrao'].'/formulario.php');
                    break;

                case 'remover':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|3');

                    include('idiomas/'.$config['idioma_padrao'].'/remover.php');
                    include('telas/'.$config['tela_padrao'].'/remover.php');
                    break;

                case 'regras':
                    $linhaObj->verificaPermissao($perfil['permissoes'], $url[2].'|4');

                    $config['banco'] = $config['banco_regras'];
                    $config['formulario'] = $config['formulario_regra'];
                    $linhaObj->Set('monitora_onde', $config['monitoramento_regra']['onde']);
                    $linhaObj->Set('config', $config);

                    $linhaObj->Set('campos','ocdr.idregra,
                                            e.nome_fantasia AS cfc,
                                            cc.nome AS centro_custo,
                                            c.nome AS categoria,
                                            cs.nome AS subcategoria');
                    $linhaObj->Set('ordem_campo', $config['banco']['primaria']);
                    $linhaObj->Set('ordem', 'desc');
                    $linhaObj->Set('limite', -1);
                    $regrasArray = $linhaObj->listarTodasRegras((int)$url[3]);

                    include('idiomas/'.$config['idioma_padrao'].'/regras.php');
                    include('telas/'.$config['tela_padrao'].'/regras.php');
                    break;

                case 'json':
                    include('telas/'.$config['tela_padrao'].'/json.php');
                    break;

                case 'opcoes':
                    include('idiomas/'.$config['idioma_padrao'].'/opcoes.php');
                    include('telas/'.$config['tela_padrao'].'/opcoes.php');
                    break;
                default:
                   header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2]);
                   exit();
            }
        } else {
           header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2]);
           exit();
        }
        
    }
} else {
    $linhaObj->Set('pagina',$_GET['pag']);
    $linhaObj->Set('campos','idclassificacao,
                            nome,
                            ativo_painel,
                            data_cad');
    $linhaObj->Set('ordem_campo',($_GET['cmp']) ? $_GET['cmp'] : $config['banco']['primaria']);
    $linhaObj->Set('ordem',($_GET['ord']) ? $_GET['ord'] : 'desc');
    $linhaObj->Set('limite',($_GET['qtd']) ? $_GET['qtd'] : 30);
    $dadosArray = $linhaObj->listarTodas();

    include('idiomas/'.$config['idioma_padrao'].'/index.php');
    include('telas/'.$config['tela_padrao'].'/index.php');
}