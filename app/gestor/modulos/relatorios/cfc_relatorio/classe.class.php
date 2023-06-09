<?php

class Relatorio extends Core
{
	function gerarRelatorio()
	{

		$this->sql = 'SELECT
					  		e.idescola, e.nome_fantasia, e.documento, e.fax,
					  		e.telefone, e.gerente_celular AS "celular_administrador", e.email, es.nome AS estado,
					  		ci.nome AS cidade, i.nome_abreviado AS sindicato,
					  		e.cep, e.razao_social, e.bairro, e.endereco, e.numero,
					  		e.complemento, lo.nome as logradouro,
					  		case when e.criar_matricula ="S" then "SIM" when e.criar_matricula = "N" then "NÃO" end as criar_matricula

						FROM
							escolas e
							LEFT OUTER join estados es ON (es.idestado = e.idestado)
							LEFT OUTER join logradouros lo ON (lo.idlogradouro = e.idlogradouro)
							LEFT OUTER join cidades ci ON (ci.idcidade = e.idcidade)
							INNER JOIN sindicatos i ON (i.idsindicato = e.idsindicato)
						WHERE
							e.ativo = "S"';

		if (!$_GET['q']['1|e.idsindicato'] && $_SESSION['adm_gestor_sindicato'] != 'S') {
			$this->sql .= ' AND e.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ')';
		}
		if ($_SESSION['adm_gestor_cfc'] != 'S' && $_SESSION["adm_idperfil"] == 26) {
			$this->sql .= ' AND e.idescola IN ('.$_SESSION['adm_cfcs'].')';
		}

		if (is_array($_GET['q'])) {
			foreach($_GET['q'] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode('|', $campo);
				$valor = str_replace('\'', '', $valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if (($valor || $valor === '0') && $valor <> 'todos') {
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if ($campo[0] == 1) {
						$this->sql .= ' AND '.$campo[1].' = "' . $valor . '" ';
					// se campo[0] for = 2, faz o filtro pelo comando like
					} elseif ($campo[0] == 2)  {
						$this->sql .= ' AND '.$campo[1].' LIKE "%'. urldecode($valor) . '%"';
					}
				}
			}
		}


		$this->groupby = 'e.idescola';
		$this->ordem_campo = 'e.nome_fantasia';
		$this->ordem = 'ASC';
		$this->limite = -1;

		return $this->retornarLinhas();
	}

	function gerarTabela($dados,$q = null,$idioma,$configuracao = "listagem")
	{
		// Buscando os idiomas do formulario
		include 'idiomas/' . $this->config['idioma_padrao'] . '/index.php';
		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Filtro</th>';
		echo '<th>Valor</th>';
		echo '</tr>';
		echo '</thead>';
		foreach($this->config["formulario"] as $ind => $fieldset){

			foreach($fieldset["campos"] as $ind => $campo){
				if ($campo["nome"]{0} == "q"){
				  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
				  $campoAux = $_GET["q"][$campoAux];
				  if ($campo["sql_filtro"]){

				  	  if ($campo["sql_filtro"] == "array"){
						  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
						  $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAux]];
					  } else {
						  $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
						  $seleciona = mysql_query($sql);
						  $linha = mysql_fetch_assoc($seleciona);
						  $campoAux = $linha[$campo["sql_filtro_label"]];
					  }
				  }

				} elseif (is_array($_GET[$campo["nome"]])){

				  if ($campo["array"]){
					  foreach($_GET[$campo["nome"]] as $ind => $val){

						 $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
					  }
				  } elseif ($campo["sql_filtro"]){
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
				if ($campoAux <> ""){
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
		echo '<br>';
		foreach($this->config[$configuracao] as $ind => $valor){

				$tamanho = "";
				if ($valor["tamanho"]) $tamanho = ' width="'.$valor["tamanho"].'"';

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

		if (count($dados) == 0){
			echo '<tr>';
			echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhuma informação foi encontrada.</td>';
			echo '</tr>';
		} else {
			foreach($dados as $i => $linha){
				echo '<tr>';
				foreach($this->config[$configuracao] as $ind => $valor){
					if ($valor["tipo"] == "banco") {
						echo '<td>'.stripslashes($linha[$valor["valor"]]).'</td>';
					} elseif ($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
						$valor = $valor["valor"]." ?>";
						$valor = eval($valor);
						echo '<td>'.stripslashes($valor).'</td>';
					} elseif ($valor["tipo"] == "array") {
						$variavel = $GLOBALS[$valor["array"]];
						echo '<td>'.$variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]].'</td>';
					} elseif ($valor["busca_tipo"] != "hidden") {
						echo '<td>'.stripslashes($valor["valor"]).'</td>';
					}
				}

				echo '</tr>';
			}

		}

		echo '</tbody>';
		echo '</table>';
	}
}
