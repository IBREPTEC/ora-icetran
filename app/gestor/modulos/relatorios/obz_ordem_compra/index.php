<?php
include 'config.php';
include 'classe.class.php';
include '../classes/relatorios.class.php';

$relatoriosObj = new Relatorios();
$relatoriosObj->set("idusuario",$usuario["idusuario"]);

$relatorioObj = new Relatorio();
$relatorioObj->set('idusuario', $usuario['idusuario']);
$relatorioObj->set('monitora_onde', 1);

if($_POST['acao'] == 'salvar_relatorio') {
    $salvar = $relatoriosObj->set('post', $_POST)
        ->salvarRelatorio();

    if($salvar['sucesso']){
        $mensagem_sucesso = 'salvar_relatorio_sucesso';
    } else {
        $mensagem_erro = $salvar['erro_texto'];
    }
}


$relatorioObj->set('config', $config);
$relatorioObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');

if ($url[3] == 'html' || $url[3] == 'xls' || $url[3] == 'pdf') {
    $arrayDatas = $relatorioObj->retornaDatas();
    $stringQuery = $relatorioObj->montarQueryFiltroData($arrayDatas["primeiraData"], $arrayDatas["ultimaData"]);
    $relatorioObj->set('campos', 'i.nome_abreviado AS sindicato,
                                e.nome_fantasia AS cfc,
                                cc.nome AS centro_custo,
                                i.idsindicato,
                                cc.idcentro_custo,
                                c.idcategoria,
                                cs.idsubcategoria,
                                oow.idsituacao,
                                oow.nome AS situacao,
                                c.nome AS categoria,
                                cs.nome AS subcategoria,
                                ooc.nome AS ordem_compra,
                                ooc.idordemdecompra,
                                ooc.descricao,
                                ooc.valor,
                                (SELECT idarquivo FROM obz_ordem_compra_arquivos WHERE idordemdecompra = ooc.idordemdecompra AND ativo = "S" LIMIT 1) AS anexo
                                ');
    $relatorioObj->set('ordem_campo', 'e.nome_fantasia ASC,
                                        cc.nome ASC,
                                        c.nome ASC,
                                        cs.nome');
    $relatorioObj->set('ordem', 'ASC');
    $relatorioObj->set('limite', -1);
    
    $dadosArray = $relatorioObj->gerarRelatorio();

    //print_r2($dadosArray);exit;
}

switch ($url[3]) {
    case "html":
        //$relatoriosObj->atualiza_visualizacao_relatorio();
        include("idiomas/".$config["idioma_padrao"]."/html.php");
        include("telas/".$config["tela_padrao"]."/html.php");
        break;

    case "xls":
        include("idiomas/".$config["idioma_padrao"]."/xls.php");
        include("telas/".$config["tela_padrao"]."/xls.php");
    break;
    case "enviar_email":
        include("idiomas/".$config["idioma_padrao"]."/enviar_email.php");
        include("telas/".$config["tela_padrao"]."/enviar_email.php");
    break;

    case "pdf":
        set_time_limit(120);
        include DIR_APP . "/assets/plugins/MPDF54/mpdf.php";
        $mpdf = new Mpdf([
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'margin_top' => 1,
            'margin_bottom' => 1,
            'margin_header' => 0,
            'margin_footer' => 0,
            'orientation' => 'P'
        ]);
        ob_start();

        include("idiomas/".$config["idioma_padrao"]."/html.php");
        include("telas/".$config["tela_padrao"]."/pdf.php");

        $saida = ob_get_contents();
        ob_end_clean();

        $nome_arquivo = $url[1]."_".$url[2]."_".time().".pdf";
        $arquivo_nome = "../storage/temp/" . $nome_arquivo;
        //$mpdf->ignore_invalid_utf8 = true;
        //$mpdf->simpleTables = true;
        $mpdf->WriteHTML($saida);
        $mpdf->Output($arquivo_nome, "F");
        if ($url[4] == 'enviar_email') {
            $arrayEmails = array_unique($_POST["emails"], SORT_REGULAR);
            $enviou = false;
            foreach($arrayEmails as $email){
                $nome = $relatorioObj->retornarNomeUsuario($email);
                $relatorioObj->Set("nome_usuario", $nome);
                $relatorioObj->Set("email_usuario", $email);
                $enviou = $relatorioObj->EnviarRelatorio($_SERVER['DOCUMENT_ROOT'] . '/storage/temp/' . $nome_arquivo);
            }
            if($enviou){
                $relatorioObj->Set('pro_mensagem_idioma','email_sucesso');
            }else{
                $relatorioObj->Set('pro_mensagem_idioma','email_falha');
            }
            $relatorioObj->Set('url','/'.$url[0].'/'.$url[1].'/'.$url[2].'/html?'.$_SERVER['QUERY_STRING']);
            $relatorioObj->Processando();
        } else {
            header('Content-type: application/save');
            header('Content-Disposition: attachment; filename="' . basename($arquivo_nome) . '"');
            header('Content-Length: ' . filesize($arquivo_nome));
            header('Expires: 0');
            header('Pragma: no-cache');
            readfile($arquivo_nome);
            exit;
        }
        break;
    case "ajax_polos":
        if ($_REQUEST['idsindicato']) {
            echo $relatorioObj->listarPolosJson($_REQUEST['idsindicato']);
        } else {
            echo $relatorioObj->listarPolosJson($url[4]); 
        }
        break;

    case "ajax_centro_custos":
        if ($_REQUEST['idsindicato']) {
            echo $relatorioObj->listarCentrosCustoJson($_REQUEST['idsindicato']);
        } else {
            echo $relatorioObj->listarCentrosCustoJson($url[4]); 
        }
        break;
    case "ajax_categorias":
        if ($_REQUEST['idsindicato'] && $_REQUEST['idcentro_custo']) {
            //echo "opa perai caceta";exit;
            echo $relatorioObj->listarCategoriasJson($_REQUEST['idsindicato'] , $_REQUEST['idcentro_custo']);
        } 
        break;
    case "ajax_subcategorias":
        if ($_REQUEST['idcategoria'] && $_REQUEST['idsindicato'] && $_REQUEST['idcentro_custo']) {
            echo $relatorioObj->listarSubcategoriasJson($_REQUEST['idsindicato'] , $_REQUEST['idcentro_custo'] , $_REQUEST['idcategoria']);
        } 
        break;
    case "buscar_usuario":
        if ($_GET["tag"]) {
            $relatorioObj->Set("get", $_GET["tag"]);
            echo $relatorioObj->buscarUsuarios();
        }
        break;

    default:
        include("idiomas/".$config["idioma_padrao"]."/index.php");
        include("telas/".$config["tela_padrao"]."/index.php");
}
