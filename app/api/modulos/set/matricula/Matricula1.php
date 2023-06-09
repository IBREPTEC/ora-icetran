<?php

class Matricula
{
    private $acessoBanco;
    public  $config;
    public  $idmatricula;
    public  $idpessoa;
    public  $idusuario;

    const ID_RIO_GRANDE_SUL = 21;
    const ID_PARANA = 16;
    const ID_MARANHAO = 10;
    const ID_MATO_GROSSO_DO_SUL = 12;

    public function __construct(Core $acessoBanco)
    {
        $this->acessoBanco = $acessoBanco;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    public function autenticar($email, $senha)
    {
        $sql = "SELECT 
                    idusuario,
                    nome
                FROM 
                    usuarios_adm
                WHERE 
                    email='{$email}' AND
                    senha='{$senha}' AND 
                    ativo='S' AND 
                    ativo_login = 'S'";

        $usuario = $this->acessoBanco->retornarLinha($sql);

        if (! $usuario) {
            throw new \Exception("erro_senha_incorreta", 401);
        } else {
            return $usuario;
        }
    }

    public function cadastrar($dados)
    {
        $this->acessoBanco->config = $this->config;
        $this->acessoBanco->executaSql('BEGIN');

        if (empty($dados)) {
            $this->acessoBanco->executaSql('ROLLBACK');
            throw new \Exception('dados_corrompidos', 400);
        }

        foreach($dados as $matricula) {

            $oferta = $this->retornarOferta($matricula['idoferta']);
            if (! $oferta) {
                $this->acessoBanco->executaSql('ROLLBACK');
                throw new \Exception("erro_oferta_invalida", 400);
            }

            $curso = $this->retornarCurso($matricula['idcurso']);
            if (! $curso) {
                $this->acessoBanco->executaSql('ROLLBACK');
                throw new \Exception("erro_curso_invalido", 400);
            }

            $escola = $this->retornarEscola($matricula['idcfc']);
            if (! $escola) {
                $this->acessoBanco->executaSql('ROLLBACK');
                throw new \Exception("erro_escola_invalida", 400);
            }

            $ofertaCursoEscola = $this->verificarMatriculasCursoEscola($matricula['idoferta'], $matricula['idcurso'], $matricula['idcfc'], $matricula['idturma']);

            if($ofertaCursoEscola['erro']) {
                $this->acessoBanco->executaSql('ROLLBACK');
                throw new \Exception($ofertaCursoEscola['erros']['mensagem'], $ofertaCursoEscola['erros']['codigo']);
            }

            if($ofertaCursoEscola['total'] == $ofertaCursoEscola['maximo_turma']) {
                $this->acessoBanco->executaSql('ROLLBACK');
                throw new \Exception("erro_maximo_turma", 424);
            }

            require_once 'Pessoa.php';
            $pessoaObj = new Pessoa($this->acessoBanco);
            $pessoaObj->config = $this->config;
            $pessoa = $pessoaObj->retornarIdPorCPF($matricula['documentoaluno']);

            if ($pessoa) {
                $matricula['idpessoa'] = $pessoa['idpessoa'];
                $pessoa = $pessoaObj->modificar($matricula);
            } else {
                $pessoa = $pessoaObj->cadastrar($matricula);
            }
            if ($pessoa['erro']) {
                $this->acessoBanco->executaSql('ROLLBACK');
                throw new \Exception($pessoa['erros']['mensagem'], $pessoa['erros']['codigo']);
            }

            if (!empty($matricula['idatendente']))
                $vendedor = $this->retornarAtendente($matricula['idatendente']);

            if (! $vendedor) {
                $this->acessoBanco->executaSql('ROLLBACK');
                throw new \Exception("erro_atendente_invalido", 400);
            }

            $situacaoAtiva = $this->retornarSituacaoAtiva();

            $matricula["valor_contrato"] = str_replace('.', '', $matricula["valor_contrato"]);
            $matricula["valor_contrato"] = str_replace(',', '.', $matricula["valor_contrato"]);
            $matricula["valor_parcela"] = str_replace('.', '', $matricula["valor_parcela"]);
            $matricula["valor_parcela"] = str_replace(',', '.', $matricula["valor_parcela"]);
            $matricula["valor_total"] = str_replace('.', '', $matricula["valor_total"]);
            $matricula["valor_total"] = str_replace(',', '.', $matricula["valor_total"]);

            $bolsa = ($matricula["valor_contrato"] == 0) ? 'S' : 'N';

            $dataDiasParaAva = new DateTime();

            $oferta_curso_escola = $this->retornarOfertaCursoEscola($matricula['idoferta'], $matricula['idcurso'], $matricula['idcfc']);

            if ($oferta_curso_escola['dias_para_ava']) {
                $dataDiasParaAva->modify('+ ' . $oferta_curso_escola['dias_para_ava'] . ' days');
            }

            $dataLimiteAva = NULL;
            if ($oferta_curso_escola['data_limite_ava']) {
                $dataLimiteAva = new DateTime($oferta_curso_escola['data_limite_ava']);
            }

            if ($dataDiasParaAva && $dataLimiteAva) {
                if ($dataDiasParaAva > $dataLimiteAva) {
                    $data_limite_acesso_ava = $dataDiasParaAva->format('Y-m-d');
                } else {
                    $data_limite_acesso_ava = $dataLimiteAva->format('Y-m-d');
                }
            } elseif ($dataDiasParaAva) {
                $data_limite_acesso_ava = $dataDiasParaAva->format('Y-m-d');
            } else {
                $data_limite_acesso_ava = $dataLimiteAva->format('Y-m-d');
            }

            $detranSituacao = 'LI';
            if (! empty($escola['detran_codigo']) && in_array($escola['idestado'], $GLOBALS['estadosDetran'])) {
                $detranSituacao = 'AL';
            }

            if ($escola['idestado'] == self::ID_RIO_GRANDE_SUL || $escola['idestado'] == self::ID_PARANA) {
                $detranSituacao = 'AL';
            }

            if (($escola['idestado'] == self::ID_MARANHAO) && $curso['codigo'] == 'REC'){
                $detranSituacao = 'LI';
            }

            $sindicato = $this->retornarSindicato($escola['idsindicato']);

            $mantenedora = $this->retornarMantenedora($sindicato["idmantenedora"]);

            $sql = "SELECT
                        COUNT(m.idmatricula) AS matricula_duplicada
                    FROM
                        matriculas m
                        INNER JOIN matriculas_workflow mw ON m.idsituacao = mw.idsituacao
                    WHERE 
                        mw.inativa <> 'S' AND 
                        mw.cancelada <> 'S' AND 
                        m.ativo = 'S' AND 
                        m.idpessoa = " . $pessoa["idpessoa"] . " AND 
                        m.idoferta = " . $matricula["idoferta"] . " AND 
                        m.idcurso = " . $matricula["idcurso"] . " AND 
                        m.idescola = " . $matricula["idcfc"];

            $matriculas = $this->acessoBanco->retornarLinha($sql);

            if ($matriculas['matricula_duplicada'] > 0) {
                $this->acessoBanco->executaSql('ROLLBACK');
                throw new \Exception('matricula_duplicada', 302);
            }

            $sql = "INSERT INTO
                          matriculas
                      SET
                          data_cad = now(),
                          data_matricula = now(),
                          data_prolongada = '" . $data_limite_acesso_ava . "',
                          detran_situacao = '" . $detranSituacao . "',
                          idmantenedora = '" . $mantenedora["idmantenedora"] . "',
                          idsindicato = '" . $sindicato["idsindicato"] . "',
                          idpessoa = " . $pessoa["idpessoa"] . ",
                          idoferta = " . $matricula["idoferta"] . ",
                          idcurso = " . $matricula["idcurso"] . ",
                          idescola = " . $matricula["idcfc"] . ",
                          idturma = " . $matricula["idturma"] . ",
                          aprovado_comercial = 'S',
                          idsituacao = " . $situacaoAtiva["idsituacao"] . ",
                          modulo = 'gestor',
                          bolsa = '" . $bolsa . "',
                          observacao = NULL,
                          data_registro = '" . date('Y-m-d') . "'";

            if ($matricula["financeiro"]) {
                if ($matricula["financeiro"]["forma_pagamento"] == "Cartão" || $matricula["financeiro"]["forma_pagamento"] == "Cartão de crédito") {
                    $sql .= ", forma_pagamento = 2,
                              autorizacao_cartao = '" . $matricula['financeiro']['autorizacao_tid'] . "'";
                    $bandeira = $this->retornarIdBandeira($matricula["financeiro"]['bandeira']);
                    $sql .= ", idbandeira = '" . $bandeira['idbandeira'] . "'";
                } else if ($matricula["financeiro"]["forma_pagamento"] == "Boleto") {
                    $sql .= ", forma_pagamento = 1";
                }
            }

            if ($bolsa == "S") {
                $sql .= ", valor_contrato = 0,
                           quantidade_parcelas = 0";
            } else if ($bolsa == "N") {
                $sql .= ", idsolicitante = NULL,
                            valor_contrato = " . $matricula["valor_contrato"] . ",
                            quantidade_parcelas = " . $matricula["financeiro"]["parcelas"];
            }

            $sql .= ", idusuario = " . $this->idusuario . ",
                       idvendedor = " . $matricula["idatendente"];

            if ($this->acessoBanco->executaSql($sql)) {
                $this->idmatricula = mysql_insert_id();

                $sql = "INSERT INTO
                            matriculas_historicos
                        SET
                            idmatricula = '" . $this->idmatricula . "',
                            data_cad = now(),
                            tipo = 'situacao',
                            acao = 'modificou',
                            para = " . $situacaoAtiva['idsituacao'] . ",
                            idusuario = " . $this->idusuario;

                $this->acessoBanco->executaSql($sql);

                $sql = 'SELECT 
                            idpessoa_sindicato, 
                            ativo 
                        FROM 
                            pessoas_sindicatos 
                        WHERE 
                            idpessoa = ' . $pessoa['idpessoa'] . ' AND 
                            idsindicato = ' . $sindicato['idsindicato'] . ' ';
                $pessoa_sindicato = $this->acessoBanco->retornarLinha($sql);

                if ($pessoa_sindicato['idpessoa_sindicato']) {
                    if ($pessoa_sindicato['ativo'] == 'N') {
                        $sql = 'UPDATE 
                                    pessoas_sindicatos 
                                SET 
                                    ativo = "S" 
                                WHERE 
                                    idpessoa_sindicato = ' . $pessoa_sindicato['idpessoa_sindicato'];

                        $resultado = $this->acessoBanco->executaSql($sql);
                    }
                } else {
                    $sql = 'INSERT INTO 
                                pessoas_sindicatos 
                            SET 
                                data_cad = NOW(), 
                                idpessoa = ' . $pessoa['idpessoa'] . ', 
                                idsindicato = ' . $sindicato['idsindicato'] . ' ';

                    $resultado = $this->acessoBanco->executaSql($sql);
                }

                $this->eviarEmailBoasVindas($this->idmatricula, $escola, $sindicato);

                if ($matricula["financeiro"]["forma_pagamento"]) {
                    if($matricula["financeiro"]["forma_pagamento"] == "Cartão" || $matricula["financeiro"]["forma_pagamento"] == "Cartão de crédito") {
                        if (! $matricula["financeiro"]['bandeira']) {
                            $this->acessoBanco->executaSql('ROLLBACK');
                            throw new \Exception('bandeira_cartao_vazio', 400);
                        }
                        if (! $matricula["financeiro"]['autorizacao_tid']) {
                            $this->acessoBanco->executaSql('ROLLBACK');
                            throw new \Exception('autorizacao_cartao_vazio', 400);
                        }
                        if (! $matricula["financeiro"]["parcelas"]) {
                            $this->acessoBanco->executaSql('ROLLBACK');
                            throw new \Exception('financeiro_quantidade_parcelas_vazio', 400);
                        }
                        $bandeira = $this->retornarIdBandeira($matricula["financeiro"]['bandeira']);
                    }

                    if (! $matricula["financeiro"]["valor_total"]) {
                        $this->acessoBanco->executaSql('ROLLBACK');
                        throw new \Exception('financeiro_valor_vazio', 400);
                    }
                    if (! $matricula["financeiro"]["vencimento_primeira"] || ! $matricula["financeiro"]["vencimento_ultima"]) {
                        $this->acessoBanco->executaSql('ROLLBACK');
                        throw new \Exception('financeiro_vencimento_vazio', 400);
                    }
                    if (! $matricula["financeiro"]["situacao"]) {
                        $this->acessoBanco->executaSql('ROLLBACK');
                        throw new \Exception('financeiro_situacao_vazio', 400);
                    }

                    $situacaoFinanceiro = $this->retornarSituacaoFinanceiro($matricula["financeiro"]["situacao"]);

                    if (empty($situacaoFinanceiro)) {
                        $sql = "SELECT 
                                    * 
                                FROM 
                                    contas_workflow 
                                WHERE 
                                    ativo = 'S'
                                    AND emaberto = 'S'";

                        $situacaoFinanceiro = $this->acessoBanco->retornarLinha($sql);
                    }

                    $sql = "SELECT 
                                * 
                            FROM 
                                eventos_financeiros 
                            WHERE 
                                ativo = 'S' 
                                AND mensalidade = 'S'";

                    $tipoFinanceiro = $this->acessoBanco->retornarLinha($sql);

                    $sql = "INSERT INTO contas_relacoes SET data_cad = now()";
                    $this->acessoBanco->executaSql($sql);
                    $idRelacao = mysql_insert_id();
                    if (! intval($matricula["financeiro"]['quantidade_parcelas'])) {
                        $matricula["financeiro"]['quantidade_parcelas'] = 1;
                    }

                    $valorParcela = !empty($matricula["financeiro"]["valor_parcela"]) ? $matricula["financeiro"]["valor_parcela"] : round($matricula["financeiro"]["valor_total"] / $matricula["financeiro"]['parcelas'], 2);
                    $valorTotal = !empty($matricula["financeiro"]["valor_total"]) ? $matricula["financeiro"]["valor_total"] : $valorParcela * $matricula["financeiro"]['parcelas'];
                    $valorPrimeiraParcela = $valorParcela + ($matricula["financeiro"]["valor_total"] - $valorTotal);
                    $data = explode("-", $matricula["financeiro"]["vencimento_primeira"]);

                    $matricula["financeiro"]['nome'] = 'Referente a uma parcela da matricula ' . $this->idmatricula;
                    $matricula["financeiro"]['parcelas'] = (int)$matricula["financeiro"]['parcelas'];

                    for ($parcela = 1; $parcela <= $matricula["financeiro"]['parcelas']; $parcela++) {
                        $matricula["financeiro"]['valor'] = $valorParcela;
                        if ($parcela == 1)
                            $matricula["financeiro"]['valor'] = $valorPrimeiraParcela;

                        $mes = ($data[1] + ($parcela - 1));
                        $dia = $data[2];

                        if ($mes == 2 && $dia >= 29) {
                            $dia = date("t", mktime(0, 0, 0, $mes, 1, $data[0]));
                        }

                        $vencimento = date("Y-m-d", mktime(0, 0, 0, ($data[1] + ($parcela - 1)), $dia, $data[0]));

                        $matricula["financeiro"]['valor'] = str_replace('.', '', $matricula["financeiro"]['valor']);
                        $matricula["financeiro"]['valor'] = str_replace(',', '.', $matricula["financeiro"]['valor']);

                        $sql = "INSERT INTO
                                      contas
                                SET
                                      data_cad = now(),
                                      tipo = 'receita',
                                      nome = '" . $matricula["financeiro"]['nome'] . "',
                                      valor = " . $matricula["financeiro"]['valor'] . ",
                                      data_vencimento = '" . $vencimento . "',
                                      idsituacao = " . $situacaoFinanceiro['idsituacao'] . ",
                                      idrelacao = " . $idRelacao . ",
                                      idmantenedora = " . $mantenedora["idmantenedora"] . ",
                                      idsindicato = " . $sindicato['idsindicato'] . ",
                                      idmatricula = " . $this->idmatricula . ",
                                      idpessoa = " . $pessoa["idpessoa"] . ",
                                      idevento = " . $tipoFinanceiro['idevento'] . ",
                                      parcela = " . $parcela . ",
                                      total_parcelas = '" . $matricula["financeiro"]['parcelas'] . "' ";
                        if($matricula["financeiro"]["forma_pagamento"] == "Cartão" || $matricula["financeiro"]["forma_pagamento"] == "Cartão de crédito") {
                            $forma_pagamento = 2;
                            $sql .= ", forma_pagamento = 2,
                                    idbandeira = " . $bandeira['idbandeira'] . ",
                                    autorizacao_cartao = '" . $matricula["financeiro"]['autorizacao_tid'] . "'";
                        } elseif ($matricula["financeiro"]["forma_pagamento"] == "Boleto") {
                            $forma_pagamento = 1;
                            $sql .= ", forma_pagamento = 1";
                        }
                        $this->acessoBanco->executaSql($sql);
                        $idconta = mysql_insert_id();

                        $sql = "INSERT INTO
                                    matriculas_historicos
                                SET
                                    idmatricula = '" . $this->idmatricula . "',
                                    data_cad = now(),
                                    tipo = 'parcela',
                                    acao = 'cadastrou',
                                    idusuario = " . $this->idusuario . ",
                                    id = " . $idconta;

                        $this->acessoBanco->executaSql($sql);

                    }

                }

            } else {
                throw new \Exception('erro_cadastro_matricula', 400);
            }
        }
        $this->acessoBanco->executaSql('COMMIT');
        $retorno["codigo"] = 200;
        $retorno["mensagem"] = "Matrícula cadastrada com sucesso";

        return $retorno;
    }

    public function retornarOferta($idOferta)
    {
        $sql = 'SELECT 
                    * 
                FROM
                    ofertas
                WHERE
                    ativo = "S"
                    AND ativo_painel = "S"
                    AND DATE_FORMAT(NOW(),"%Y-%m-%d") BETWEEN data_inicio_matricula AND data_fim_matricula
                    AND idoferta = ' . $idOferta;

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarCurso($idCurso)
    {
        $sql = 'SELECT 
                    *
                FROM
                    cursos
                WHERE
                    ativo = "S"
                    AND ativo_painel = "S"
                    AND idcurso = ' . $idCurso;

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarEscola($idEscola)
    {
        $sql = 'SELECT 
                    *
                FROM
                    escolas
                WHERE
                    ativo = "S"
                    AND ativo_painel = "S"
                    AND idescola = ' . $idEscola;

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarAtendente($idAtendente)
    {
        $sql = "SELECT 
                    nome 
                FROM
                    vendedores
                WHERE
                    ativo = 'S'
                    AND ativo_login = 'S'
                    AND venda_bloqueada = 'N'
                    AND idvendedor = " . $idAtendente;

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarSituacaoAtiva()
    {
        $sql = "SELECT 
                    * 
                FROM
                    matriculas_workflow
                WHERE
                    ativo = 'S'
                    AND ativa = 'S'
                ORDER BY
                    idsituacao DESC LIMIT 1";

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarSindicato($idSindicato)
    {
        $sql = "SELECT 
                    * 
                FROM 
                    sindicatos 
                WHERE 
                    idsindicato = '" . $idSindicato . "' AND 
                    ativo = 'S'";

        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarMantenedora($idMantenedora)
    {
        $sql = "SELECT 
                    * 
                FROM 
                    mantenedoras 
                WHERE 
                    idmantenedora='" . $idMantenedora . "' AND 
                    ativo = 'S'";

        return $this->acessoBanco->retornarLinha($sql);
    }

    private function verificarMatriculasCursoEscola($idOferta, $idCurso, $idEscola, $idTurma)
    {
        if (! intval($idOferta) || ! intval($idCurso) || ! intval($idEscola) || ! intval($idTurma)) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_dados_corrompidos';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $sql_sindicato = 'SELECT 
                                idsindicato 
                            FROM 
                                escolas 
                            WHERE 
                                idescola = ' . $idEscola;

        $sindicato = $this->acessoBanco->retornarLinha($sql_sindicato);

        $sql = 'SELECT 
                    COUNT(1) AS total,
                    (SELECT 
                        oci.limite
                     FROM 
                        ofertas_cursos_escolas ocp
                        INNER JOIN escolas p ON ocp.idescola = p.idescola
                        INNER JOIN ofertas_cursos_sindicatos oci ON ocp.idoferta = oci.idoferta AND ocp.idcurso = oci.idcurso AND p.idsindicato = oci.idsindicato AND oci.ativo = "S"
                     WHERE
                        ocp.idoferta = ' . $idOferta . '
                        AND ocp.idcurso = ' . $idCurso . '
                        AND p.idescola = ' . $idEscola . '
                        AND ocp.ativo = "S") AS maximo_turma
                  FROM 
                      matriculas
                  WHERE 
                      idoferta = ' . $idOferta . '
                      AND idcurso = ' . $idCurso . '
                      AND idsindicato = ' . $sindicato['idsindicato'] . '
                      AND idturma = ' . $idTurma . '
                      AND ativo = "S" ';

        $resultado = $this->acessoBanco->executaSql($sql);
        if (! $resultado) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_verificar_matriculas';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }
        $matriculas = mysql_fetch_assoc($resultado);
        return $matriculas;
    }

    private function retornarOfertaCursoEscola($idOferta, $idCurso, $idEscola)
    {
        $sql = 'SELECT 
                  * 
              FROM
                ofertas_cursos_escolas oc
              WHERE
                idcurso = ' . $idCurso . '
                AND idescola = ' . $idEscola . '
                AND idoferta = ' . $idOferta . '
                AND ativo = "S" ';
        return $this->acessoBanco->retornarLinha($sql);
    }

    private function retornarIdBandeira($bandeira)
    {
        $sql = "SELECT 
                    idbandeira
                FROM 
                    bandeiras_cartoes 
                WHERE 
                    ativo = 'S' AND 
                    ativo_painel = 'S' AND
                    nome LIKE '".$bandeira."%'";

        return $this->acessoBanco->retornarLinha($sql);
    }

    private function eviarEmailBoasVindas($idmatricula, $escola, $sindicato)
    {
        $sql = 'SELECT 
                    * 
                FROM 
                    matriculas 
                WHERE 
                    idmatricula = ' . $idmatricula;

        $matricula = $this->acessoBanco->retornarLinha($sql);

        if ($matricula['idmatricula']) {
            $sql = 'SELECT 
                        * 
                    FROM 
                        pessoas 
                    WHERE 
                        idpessoa = ' . $matricula['idpessoa'];
            $pessoa = $this->acessoBanco->retornarLinha($sql);

            $sql = 'SELECT 
                        * 
                    FROM 
                        ofertas 
                    WHERE 
                        idoferta = ' . $matricula['idoferta'];
            $oferta = $this->acessoBanco->retornarLinha($sql);

            $sql = "SELECT
                        c.*,
                        ci.email_boas_vindas_sindicato,
                        ci.sms_boas_vindas_sindicato
                    FROM
                        cursos c
                        LEFT JOIN cursos_sindicatos ci ON c.idcurso = ci.idcurso AND ci.ativo = 'S' AND ci.idsindicato = '" . $matricula['idsindicato'] . "'
                    WHERE
                        c.idcurso = " . $matricula['idcurso'];
            $curso = $this->acessoBanco->retornarLinha($sql);

            if ($curso['email_boas_vindas_instituicao'])
                $emailBoasVindas = $curso['email_boas_vindas_instituicao'];
            else
                $emailBoasVindas = $curso['email_boas_vindas'];

            if ($emailBoasVindas) {
                $emailBoasVindas = str_ireplace("[[MATRICULA]]", $matricula['idmatricula'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[NUMERO_CONTRATO]]", $matricula['numero_contrato'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[VALOR_CONTRATO]]", number_format($matricula['valor_contrato'], 2, ',', '.'), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[FORMA_PAGAMENTO]]", ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula['forma_pagamento']]), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[QUANTIDADE_PARCELAS]]", $matricula['quantidade_parcelas'], $emailBoasVindas);

                $emailBoasVindas = str_ireplace("[[NOME_ALUNO]]", ($pessoa['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[ID_ALUNO]]", $pessoa['idpessoa'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[CPF_CNPJ_ALUNO]]", $pessoa['documento'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[EMAIL_ALUNO]]", $pessoa['email'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[CURSO]]", ($curso['nome']), $emailBoasVindas);

                $emailBoasVindas = str_ireplace("[[OFERTA]]", ($oferta['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[POLO]]", ($escola['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[INSTITUICAO]]", ($sindicato['nome']), $emailBoasVindas);

                $emailBoasVindas = utf8_decode($emailBoasVindas);

                $nomeDe = utf8_decode($GLOBALS['config']['tituloSistema'] . ' - ' . $GLOBALS['config']['tituloEmpresa']);
                if ($curso['email']) {
                    $emailDe = $curso['email'];
                } else {
                    $emailDe = $GLOBALS['config']['emailSistema'];
                }
                $assunto = 'BEM VINDO AO CURSO';
                $nomePara = utf8_decode($pessoa['nome']);
                $emailPara = $pessoa['email'];

                $this->acessoBanco->enviarEmail($nomeDe, $emailDe, $assunto, $emailBoasVindas, $nomePara, $emailPara);

            }

            if ($curso['sms_boas_vindas_instituicao'])
                $smsBoasVindas = $curso['sms_boas_vindas_instituicao'];
            else
                $smsBoasVindas = $curso['sms_boas_vindas'];

            if ($smsBoasVindas && $pessoa['celular'] && $GLOBALS['config']['integrado_com_sms']) {
                $smsBoasVindas = str_ireplace("[[MATRICULA]]", $matricula['idmatricula'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[NUMERO_CONTRATO]]", $matricula['numero_contrato'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[VALOR_CONTRATO]]", number_format($matricula['valor_contrato'], 2, ',', '.'), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[FORMA_PAGAMENTO]]", ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula['forma_pagamento']]), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[QUANTIDADE_PARCELAS]]", $matricula['quantidade_parcelas'], $smsBoasVindas);

                $smsBoasVindas = str_ireplace("[[NOME_ALUNO]]", ($pessoa['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[ID_ALUNO]]", $pessoa['idpessoa'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[CPF_CNPJ_ALUNO]]", $pessoa['documento'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[EMAIL_ALUNO]]", $pessoa['email'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[CURSO]]", ($curso['nome']), $smsBoasVindas);

                $smsBoasVindas = str_ireplace("[[OFERTA]]", ($oferta['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[POLO]]", ($escola['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[INSTITUICAO]]", ($sindicato['nome']), $smsBoasVindas);

                $smsBoasVindas = html_entity_decode($smsBoasVindas);

                if ($smsBoasVindas) {
                    $this->enviarSms($matricula['idmatricula'], 'M', $pessoa['nome'], $pessoa['celular'], $smsBoasVindas, $pessoa['idpessoa']);
                }
            }
        }
    }

    private function enviarSms($idchave, $origem, $nome, $celular, $sms, $idpessoa)
    {

        require_once '../../../../classes/sms.class.php';

        $smsobj = new Sms();

        $smsobj->Set('idchave', $idchave);
        $smsobj->Set('origem', $origem);

        $smsobj->Set('url_webservicesms', $GLOBALS['config']['linkapiSMS']);

        $contaSms = $this->retornarContaSms($idpessoa);
        if ($contaSms) { //conta SMS local
            $dados_gateway = array(
                'loginSMS' => $contaSms['login'],
                'tokenSMS' => $contaSms['token'],
                'celular' => $celular,
                'nome' => $nome,
                'mensagem' => $sms
            );
        } else {
            $dados_gateway = array( //conta SMS geral
                'loginSMS' => $GLOBALS['config']['loginSMS'],
                'tokenSMS' => $GLOBALS['config']['tokenSMS'],
                'celular' => $celular,
                'nome' => $nome,
                'mensagem' => $sms
            );
        }

        $smsobj->Set('dado_seguro', $dados_gateway);
        $smsobj->ExecutaIntegraSMS();
    }

    private function retornarContaSms($id)
    {

        $sql = "SELECT
                  i.sms, i.sms_login as login, i.sms_token as token , m.idsindicato
                FROM
                  matriculas m
                INNER JOIN sindicatos i ON (i.idsindicato = m.idsindicato)
                WHERE m.idpessoa = " . $id;

        $contaSms = $this->acessoBanco->retornarLinha($sql);

        if ($contaSms['sms'] == 'S') {
            return $contaSms;
        }

        return false;
    }

    private function retornarSituacaoFinanceiro($nomeSituacao)
    {
        $sql = "SELECT 
                    * 
                FROM 
                    contas_workflow 
                WHERE 
                    ativo = 'S'
                    AND nome = '" . $nomeSituacao . "'";
        return $this->acessoBanco->retornarLinha($sql);
    }
}