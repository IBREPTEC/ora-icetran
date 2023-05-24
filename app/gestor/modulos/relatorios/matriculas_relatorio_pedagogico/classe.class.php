<?php

/**
 * Class Relatório
 *
 */
class Relatorio extends Core
{
    /**
     * Conteudo que armazena informações temporaria
     * ou final dos relatórios
     *
     * @var string
     */
    private $_content = null;

    /**
     * @param string $content
     */
    private function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }


    /**
     * Concatena uma valor a uma variável já iniciada
     *
     * @param $var    string variavel a ser concatenada
     * @param $value  string valor adicionado para a variável
     *
     * @return $this
     */
    public function concat($var, $value)
    {
        $this->{$var} .= $value;
        return $this;
    }

    /**
     * Gera os dados do relatório
     *
     * @return mixed
     */
    function gerarRelatorio(){
        if($_GET['q']['de_ate_matricula|tipo_data_matricula_filtro|ma.data_matricula'] == 'PER' && (!$_GET["de_matricula"] || !$_GET["ate_matricula"]) ){
            unset($_GET['q']['de_ate_matricula|tipo_data_matricula_filtro|ma.data_matricula']);
        }

        if ($_GET['q']['de_ate|tipo_data_filtro|ma.data_cad'] == 'PER' && (!$_GET["de"] || !$_GET["ate"])) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'datas_obrigatorias';
            return $retorno;
        }
        if (dataDiferenca(formataData($_GET["de"], 'en', 0), formataData($_GET["ate"], 'en', 0), 'D') > 365) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'intervalo_maior_um_ano';
            return $retorno;
        }   

        
        $_request = $_GET['q'];

        $this->_setQueryHeader();

        $this->sql .= " WHERE ma.ativo='S'";
        
        if(!$_GET["q"]["1|ma.idsindicato"] && $_SESSION['adm_gestor_sindicato'] != 'S'){
            $this->sql .= ' and ma.idsindicato in ('.$_SESSION['adm_sindicatos'].')';   
        }       


        if (is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {

                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);

                if(($valor || $valor === "0") && $valor <> "todos") {

                    if($campo[0] == 1) {
                        $this->sql .= " and ".$campo[1]." = '".$valor."' ";
                    } elseif($campo[0] == 2)  {
                        $this->sql .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
                    } elseif($campo[0] == 'de_ate' || $campo[0] == 'de_ate_matricula') {
                        if($valor == 'HOJ') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
                        } elseif($valor == 'ONT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } else if($valor == 'SET') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif($valor == 'QUI') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } else if($valor == 'MAT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
                        } else if($valor == 'MPR') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } else if($valor == 'MAN') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        }
                    }
                }
            }
        }

        if($_GET["situacao"]) {
            $this->sql .= " and (ma.idsituacao = ".implode(" or ma.idsituacao=", $_GET["situacao"]).") ";
        }

        if($_GET["de"] && $_GET['q']['de_ate|tipo_data_filtro|ma.data_cad'] == 'PER') {
            $this->sql .= " and (ma.data_cad >= '".formataData($_GET["de"],'en',0)." 00:00:00') ";
        }

        if($_GET["ate"] && $_GET['q']['de_ate|tipo_data_filtro|ma.data_cad'] == 'PER') {
            $this->sql .= " and (ma.data_cad <= '".formataData($_GET["ate"],'en',0)." 23:59:59') ";
        }
        
        if($_GET["de_matricula"] && $_GET['q']['de_ate_matricula|tipo_data_matricula_filtro|ma.data_matricula'] == 'PER') {
            $this->sql .= " and (ma.data_matricula >= '".formataData($_GET["de_matricula"],'en',0)." 00:00:00') ";
        }

        if($_GET["ate_matricula"] && $_GET['q']['de_ate_matricula|tipo_data_matricula_filtro|ma.data_matricula'] == 'PER') {
            $this->sql .= " and (ma.data_matricula <= '".formataData($_GET["ate_matricula"],'en',0)." 23:59:59') ";
        }


        $this->setContent(
            $this->set('ordem_campo', 'ma.idmatricula')
                ->set('ordem', 'asc')
                ->set('groupby', 'ma.idmatricula')
                ->retornarLinhas()
        );

        return $this->getContent();
    }

    /**
     * @return $this
     */
    private function _setQueryHeader()
    {
        $this->sql =<<< QUERY
            SELECT
                {$this->campos}
                    FROM
                        matriculas ma
                        LEFT JOIN folhas_registros_diplomas_matriculas folhas_matriculas ON (folhas_matriculas.idmatricula=ma.idmatricula and 
                        folhas_matriculas.ativo='S')
                        LEFT JOIN folhas_registros_diplomas folhas ON (folhas.idfolha=folhas_matriculas.idfolha)
                        INNER JOIN sindicatos inst ON (ma.idsindicato=inst.idsindicato)
                        INNER JOIN mantenedoras mant ON (ma.idmantenedora=mant.idmantenedora)
                        INNER JOIN cursos cu ON (ma.idcurso=cu.idcurso)
                        INNER JOIN ofertas_turmas tu ON (ma.idturma=tu.idturma)                     
                        INNER JOIN ofertas o ON (ma.idoferta=o.idoferta)
                        INNER JOIN escolas po ON (ma.idescola=po.idescola)
                        INNER JOIN pessoas pe ON (ma.idpessoa=pe.idpessoa)
                        INNER JOIN vendedores ve ON (ma.idvendedor=ve.idvendedor)
                        INNER JOIN matriculas_workflow mw on (ma.idsituacao = mw.idsituacao)
QUERY;
        return $this;
    }
    /**
     *
     *
     * @param $dados
     * @param null $q
     * @param $idioma
     * @param string $configuracao
     */
    function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {

        if($_GET['q']['de_ate|tipo_data_filtro|ma.data_cad'] == 'PER' && (!$_GET["de"] || !$_GET["ate"]) ){
            unset($_GET['q']['de_ate|tipo_data_filtro|ma.data_cad']);
        }
        if($_GET['q']['de_ate_matricula|tipo_data_matricula_filtro|ma.data_matricula'] == 'PER' && (!$_GET["de_matricula"] || !$_GET["ate_matricula"]) ){
            unset($_GET['q']['de_ate_matricula|tipo_data_matricula_filtro|ma.data_matricula']);
        }   

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
                    if($campo["nome"]{0} == "q"){
                      $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
                      $campoAux = $_GET["q"][$campoAux];
                      
                      if($campo["sql_filtro"]){
                        if($campo["sql_filtro"] == "array"){
                          $campoAuxNovo = str_replace(array("q[","]"),"",$campo["nome"]);
                          $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAuxNovo]];
                        } else {
                          $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
                          $seleciona = mysql_query($sql);
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
                             $seleciona = mysql_query($sql);
                             $linha = mysql_fetch_assoc($seleciona);
                             $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
                          }
                      }

                      $campoAux = implode($_GET[$campo["nome"]], ", ");
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

            echo '<table class="zebra-striped" id="sortTableExample">';
            echo '<thead>';
            echo '<tr>';

            foreach($this->config[$configuracao] as $ind => $valor){

                    $tamanho = "";
                    if($valor["tamanho"]) $tamanho = ' width="'.$valor["tamanho"].'"';

                    $th = '<th class="';
                    $th.= $class.' headerSortReloca" '.$tamanho.'>';
                    echo $th;

                    echo "<div class='headerNew'>".$idioma[$valor["variavel_lang"]]."</div>";

                    echo '</th>';

            }
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';

            if(count($dados) == 0){
                echo '<tr>';
                echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhum informação foi encontrada.</td>';
                echo '</tr>';
            } else {


            $totalValor = 0;

                foreach($dados as $i => $linha){
                    echo '<tr>';
                    foreach($this->config[$configuracao] as $ind => $valor){

                        /*if($valor["id"] == 'valor_contrato') {
                            $total_valor_contrato += $linha["valor_contrato"];
                        }*/

                        if($valor["tipo"] == "banco") {
                            //echo '<td>'.stripslashes(strtoupper($linha[$valor["valor"]])).'</td>';
                            echo '<td>'.stripslashes($linha[$valor["valor"]]).'</td>';
                        } elseif($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
                            $valor = $valor["valor"]." ?>";
                            $valor = eval($valor);
                            echo '<td>'.stripslashes($valor).'</td>';
                        } elseif($valor["tipo"] == "array") {
                            $variavel = $GLOBALS[$valor["array"]];
                            echo '<td>'.strtoupper($variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]]).'</td>';
                        } elseif($valor["busca_tipo"] != "hidden") {
                            echo '<td>'.stripslashes($valor["valor"]).'</td>';
                        }
                    }

                    echo '</tr>';
                }


                    /*echo '<tr>';
                    echo '<td style="text-align:right" colspan="'.(count($this->config[$configuracao])-1).'">Total:</td>';
                    echo '<td>'.number_format($total_valor_contrato,2,',','.').'</td>';
                    echo '</tr>';*/

            }

            echo '</tbody>';
            echo '</table>';
        }

    /**
     *
     *
     *
     * @api
     */
    function RetornarCursosOferta() {
        $this->sql = "SELECT c.idcurso, c.nome
                          FROM cursos c
                          INNER JOIN ofertas_cursos oc on c.idcurso = oc.idcurso and oc.ativo = 'S'
                          WHERE oc.idoferta = '".$this->id."'";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
    
    function RetornarVendedoresSindicato($idsindicato) {
        $this->sql = 'SELECT 
                            ve.idvendedor, 
                            ve.nome
                        FROM 
                            vendedores ve
                          WHERE
                          ( 
                            (
                                SELECT 
                                    vi.idvendedor 
                                FROM 
                                    vendedores_sindicatos vi, 
                                    sindicatos i 
                                WHERE 
                                    vi.idvendedor = ve.idvendedor AND 
                                    vi.ativo="S" AND 
                                    vi.idsindicato = "'.$idsindicato.'"
                                LIMIT 1
                            ) IS NOT NULL OR
                            (
                                SELECT 
                                    vi.idvendedor 
                                FROM 
                                    vendedores_sindicatos vi, 
                                    sindicatos i 
                                WHERE 
                                    vi.idvendedor = ve.idvendedor AND 
                                    vi.ativo="S" 
                                LIMIT 1
                            ) IS NULL
                          ) AND 
                            ve.ativo = "S" 
                        GROUP BY 
                            ve.idvendedor
                        ORDER BY ve.nome';
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
}