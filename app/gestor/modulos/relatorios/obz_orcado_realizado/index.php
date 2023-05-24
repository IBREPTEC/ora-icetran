<?php
include 'config.php';
include 'classe.class.php';
include '../classes/relatorios.class.php';
$relatoriosObj = new Relatorios();
$relatoriosObj->Set("idusuario",$usuario["idusuario"]);
$relatorioObj = new Relatorio();
$relatorioObj->Set("idusuario",$usuario["idusuario"]);
$relatorioObj->Set("monitora_onde",1);
$relatorioObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
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
    $dadosArray = $relatorioObj->gerarRelatorio();
}

switch ($url[3]) {
    case "html":
    if($dadosArray["quantidadeLinhas"]){
        $dadosGrafico2 = $relatorioObj->gerarDadosRealizadoGrafico();
        if($dadosGrafico2["string_categoria"] && $dadosGrafico2["string_subcategoria"]){
            $dados = $relatorioObj->gerarDadosContas($dadosGrafico2["total_orcado"], $dadosGrafico2["string_categoria"], $dadosGrafico2["string_subcategoria"], $dadosGrafico2["stringConta"]);
        }else{
           $dados = $dadosGrafico2["total_orcado"];
        }
    }

    $pastaRelatorios = $_SERVER['DOCUMENT_ROOT']."/storage/relatorios_obz";
    if (!is_dir($pastaRelatorios))
        mkdir($pastaRelatorios, 0777);
    $raiz = "../storage/relatorios_obz";
    apagar_recursividade($raiz, $raiz);
    include 'telas/' . $config['tela_padrao'] . '/graficos_faturamento.php';
    if($dadosArray["quantidadeLinhas"]){
        gerarGraficoOrcadoRealizado1($dados);
        gerarGraficoOrcadoRealizado2($dadosGrafico2);
    }
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
