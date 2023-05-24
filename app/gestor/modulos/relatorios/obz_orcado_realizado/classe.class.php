<?php
class Relatorio extends Core
{
    //Gera os dados do relatório*/
    public function gerarRelatorio()
    {
        if ($_GET['de_ate'] == 'PER') {
            if (!$_GET['de'] || !$_GET['ate']) {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'datas_obrigatorias';
                return $retorno;
            }
            if (dataDiferenca(formataData($_GET['de'], 'en', 0), formataData($_GET['ate'], 'en', 0), 'D') > 365) {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'intervalo_maior_um_ano';
                return $retorno;
            }
        }

        $dadosTipoPeriodo = $this->geraDadosTipoPeriodo($_GET['de_ate']);

        $this->set('pagina', 1)
            ->set('ordem', 'DESC')
            ->set('limite', -1)
            ->set('ordem_campo', 'cc.nome')
            ->set(
                'campos',
                'cc.nome AS centro_custo,
                i.nome AS sindicato,
                c.nome AS categoria,
                cs.nome AS subcategoria,
                e.nome_fantasia as cfc,
                e.idescola,
                oog.ano,
                oog.justificativa_1,
                oog.justificativa_2,
                oog.justificativa_3,
                oog.justificativa_4,
                oog.justificativa_5,
                oog.justificativa_6,
                oog.justificativa_7,
                oog.justificativa_8,
                oog.justificativa_9,
                oog.justificativa_10,
                oog.justificativa_11,
                oog.justificativa_12,
                oog.memorial,
                oog.idcentro_custo,
                oog.idsindicato,
                oog.idcategoria,
                oog.idsubcategoria,
                (SELECT GROUP_CONCAT(ooc.idordemdecompra) FROM obz_ordem_compra ooc
                    WHERE ooc.idsubcategoria = oog.idsubcategoria
                    AND ooc.idcategoria = oog.idcategoria
                    AND ooc.idsindicato = oog.idsindicato
                    AND ooc.idcentro_custo = oog.idcentro_custo GROUP BY oog.idsindicato ) as ordem_de_compra,
                    '.$dadosTipoPeriodo['string']["meses"].',
                    IFNULL(ua_dono.nome, "Sem dono") as dono,
                    IFNULL(ua_resp.nome, "Sem responsável") as responsavel,
                    ('.$dadosTipoPeriodo['string']["orcado"].') as orcado'
               );

        $this->sql = "SELECT
                        {$this->campos}
                      FROM
                        obz_orcamentos_gastos oog
                            INNER JOIN sindicatos i
                                ON (i.idsindicato = oog.idsindicato)
                            INNER JOIN centros_custos cc
                                ON (cc.idcentro_custo = oog.idcentro_custo
                                    AND cc.ativo = 'S'
                                    AND cc.ativo_painel = 'S')
                            INNER JOIN escolas e
                                ON (e.idescola = oog.idescola
                                    AND e.ativo = 'S'
                                    AND e.ativo_painel = 'S')
                            INNER JOIN categorias c
                                ON (c.idcategoria = oog.idcategoria
                                    AND c.ativo = 'S'
                                    AND c.ativo_painel = 'S')
                            INNER JOIN categorias_subcategorias cs
                                ON (cs.idsubcategoria = oog.idsubcategoria
                                    AND cs.ativo = 'S'
                                    AND cs.ativo_painel = 'S')
                            LEFT JOIN obz_sindicatos_usuarios_adm aiua_resp
                                ON (aiua_resp.idcentro_custo = oog.idcentro_custo
                                    AND oog.idsindicato = aiua_resp.idsindicato)
                            LEFT JOIN obz_sindicatos_usuarios_adm aiua_dono
                                ON (aiua_dono.idcentro_custo = oog.idcentro_custo
                                    AND oog.idsindicato = aiua_dono.idsindicato)
                            LEFT JOIN usuarios_adm ua_dono
                                ON (ua_dono.idusuario = aiua_dono.iddono)
                            LEFT JOIN usuarios_adm ua_resp
                                ON (ua_resp.idusuario = aiua_resp.idresponsavel)
                      WHERE
                        cc.ativo = 'S'
                        AND oog.ano = '{$dadosTipoPeriodo['ano']}'";


        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                $campo = explode('|',$campo);
                $valor = str_replace('\'','',$valor);
                if (($valor || $valor === '0') && $valor != 'todos') {
                    if ($campo[0] == 1) {
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                    } elseif ($campo[0] == 2)  {
                        $this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($valor) . '%"';
                    }
                }
            }
        }

        if(is_array($_GET["idcentro_custo"])){
            $this->sql .= " AND oog.idcentro_custo IN (";
                $this->sql .= implode(", ", $_GET["idcentro_custo"]);
                $this->sql .= " ) ";
        }

        if(is_array($_GET["idescola"])){
            $this->sql .= " AND oog.idescola IN (";
                $this->sql .= implode(", ", $_GET["idescola"]);
                $this->sql .= " ) ";
        }

        $this->sql .= " GROUP BY oog.idsindicato,
                                 oog.idcentro_custo,
                                 oog.idescola,
                                 oog.idcategoria,
                                 oog.idsubcategoria";

        $orcados = $this->executaSql($this->sql);

        while($linha = mysql_fetch_assoc($orcados)) {
            $ind = $linha["idsindicato"].$linha["idcentro_custo"].$linha["idcategoria"].$linha["idsubcategoria"].$linha["idescola"];
            $retorno[$ind] = $linha;
            $retorno[$ind]["orcado"] = $linha["orcado"];
        }

        $this->set('pagina', 1)
             ->set('ordem', 'DESC')
             ->set('limite', -1)
             ->set('ordem_campo', 'cc.nome')
             ->set('campos','con.idsindicato,
                             ccc.idcentro_custo, 
                             con.idcategoria,
                             con.idsubcategoria,
							 con.idescola,
                             SUM(ccc.valor) as realizado,
                             cc.nome AS centro_custo,
                             i.nome AS sindicato,
                             c.nome AS categoria,
                             cs.nome AS subcategoria,
                             e.nome_fantasia as cfc,
                             ccc.idconta_centro_custo,
                             IFNULL(ua_dono.nome, "Sem dono") as dono,
                             IFNULL(ua_resp.nome, "Sem responsável") as responsavel');

        $this->sql = "SELECT
                        {$this->campos}
                      FROM
                        contas_centros_custos ccc
                            INNER JOIN contas con
                                ON (ccc.idconta = con.idconta
                                    AND ccc.ativo = 'S')
                            INNER JOIN centros_custos cc
                                ON (cc.idcentro_custo = ccc.idcentro_custo
                                    AND cc.ativo = 'S')
                            INNER JOIN sindicatos i
                                ON (i.idsindicato = con.idsindicato)
                            INNER JOIN contas_workflow cw
                                ON (cw.idsituacao = con.idsituacao
                                    AND cw.ativo = 'S'
                                    AND cw.pago = 'S'
                                    /*AND cw.cancelada <> 'S'
                                    AND cw.renegociada <> 'S'
                                    AND cw.transferida <> 'S'*/)
                            LEFT JOIN escolas e
                                ON (e.idescola = con.idescola AND
                                    e.ativo = 'S'
                                    AND e.ativo_painel = 'S')
                            INNER JOIN categorias c
                                ON (c.idcategoria = con.idcategoria
                                    AND c.ativo = 'S'
                                    AND c.ativo_painel = 'S')
                            INNER JOIN categorias_subcategorias cs
                                ON (cs.idsubcategoria = con.idsubcategoria
                                    AND cs.ativo = 'S'
                                    AND cs.ativo_painel = 'S')
                            LEFT JOIN obz_sindicatos_usuarios_adm aiua_resp
                                ON (aiua_resp.idcentro_custo = ccc.idcentro_custo
                                    AND i.idsindicato = aiua_resp.idsindicato)
                            LEFT JOIN obz_sindicatos_usuarios_adm aiua_dono
                                ON (aiua_dono.idcentro_custo = ccc.idcentro_custo
                                    AND i.idsindicato = aiua_dono.idsindicato)
                            LEFT JOIN usuarios_adm ua_dono
                                ON (ua_dono.idusuario = aiua_dono.iddono)
                            LEFT JOIN usuarios_adm ua_resp
                                ON (ua_resp.idusuario = aiua_resp.idresponsavel)
                      WHERE
                         con.tipo = 'despesa'
                         AND ".$dadosTipoPeriodo['stringConta']."
                         AND con.ativo = 'S'";

        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                $campo = explode('|',$campo);
                $valor = str_replace('\'','',$valor);
                if (($valor || $valor === '0') && $valor != 'todos') {
                    if ($campo[0] == 1) {
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                    } elseif ($campo[0] == 2)  {
                        $this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($valor) . '%"';
                    }
                }
            }
        }

        if(is_array($_GET["idcentro_custo"])){
            $this->sql .= " AND ccc.idcentro_custo IN (";
                $this->sql .= implode(", ", $_GET["idcentro_custo"]);
                $this->sql .= " ) ";
        }


        $this->sql .= " GROUP BY con.idsindicato,
                                 ccc.idcentro_custo,
                                 con.idcategoria,
								 con.idescola,
                                 con.idsubcategoria";

        $realizados = $this->executaSql($this->sql);

        while($linha = mysql_fetch_assoc($realizados)) {

            $ind = $linha["idsindicato"].$linha["idcentro_custo"].$linha["idcategoria"].$linha["idsubcategoria"].$linha["idescola"];

            if(!$retorno[$ind]["orcado"]){
                $retorno[$ind]["orcado"] = 0.00;
            }
            $retorno[$ind]["realizado"] = $linha["realizado"];
            $retorno[$ind]["idsindicato"] = $linha["idsindicato"];
            $retorno[$ind]["idcentro_custo"] = $linha["idcentro_custo"];
            $retorno[$ind]["idcategoria"] = $linha["idcategoria"];
            $retorno[$ind]["idsubcategoria"] = $linha["idsubcategoria"];
            $retorno[$ind]["ano"] = $dadosTipoPeriodo['ano'];
            $retorno[$ind]["idescola"] = $linha["idescola"];
            $retorno[$ind]["categoria"] = $linha["categoria"];
            $retorno[$ind]["subcategoria"] = $linha["subcategoria"];
            $retorno[$ind]["cfc"] = $linha["cfc"];
            $retorno[$ind]["dono"] = $linha["dono"];
            $retorno[$ind]["responsavel"] = $linha["responsavel"];
            $retorno[$ind]["centro_custo"] = $linha["centro_custo"];
        }

        foreach($retorno as $ind => $ret){
            if(is_int($ind)){
                if($ret["orcado"] == 0.00 && $ret["realizado"] == 0.00){
                    unset($retorno[$ind]);
                }
            }
        }

        $retorno["quantidadeLinhas"] = count($retorno);
        $meses = array("mes_1", "mes_2", "mes_3", "mes_4", "mes_5", "mes_6", "mes_7", "mes_8", "mes_9", "mes_10", "mes_11", "mes_12");
        $mesesAux = array("mes_1" => "JAN", "mes_2" => "FEV", "mes_3" => "MAR", "mes_4" => "ABR", "mes_5" => "MAI", "mes_6" => "JUN", "mes_7" => "JUL", "mes_8" => "AGO", "mes_9" => "SET", "mes_10" => "OUT", "mes_11" => "NOV", "mes_12" => "DEZ");


        //VERIFICA SE ESTA DENTRO DO MESMO MÊS O FILTRO
        $datainiciojutificativa = explode('-',$dadosTipoPeriodo["primeira_data"]);
        $datafimjutificativa = explode('-',$dadosTipoPeriodo["ultima_data"]);
        if($datainiciojutificativa[1] == $datafimjutificativa[1]){
          $liberaJustificativa = (int) $datainiciojutificativa[1];
        }else{
            $contIni = (int) $datainiciojutificativa[1];
            $contFim = (int) $datafimjutificativa[1];
            $liberaJustificativa = array();
            for($i=$contIni;$i<=$contFim;$i++){
                $liberaJustificativa[$i] = $i;
            }
        }

        if($retorno["quantidadeLinhas"]){
          $retorno["ano"] = $dadosTipoPeriodo['ano'];
          $retorno = $this->calculaValores($retorno, $liberaJustificativa);
          $retorno["stringConta"] = $dadosTipoPeriodo['stringConta'];
          $retorno["stringContaAnterior"] = $dadosTipoPeriodo['stringContaAnterior'];
        }

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
    public function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {
        // Buscando os idiomas do formulario
        include("idiomas/pt_br/index.php");
        echo '<table class="zebra-striped" id="sortTableExample">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Filtro</th>';
        echo '<th>Valor</th>';
        echo '</tr>';
        echo '</thead>';
        foreach($this->config["formulario"] as $ind => $fieldset){
            foreach($fieldset["campos"] as $ind => $campo){
                if($campo["nome"][0] == "q"){
                  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
                  $campoAux = $_GET["q"][$campoAux];
                  if($campo["sql_filtro"]){
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
         } else {
          $campoAux = $_GET[$campo["nome"]];
         }
              if($campoAux <> ""){
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
    public function listarPolosJson($idsindicato)
    {
        $this->sql = 'SELECT
                        e.idescola,
                        e.razao_social AS nome
                        FROM
                        escolas e
                        WHERE
                        e.idsindicato = "' . $idsindicato . '" AND
                        e.ativo = "S"';

        $this->limite = -1;
        $this->ordem_campo = 'e.razao_social';
        $this->ordem = 'ASC';
        return json_encode($this->retornarLinhas());
    }
    //Lista Centros de custo da instituição
    public function listarCentrosCustoJson($idsindicato)
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

    public function gerarDadosContas($dados,$array_categorias, $array_subcategorias, $stringCondicaoConta)
    {

      $categorias = array_unique($array_categorias);
      $subcategorias = array_unique($array_subcategorias);

      $this->sql = "SELECT
                      DATE_FORMAT(con.data_vencimento,'%m') as mes,
                      SUM(ccc.valor) AS total
                    FROM
                      contas con
                    INNER JOIN contas_centros_custos ccc
                        ON (ccc.idconta = con.idconta )
                    LEFT JOIN escolas e
                        ON (e.idescola = con.idescola and
                            e.ativo='S')
                    INNER JOIN sindicatos i
                        ON (i.idsindicato = con.idsindicato )
                  INNER JOIN contas_workflow cw
                        ON (cw.idsituacao = con.idsituacao)
                  WHERE
                  con.tipo = 'despesa'
                  AND e.ativo = 'S'
                  AND i.ativo = 'S'
                  AND cw.ativo = 'S'
                  AND cw.pago = 'S'
                  /*AND cw.cancelada <> 'S'
                  AND cw.renegociada <> 'S'
                  AND cw.transferida <> 'S'*/
                  AND con.ativo = 'S'
                  AND ccc.ativo = 'S'
                  AND ".$stringCondicaoConta."";

    if (is_array($_GET['q'])) {
        foreach ($_GET['q'] as $campo => $valor) {
            $campo = explode('|',$campo);
            $valor = str_replace('\'','',$valor);
            if (($valor || $valor === '0') && $valor != 'todos') {
                if ($campo[0] == 1) {
                    $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                } elseif ($campo[0] == 2)  {
                    $this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($valor) . '%"';
                }
            }
        }
    }

    if(is_array($_GET["idcentro_custo"])){
        $this->sql .= " AND ccc.idcentro_custo IN (";
            $this->sql .= implode(", ", $_GET["idcentro_custo"]);
            $this->sql .= " ) ";
    }
    if(is_array($_GET["idescola"])){
        $this->sql .= " AND e.idescola IN (";
            $this->sql .= implode(", ", $_GET["idescola"]);
            $this->sql .= " ) ";
    }

    $this->sql .= " GROUP BY mes";
    $this->limite = -1;
    $this->groupby = "con.idconta";
    $this->ordem_campo = "con.idconta";
    //echo "<!--".$this->sql."-->";
    $dadosAux = $this->retornarLinhas();

    foreach($dadosAux as $index => $dado){
        $total += $dado["total"];
        if(substr($dado["mes"], 0, 1) == 0){
            $dadosAux[$index]["mes"] = substr($dado["mes"],1);
        }
        $dadosAux[$index]["mes"] = $dadosAux[$index]["mes"] - 1;
    }

    $meses = array(0 => "JAN", 1 => "FEV", 2 => "MAR", 3 => "ABR", 4 => "MAI", 5 => "JUN", 6 => "JUL", 7 => "AGO", 8 => "SET", 9 => "OUT", 10 => "NOV", 11 => "DEZ");
    if(!$dados){
        $dados = array(
            array(0 => "JAN", 1 => 0.00),
            array(0 => "FEV", 1 => 0.00),
            array(0 => "MAR", 1 => 0.00),
            array(0 => "ABR", 1 => 0.00),
            array(0 => "MAI", 1 => 0.00),
            array(0 => "JUN", 1 => 0.00),
            array(0 => "JUL", 1 => 0.00),
            array(0 => "AGO", 1 => 0.00),
            array(0 => "SET", 1 => 0.00),
            array(0 => "OUT", 1 => 0.00),
            array(0 => "NOV", 1 => 0.00),
            array(0 => "DEZ", 1 => 0.00),
        );
    }

    if($dados){
        foreach($dados as $i => $d){
            foreach($dadosAux as $key => $val){
                if($meses[$val["mes"]] == $d[0]){
                  $dados[$i][2] = number_format($val["total"], 2, ".", "");
              }
          }
        }
    }

    return $dados;
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
    private function montarQueryFiltroData($primeiraData, $ultimaData)
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
                $retorno["orcado"] .= "IFNULL(mes_$i, 0)";
                $retorno["meses"] .= "oog.mes_$i";
            }else{
                $retorno["orcado"] .= "IFNULL(mes_$i, 0) + ";
                $retorno["meses"] .= "oog.mes_$i,";
            }
        }
        return $retorno;
    }
    public function EnviarRelatorio($arquivo)
    {
        include('idiomas/' . $this->config['idioma_padrao'] . '/index.php');
        $message  = 'Olá <strong>' . $this->nome_usuario . '</strong>,
        <br><br>
        Segue em anexo o ' . $idioma['pagina_titulo'] . '.';
        $assunto = 'Você acabou de receber o ' . $idioma['pagina_titulo'] . '!';
        $nomeDe = $GLOBALS['config']['tituloEmpresa'];
        $emailDe = $GLOBALS['config']['emailSistema'];
        $emailPara = $this->email_usuario;
        $nomePara  = $this->nome_usuario;
        $this->anexoEmail = $arquivo;
        return $this->EnviarEmail($nomeDe,$emailDe,utf8_decode($assunto),utf8_decode($message),$nomePara,$emailPara,"layout");
    }
    public function buscarUsuarios()
    {
        $this->sql = "select
        ua.email as 'key',
        ua.nome as value
        from
        usuarios_adm ua
        where
        ua.nome LIKE '%" . $this->get["tag"] . "%'";
        $this->limite = -1;
        $this->ordem_campo = "ua.nome";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }
    public function retornarNomeUsuario($email){
        $sql = "SELECT nome FROM usuarios_adm WHERE email = '{$email}'";
        $dado = $this->retornarLinha($sql);
        return $dado["nome"];
    }

    public function gerarDadosRealizadoGrafico()
    {

        switch($_GET['de_ate']){
            case 'PER':
            $de = explode("/" , $_GET['de']);
            $aux = $_GET['de'];
            $_GET['de'] = "01/01/".$de[2];
            $dadosTipoPeriodo = $this->geraDadosTipoPer();
            $_GET['de'] = $aux;
            break;
            case 'HOJ':
                $dadosTipoPeriodo = $this->geraDadosTipoHoj(true);
            break;
            case 'ONT':
                $dadosTipoPeriodo = $this->geraDadosTipoOnt(true);
            break;
            case 'QUI':
                $dadosTipoPeriodo = $this->geraDadosTipoQui(true);
            break;
            case 'SET':
                $dadosTipoPeriodo = $this->geraDadosTipoSet(true);
            break;
            case 'MAT':
                $dadosTipoPeriodo = $this->geraDadosTipoMat(true);
            break;
            case 'MPR':
                $dadosTipoPeriodo = $this->geraDadosTipoMpr(true);
            break;
            case 'MAN':
                $dadosTipoPeriodo = $this->geraDadosTipoMan(true);
            break;
        }

        if($dadosTipoPeriodo['trazerMesAnterior']){
           $dadosTipoPeriodo['string']["meses"] = $dadosTipoPeriodo['stringMesAnterior']["meses"];
           $dadosTipoPeriodo['string']["orcado"] = $dadosTipoPeriodo['stringMesAnterior']["orcado"];
           $dadosTipoPeriodo['stringConta'] = $dadosTipoPeriodo['stringContaAnterior'];
        }else{
           $stringMeses = $dadosTipoPeriodo['string']["meses"];
           $stringOrcado = $dadosTipoPeriodo['string']["orcado"];
           $stringRealizado = $dadosTipoPeriodo['stringConta'];
        }

        $this->set('pagina', 1)
            ->set('ordem', 'DESC')
            ->set('limite', -1)
            ->set('ordem_campo', 'cc.nome')
            ->set(
                'campos',
                'cc.nome AS centro_custo,
                i.nome AS sindicato,
                c.nome AS categoria,
                cs.nome AS subcategoria,
                e.nome_fantasia as cfc,
                e.idescola,
                oog.ano,
                oog.justificativa_1,
                oog.justificativa_2,
                oog.justificativa_3,
                oog.justificativa_4,
                oog.justificativa_5,
                oog.justificativa_6,
                oog.justificativa_7,
                oog.justificativa_8,
                oog.justificativa_9,
                oog.justificativa_10,
                oog.justificativa_11,
                oog.justificativa_12,
                oog.memorial,
                oog.idcentro_custo,
                oog.idsindicato,
                oog.idcategoria,
                oog.idsubcategoria,
                (SELECT GROUP_CONCAT(ooc.idordemdecompra) FROM obz_ordem_compra ooc
                    WHERE ooc.idsubcategoria = oog.idsubcategoria
                    AND ooc.idcategoria = oog.idcategoria
                    AND ooc.idsindicato = oog.idsindicato
                    AND ooc.idcentro_custo = oog.idcentro_custo GROUP BY oog.idsindicato ) as ordem_de_compra,
                    '.$dadosTipoPeriodo['string']["meses"].',
                    IFNULL(ua_dono.nome, "Sem dono") as dono,
                    IFNULL(ua_resp.nome, "Sem responsável") as responsavel,
                    ('.$dadosTipoPeriodo['string']["orcado"].') as orcado'
               );

        $this->sql = "SELECT
                        {$this->campos}
                      FROM
                        obz_orcamentos_gastos oog
                            INNER JOIN sindicatos i
                                ON (i.idsindicato = oog.idsindicato)
                            INNER JOIN centros_custos cc
                                ON (cc.idcentro_custo = oog.idcentro_custo
                                    AND cc.ativo = 'S'
                                    AND cc.ativo_painel = 'S')
                            INNER JOIN escolas e
                                ON (e.idescola = oog.idescola
                                    AND e.ativo = 'S'
                                    AND e.ativo_painel = 'S')
                            INNER JOIN categorias c
                                ON (c.idcategoria = oog.idcategoria
                                    AND c.ativo = 'S'
                                    AND c.ativo_painel = 'S')
                            INNER JOIN categorias_subcategorias cs
                                ON (cs.idsubcategoria = oog.idsubcategoria
                                    AND cs.ativo = 'S'
                                    AND cs.ativo_painel = 'S')
                            LEFT JOIN obz_sindicatos_usuarios_adm aiua_resp
                                ON (aiua_resp.idcentro_custo = oog.idcentro_custo
                                    AND oog.idsindicato = aiua_resp.idsindicato)
                            LEFT JOIN obz_sindicatos_usuarios_adm aiua_dono
                                ON (aiua_dono.idcentro_custo = oog.idcentro_custo
                                    AND oog.idsindicato = aiua_dono.idsindicato)
                            LEFT JOIN usuarios_adm ua_dono
                                ON (ua_dono.idusuario = aiua_dono.iddono)
                            LEFT JOIN usuarios_adm ua_resp
                                ON (ua_resp.idusuario = aiua_resp.idresponsavel)
                      WHERE
                        cc.ativo = 'S'
                        AND oog.ano = '{$dadosTipoPeriodo['ano']}'";


        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                $campo = explode('|',$campo);
                $valor = str_replace('\'','',$valor);
                if (($valor || $valor === '0') && $valor != 'todos') {
                    if ($campo[0] == 1) {
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                    } elseif ($campo[0] == 2)  {
                        $this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($valor) . '%"';
                    }
                }
            }
        }

        if(is_array($_GET["idcentro_custo"])){
            $this->sql .= " AND oog.idcentro_custo IN (";
                $this->sql .= implode(", ", $_GET["idcentro_custo"]);
                $this->sql .= " ) ";
        }

        if(is_array($_GET["idescola"])){
            $this->sql .= " AND oog.idescola IN (";
                $this->sql .= implode(", ", $_GET["idescola"]);
                $this->sql .= " ) ";
        }

        $this->sql .= " GROUP BY oog.idsindicato,
                                 oog.idcentro_custo,
                                 oog.idescola,
                                 oog.idcategoria,
                                 oog.idsubcategoria";

        $orcados = $this->executaSql($this->sql);

        while($linha = mysql_fetch_assoc($orcados)) {
            $ind = $linha["idsindicato"].$linha["idcentro_custo"].$linha["idcategoria"].$linha["idsubcategoria"].$linha["idescola"];
            $retorno[$ind] = $linha;
            $retorno[$ind]["orcado"] = $linha["orcado"];
        }

        $this->set('pagina', 1)
             ->set('ordem', 'DESC')
             ->set('limite', -1)
             ->set('ordem_campo', 'cc.nome')
             ->set('campos','con.idsindicato,
                             ccc.idcentro_custo,
                             con.idcategoria,
                             con.idsubcategoria,
                             SUM(ccc.valor) as realizado,
                             cc.nome AS centro_custo,
                             i.nome AS sindicato,
                             c.nome AS categoria,
                             cs.nome AS subcategoria,
                             e.nome_fantasia as cfc,
                             ccc.idconta_centro_custo,
                             IFNULL(ua_dono.nome, "Sem dono") as dono,
                             IFNULL(ua_resp.nome, "Sem responsável") as responsavel');

        $this->sql = "SELECT
                        {$this->campos}
                      FROM
                        contas_centros_custos ccc
                            INNER JOIN contas con
                                ON (ccc.idconta = con.idconta
                                    AND ccc.ativo = 'S')
                            INNER JOIN centros_custos cc
                                ON (cc.idcentro_custo = ccc.idcentro_custo
                                    AND cc.ativo = 'S')
                            INNER JOIN sindicatos i
                                ON (i.idsindicato = con.idsindicato)
                            INNER JOIN contas_workflow cw
                                ON (cw.idsituacao = con.idsituacao
                                    AND cw.ativo = 'S'
                                    AND cw.pago = 'S'
                                    /*AND cw.cancelada <> 'S'
                                    AND cw.renegociada <> 'S'
                                    AND cw.transferida <> 'S'*/)
                            LEFT JOIN escolas e
                                ON (e.idescola = con.idescola AND e.ativo = 'S'
                                    AND e.ativo_painel = 'S')
                            INNER JOIN categorias c
                                ON (c.idcategoria = con.idcategoria
                                    AND c.ativo = 'S'
                                    AND c.ativo_painel = 'S')
                            INNER JOIN categorias_subcategorias cs
                                ON (cs.idsubcategoria = con.idsubcategoria
                                    AND cs.ativo = 'S'
                                    AND cs.ativo_painel = 'S')
                            LEFT JOIN obz_sindicatos_usuarios_adm aiua_resp
                                ON (aiua_resp.idcentro_custo = ccc.idcentro_custo
                                    AND i.idsindicato = aiua_resp.idsindicato)
                            LEFT JOIN obz_sindicatos_usuarios_adm aiua_dono
                                ON (aiua_dono.idcentro_custo = ccc.idcentro_custo
                                    AND i.idsindicato = aiua_dono.idsindicato)
                            LEFT JOIN usuarios_adm ua_dono
                                ON (ua_dono.idusuario = aiua_dono.iddono)
                            LEFT JOIN usuarios_adm ua_resp
                                ON (ua_resp.idusuario = aiua_resp.idresponsavel)
                      WHERE
                         con.tipo = 'despesa'
                         AND ".$dadosTipoPeriodo['stringConta']."
                         AND con.ativo = 'S'";

        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                $campo = explode('|',$campo);
                $valor = str_replace('\'','',$valor);
                if (($valor || $valor === '0') && $valor != 'todos') {
                    if ($campo[0] == 1) {
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                    } elseif ($campo[0] == 2)  {
                        $this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($valor) . '%"';
                    }
                }
            }
        }

        if(is_array($_GET["idcentro_custo"])){
            $this->sql .= " AND ccc.idcentro_custo IN (";
                $this->sql .= implode(", ", $_GET["idcentro_custo"]);
                $this->sql .= " ) ";
        }

        $this->sql .= " GROUP BY con.idsindicato,
                                 ccc.idcentro_custo,
                                 con.idcategoria,
                                 con.idsubcategoria";

        $realizados = $this->executaSql($this->sql);
        while($linha = mysql_fetch_assoc($realizados)) {

            if(!$retorno[$ind]["orcado"]){
                $retorno[$ind]["orcado"] = 0.00;
            }
            $ind = $linha["idsindicato"].$linha["idcentro_custo"].$linha["idcategoria"].$linha["idsubcategoria"].$linha["idescola"];
            $retorno[$ind]["idsindicato"] = $linha["idsindicato"];
            $retorno[$ind]["idcentro_custo"] = $linha["idcentro_custo"];
            $retorno[$ind]["idcategoria"] = $linha["idcategoria"];
            $retorno[$ind]["idsubcategoria"] = $linha["idsubcategoria"];
            $retorno[$ind]["ano"] = $dadosTipoPeriodo['ano'];
            $retorno[$ind]["idescola"] = $linha["idescola"];
            $retorno[$ind]["categoria"] = $linha["categoria"];
            $retorno[$ind]["subcategoria"] = $linha["subcategoria"];
            $retorno[$ind]["cfc"] = $linha["cfc"];
            $retorno[$ind]["dono"] = $linha["dono"];
            $retorno[$ind]["responsavel"] = $linha["responsavel"];
            $retorno[$ind]["centro_custo"] = $linha["centro_custo"];
            $retorno[$ind]["realizado"] = $linha["realizado"];
            $retorno["string_categoria"][] = $linha["idcategoria"];
            $retorno["string_subcategoria"][] = $linha["idsubcategoria"];
        }

        $retorno["quantidadeLinhas"] = count($retorno);
        if($retorno["quantidadeLinhas"]){
          #valores totais
          $retorno = $this->calculaValores($retorno,null);
          $retorno["stringContaAnterior"] = $dadosTipoPeriodo['stringContaAnterior'];
          $retorno["stringConta"] = $dadosTipoPeriodo['stringConta'];
          $retorno["trazerMesAnterior"] = $dadosTipoPeriodo['trazerMesAnterior'];
          $retorno['ano'] = $dadosTipoPeriodo['ano'];
        }
        return $retorno;
    }

    private function geraDadosTipoPeriodo($filtro)
    {
      $dados = array();
      switch ($filtro) {
        case 'PER':
        $dados = $this->geraDadosTipoPer();
        break;
        case 'HOJ':
        $dados = $this->geraDadosTipoHoj();
        break;
        case 'ONT':
        $dados = $this->geraDadosTipoOnt();
        break;
        case 'QUI':
        $dados = $this->geraDadosTipoQui();
        break;
        case 'SET':
        $dados = $this->geraDadosTipoSet();
        break;
        case 'MAT':
        $dados = $this->geraDadosTipoMat();
        break;
        case 'MPR':
        $dados = $this->geraDadosTipoMpr();
        break;
        case 'MAN':
        $dados = $this->geraDadosTipoMan();
        break;
      }
        return $dados;
    }
    /**
     *
     * Retorna valores do filtro PER
     *
     * @access private
     * @author waglero
     * @return  array
     */
    private function geraDadosTipoPer()
    {
      $dados = array();
      $primeiraData = formataData($_GET['de'], 'en', 0);
      $ultimaData = formataData($_GET['ate'], 'en', 0);
      $dados['string'] = $this->montarQueryFiltroData($primeiraData, $ultimaData);
      $dados['stringConta'] = "con.data_vencimento BETWEEN '$primeiraData' AND '$ultimaData'";
      $dados['primeira_data'] = $primeiraData;
      $dados['ultima_data'] = $ultimaData;
      $dados['ano'] = substr($primeiraData,0, 4);
      $mes = substr($primeiraData, 5, 2);

      if(dataDiferenca($dados['primeira_data'], $dados['ultima_data'], 'D') <= 31 && $mes != "01"){
          $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, substr($dados['primeira_data'], 5, 2) - 1 , 01, date('Y')));
          $dados['stringContaAnterior'] = "con.data_vencimento BETWEEN '{$dados['dataMesAnterior']}' AND '$ultimaData'";
          $dados['stringMesAnterior'] = $this->montarQueryFiltroData($dados['dataMesAnterior'], $dados['ultima_data']);
          $dados['trazerMesAnterior'] = true;
        }else{
          $dados['trazerMesAnterior'] = false;
        }

        return $dados;
    }
    /**
     *
     * Retorna valores do filtro Hoj
     *
     * @access private
     * @author waglero
     * @return  array
     */
    private function geraDadosTipoHoj($grafico = false)
    {
      $dados = array();

      if($grafico){
          $dados['string'] = $this->montarQueryFiltroData(date("Y")."-01-01" , date("Y-m-d"));
      }else{
          $dados['string'] = $this->montarQueryFiltroData(date("Y-m-d"), date("Y-m-d"));
      }
      $dados['stringConta'] = "con.data_vencimento = '".date("Y-m-d")."'";
      if($grafico){
          $dados['primeira_data'] = date("Y")."-01-01";
      }else{
          $dados['primeira_data'] = date("Y-m-d");
      }
      $dados['ultima_data'] = date("Y-m-d");
      $dados['ano'] = substr(date("Y-m-d"),0, 4);
      $mes = substr($dados['ultima_data'], 5, 2);
      $dados['trazerMesAnterior'] = false;
      if($mes != "01"){
        $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 01, date('Y')));
        $dados['stringContaAnterior'] = "con.data_vencimento BETWEEN '{$dados['primeira_data']}' AND '{$dados['ultima_data']}'";
        $dados['stringMesAnterior'] = $this->montarQueryFiltroData($dados['primeira_data'], $dados['ultima_data']);
        $dados['trazerMesAnterior'] = true;
    }
        return $dados;
    }
    /**
     *
     * Retorna valores do filtro Ont
     *
     * @access private
     * @author waglero
     * @return  array
     */
    private function geraDadosTipoOnt($grafico = false)
    {
      $dados = array();
      $dados['dataOntem'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
      if($grafico){
          $dados['string'] = $this->montarQueryFiltroData(date("Y")."-01-01", $dados['dataOntem']);
      }else{
          $dados['string'] = $this->montarQueryFiltroData($dados['dataOntem'], $dados['dataOntem']);
      }
      $dados['stringConta'] = "con.data_vencimento = '{$dados['dataOntem']}'";
      if($grafico){
          $dados['primeira_data'] = date("Y")."-01-01";
      }else{
          $dados['primeira_data'] = $dados['dataOntem'];
      }
      $dados['ultima_data'] = $dados['dataOntem'];
      $dados['ano'] = substr($dados['dataOntem'],0, 4);
      $mes = substr($dados['ultima_data'], 5, 2);
      $dados['trazerMesAnterior'] = false;
      if($mes != "01"){
        $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 01, date('Y')));
        $dados['stringContaAnterior'] = "con.data_vencimento BETWEEN '{$dados['primeira_data']}' AND '{$dados['ultima_data']}'";
        $dados['stringMesAnterior'] = $this->montarQueryFiltroData($dados['primeira_data'], $dados['ultima_data']);
        $dados['trazerMesAnterior'] = true;
    }
        return $dados;
    }

    /**
     *
     * Retorna valores do filtro Set
     *
     * @access private
     * @author waglero
     * @return  array
     */
    private function geraDadosTipoSet($grafico = false)
    {
      $dados = array();
      $dados['dataHoje'] = date("Y-m-d");
      $dados['dataUltimaSemana'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y')));

      if($grafico){
          $dados['string'] = $this->montarQueryFiltroData(date("Y")."-01-01" , $dados['dataHoje']);
      }else{
          $dados['string'] = $this->montarQueryFiltroData($dados['dataUltimaSemana'], $dados['dataHoje']);
      }
      $dados['stringConta'] = "con.data_vencimento BETWEEN '{$dados['dataUltimaSemana']}' AND '{$dados['dataHoje']}'";
      if($grafico){
          $dados['primeira_data'] = date("Y")."-01-01";
      }else{
          $dados['primeira_data'] = $dados['dataUltimaSemana'];
      }
      $dados['ultima_data'] = $dados['dataHoje'];
      $dados['ano'] = substr($dados['dataUltimaSemana'],0, 4);
      $mes = substr($dados['ultima_data'], 5, 2);
      $dados['trazerMesAnterior'] = false;
      if($mes != "01"){
        $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 01, date('Y')));
        $dados['stringContaAnterior'] = "con.data_vencimento BETWEEN '{$dados['primeira_data']}' AND '{$dados['ultima_data']}'";
        $dados['stringMesAnterior'] = $this->montarQueryFiltroData($dados['primeira_data'], $dados['ultima_data']);
        $dados['trazerMesAnterior'] = true;
        }
        return $dados;
    }
    /**
     *
     * Retorna valores do filtro Qui
     *
     * @access private
     * @author waglero
     * @return  array
     */
    private function geraDadosTipoQui($grafico = false)
    {
      $dados = array();
      $dados['dataHoje'] = date("Y-m-d");
      $dados['dataUltimaQuinzena'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 16, date('Y')));
      if($grafico){
          $dados['string'] = $this->montarQueryFiltroData(date("Y")."-01-01" , $dados['dataHoje']);
      }else{
          $dados['string'] = $this->montarQueryFiltroData($dados['dataUltimaQuinzena'], $dados['dataHoje']);
      }
      $dados['stringConta'] = "con.data_vencimento BETWEEN '{$dados['dataUltimaQuinzena']}' AND '{$dados['dataHoje']}'";
      if($grafico){
          $dados['primeira_data'] = date("Y")."-01-01";
      }else{
          $dados['primeira_data'] = $dados['dataUltimaQuinzena'];
      }
      $dados['ultima_data'] = $dados['dataHoje'];
      $dados['ano'] = substr($dados['dataUltimaQuinzena'],0, 4);
      $mes = substr($dados['ultima_data'], 5, 2);
      $dados['trazerMesAnterior'] = false;
      if($mes != "01"){
        $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 01, date('Y')));
        $dados['stringContaAnterior'] = "con.data_vencimento BETWEEN '{$dados['primeira_data']}' AND '{$dados['ultima_data']}'";
        $dados['stringMesAnterior'] = $this->montarQueryFiltroData($dados['primeira_data'], $dados['ultima_data']);
        $dados['trazerMesAnterior'] = true;
    }
        return $dados;
    }
    /**
     *
     * Retorna valores do filtro Mat
     *
     * @access private
     * @author waglero
     * @return  array
     */
    private function geraDadosTipoMat($grafico = false)
    {
      $dados = array();
      $dados['mesAtual'] = date("Y-m");
      $dados['dataMesAtual'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('t'), date('Y')));
      if($grafico){
          $dados['string'] = $this->montarQueryFiltroData(date("Y")."-01-01" , $dados['dataMesAtual']);
      }else{
          $dados['string'] = $this->montarQueryFiltroData($dados['dataMesAtual'], $dados['dataMesAtual']);
      }
      $dados['stringConta'] = "DATE_FORMAT(con.data_vencimento, '%Y-%m') = '{$dados['mesAtual']}'";
      if($grafico){
          $dados['primeira_data'] = date("Y")."-01-01";
      }else{
          $dados['primeira_data'] = $dados['dataMesAtual'];
      }

      $dados['ultima_data'] = $dados['dataMesAtual'];
      $dados['ano'] = substr($dados['dataMesAtual'],0, 4);
      $mes = substr($dados['ultima_data'], 5, 2);
      $dados['trazerMesAnterior'] = false;
      if($mes != "01"){
        $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 01, date('Y')));
        $dados['stringContaAnterior'] = "con.data_vencimento BETWEEN '{$dados['primeira_data']}' AND '{$dados['ultima_data']}'";
        $dados['stringMesAnterior'] = $this->montarQueryFiltroData($dados['primeira_data'], $dados['ultima_data']);
        $dados['trazerMesAnterior'] = true;
        }
        return $dados;
    }
    /**
     *
     * Retorna valores do filtro Mpr
     *
     * @access private
     * @author waglero
     * @return  array
     */
    private function geraDadosTipoMpr($grafico = false)
    {
      $dados = array();
      $dados['proximoMes'] = date('Y-m', mktime(0, 0, 0, date('m') + 1,  date("t"), date('Y')));
      $dados['dataProximoMes'] = date('Y-m-d', mktime(0, 0, 0, date('m') + 1,  date("t"), date('Y')));

      if($grafico){
          $dados['string'] = $this->montarQueryFiltroData(date("Y")."-01-01" , $dados['dataProximoMes']);
      }else{
          $dados['string'] = $this->montarQueryFiltroData($dados['dataProximoMes'], $dados['dataProximoMes']);
      }

      $dados['stringConta'] =  "DATE_FORMAT(con.data_vencimento, '%Y-%m') = '{$dados['proximoMes']}'";

      if($grafico){
          $dados['primeira_data'] = date("Y")."-01-01";
      }else{
          $dados['primeira_data'] = $dados['dataProximoMes'];
      }

      $dados['ultima_data'] = $dados['dataProximoMes'];
      $dados['ano'] = substr($dados['dataProximoMes'],0, 4);
      $mes = substr($dados['ultima_data'], 5, 2);
      $dados['trazerMesAnterior'] = false;

      if($mes != "01"){
        $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, substr($dados['primeira_data'], 5, 2) - 1, 01, date('Y')));
        $dados['stringContaAnterior'] = "con.data_vencimento BETWEEN '{$dados['primeira_data']}' AND '{$dados['ultima_data']}'";
        $dados['stringMesAnterior'] = $this->montarQueryFiltroData($dados['primeira_data'], $dados['ultima_data']);
        $dados['trazerMesAnterior'] = true;
      }

      return $dados;
    }
    /**
     *
     * Retorna valores do filtro Man
     *
     * @access private
     * @author waglero
     * @return  array
     */
    private function geraDadosTipoMan($grafico = false)
    {
      $dados = array();
      $dados['mesAnterior'] = date('Y-m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));
      $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, date("d"), date('Y')));

      if($grafico){
          $dados['string'] = $this->montarQueryFiltroData(date("Y")."-01-01" , $dados['dataMesAnterior']);
      }else{
          $dados['string'] = $this->montarQueryFiltroData($dados['dataMesAnterior'], $dados['dataMesAnterior']);
      }

      $dados['stringConta'] =  "DATE_FORMAT(con.data_vencimento, '%Y-%m') = '{$dados['mesAnterior']}'";

      if($grafico){
          $dados['primeira_data'] = date("Y")."-01-01";
      }else{
          $dados['primeira_data'] = $dados['dataMesAnterior'];
      }

      $dados['ultima_data'] = $dados['dataMesAnterior'];
      $dados['ano'] = substr($dados['dataMesAnterior'],0, 4);
      $mes = substr($dados['ultima_data'], 5, 2);
      $dados['trazerMesAnterior'] = false;

      if($mes != "01"){
        $dados['dataMesAnterior'] = date('Y-m-d', mktime(0, 0, 0, substr($dados['ultima_data'], 5, 2) - 1, 01, date('Y')));
        $dados['stringContaAnterior'] = "con.data_vencimento BETWEEN '{$dados['primeira_data']}' AND '{$dados['ultima_data']}'";
        $dados['stringMesAnterior'] = $this->montarQueryFiltroData($dados['primeira_data'], $dados['ultima_data']);
        $dados['trazerMesAnterior'] = true;
      }

      return $dados;
    }

    private function calculaValores($retorno, $liberaJustificativa = null)
    {
        $meses = array("mes_1", "mes_2", "mes_3", "mes_4", "mes_5", "mes_6", "mes_7", "mes_8", "mes_9", "mes_10", "mes_11", "mes_12");
        $mesesAux = array("mes_1" => "JAN", "mes_2" => "FEV", "mes_3" => "MAR", "mes_4" => "ABR", "mes_5" => "MAI", "mes_6" => "JUN", "mes_7" => "JUL", "mes_8" => "AGO", "mes_9" => "SET", "mes_10" => "OUT", "mes_11" => "NOV", "mes_12" => "DEZ");

        if($_GET["meta"]){
            $meta = str_replace(array(".", ","), array("", "."), $_GET["meta"]);
        }

        foreach($retorno as $indice => $dado){

            if (is_int($indice)){
                if(is_array($liberaJustificativa)){
                    foreach ($liberaJustificativa as $key => $justifica) {
                        if($dado["justificativa_".$justifica]){
                            $retorno[$indice]["justificativa_texto"] .= "<strong>".$mesesAux["mes_".$justifica].": </strong>".$dado["justificativa_".$justifica].".<br>";
                        }
                    }
                }else{
                    $retorno[$indice]["justificativa_texto"] = $dado["justificativa_".$liberaJustificativa];
                }

                #array para ser usado no grafico de linhas (valores orçados)
                $c = 0;
                $totalOrcado = 0;
                foreach($dado as $ind => $val){
                    if(in_array($ind, $meses)){
                        $totalOrcado += $val;
                        $arrayValores[$c][0] = $mesesAux[$ind];
                        $metaAux = porcentagem($dado[$ind], $meta);
                        $val = $dado[$ind] - $metaAux;
                        $arrayValores[$c][1] += $val;
                        $c++;
                    }
                }
                #calcula todos os valores
                $metaAux = porcentagem($retorno[$indice]["orcado"], $meta);
                $realizadoTotal += $dado["realizado"];
                $orcadoTotal += $dado["orcado"];
                $retorno[$indice]["meta"] = $retorno[$indice]["orcado"] - $metaAux;
                $metaTotal += $retorno[$indice]["meta"];
                $variacao = $retorno[$indice]["meta"] - $dado["realizado"];

                if($variacao >= 0){
                    $corVariacao = "green";
                    $variacaoRealTotal += $variacao;
                    $variacao_real = "R$ +".number_format($variacao, 2, ",", ".");
                }else{
                    $corVariacao = "red";
                    $variacaoRealTotal += $variacao;
                    $variacao_real = "R$ ".number_format($variacao, 2, ",", ".");
                }

                $retorno[$indice]["variacao_real"] = $variacao_real;
                $retorno[$indice]["corVariacao"] = $corVariacao;
                $valor = $dado["realizado"] / $retorno[$indice]["meta"] * 100;
                $variacaoPorcentoTotal += $valor;
                $retorno[$indice]["variacao_porcento"] = number_format($valor, 2)." %";
                $retorno[$indice]["meta"] = "R$ ".number_format($retorno[$indice]["meta"], 2, ",", ".");
                $retorno[$indice]["orcado"] = "R$ ".number_format($dado["orcado"], 2, ",", ".");
                $retorno[$indice]["realizado"] = "R$ ".number_format($dado["realizado"], 2, ",", ".");
            }
        }

        #valores totais
        $retorno["metaTotal"] = $metaTotal ? $metaTotal : 0.00;
        $retorno["realizadoTotal"] = $realizadoTotal ? $realizadoTotal : 0.00;
        $retorno["orcadoTotal"] = $orcadoTotal ? $orcadoTotal : 0.00;
        $retorno["variacaoRealTotal"] = $variacaoRealTotal ? $variacaoRealTotal : 0.00;
        $retorno["variacaoPorcentoTotal"] = $variacaoPorcentoTotal ? $variacaoPorcentoTotal : 0.00;
        $retorno["total_orcado_ano"] = $totalOrcado ? $totalOrcado : 0.00;
        $retorno["total_orcado"] = $arrayValores;
        if($liberaJustificativa == null){
            $retorno["liberaJustificativa"] = 0;
        }
        else{
            $retorno["liberaJustificativa"] = $liberaJustificativa;
        }
        return $retorno;
    }
}

