<?php
include '../classes/contas.class.php';
include 'config.php';
include 'config.listagem.php';

//Incluimos o arquivo com variaveis padrão do sistema.
include 'idiomas/' . $config['idioma_padrao'] . '/idiomapadrao.php';

$linhaObj = new Contas();

$linhaObj->set('idescola', $usuario['idescola']);
$linhaObj->set('modulo',  $url[0]);
$linhaObj->set('monitora_onde', $config['monitoramento']['onde']);

if ($_POST['acao'] == 'capturar_pagarme') {
    require_once '../classes/pagarme.class.php';
    $pagarmeObj = new PagarmeObj();
    $salvar = $pagarmeObj->set('post', $_POST)
        ->set('idescola', $usuario['idescola'])
        ->set('modulo',  $url[0])
        ->criarTransacao();

    if ($salvar['sucesso']) {
        $pagarmeObj->set('pro_mensagem_idioma', 'criar_transacao_pagarme_sucesso');
        $pagarmeObj->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2]);
        $pagarmeObj->Processando();
    }
} elseif ($_POST['acao'] == 'criar_boleto_pagarme') {
    require_once '../classes/pagarme.class.php';
    $pagarmeObj = new PagarmeObj();
    $salvar = $pagarmeObj->set('idescola', $usuario['idescola'])
        ->criarTransacaoBoleto($_POST['idconta']);

    if ($salvar['sucesso']) {
        $pagarmeObj->set('pro_mensagem_idioma', 'criar_boleto_pagarme_sucesso');
        $pagarmeObj->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2]);
        $pagarmeObj->processando();
    }
}

if (isset($url[3])) {
    $linhaObj->set('id', (int) $url[3]);
    $linhaObj->set('campos', '*');
    $linha = $linhaObj->retornar();

    if ($linha) {
        switch ($url[4]) {
            case 'ficha':
                $linhaObj->set('campos', 'cm.idconta_matricula,
                    cm.idmatricula,
                    p.nome,
                    cm.data_cad,
                    cm.valor_fatura,
                    cm.valor_total,
                    cm.parcela,
                    cm.total_parcelas,
                    cs.nome as curso, 
                    c.data_vencimento AS vencimento
                    ');
                $linhaObj->set('ordem_campo','cm.idconta_matricula');
                $linhaObj->set('ordem','DESC');
                $linhaObj->set('limite',-1);
                $dadosArray = $linhaObj->retornarMatriculasFatura($linha['idconta']);

                include('idiomas/' . $config['idioma_padrao'] . '/ficha.php');
                include('telas/' . $config['tela_padrao'] . '/ficha.php');
                break;

            default:
                header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
                exit();
        }
    } else {
        header('Location: /' . $url[0] . '/' . $url[1] . '/' . $url[2]);
        exit();
    }
} else {
    $linhaObj->set('pagina', $_GET['pag']);
    $linhaObj->set('campos', 'c.idconta,
        e.nome_fantasia AS escola,
        c.valor,
        c.data_vencimento,
        c.qnt_matriculas,
        cw.nome AS situacao,
        cw.cor_bg AS situacao_cor_bg,
        cw.cor_nome AS situacao_cor_nome,
        c.data_modificacao_fatura,
        c.data_cad,
        cw.pago,
        SUM(cm.qtd_parcelas) AS qtd_parcelas,
        e.documento,
        e.email,
        CONCAT_WS(" ", l.nome, e.endereco) AS endereco,
        e.numero,
        e.complemento,
        e.bairro,
        cid.nome AS cidade,
        est.sigla AS uf,
        e.cep,
        e.telefone,
        (
            SELECT
                COUNT(p.idpagarme)
            FROM
                pagarme p
            WHERE
                p.idconta = c.idconta AND
                p.status IN ("processing", "authorized", "waiting_payment", "pending_refund") AND
                p.payment_method <> "boleto" AND
                p.ativo = "S"
        ) AS totalPagamentosAbertos');
    $linhaObj->set('mantem_groupby',true);
    $_GET['cmp'] = ($_GET['cmp']) ? $_GET['cmp'] : 'c.';
    $linhaObj->set('ordem_campo',  $_GET['cmp']. $config['banco']['primaria']);
    $_GET['ord'] = ($_GET['ord']) ? $_GET['ord'] : 'desc';
    $linhaObj->set('ordem', $_GET["ord"]);
    $_GET['qtd'] = ($_GET['qtd'])? (int)$_GET['qtd'] : 30;
    $linhaObj->set("limite", $_GET["qtd"]);
    $linhaObj->set('distinct','DISTINCT ');
    $linhaObj->set('groupby','c.idconta');
    $dadosArray = $linhaObj->listarTodasFaturas();
    $numTotalRegistros = $linhaObj->Get("total");
    $qtdPorPagina = $_GET['qtd'];
    
    include('idiomas/' . $config['idioma_padrao'] . '/index.php');
    include('telas/' . $config['tela_padrao'] . '/index.php');
}