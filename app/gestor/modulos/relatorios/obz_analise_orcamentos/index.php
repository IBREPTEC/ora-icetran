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
    $relatorioObj->set('campos', 'i.nome AS sindicato,
                                e.nome_fantasia AS cfc,
                                cc.nome AS centro_custo,
                                i.idsindicato,
                                cc.idcentro_custo,
                                c.idcategoria,
                                cs.idsubcategoria,
                                uar.nome AS responsavel,
                                uad.nome AS dono,
                                c.nome AS categoria,
                                cs.nome AS subcategoria,
                                oog.descricao,
                                oog.memorial,
                                ('.$stringQuery["orcado"].') AS ano_atual_orcado
                                ');
    $relatorioObj->set('ordem_campo', 'e.nome_fantasia ASC,
                                        cc.nome ASC,
                                        c.nome ASC,
                                        cs.nome');
    $relatorioObj->set('ordem', 'ASC');
    $relatorioObj->set('limite', -1);
    
    $dadosArray = $relatorioObj->gerarRelatorio();

    if(count($dadosArray)){
        $dadosArray["ano"] = $arrayDatas["ano"]; 
    }

    //print_r2($dadosArray);exit;
}

switch ($url[3]) {
    case "html":
        $relatoriosObj->atualiza_visualizacao_relatorio();
        include("idiomas/".$config["idioma_padrao"]."/html.php");
        include("telas/".$config["tela_padrao"]."/html.php");
        break;

    case "xls":
        include("idiomas/".$config["idioma_padrao"]."/xls.php");
        include("telas/".$config["tela_padrao"]."/xls.php");
        break;

    case "pdf":
        include DIR_APP . "/assets/plugins/MPDF54/mpdf.php";

        $marginLeft = $marginRight = $marginHeader = $marginFooter = 1;
        $mpdf = new mPDF('P', 'A4', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');

        ob_start();

        include 'idiomas/' . $config['idioma_padrao'] . '/html.php';
        include 'telas/' . $config['tela_padrao'] . '/html.php';

        $saida = ob_get_contents();
        ob_end_clean();

        $pastaRelatorios = DIR_APP . "/storage/relatorios_gerenciais";

        $nome_arquivo = $url[1]."_".$url[2]."_".time().".pdf";
        $arquivo_nome = "../storage/temp/" . $nome_arquivo;

        $mpdf->simpleTables = true;
        $mpdf->packTableData = true;
        set_time_limit(120);
        $mpdf->WriteHTML($saida);

        $mpdf->Output($arquivo_nome);


        if ($url[4] == 'enviar_email') { 
            $relatorioObj->EnviarRelatorio($_SERVER['DOCUMENT_ROOT'] . '/storage/temp/' . $nome_arquivo);
            $relatorioObj->Set('pro_mensagem_idioma','email_sucesso');
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

    default:
        include("idiomas/".$config["idioma_padrao"]."/index.php");
        include("telas/".$config["tela_padrao"]."/index.php");
}
