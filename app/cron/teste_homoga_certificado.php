<?php

$caminhoApp = realpath(__DIR__ . '/..');

// Includes gerais
require_once $caminhoApp . '/includes/config.php';
require_once $caminhoApp . '/especifico/inc/config.especifico.php';
require_once $caminhoApp . '/includes/funcoes.php';
require_once 'includes/funcoes.php';

// Classe Core (classe pai)
require_once $caminhoApp . '/classes/core.class.php';
$coreObj = new Core;
include_once $caminhoApp . '/classes/matriculas.class.php';
include_once $caminhoApp . '/classes/usuarios.class.php';
$matriculaObj = new Matriculas();
$usuariosObj = new Usuarios();
$usuarios = $usuariosObj->ListarTodas();
$situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();
$situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();
$situacaoHomologarCertificado = $matriculaObj->retornarSituacaoHomologarCertificado();

$sql = "SELECT 
            ma.idmatricula,
            ma.idpessoa,
            ma.idsituacao,
            ma.idsindicato,
            ma.idcurso,
            oc.porcentagem_minima_disciplinas,
            cs.homologar_certificado
        FROM matriculas ma
        INNER JOIN cursos c ON (ma.idcurso = c.idcurso)
        INNER JOIN sindicatos i ON (i.idsindicato = ma.idsindicato)
        INNER JOIN cursos_sindicatos cs ON (i.idsindicato = cs.idsindicato and c.idcurso = cs.idcurso)
        
        -- verificaMatriculaAprovadaNotasDias
        INNER JOIN (
            SELECT idmatricula, para, MIN(data_cad) as data_conclusao 
            FROM matriculas_historicos 
            GROUP BY idmatricula, para
        )  mh ON (ma.idmatricula = mh.idmatricula AND mh.para = ".$situacaoAtiva['idsituacao'].")
        INNER JOIN ofertas_cursos oc ON (ma.idoferta = oc.idoferta AND c.idcurso = oc.idcurso)
        WHERE 
            ma.idsituacao = ".$situacaoAtiva['idsituacao']." AND
            ma.ativo = 'S' AND 
            NOW() >= DATE_ADD(mh.data_conclusao, INTERVAL oc.gerar_quantidade_dias DAY)
        ORDER BY mh.data_conclusao, oc.gerar_quantidade_dias
        ";
$query = $matriculaObj->executaSql($sql);
// echo $sql;
while ($linha = mysql_fetch_assoc($query)) {
    $matriculaObj->set('id', $linha['idmatricula'])->set('idpessoa', $linha['idpessoa']);
    $matricula = $matriculaObj->retornar();
    $aluno = $matriculaObj->retornarPessoa();
    $sindicato = $matriculaObj->RetornarSindicato();
    $curso = $matriculaObj->RetornarCurso();
    $cfc = $matriculaObj->RetornarEscola();
    $alunoAprovadoNotas = $matriculaObj->verificaMatriculaAprovadaNotas($linha['porcentagem_minima_disciplinas']);
    $porcentagemCursoAtual = $matriculaObj->porcentagemCursoAtual((int) $linha['idmatricula']);
    $documentosObrigatoriosPendentes = $matriculaObj->retornarDocumentosPendentes($linha['idmatricula'], $linha['idsindicato'], $linha['idcurso'], false);

    if($alunoAprovadoNotas && empty($documentosObrigatoriosPendentes) && $porcentagemCursoAtual == 100){
        $novaSituacao = ($linha['homologar_certificado'] == 'S') ? $situacaoHomologarCertificado : $situacaoConcluido;
        echo "Matrícula ".$linha['idmatricula']." -> Homologa? ".$linha['homologar_certificado']." -> Situação de ".$linha['idsituacao']." para ".$novaSituacao['idsituacao'];
    }
}
$nomeDe = $GLOBALS["config"]["tituloEmpresa"];
$emailDe = $GLOBALS["config"]["emailSistema"];
die();
