<?php
include 'config.php';
include 'classe.class.php';
include '../classes/relatorios.class.php';

$relatoriosObj = new Relatorios();
$relatoriosObj->set("idusuario",$usuario["idusuario"]);

$relatorioObj = new Relatorio();
$relatorioObj->set('idusuario', $usuario['idusuario'])
    ->set('monitora_onde', 1);

if($_POST['acao'] == 'salvar_relatorio') {
    $salvar = $relatoriosObj->set('post', $_POST)
        ->salvarRelatorio();

    if($salvar['sucesso']){
        $mensagem_sucesso = 'salvar_relatorio_sucesso';
    } else {
        $mensagem_erro = $salvar['erro_texto'];
    }
}

$config['situacoesArray'] = array();
$sql = "SELECT idsituacao, nome, cor_nome, cor_bg FROM matriculas_workflow WHERE ativo='S'";
$seleciona = mysql_query($sql);
while($situacao = mysql_fetch_assoc($seleciona)) {
   $config['situacoesArray'][$situacao['idsituacao']] = $situacao;
}
    $relatorioObj->set('config', $config);
    $relatorioObj->verificaPermissao($perfil['permissoes'], $url[2].'|1');

    if ($url[3] == 'html' || $url[3] == 'xls') {
        $relatorioObj->set('pagina', 1)
            ->set('ordem', 'DESC')
            ->set('limite', -1)
            ->set('ordem_campo', 'ma.idmatricula')
            ->set('campos', 'ma.idmatricula,
                            pe.nome AS cliente,
                            pe.documento,
                            sb.nome AS solicitante,
                            ve.nome AS vendedor,
                            IF(ma.porcentagem_manual > ma.porcentagem, ma.porcentagem_manual, ma.porcentagem) as porcentagem,
                            frdm.data_cad AS data_conclusao,
                            (
                                SELECT
                                    MAX(mn.nota)
                                FROM
                                    matriculas_notas mn
                                WHERE
                                    mn.idmatricula = ma.idmatricula AND
                                    mn.ativo = "S"
                            ) AS maior_nota,
                            (
                                SELECT
                                    IF(MAX(mn.nota) >= cu.media, "Aprovado", "Reprovado")
                                FROM
                                    matriculas mat
                                    INNER JOIN matriculas_notas mn ON (mn.idmatricula = mat.idmatricula)
                                    INNER JOIN ofertas_cursos_escolas ocp on (ocp.idoferta = mat.idoferta AND ocp.idcurso = mat.idcurso AND ocp.idescola = mat.idescola)
                                    INNER JOIN curriculos cu ON (cu.idcurriculo = ocp.idcurriculo)
                                WHERE
                                    mat.idmatricula = ma.idmatricula AND
                                    mn.ativo = "S"
                            ) AS situacao_nota'
        );

        $dadosArray = $relatorioObj->gerarRelatorio();

        // Fazemos os selects para poupar
        $sql = " SELECT idsituacao FROM matriculas_workflow WHERE ativo = 'S' and fim = 'S' ";
        $wf_vendida = $relatorioObj->retornarLinha($sql);

        // Buscamos qual é o workflow que não é inativa e cancelada.
        $sql = " SELECT idsituacao FROM matriculas_workflow WHERE ativo = 'S' and fim <> 'S' and inativa <> 'S' and cancelada <> 'S' ";
        $seleciona = mysql_query($sql);
        $status = array();
        while($linha = mysql_fetch_assoc($seleciona)){
            $wf_statusLiberados[] = $linha["idsituacao"];
        }
    }

$linha["de"] = date("d/m/Y", mktime(0, 0, 0, date("m"), date("d")-7, date("Y")));
$linha["ate"] = date("d/m/Y", mktime(0, 0, 0, date("m"), date("d"), date("Y")));


switch ($url[3]) {
    case "ajax_cursos":
        if ($_REQUEST['idoferta']) {
            $relatorioObj->set("id",intval($_REQUEST['idoferta']));
            $relatorioObj->RetornarCursosOferta();
            exit();
        }
        break;
	case "ajax_cidades":
		($_REQUEST['idestado'])
		 ?
			$relatorioObj->RetornarJSON("cidades", mysql_real_escape_string($_REQUEST['idestado']), "idestado", "idcidade, nome", "ORDER BY nome")
		 :
			$relatorioObj->RetornarJSON("cidades", $url[5], "idestado", "idcidade, nome", "ORDER BY nome");
		exit();
		break;
    case "ajax_solicitantes":
        if ($_REQUEST['idbolsa'] && $_REQUEST['idbolsa'] != 'N') {
            $relatorioObj->RetornarSolicitantesBolsas();
            exit();
        } else {
            $vazio = array();
            echo json_encode($vazio);
            exit;
        }
        break;
    case "ajax_vendedores":
        ($_REQUEST['idsindicato'])
         ?
            $relatorioObj->RetornarVendedoresSindicato(mysql_real_escape_string($_REQUEST['idsindicato']))
         :
            $relatorioObj->RetornarVendedoresSindicato((int)$url[5]);
        exit();
        break;
    case "html":
        $relatoriosObj->atualiza_visualizacao_relatorio();
        include("idiomas/".$config["idioma_padrao"]."/html.php");
        include("telas/".$config["tela_padrao"]."/html.php");
        break;
    case "xls":
        include("idiomas/".$config["idioma_padrao"]."/xls.php");
        include("telas/".$config["tela_padrao"]."/xls.php");
        break;
    default:
        include("idiomas/".$config["idioma_padrao"]."/index.php");
        include("telas/".$config["tela_padrao"]."/index.php");
}