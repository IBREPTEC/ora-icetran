<?php
ini_set('soap.wsdl_cache_enabled', '0');
define('INTERFACE_DETRAN_SE_LIBERACAO', retornarInterface('detran_se_liberacao')['id']);
require_once $caminho . '/app/classes/matriculas.class.php';
$codTransacao = 431;//Consulta Processo do aluno
$matriculaObj = new Matriculas();
$siglaEstado = 'SE';

$linha['idmatricula'] = 28878;

    try {
        $matriculaObj->executaSql('BEGIN');

        $linha['detran_tipo_aula'] = $detran_tipo_aula[$siglaEstado][$linha['idcurso']];

        $stringEnvio = '43104490870507R   CFC166';

        $opcoesSOAP = array(
            'trace' => 1,
            array('exceptions' => true)
        );
        $soapCliente = new SoapClient($config['detran'][$siglaEstado]['urlWsl'], $opcoesSOAP);

        $dadosSOAP = [
            'executaTransacao' => [
                'pUsuario' => $config['detran'][$siglaEstado]['pUsuario'],
                'pSenha' => $config['detran'][$siglaEstado]['pSenha'],
                'pAmbiente' => $config['detran'][$siglaEstado]['pAmbiente'],
                'pMensagem' => $stringEnvio,
            ]
        ];
        $options = ['location' => $config['detran'][$siglaEstado]['urlSoap']];

        $transacoes->iniciaTransacao(INTERFACE_DETRAN_SE_LIBERACAO, 'E', $dadosSOAP);
        $respostaSoap = $soapCliente->__soapCall('executaTransacao', $dadosSOAP, $options);

        if (substr($respostaSoap->executaTransacaoResult, 0, 3) == 999) {
            $sql = 'UPDATE matriculas SET detran_situacao = "LI" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'LI', null);
            $transacoes->finalizaTransacao(null, 2, null, $respostaSoap->executaTransacaoResult);

        } elseif (substr($respostaSoap->executaTransacaoResult, 0, 3) == 998) {
            $sql = 'UPDATE matriculas SET detran_situacao = "NL" WHERE idmatricula = ' . $linha['idmatricula'];
            $matriculaObj->executaSql($sql);

            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'modificou', 'AL', 'NL', null);
            $transacoes->finalizaTransacao(null, 5, null, $respostaSoap->executaTransacaoResult);
        }

        salvarLogDetran($matriculaObj, $codTransacao, $linha['idmatricula'], $respostaSoap->executaTransacaoResult, $stringEnvio);

        $matriculaObj->executaSql('COMMIT');
    } catch (Exception $excecao) {
        $transacoes->finalizaTransacao(
            null,
            3,
            json_encode(['codigo' => $excecao->getCode(), 'mensagem' => $excecao->getMessage()])
        );
        if ($linha['acao_historico'] != 'detran_nao_respondeu') {
            $matriculaObj->set('id', $linha['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
        }
    }

