<?php
class Relatorio extends Core
{
    //Gera os dados do relatório
    function gerarRelatorio()
    {
        //INÍCIO Filtros da data de vencimento da conta
        if ($_GET['vencimento_conta'] == 'PER') {
            $data_aux_de = $_GET['de'];
            $data_aux_ate = $_GET['ate'];

            if (!$data_aux_de || !$data_aux_ate) {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'datas_obrigatorias';
                return $retorno;
            }

            if (dataDiferenca(formataData($data_aux_de, 'en', 0), formataData($data_aux_ate, 'en', 0), 'D') > 365) {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'intervalo_maior_um_ano';
                return $retorno;
            }
            #VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - ##### SEM DIA NA DATA - FIM
        } elseif (!$_GET['vencimento_conta']) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'tipo_data_filtro_vazio';
			return $retorno;
        }

        $filtroConta = '';
        if ($data_aux_de) {
            $data = explode("-", formataData($data_aux_de,'en',0));
            $dataDe = date('Y-m-d', mktime(0, 0, 0, $data[1], $data[2], $data[0] - 1));
            $filtroConta .= " AND (con.data_vencimento >= '". $dataDe ." 00:00:00') ";
            $anoAnterior = substr(formataData($data_aux_de,'en',0), 0, 4) - 1;
            $anoAtual = substr(formataData($data_aux_de,'en',0), 0, 4);
        }
        if ($data_aux_ate) {
            $data = explode("-", formataData($data_aux_ate,'en',0));
            $dataAte = date('Y-m-d', mktime(0, 0, 0, $data[1], $data[2], $data[0] - 1));;
            $filtroConta .= " AND (con.data_vencimento <= '". $dataAte ." 23:59:59') ";
        }

        if ($_GET['vencimento_conta'] == 'HOJ') {
            $filtroConta .= ' AND DATE_FORMAT(con.data_vencimento,"%Y-%m-%d") = "' . date('Y-m-d') . '"';
        } elseif ($_GET['vencimento_conta'] == 'ONT') {
            $filtroConta .= ' AND DATE_FORMAT(con.data_vencimento,"%Y-%m-%d") = "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))) . '"';
        } elseif ($_GET['vencimento_conta'] == 'SET') {
            $filtroConta .= ' AND DATE_FORMAT(con.data_vencimento,"%Y-%m-%d") <= "' . date('Y-m-d').'"
                          AND DATE_FORMAT(con.data_vencimento,"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y'))) . '"';
        } elseif ($_GET['vencimento_conta'] == 'QUI') {
            $filtroConta .= ' AND DATE_FORMAT(con.data_vencimento,"%Y-%m-%d") <= "' . date('Y-m-d').'"
                          AND DATE_FORMAT(con.data_vencimento,"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 15, date('Y'))) . '"';
        } elseif ($_GET['vencimento_conta'] == 'MAT') {
            $filtroConta .= ' AND DATE_FORMAT(con.data_vencimento,"%Y-%m") = "' . date('Y-m').'"';
        } elseif ($_GET['vencimento_conta'] == 'MPR') {
            $filtroConta .= ' AND DATE_FORMAT(con.data_vencimento,"%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '"';
        } elseif ($_GET['vencimento_conta'] == 'MAN') {
            $filtroConta .= ' AND DATE_FORMAT(con.data_vencimento,"%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))) . '"';
        }

        if(!$anoAnterior){
           $anoAnterior = date('Y') - 1;
           $anoAtual = date('Y');
        }

        $this->campos .= ',(
                            SELECT
                                SUM(ccc.valor)
                            FROM
                                contas con
                                INNER JOIN contas_centros_custos ccc ON (ccc.idconta = con.idconta AND ccc.ativo = "S")
                                INNER JOIN contas_workflow cw ON (cw.idsituacao = con.idsituacao AND cw.ativo = "S" AND cw.pago = "S")
                            WHERE
                                con.idsindicato = i.idsindicato AND
                                con.idcategoria = c.idcategoria AND
                                con.idsubcategoria = cs.idsubcategoria AND
                                ccc.idcentro_custo = cc.idcentro_custo AND
                                con.tipo = "despesa" AND
                                DATE_FORMAT(con.data_vencimento,"%Y") = '.$anoAnterior.' AND
                                con.ativo = "S"
                                ' . $filtroConta . '
                        ) AS ano_anterior_realizado';
        //FIM Filtros da data de vencimento da conta

        $this->sql = 'SELECT
                            ' . $this->campos . '
                        FROM
                            sindicatos i
                            INNER JOIN escolas e ON (e.idsindicato = i.idsindicato AND e.ativo = "S")
                            INNER JOIN centros_custos_sindicatos cci ON (cci.idsindicato = i.idsindicato AND cci.ativo = "S")
                            INNER JOIN centros_custos cc ON (cc.idcentro_custo = cci.idcentro_custo AND cc.ativo = "S" AND cc.ativo_painel = "S")
                            INNER JOIN obz_subcategorias_centros osc ON (osc.idsindicato = i.idsindicato AND osc.idcentro_custo = cc.idcentro_custo AND osc.ativo = "S")
                            INNER JOIN categorias_subcategorias cs ON (cs.idsubcategoria = osc.idsubcategoria AND cs.ativo = "S" AND cs.ativo_painel = "S")
                            INNER JOIN categorias c ON (c.idcategoria = cs.idcategoria AND c.ativo = "S" AND c.ativo_painel = "S")
                            LEFT JOIN obz_sindicatos_usuarios_adm oiua ON (oiua.idsindicato = i.idsindicato AND oiua.idcentro_custo = cc.idcentro_custo AND oiua.ativo = "S")
                            LEFT JOIN usuarios_adm uar ON (uar.idusuario = oiua.idresponsavel)
                            LEFT JOIN usuarios_adm uad ON (uad.idusuario = oiua.iddono)
                            LEFT JOIN obz_orcamentos_gastos oog ON (oog.idsindicato = i.idsindicato AND oog.idescola = e.idescola AND oog.idcentro_custo = cc.idcentro_custo AND oog.idcategoria = c.idcategoria AND oog.idsubcategoria = cs.idsubcategoria AND oog.ano = '.$anoAtual.')
                        WHERE
                            i.ativo = "S"';

//		if($_SESSION['adm_gestor_instituicao'] != 'S'){
//			  $this->sql .= ' AND i.idsindicato IN ('.$_SESSION['adm_sindicatos'].')';
//		}
//
//        if($_SESSION['adm_gestor_polo'] != 'S'){
//            $this->sql .= ' AND p.idpolo IN ('.$_SESSION['adm_polos'].')';
//        }
        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                $campo = explode('|',$campo);
                $valor = str_replace('\'','',$valor);

                if (($valor || $valor === '0') && $valor != 'todos') {
                    if ($campo[0] == 1) {
						if ($valor == 'sem_cidade'){
                            $this->sql .= ' AND ' . $campo[1] . ' IS NULL ';
                        } else {
                            $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                        }
                    } elseif ($campo[0] == 2)  {
                        $this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($valor) . '%"';
                    } elseif ($campo[0] == 'de_ate') {
                        if ($valor == 'HOJ') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") = "' . date('Y-m-d') . '"';
                        } elseif ($valor == 'ONT') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") = "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))) . '"';
                        } elseif ($valor == 'SET') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") <= "' . date('Y-m-d').'"
                                          AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y'))) . '"';
                        } elseif ($valor == 'QUI') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") <= "' . date('Y-m-d').'"
                                          AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 15, date('Y'))) . '"';
                        } elseif ($valor == 'MAT') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m').'"';
                        } elseif ($valor == 'MPR') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '"';
                        } elseif ($valor == 'MAN') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))) . '"';
                        }
                    }
                }
            }
        }

//		if ($_GET['idpolo']) {
//		  	$this->sql .= ' AND p.idpolo IN ('.implode(', ', $_GET['idpolo']).')';
//		}

        if ($_GET['idcentro_custo']) {
            $this->sql .= ' AND cc.idcentro_custo IN ('.implode(', ', $_GET['idcentro_custo']).')';
        }

        $this->sql .= " GROUP BY i.idsindicato,
                                e.idescola,
                                cc.idcentro_custo,
                                c.idcategoria,
                                cs.idsubcategoria";
        $this->ordem = null;
        $retorno = $this->retornarLinhas();
        if(count($retorno)){
            $retorno["anoAnterior"] = $anoAnterior;
        }
        //print_r2($this->sql);
		return $retorno;
    }

    /**
     *
     *
     * @param $dados
     * @param null $q
     * @param $idioma
     * @param string $configuracao
     */
    function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem")
    {
        // Buscando os idiomas do formulario
        include('idiomas/' . $this->config['idioma_padrao'] . '/index.php');
        echo '<table class="zebra-striped" id="sortTableExample">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Filtro</th>';
        echo '<th>Valor</th>';
        echo '</tr>';
        echo '</thead>';

        foreach ($this->config["formulario"] as $ind => $fieldset){
            foreach ($fieldset["campos"] as $ind => $campo){
                if ($campo["nome"][0] == "q"){
                  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
                  $campoAux = $_GET["q"][$campoAux];

                    if(empty($campoAux))
                    {
                        $campoAux = '""';
                    }


                  if ($campo["sql_filtro"]){
                    if($campo["sql_filtro"] == "array"){
                      $campoAuxNovo = str_replace(array("q[","]"),"",$campo["nome"]);
                      $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAuxNovo]];
                    } else {
                      $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
                        $seleciona = $this->executaSql($sql);
                        $linha = mysql_fetch_assoc($seleciona);
                      $campoAux = $linha[$campo["sql_filtro_label"]];
                    }
                  }
                } elseif(is_array($_GET[$campo["nome"]])){
                  if($campo["array"]){
                      foreach($_GET[$campo["nome"]] as $ind => $val){
                         $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
                      }
                  } elseif($campo["sql_filtro"]){
                      foreach($_GET[$campo["nome"]] as $ind => $val){
                         $sql = str_replace("%",$val,$campo["sql_filtro"]);
                          $seleciona = $this->executaSql($sql);
                          $linha = mysql_fetch_assoc($seleciona);
                         $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
                      }
                  }

                  $campoAux = implode(", ", $_GET[$campo["nome"]]);
                } elseif($campo["sql_filtro"]) {
                    if($campo["sql_filtro"] == "array"){
                        $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET[$campo["nome"]]];
                    } else {
                        $sql = str_replace("%",'"' . $campoAux . '"', $campo["sql_filtro"]);
                        $seleciona = $this->executaSql($sql);
                        $linha = mysql_fetch_assoc($seleciona);
                        $campoAux = $linha[$campo["sql_filtro_label"]];
                    }
                } else {
                  $campoAux = $_GET[$campo["nome"]];
                }
                if($campoAux <> "" && $campoAux <> '""'){
                    echo '<tr>';
                    echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';
                    echo '<td>'.$campoAux.'</td>';
                    echo '</tr>';
                }
            }
        }
        echo '</table><br>';
    }

    //Lista Polos da instituição
    function listarPolosJson($idsindicato)
    {
        $this->sql = 'SELECT
                            e.idescola,
                            e.razao_social AS nome
                        FROM
                            escolas e
                        WHERE
                            e.idsindicato = "' . $idsindicato . '" AND
                            e.ativo = "S" AND
                            e.ativo_painel = "S"';

//        if($_SESSION['adm_gestor_polo'] != 'S'){
//            $this->sql .= ' and p.idpolo in ('.$_SESSION['adm_polos'].')';
//        }

        $this->limite = -1;
        $this->ordem_campo = 'e.razao_social';
        $this->ordem = 'ASC';

        return json_encode($this->retornarLinhas());
    }

    //Lista Centros de custo da instituição
    function listarCentrosCustoJson($idsindicato)
    {
        $this->sql = 'SELECT
                            cc.idcentro_custo,
                            cc.nome
                        FROM
                            centros_custos cc
                            INNER JOIN centros_custos_sindicatos cci ON (cci.idcentro_custo = cc.idcentro_custo AND cci.ativo = "S")
                        WHERE
                            cci.idsindicato = "' . $idsindicato . '" AND
                            cc.ativo = "S" AND
                            cc.ativo_painel = "S"
                        GROUP BY
                            cc.idcentro_custo';

        $this->limite = -1;
        $this->ordem_campo = 'cc.nome';
        $this->ordem = 'ASC';


        return json_encode($this->retornarLinhas());
    }

    function EnviarRelatorio($arquivo)
    {
        include('idiomas/' . $this->config['idioma_padrao'] . '/index.php');

        $message  = 'Olá <strong>' . $GLOBALS['usuario']['nome'] . '</strong>,
                    <br><br>
                    Segue em anexo o ' . $idioma['pagina_titulo'] . '.';

        $assunto = 'Você acabou de receber o ' . $idioma['pagina_titulo'] . '!';

        $nomeDe = $GLOBALS['config']['tituloEmpresa'];
        $emailDe = $GLOBALS['config']['emailSistema'];

        $emailPara  = $GLOBALS['usuario']["email"];
        $nomePara  = $GLOBALS['usuario']["nome"];

        $this->anexoEmail = $arquivo;
        return $this->EnviarEmail($nomeDe,$emailDe,utf8_decode($assunto),utf8_decode($message),$nomePara,$emailPara,"layout");
    }
    public function retornaDatas()
    {
        $arrayDatas = array();
        if($_GET['vencimento_conta'] == 'PER'){
            //Periodo definido pelo usuário
            $primeiraData = formataData($_GET['de'], 'en', 0);
            $ultimaData = formataData($_GET['ate'], 'en', 0);
            $primeira_data = $primeiraData;
            $ultima_data = $ultimaData;
            $arrayDatas["primeiraData"] = $primeira_data;
            $arrayDatas["ultimaData"] = $ultima_data;
            $arrayDatas["ano"] = substr($primeiraData,0, 4);
        }else if ($_GET['vencimento_conta'] == 'HOJ') {
            //Hoje
            $primeira_data = date("Y-m-d");
            $ultima_data = date("Y-m-d");
            $arrayDatas["primeiraData"] = $primeira_data;
            $arrayDatas["ultimaData"] = $ultima_data;
            $arrayDatas["ano"] = substr(date("Y-m-d"),0, 4);
        }else if ($_GET['vencimento_conta'] == 'ONT') {
            //Ontem
            $dataOntem = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
            $primeira_data = $dataOntem;
            $ultima_data = $dataOntem;
            $arrayDatas["primeiraData"] = $primeira_data;
            $arrayDatas["ultimaData"] = $ultima_data;
            $arrayDatas["ano"] = substr($dataOntem,0, 4);
        }else if ($_GET['vencimento_conta'] == 'SET') {
            //Última semana
            $dataHoje = date("Y-m-d");
            $dataUltimaSemana = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y')));
            $primeira_data = $dataUltimaSemana;
            $ultima_data = $dataHoje;
            $arrayDatas["primeiraData"] = $primeira_data;
            $arrayDatas["ultimaData"] = $ultima_data;
            $arrayDatas["ano"] = substr($dataUltimaSemana,0, 4);
        }else if ($_GET['vencimento_conta'] == 'QUI') {
            //QUI
            $dataHoje = date("Y-m-d");
            $dataUltimaQuinzena = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 16, date('Y')));
            $primeira_data = $dataUltimaQuinzena;
            $ultima_data = $dataHoje;
            $arrayDatas["primeiraData"] = $primeira_data;
            $arrayDatas["ultimaData"] = $ultima_data;
            $arrayDatas["ano"] = substr($dataUltimaQuinzena,0, 4);
        }else if ($_GET['vencimento_conta'] == 'MAT') {
            //Mês atual
            $mesAtual = date("Y-m");
            $dataMesAtual = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
            $primeira_data = $dataMesAtual;
            $ultima_data = $dataMesAtual;
            $arrayDatas["primeiraData"] = $primeira_data;
            $arrayDatas["ultimaData"] = $ultima_data;
            $arrayDatas["ano"] = substr($dataMesAtual,0, 4);
        }else if ($_GET['vencimento_conta'] == 'MPR') {
            //Próximo mês
            $proximoMes = date('Y-m', mktime(0, 0, 0, date('m') + 1,  date('d'), date('Y')));
            $dataProximoMes = date('Y-m-d', mktime(0, 0, 0, date('m') + 1,  date('d'), date('Y')));
            $primeira_data = $dataProximoMes;
            $ultima_data = $dataProximoMes;
            $arrayDatas["primeiraData"] = $primeira_data;
            $arrayDatas["ultimaData"] = $ultima_data;
            $arrayDatas["ano"] = substr($dataProximoMes,0, 4);
        }else if ($_GET['vencimento_conta'] == 'MAN') {
            //Mês anterior
            $mesAnterior = date('Y-m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));
            $dataMesAnterior = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));
            $primeira_data = $dataMesAnterior;
            $ultima_data = $dataMesAnterior;
            $arrayDatas["primeiraData"] = $primeira_data;
            $arrayDatas["ultimaData"] = $ultima_data;
            $arrayDatas["ano"] = substr($dataMesAnterior,0, 4);
        }

        return $arrayDatas;
    }

    /**
     *
     * Retorna uma string formatada para ser usada na query
     *
     * @author waglero
     * @param string $primeiraData data inicial
     * @param string $ultimaData data final
     * @access private
     * @return string
     */
    public function montarQueryFiltroData($primeiraData, $ultimaData)
    {
        $data_aux_de = $primeiraData;
        $data_aux_ate = $ultimaData;
        $valAux = explode("-",$data_aux_de);
        $valAux2 = explode("-",$data_aux_ate);
        $primeiroMes = $valAux[1];
        $ultimoMes = $valAux2[1];
        $retorno = array();

        if(substr($primeiroMes, 0, 1) == 0){
            $primeiroMes = (int)substr($primeiroMes,1);
        }
        if(substr($ultimoMes, 0, 1) == 0){
            $ultimoMes = (int)substr($ultimoMes,1);
        }

        $stringOrcado = "";
        for($i = $primeiroMes; $i <= $ultimoMes; $i++){
            if($i + 1 > $ultimoMes){
                $retorno["orcado"] .= "IFNULL(oog.mes_$i, 0)";
                $retorno["meses"] .= "oog.mes_$i";
            }else{
                $retorno["orcado"] .= "IFNULL(oog.mes_$i, 0) + ";
                $retorno["meses"] .= "oog.mes_$i,";
            }
        }
        return $retorno;
    }
}
