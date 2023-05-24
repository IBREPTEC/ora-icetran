<?php
include('config.php');
include('config.formulario.php');
include('config.listagem.php');
include("../classes/obz.subcategorias.class.php");

// Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/" . $config["idioma_padrao"] . "/idiomapadrao.php");

$linhaObj  = new Categorias();
$linhaObj2 = new SubCategoriasOBZ();

$linhaObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1');

$linhaObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', $config['monitoramento']['onde']);

// Rotina para salvar uma categoria ou subcategoria

    if ('salvar_subcategoria' == $_POST['acao']) {

        $modificar_sucesso = 'modificar_sucesso_subcategoria';
        $cadastrar_sucesso = 'cadastrar_sucesso_subcategoria';

        $config['banco'] = $config['banco_subcategoria'];

        $linhaObj->config['banco'] = $config['banco_subcategoria'];
        $linhaObj->config['formulario'] = $config['formulario_subcategoria'];
        
        $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");

        $url_redireciona = "/{$url[0]}/{$url[1]}/{$url[2]}";

        if ($_POST['acao_url']) {
            $url_redireciona .= '?' . base64_decode($_POST['acao_url']);
        }

        $linhaObj->set('post', $_POST);


        if ($_POST[$config["banco"]["primaria"]] && $linhaObj2->verificaExistencia($url[3])){
            $salvar = $linhaObj->Modificar();
        }else{
            unset($linhaObj->config["banco"]["primaria"]);
            $salvar = $linhaObj->Cadastrar();
        }
        
        if ($salvar['sucesso']) {
        if ($_POST[$config['banco']['primaria']]) {
            $linhaObj->set('pro_mensagem_idioma', ($_POST[$config['banco']['primaria']]) ? $modificar_sucesso : $cadastrar_sucesso);
        }
        $linhaObj->set('url', $url_redireciona)->processando();
        }
    }

    if ('salvar_centros' == $_POST['acao']) {
        //print_r2($_POST);exit;
        
        foreach($_POST['idcentro_custo'] as $centro){
            $sucesso = $linhaObj2->InsereSubCentro($url[3],$centro, $_POST['idsindicato']);
        }
        
        $url_redireciona = "/{$url[0]}/{$url[1]}/{$url[2]}/{$url[3]}/{$url[4]}";
        $mensagem_sucesso = 'mensagem_sucesso';
        
        if($sucesso){
            //echo "deu ruim";exit;
            $linhaObj->set('pro_mensagem_idioma',$mensagem_sucesso);
            $linhaObj->set('url', $url_redireciona)->processando();
        }
    }else if ('remover_centro' == $_POST['acao']) {
        //print_r2($_POST);exit;
        $sucesso = $linhaObj2->RemoveSubCentro($_POST['remover']);
        
        if($sucesso = true){
            //echo "deu ruim";exit;
            $url_redireciona = "/{$url[0]}/{$url[1]}/{$url[2]}/{$url[3]}/{$url[4]}";
            $mensagem_sucesso = 'remover_sucesso';
            $linhaObj->set('pro_mensagem_idioma',$mensagem_sucesso);
            $linhaObj->set('url', $url_redireciona)->processando();
        }
    }
    
if (isset($url[3])) {

   
        $linhaObj->Set("id", (int)$url[3]);
        $linhaObj2->Set("id", (int)$url[3]);

        $linhaObj->Set("campos", "c.nome as categoria, cs.*");
        $linha = $linhaObj->RetornarSubcategoria();
        
        //print_r2($linha);exit;

        if ($linha) {

            switch ($url[4]) {
                
                case "editarsubcategoria":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2");
                    $config["banco"] = $config["banco_subcategoria"];
                    $linhaObj->config["banco"] = $config["banco_subcategoria"];
                    $linhaCat = $linhaObj->RetornarSubcategoria();
                    
                    $linhaObj2->Set("campos", " * ");



                    $linha = $linhaObj2->Retornar();
                    
                    //print_r2($linha);exit;
                    
                    include("idiomas/" . $config["idioma_padrao"] . "/formulario.subcategoria.php");
                    include("telas/" . $config["tela_padrao"] . "/formulario.subcategoria.php");
                    break;
                
				case "json":
					include("idiomas/" . $config["idioma_padrao"] . "/json.php");
					include("telas/" . $config["tela_padrao"] . "/json.php");
					break;
                
                case "opcoessubcategoria":
                    //print_r2($linha);exit;
                    include("idiomas/" . $config["idioma_padrao"] . "/opcoes.subcategoria.php");
                    include("telas/" . $config["tela_padrao"] . "/opcoes.subcategoria.php");
                    break;
                
                case "centro_custo":
                    $linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3");
                    $Sindicatos = $linhaObj2->retornarInstituicoes($url[3]);



                   foreach($Sindicatos as $sindicato){

                        $CentroDeCustos[] = $linhaObj2->RetornaSubCentroInstituicao($url[3],$sindicato['idsindicato']);

                   }


                    include("idiomas/" . $config["idioma_padrao"] . "/centro_custo.php");
                    include("telas/" . $config["tela_padrao"] . "/centro_custo.php");
                    break;
                
                case 'associar_subcategoria':
 					$linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4");
                    $assocIterator = $linhaObj->retornarAssociacoes(Request::url(4));

                    $content = '';
                    foreach ($assocIterator as $associacao) {
                        $content .= print_r($associacao, 1);
                    }
					$linhaObj->Set("id", intval($url[3]));
                    $linhaObj->Set("campos", "*");
                    $sindicatos = $linhaObj->ListarSindicatosAssociados();
                    require 'idiomas/' . $config['idioma_padrao'] . '/associar.php';
                    require 'telas/' . $config['tela_padrao'] . '/associar.php';
                    break;
                default:
                    header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
                    exit();
            }
        } else {
            header("Location: /" . $url[0] . "/" . $url[1] . "/" . $url[2]);
            exit();
        }
 }
 else {
    $linhaObj->Set("pagina", $_GET["pag"]);
    if (!$_GET["ordem"]) {
        $_GET["ordem"] = "ASC";
    }
    $linhaObj->Set("ordem", $_GET["ord"]);
    if (!$_GET["qtd"]) $_GET["qtd"] = 30;
    $linhaObj->Set("limite", intval($_GET["qtd"]));
    if (!$_GET["cmp"]) $_GET["cmp"] = "categoria ASC, subcategoria ASC, idsubcategoria";
    $linhaObj->Set("ordem_campo", $_GET["cmp"]);
    $linhaObj->Set("campos", "c.idcategoria, c.nome as categoria, c.idcategoria AS idsubcategoria, '- -' AS subcategoria, c.ativo_painel, c.data_cad, 'C' AS tipo");
    $linhaObj->Set("campos_2", "c.idcategoria, c.nome as categoria, cs.idsubcategoria, cs.nome AS subcategoria, cs.ativo_painel, cs.data_cad, 'S' AS tipo");
    $dadosArray = $linhaObj->ListarTodas();
    include("idiomas/" . $config["idioma_padrao"] . "/index.php");
    include("telas/" . $config["tela_padrao"] . "/index.php");
}