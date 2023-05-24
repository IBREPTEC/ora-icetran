<?php
$caminho = dirname(__DIR__);

require_once $caminho.'/../app/includes/config.php';
require_once $caminho.'/../app/includes/funcoes.php';
require_once $caminho.'/../detran/includes/funcoes.php';
require_once $caminho.'/../app/classes/PHPMailer/PHPMailerAutoload.php';
require_once $caminho.'/../app/classes/core.class.php';
require_once $caminho . '/../app/classes/matriculas.class.php';
require_once $caminho . '/../app/classes/orio/Transacoes.php';
require_once $caminho . '/../app/classes/detran.class.php';
require_once $caminho.'/../app/especifico/inc/config.detran.php';
require_once $caminho.'/../app/especifico/inc/config.banco.php';
require_once $caminho.'/../app/especifico/inc/config.especifico.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(1);
ini_set('display_errors', 1);

ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl',0);

$codTransacao = 10; //Cadastro certificado

$coreObj = new Core;
$detran = new Detran();
$transacoes = new Transacoes();
$matriculaObj = new Matriculas();

$siglaEstado = 'RJ';
define('INTERFACE_DETRAN_RJ_CERTIFICADO', retornarInterface('detran_rj_certificado')['id']);


$numeroSequencial = mysql_fetch_assoc($matriculaObj->executaSql('SELECT (COUNT(*)+1) as proximo FROM detran_logs'));

$parteFixa = [
    'sequencial' => str_pad($numeroSequencial['proximo'], 6, '0', STR_PAD_LEFT),
    'cod-transacao' => str_pad($codTransacao, 3, 0, STR_PAD_LEFT),
    'modalidade' => '4',
    'cliente' => str_pad($config['detran'][$siglaEstado]['pUsuario'], 11),
    'uf-transa' => 'BR',
    'uf-origem' => 'BR',
    'uf-destino' => 'RJ',
    'tipo' => '0',
    'tamanho' => '0054',
    'retorno' => '00',
    'juliano' => str_pad(date('z')+1, 3, '0', STR_PAD_LEFT),
];

if( is_array($detran_tipo_aula[$siglaEstado]) ){
    $cursosIn = 'AND c.idcurso IN (' . implode(',', array_keys($detran_tipo_aula[$siglaEstado])) . ')';
}
$idRioJaneiro = (int)$GLOBALS['estadosDetran'][$siglaEstado];
$sql = 'SELECT
        m.idmatricula, m.data_matricula, m.data_conclusao, m.renach, p.documento, p.categoria, e.detran_codigo, c.idcurso, m.idmatricula, p.data_nasc, e.detran_codigo, c.idcurso, frdm.idfolha_matricula, m.idoferta, m.idcurso, m.idescola, m.data_primeiro_acesso,
        (
            SELECT oca.idava
            FROM ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND oca.idcurriculo = oce.idcurriculo AND oca.ativo = "S" AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = "S")
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = "S" )
                INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina AND d.ativo = "S")
            WHERE oce.idoferta = o.idoferta
                AND oce.idcurso = c.idcurso
                AND oce.idescola = e.idescola
                AND d.iddisciplina IN (' . implode(',', array_keys($detran_codigo_materia[$siglaEstado])) . ')
            LIMIT 1
        ) AS idava,
        (
            SELECT mh.acao
            FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula
            AND mh.tipo = "detran_certificado"
            ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad
            FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula
            AND mh.tipo = "detran_certificado"
            ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = "detran_situacao" AND mh.para = "LI"
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso
    FROM matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo="S")
    WHERE
        m.idmatricula in (16816) AND
        m.renach IS NOT NULL
        AND m.detran_certificado = "N"
        AND cw.cancelada = "N"
        AND m.detran_situacao = "LI"
        '.$cursosIn.'
        AND e.idestado = ' . $idRioJaneiro . '
        AND m.ativo = "S"
        AND cw.fim = "S"
    ORDER BY data_ultimo_historico ASC
    LIMIT 10';
    echo $sql;
    echo "<br><br>";
$query = $matriculaObj->executaSql($sql);

while ($dados = mysql_fetch_assoc($query)) {
    ini_set('soap.wsdl_cache_enabled', '0');
    ini_set('soap.wsdl_cache_ttl', 0);

    $retornoConsulta = [
        // CRÍTICAS ESTADUAIS
        '0002' => 'Campos obrigatórios ausentes',
        '0006' => 'Inexistência do número de formulário RENACH',
        '0071' => 'Requerimento 4 incompatível',
        '0072' => 'Certificado de Leg . deve ser para requerimento de primeira hab . ou reabilitação .',
        '0073' => 'País inválido para certificado de auto no requerimento de estrangeiro',
        '0074' => 'País não signatário para certificado de auto no requerimento de estrangeiro',
        '0075' => 'Tipo de requerimento incompatível para certificado de auto',
        '0076' => 'País inválido para certificado de moto no requerimento de estrangeiro',
        '0077' => 'País não signatário para certificado de moto no requerimento de estrangeiro',
        '0078' => 'Necessário categ . A e categ . pretendida X para certificado de moto no req . de mudança de categoria',
        '0079' => 'Condutor não necessita prova de atualização para certificado de atualização',
        '0101' => 'Formulário RENACH encerrado',
        '0105' => 'Serviço liberado apenas a idoso, 2 reprov . ou ativ . remunerada para certificado de atualização',
        '0107' => 'Sem autoriz . especial recepção de carga horária em req . mudança / adição para certificado de auto',
        '0108' => 'Necessita prova de atualização no requerimento de estrangeiro',
        '0109' => 'Formulário RENACH sem certificado de simulador / isenção para certificado de auto',
        '0199' => 'Condutor não exerce atividade remunerada',
        '0217' => 'Código do curso não tabelado',
        '0302' => 'Formulário RENACH em fase de emissão para certificado de CRCI',
        '0305' => 'Formulário RENACH sem reciclagem para certificado de CRCI',
        '0802' => 'Número de formulário RENACH inválido',
        '0950' => 'Código de curso inválido',
        '0999' => 'Transação efetuada com sucesso',
        '1003' => 'Curso - certificado já informado',
        '1004' => 'Data do início ou fim do curso superior a data corrente',
        '1005' => 'Carga horária insuficiente',
        '1006' => 'Carga horária incompatível com período',
        '1007' => 'Data início ou fim do curso diferente de AAAA / MM / DD',
        '1008' => 'Sem autorização especial para recepção de carga horária',
        '1009' => 'Código de resultado de exame clínico inválido para exame médico',
        '1010' => 'Sem autorização especial para ignorar crítica',
        '1011' => 'Reprovado no exame psicológico',
        '1012' => 'Autorização especial para ignorar crítica',
        '1013' => 'Resultado do exame de Leg . diferente de 1',
        '1014' => 'Resultado do exame de Leg . diferente de 1 sem transferência .',
        '1015' => 'Formulário RENACH já possui certificado de auto',
        '1016' => 'Formulário RENACH já possui certificado de moto / já aprovado no exame de Leg . na atualização',
        '1017' => 'Indicador de reaproveitamento de cursos diferente de 1 ou 2',
        '1018' => 'Data de inclusão do requerimento maior que 12 meses',
        '1020' => 'Candidato não está matriculado no curso',
        '1022' => 'Carga horária diferente de 20',
        '1023' => 'Carga horária máxima diária excedida',
        '1024' => 'Curso não é de CRCI',
        '1025' => 'Curso não é a distancia',
        '1026' => 'Carga horária inferior a 20',
        '1029' => 'Data da matrícula é posterior a data do início do curso',
        '1031' => 'Carga horária inferior a 20 na categoria X para certificado de Leg .',
        '1032' => 'Carga horaria inferior a 45 nas categorias A, B, AB ou XB para certificado de Leg .',
        '1033' => 'Carga horária inferior a 10 nas categorias X ou XB para certificado de moto .',
        '1034' => 'Carga horária inferior a 10 nas categorias XB, XC, XD ou XE na adic . Categ . Para certific . de moto',
        'R999' => 'Código de erro do REFOR',
        '6001' => 'Formulário RENACH em branco',
        '6002' => 'Formulário RENACH fora do formato "RJ99999999"',
        '6003' => 'Formulário RENACH com DV inválido',
        '6004' => 'CAER em branco',
        '6005' => 'CAER não encontrado',
        '6006' => 'Código de curso inválido',
        '6007' => 'Tipo de requerimento inválido para curso informado',
        '6008' => 'Data de início / fim de curso em branco',
        '6009' => 'Data de início / fim de curso fora do formato "AAAAMMDD"',
        '6010' => 'CPF do instrutor fora do formato "NNNNNNNNNNN"',
        '6011' => 'CPF do instrutor zerado',
        '6012' => 'CPF do instrutor com DV inválido',
        '6013' => 'Formulário RENACH não encontrado',
        // CRÍTICAS NACIONAIS
        '000' => 'Transação Efetuada .',
        '002' => 'Campos Obrigatórios Ausentes .',
        '006' => 'Inexistência de Número De Formulário RENACH na Base .',
        '011' => 'UF de Origem da Transação Diferente da UF de Domínio .',
        '016' => 'Inexistência de Número de Registro na Base .',
        '017' => 'CNH Sendo Emitida .',
        '042' => 'Liberação já Registrada – Confirme Alteração de Dados .',
        '222' => 'Modalidade do Curso Fora da Tabela ou Ausente ou Não se Aplica ao Curso .',
        '345' => 'Curso / Exame não compatível com a Categoria Registrada no Prontuário do Candidato / Condutor .',
        '346' => 'Exame não compatível com a Categoria Registrada no Prontuário do Candidato / Condutor .',
        '375' => 'O Número Formulário RENACH só Aceito Para Candidato .',
        '376' => 'Tipo de Evento Inválido ou Ausente .',
        '377' => 'Tipo Atualização Evento Inválido ou Ausente .',
        '388' => 'Categoria Permitida Deve ser Igual ou Inferior a Categoria Pretendida .',
        '389' => 'CNH Cancelada Por Erro Gráfica .',
        '390' => 'O Campo Modalidade é Obrigatório Para Este Curso .',
        '391' => 'A Carga Horária é Obrigatório Para Este Curso .',
        '392' => 'O Município do Evento não Pertence a UF Evento .',
        '393' => 'Data Validade é Obrigatório Para Este Evento .',
        '394' => 'Categoria é Obrigatório Para Este Curso .',
        '395' => 'Evento Já Registrado no Prontuário do Condutor / Candidato .',
        '396' => 'Código do Curso Fora da Tabela ou Ausente .',
        '397' => 'Data Inicio Curso Inválida .',
        '398' => 'Data Fim Curso Inválida .',
        '399' => 'CNPJ Entidade Credenciada Inválido ou Ausente .',
        '400' => 'CPF Instrutor Inválido ou Ausente .',
        '401' => 'Município Evento Fora da Tabela ou Ausente .',
        '402' => 'UF Evento Fora da Tabela ou Ausente .',
        '403' => 'Evento Não Cadastrado no Prontuário do Condutor / Candidato .',
        '404' => 'Dados não Divergem dos Cadastrados na BINCO / BCA .',
        '405' => 'Atributos Divergem dos Cadastrados na BINCO / BCA .',
        '406' => 'Evento Rejeitado – Evento é Pré - Requisito Para CNH Autorizada / Emitida .',
        '407' => 'Código do Exame Fora da Tabela ou Ausente .',
        '409' => 'Resultado Inválido ou Ausente .',
        '410' => 'CPF Examinador 1 Inválido ou Ausente .',
        '411' => 'CPF Examinador 2 Inválido .',
        '412' => 'Categoria Pretendida Fora da Tabela ou Ausente .',
        '413' => 'Categoria Permitida Fora da Tabela ou Ausente .',
        '418' => 'UF Detran Infração Fora da Tabela ou Ausente .',
        '467' => 'Número - Chave(Formulário RENACH) inválido para Condutor .',
        '472' => 'Carga - horária Inválida .',
        '473' => 'Data - Validade Inválida ou Ausente ou Não se Aplica ao Curso / Exame .',
        '474' => 'Dados de Liberação inválidos para tipo de atualização informado .',
        '479' => 'Número - Certificado Inválido .',
        '512' => 'Data Inicio - Penalidade - Bloqueios com Data Calendário no Prontuário, Confirme .',
        '513' => 'Data Inicio - Penalidade - Bloqueios Igual Data Inicio - Penalidade - Bloqueios do Prontuário .',
        '593' => 'Carga horária insuficiente para o Curso / Exame',
        '603' => 'Condutor transferido CNH autorizada ou emitida',
        '611' => 'Inexistência do Código da Entidade / Profissional na Base',
        '616' => 'Entidade ou Profissional Bloqueado',
        '625' => 'Entidade com credenciamento em situação inativa',
        '639' => 'Vínculo do profissional à entidade inexistente',
        '658' => 'Vínculo do profissional à entidade inativo',
        '659' => 'Vínculo do profissional à entidade bloqueado',
        '661' => 'Vínculo do profissional à entidade com mudança de UF',
        '662' => 'Evento não compatível com a entidade / profissional',
        '663' => 'Entidade não cadastrada / vinculada UF do evento',
        '752' => 'Número de Registro Inválido .',
        '801' => 'UF do Número Formulário RENACH Fora da Tabela de UF’s .',
        '802' => 'Número Formulário RENACH Inválido ou Ausente .',
        '804' => 'UF do Número Formulário RENACH Diferente da UF de Origem da Transação .',
        '837' => 'Código da Categoria Fora da Tabela .',
        '890' => 'Tipo da Chave de Pesquisa Inválido .',
        '998' => 'Tamanho da Transação Menor que o Tamanho Esperado',
    ];

    $numeroSequencial = mysql_fetch_assoc($coreObj->executaSql('SELECT (COUNT(*)+1) as proximo FROM detran_logs'));

    $parteFixa = [
        'sequencial' => str_pad($numeroSequencial['proximo'], 6, '0', STR_PAD_LEFT),
        'cod-transacao' => str_pad(10, 3, 0, STR_PAD_LEFT),
        'modalidade' => '4',
        'cliente' => str_pad($config['detran']['RJ']['pUsuario'], 11),
        'uf-transa' => 'BR',
        'uf-origem' => 'BR',
        'uf-destino' => 'RJ',
        'tipo' => '0',
        'tamanho' => '0054',
        'retorno' => '00',
        'juliano' => str_pad(date('z') + 1, 3, '0', STR_PAD_LEFT),
    ];

    try {
        $matriculaObj->executaSql('BEGIN');

        $conexaoSOAP = new SoapClient($config['detran']['RJ']['urlWsl']);

        $auth = array(
            'exx-natural-security' => 'TRUE',
            'exx-natural-library' => 'LIBBRK',
            'exx-rpc-userID' => $config['detran']['RJ']['pUsuario'],
            'exx-rpc-password' => $config['detran']['RJ']['pSenha'],
        );
        $authvar = new SoapVar($auth, SOAP_ENC_OBJECT);
        $header = new SoapHeader('urn:com.softwareag.entirex.xml.rt', 'EntireX', $authvar);
        $conexaoSOAP->__setSoapHeaders($header);
        $data_inicio_curso = new DateTime($dados['data_inicio_curso']);

        $parteVariavel = [
            'tipo-chave' => '1',
            'numero-chave' => str_pad($dados['renach'], 11),
            'codigo-evento' => 'C',
            'codigo-atualizacao' => 'I',
            'codigo-curso' => $GLOBALS['detran_tipo_aula']['RJ'][$dados['idcurso']],
            'codigo-modalidade' => 2,
            'numero-certificado' => str_pad($dados["idfolha_matricula"], 15),
            // 'data-inicio' => $data_inicio_curso->format('Ymd'),
            'data-inicio' => '20210418',
            // 'data-fim' => str_replace('-', '', $dados['data_conclusao']),
            'data-fim' => '20210714',
            'carga-horaria' => str_pad(30, 3, 0, STR_PAD_LEFT),
            'entidade-credenciada' => $config['detran']['RJ']['registro_empresa'],
            'cpf-instrutor' => $config['detran']['RJ']['registro_instrutor'],
            'codigo-municipio-curso' => '08105',
            'uf-curso' => 'SC',
            'data-validade' => str_pad('', 8, 0, STR_PAD_LEFT),
            'codigo-categoria' => str_pad($dados['categoria'], 4),
            'codigo-local-dh' => $config['detran']['RJ']['caer'],
            'codigo-disciplina' => '',
            'numero-turma' => '',
            'codigo-operacao' => '',
        ];

        $string_tripa = str_pad(implode('', $parteVariavel), 3237);
        for ($i = 0; $i < 13; $i++) {
            $indice = $i * 249;
            $variavel[$i] = substr($string_tripa, $indice, 249);
        }

        $dadosSOAP = [
            'GTRN0003' => [
                'PARTE-FIXA' => implode('', $parteFixa),
                'PARTE-VARIAVEL' => $variavel,
            ]
        ];

        $options = [];

        $transacoes->iniciaTransacao(INTERFACE_DETRAN_RJ_CERTIFICADO, 'E', $dadosSOAP);
        $respostaSoap = $conexaoSOAP->__soapCall('GTRN0003', $dadosSOAP, $options);

        $retorno = json_decode(json_encode($respostaSoap), true);
        echo "<br>Matrícula: ".$dados['idmatricula'];
        echo "<br>Envio:<br>";
        var_dump(json_encode($dadosSOAP));
        echo "<br>Retorno:<br>";
        var_dump(json_encode($respostaSoap));
        echo "<br><br>";

        if (is_array($retorno['PARTE-VARIAVEL']['string'])) {
            $codigoRetorno = substr($retorno['PARTE-VARIAVEL']['string'][0], 0, 4);
        } else {
            $codigoRetorno = substr($retorno['PARTE-VARIAVEL']['string'], 0, 4);
        }
        $stringEnvio = trim(implode('', $parteFixa) . ' | ' . implode('', $variavel));

        if (in_array($codigoRetorno, ['0999', '000'])) {
            $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
            $coreObj->executaSql($sql);

            $matriculaObj->set('id', $dados['idmatricula'])
                ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
            $transacoes->finalizaTransacao(null, 2, null, $retorno);
        } else {
            $transacoes->set('json', json_encode($retorno));
            $transacoes->finalizaTransacao(null, 5);
            $transacoes->set('json', NULL);
        }

        salvarLogDetran(
            $matriculaObj,
            427,
            $dados['idmatricula'],
            $codigoRetorno . ' - ' . $retornoConsulta[$codigoRetorno],
            $stringEnvio
        );

        $coreObj->executaSql('COMMIT');
    } catch (Exception $excecao) {
        $transacoes->finalizaTransacao(null, 3, json_encode([
                'codigo' => $excecao->getCode(),
                'mensagem' => $excecao->getMessage()
            ])
        );
        if ($dados['acao_historico'] != 'detran_nao_respondeu') {
            $matriculaObj->set('id', $dados['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
        }
    }
}
