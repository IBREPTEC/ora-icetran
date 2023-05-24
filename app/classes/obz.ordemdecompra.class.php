<?php
class OrdemDeCompra extends Core
{
	var $idpessoa = NULL;
	var $idgestor = NULL;
    var $gestor_empreendimento = NULL;
	var $pagina_inicial_total = null;

	function ListarTodas() {

		$this->retorno = array();

		$this->sql = '
			SELECT ' . $this->campos . '
			FROM obz_ordem_compra oc
			INNER JOIN sindicatos i ON oc.idsindicato = i.idsindicato
			INNER JOIN escolas e ON oc.idescola = e.idescola
			INNER JOIN centros_custos cc ON oc.idcentro_custo = cc.idcentro_custo
			INNER JOIN categorias c ON oc.idcategoria = c.idcategoria
			INNER JOIN categorias_subcategorias sc ON oc.idsubcategoria = sc.idsubcategoria
			INNER JOIN usuarios_adm ua ON oc.idusuario = ua.idusuario
			INNER JOIN obz_ordem_workflow ow ON oc.idsituacao = ow.idsituacao	';

		$this->sql .= "	WHERE oc.ativo = 'S' ";

		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				if(($valor || $valor === "0") and $valor <> "todos") {
					if($campo[0] == 1) {
						$this->sql .= " and ".$campo[1]." = '".$valor."' ";
					} elseif($campo[0] == 2)  {
						$busca = str_replace("\\'","",$valor);
						$busca = str_replace("\\","",$busca);
						$busca = explode(" ",$busca);
						foreach($busca as $ind => $buscar){
							$this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
						}
					} elseif($campo[0] == 3)  {
						$this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
					} elseif($campo[0] == 4)  {
						$valor = str_replace('.','',$valor);
						$valor = str_replace(',','.',$valor);
						$this->sql .= " and ".$campo[1]." = '".$valor."' ";
					}
				}
			}
		}
//		if($_SESSION['adm_gestor_instituicao'] != 'S'){
//
//			$this->sql .= ' AND oc.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
//
//			$this->sql .= '
//				AND (
//						EXISTS (
//								SELECT * FROM centros_custos_sindicatos WHERE idcentro_custo = oc.idcentro_custo and ativo="S" and idsindicato in ('.$_SESSION['adm_sindicatos'].')
//						) OR NOT EXISTS (
//								SELECT * FROM centros_custos_sindicatos WHERE idcentro_custo = oc.idcentro_custo and ativo="S" LIMIT 1
//						)
//					)
//			';
//
//			$this->sql .= 'AND (
//						EXISTS (
//								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = oc.idsubcategoria and ativo="S" and idsindicato in ('.$_SESSION['adm_sindicatos'].')
//						) OR NOT EXISTS (
//								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = oc.idsubcategoria and ativo="S" LIMIT 1
//						)
//			)';
//
//		}

//        if($_SESSION['adm_gestor_polo'] != 'S'){
//            $this->sql .= ' and po.idescola in ('.$_SESSION['adm_polos'].')';
//        }

		$this->groupby = "oc.idordemdecompra";

		return $this->retornarLinhas();
	}

	function Retornar() {
		$this->sql = 'SELECT
						'.$this->campos.'
					FROM obz_ordem_compra oc
						INNER JOIN sindicatos i ON oc.idsindicato = i.idsindicato
						INNER JOIN escolas e ON oc.idescola = e.idescola
						INNER JOIN centros_custos cc ON oc.idcentro_custo = cc.idcentro_custo
						INNER JOIN categorias c ON oc.idcategoria = c.idcategoria
						INNER JOIN categorias_subcategorias sc ON oc.idsubcategoria = sc.idsubcategoria
						INNER JOIN usuarios_adm ua ON oc.idusuario = ua.idusuario
						INNER JOIN obz_ordem_workflow ow ON oc.idsituacao = ow.idsituacao
						LEFT JOIN usuarios_adm ua2 ON oc.idusuario_status = ua2.idusuario
						LEFT JOIN obz_ordem_workflow ow2 ON oc.idsituacao_status = ow2.idsituacao
						LEFT JOIN obz_motivos_ordemcompra moc ON oc.idmotivo = moc.idmotivo
					WHERE
						oc.ativo = "S" ';


		$this->sql .= ' AND oc.idordemdecompra = '.$this->id;
		$this->sql .= ' GROUP BY oc.idordemdecompra ';

		return $this->retornarLinha($this->sql);
	}

	function retornaArquivosOrdem($idordemdecompra) {
		$this->sql = "SELECT * FROM obz_ordem_compra_arquivos where ativo = 'S' AND idordemdecompra = ".$idordemdecompra;

		$this->limite = -1;
		$this->ordem = "asc";
		$this->ordem_campo = "idarquivo";
		$this->groupby = "idarquivo";
		$dados = $this->retornarLinhas();

		return $dados;
	}


	function retornaArquivo($idordemdecompra,$idarquivo) {
		$this->sql = "SELECT * FROM obz_ordem_compra_arquivos where idarquivo = ".$idarquivo." AND idordemdecompra = ".$idordemdecompra;
		$retorno = $this->retornarLinha($this->sql);

		return $retorno;
	}


	function ExcluirArquivo($idarquivo, $pasta) {
		$arquivo = $this->retornaArquivoResposta($idarquivo);

		if(unlink($_SERVER["DOCUMENT_ROOT"]."/storage/".$pasta."/".$arquivo["servidor"])) {

			  $this->sql = "UPDATE atendimentos_arquivos SET ativo = 'N' where idarquivo = ".$idarquivo;
			  $this->executaSql($this->sql);

			  $this->monitora_oque = 17;
			  $this->monitora_onde = 56;
			  $this->monitora_qual = $idarquivo;
			  $this->Monitora();

			  $info["sucesso"] = true;

		} else {
			  $info["sucesso"] = false;
		}
		return json_encode($info);
	}

	function Listarsindicatos() {
		$this->retorno = array();

		$this->sql = 'select idsindicato, nome_abreviado as nome from sindicatos where ativo = "S"';

//		if($_SESSION['adm_gestor_instituicao'] != 'S')
//			$this->sql .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';

		$this->sql .= ' GROUP BY idsindicato  ';

		$this->ordem = "ASC";
		$this->limite = "-1";
		$this->ordem_campo = "nome_abreviado";
		return $this->retornarLinhas();
	}

	function RetornarPoloInstituicao($idsindicato){
			$this->sql = 'SELECT idescola, nome_fantasia as nome FROM escolas where ativo="S"';
			$this->sql .= ' and idsindicato in ('.$idsindicato.')';
//            if($_SESSION['adm_gestor_polo'] != 'S'){
//                $this->sql .= ' and idescola in ('.$_SESSION['adm_polos'].')';
//            }
			$this->sql .= ' GROUP BY idescola  ';
			$this->ordem = "ASC";
			$this->limite = "-1";
			$this->ordem_campo = "nome_fantasia";
			return $this->retornarLinhas();
	}
	function RetornarCentroDeCustoInstituicao($idsindicato){
			$this->sql = 'SELECT cc.idcentro_custo, cc.nome FROM centros_custos cc
									WHERE cc.ativo="S"';
			$this->sql .= '
				AND (
						EXISTS (
								SELECT * FROM centros_custos_sindicatos WHERE idcentro_custo = cc.idcentro_custo and ativo="S" and idsindicato in ('.$idsindicato.')
						) OR NOT EXISTS (
								SELECT * FROM centros_custos_sindicatos WHERE idcentro_custo = cc.idcentro_custo and ativo="S" LIMIT 1
						)
					)
				AND (
						EXISTS (
								SELECT idsubcategoria_centro FROM obz_subcategorias_centros WHERE idcentro_custo = cc.idcentro_custo and idsindicato in ('.$idsindicato.') and ativo="S"
						) OR NOT EXISTS (
								SELECT idsubcategoria_centro FROM obz_subcategorias_centros WHERE idcentro_custo = cc.idcentro_custo and ativo="S" LIMIT 1
						)
					)
			';
			$this->sql .= ' GROUP BY cc.idcentro_custo  ';
			$this->ordem = "ASC";
			$this->limite = "-1";
			$this->ordem_campo = "cc.nome";
			return $this->retornarLinhas();
	}
	function RetornarCategoriaInstituicao($idsindicato,$idcentro_custo){
			$this->sql = 'SELECT c.idcategoria, c.nome
								FROM categorias c
								 INNER JOIN categorias_subcategorias cs ON (c.idcategoria = cs.idcategoria)
								WHERE c.ativo="S" and cs.ativo="S"';
			$this->sql .= '
				AND (
						EXISTS (
								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = cs.idsubcategoria and ativo="S" and idsindicato in ('.$idsindicato.')
						) OR NOT EXISTS (
								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = cs.idsubcategoria and ativo="S" LIMIT 1
						)
					)
				AND (
						EXISTS (
								SELECT idsubcategoria_centro FROM obz_subcategorias_centros WHERE idsubcategoria = cs.idsubcategoria and idcentro_custo = '.$idcentro_custo.' and ativo="S" and idsindicato in ('.$idsindicato.')
						) OR NOT EXISTS (
								SELECT idsubcategoria_centro FROM obz_subcategorias_centros WHERE idsubcategoria = cs.idsubcategoria and ativo="S" LIMIT 1
						)
					)
			';
			$this->sql .= ' GROUP BY c.idcategoria  ';
			$this->ordem = "ASC";
			$this->limite = "-1";
			$this->ordem_campo = "c.nome";
			return  $this->retornarLinhas();
	}


	function RetornarSubCategoriaInstituicao($idsindicato,$idcategoria,$idcentro_custo){


		$this->sql = 'SELECT cs.idsubcategoria, cs.nome
						FROM categorias_subcategorias cs
							WHERE  cs.ativo="S" AND cs.idcategoria = "'.$idcategoria.'" ';
		$this->sql .= '
				AND (
						EXISTS (
								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = cs.idsubcategoria and ativo="S" and idsindicato in ('.$idsindicato.')
						) OR NOT EXISTS (
								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = cs.idsubcategoria and ativo="S" LIMIT 1
						)
					)
				AND (
						EXISTS (
								SELECT idsubcategoria_centro FROM obz_subcategorias_centros WHERE idsubcategoria = cs.idsubcategoria and idcentro_custo = '.$idcentro_custo.' and ativo="S" and idsindicato in ('.$idsindicato.')
						) OR NOT EXISTS (
								SELECT idsubcategoria_centro FROM obz_subcategorias_centros WHERE idsubcategoria = cs.idsubcategoria and ativo="S" LIMIT 1
						)
					)
			';

		$this->sql .= ' GROUP BY cs.idsubcategoria  ';
		$this->ordem = "ASC";
		$this->limite = "-1";
		$this->ordem_campo = "cs.nome";
		return  $this->retornarLinhas();
	}


	function listarMotivosOrdem() {
		$this->sql = "select * from obz_motivos_ordemcompra where ativo='S' and ativo_painel='S' ";
		$this->ordem = "ASC";
		$this->limite = "-1";
		$this->ordem_campo = "nome";
		return $this->retornarLinhas();

	}


	function valorRealizado($idsindicato, $idpolo, $idcentro_custo, $idcategoria, $idsubcategoria,$data)
	{

		$sql = "select idsituacao from contas_workflow where cancelada='S' AND ativo='S' LIMIT 1";
		$cancelado = $this->retornarLinha($sql);

		$this->sql = 'SELECT SUM(c.valor) as total
						FROM contas  c
						INNER JOIN contas_centros_custos cc ON (c.idconta = cc.idconta)
							WHERE
							c.idsindicato = "'.$idsindicato.'" AND
							cc.idcentro_custo = "'.$idcentro_custo.'" AND
							c.idcategoria = "'.$idcategoria.'" AND
							c.idsubcategoria = "'.$idsubcategoria.'" AND
							DATE_FORMAT(c.data_vencimento,"%m-%Y") = "'.$data.'" AND
							c.ativo="S" AND cc.ativo="S" ';
		if($cancelado['idsituacao']){
			$this->sql .= ' AND c.idsituacao != '.$cancelado['idsituacao'];
		}

		$total = $this->retornarLinha($this->sql);
		$total['total'] = $total['total'] * (-1);
		return $total['total'];

	}


	function valorOrcado($idsindicato, $idpolo, $idcentro_custo, $idcategoria, $idsubcategoria,$mes,$ano)
	{

		$this->sql = 'SELECT mes_'.(int)$mes.' as total
						FROM obz_orcamentos_gastos
							WHERE
							idescola = "'.$idpolo.'" AND
							idsindicato = "'.$idsindicato.'" AND
							idcentro_custo = "'.$idcentro_custo.'" AND
							idcategoria = "'.$idcategoria.'" AND
							idsubcategoria = "'.$idsubcategoria.'" AND
							ano = "'.$ano.'" LIMIT 1';
		$total = $this->retornarLinha($this->sql);
		return $total['total'];

	}

	function orcadoResto($idsindicato, $idpolo, $idcentro_custo, $idcategoria, $idsubcategoria,$mes,$ano)
	{
		$data = $mes.'-'.$ano;

		$valorOrcado = $this->valorOrcado($idsindicato, $idpolo, $idcentro_custo, $idcategoria, $idsubcategoria,$mes,$ano) ;
		$valorRealizado = $this->valorRealizado($idsindicato, $idpolo, $idcentro_custo, $idcategoria, $idsubcategoria,$data) ;

		$resto = $valorOrcado - $valorRealizado;
		return $resto  ;
	}

	function usuarioAdmGestor($idusuario){
		$this->sql = 'SELECT gestor_centro_custo , comite_obz
						FROM obz_usuarios_adm
							WHERE
							idusuario = "'.$idusuario.'" LIMIT 1 ';
		return $this->retornarLinha($this->sql);
	}

	function retornarDonoInstituicaoCentro($idcentro_custo,$idsindicato){
		$this->sql = 'SELECT idresponsavel , iddono
						FROM obz_sindicatos_usuarios_adm
							WHERE
							idcentro_custo = "'.$idcentro_custo.'" and idsindicato = "'.$idsindicato.'" and ativo = "S" LIMIT 1 ';
		return $this->retornarLinha($this->sql);
	}

	function Cadastrar()
	{


			$permissoes = 'jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf';
			$campo = array("pasta" => "obz_ordemcompra_arquivos");

			$existe_arquivos = false;
			foreach ($_FILES['arquivo']['name'] as $ind => $arq)
				if ($arq)
					$existe_arquivos = true;

			if ($existe_arquivos) {
				foreach ($_FILES['arquivo']['name'] as $ind => $arquivo) {
					$file['name'] = $_FILES['arquivo']['name'][$ind];
					$file['tmp_name'] = $_FILES['arquivo']['tmp_name'][$ind];
					$file['size'] = $_FILES['arquivo']['size'][$ind];
					unset($nome_servidor);

					$file_aux['name'] = $file;
					$validacao_tamanho = $this->ValidarArquivo($file_aux['name']);

					if($validacao_tamanho) {
						$this->retorno["erro"] = true;
						$this->retorno["erros"][] = $validacao_tamanho;
						return $this->retorno;
					}
				}
			}


			/*
			TOMAZ IRA DEFINIR ESSA PARTE COM O CLIENTE
			$valorNaoUtilizada = $this->valorOrdemNaoUtilizada($this->post['idsindicato'],
															$this->post['idescola'],
															$this->post['idcentro_custo'],
															$this->post['idcategoria'],
															$this->post['idsubcategoria'],
															date('m-Y'));
			*/


			$resto 	= $this->orcadoResto($this->post['idsindicato'],
										 $this->post['idescola'],
										 $this->post['idcentro_custo'],
										 $this->post['idcategoria'],
										 $this->post['idsubcategoria'],
										 date('m'),
										 date('Y')) ;


			$valorSolicitado = str_replace('.','',$_POST['valor']);
			$valorSolicitado = str_replace(',','.',$valorSolicitado);

			if($valorSolicitado <= $resto){
				$idsituacao = $this->retornarSituacaoAutorizado();
			}else{
				$idsituacao = $this->retornarSituacaoAguardando();
			}

			$this->post['idsituacao'] = $idsituacao['idsituacao'];
			$this->retorno = $this->salvarDados();

			if ($this->retorno['id'])
					$this->addHistorico($this->retorno['id'], $this->idusuario, NULL, $this->post['idsituacao'], 'CAD');

			if ($this->retorno['id'] && $existe_arquivos) {

				foreach ($_FILES['arquivo']['name'] as $ind => $arquivo) {
					$file['name'] = $_FILES['arquivo']['name'][$ind];
					$file['tmp_name'] = $_FILES['arquivo']['tmp_name'][$ind];
					$file['size'] = $_FILES['arquivo']['size'][$ind];
					unset($nome_servidor);

					$file_aux['name'] = $file;
					$validacao_tamanho = $this->ValidarArquivo($file_aux['name']);

					if($validacao_tamanho) {
						$this->retorno["erro"] = true;
						$this->retorno["erros"][] = $validacao_tamanho;
						return $this->retorno;
					}

					$nome_servidor = $this->uploadFile($file, $campo);

					if ($nome_servidor) {
						$sql = "insert into obz_ordem_compra_arquivos
							set
							  idordemdecompra = '".$this->retorno['id']."',
							  ativo = 'S',
							  data_cad = NOW(),
							  nome = '".$_FILES['arquivo']['name'][$ind]."',
							  tipo = '".$_FILES['arquivo']['type'][$ind]."',
							  tamanho = '".$_FILES['arquivo']['size'][$ind]."',
							  servidor = '".$nome_servidor."' ";
						$query_arquivo = $this->executaSql($sql);
						$idarquivo = $this->retornarLastInsertIdQuery();
					}
				}

			}

			return $this->retorno;
	}

	function addHistorico($idordemdecompra, $idusuario = "NULL", $de, $para, $tipo) {

		//TIPO DE HISTORICO
		//S = Modificou Status
		//CAD = CADASTROU

		$this->sql = "insert obz_ordem_compra_historicos
						set
						  idordemdecompra = ".intval($idordemdecompra).", ";
						 if ($idusuario)
						    $this->sql .= " idusuario = ".$idusuario.", ";
		$this->sql .= " data_cad = NOW(),
						  tipo = '".$tipo."'";
		if($de) $this->sql .= ", de = '".$de."'";
		if($para) $this->sql .= ", para = '".$para."'";

		return $executa_monitora_assunto = $this->executaSql($this->sql);
	}

	function retornarHistorico() {
		//TIPO DE HISTORICO
		//S = Modificou Status
		//CAD = CADASTROU
		/*
		INNER JOIN sindicatos i ON oc.idsindicato = i.idsindicato
						INNER JOIN escolas po ON oc.idescola = po.idescola
						INNER JOIN centros_custos cc ON oc.idcentro_custo = cc.idcentro_custo
						INNER JOIN categorias c ON oc.idcategoria = c.idcategoria
						INNER JOIN categorias_subcategorias sc ON oc.idsubcategoria = sc.idsubcategoria
						INNER JOIN usuarios_adm ua ON oc.idusuario = ua.idusuario
						INNER JOIN obz_ordem_workflow ow ON oc.idsituacao = ow.idsituacao
		*/
		$this->sql = "SELECT
						oh.idhistorico,
						oh.idordemdecompra,
						oh.data_cad,
						oh.tipo,
						oh.de,
						oh.para,
						ua.nome AS usuario,
						ow.nome AS status_de,
						ocw.nome AS status_para,
						ow.cor_bg AS cor_de,
						ocw.cor_bg AS cor_para
					  FROM
						obz_ordem_compra_historicos oh
						INNER JOIN obz_ordem_compra oc ON (oh.idordemdecompra = oc.idordemdecompra)
						INNER JOIN usuarios_adm ua ON (oh.idusuario = ua.idusuario)
						LEFT JOIN obz_ordem_workflow ow ON (oh.de = ow.idsituacao)
						INNER JOIN obz_ordem_workflow ocw ON (oh.para = ocw.idsituacao)
					  WHERE
					  	oh.idordemdecompra = ".$this->id;

		$this->limite = -1;
		$this->ordem_campo = "oh.data_cad";
		$this->ordem = "asc";
		$this->groupby = "oh.idhistorico";
		return $this->retornarLinhas();
	}

	function retornarRelacionamentosWorkflow($idsituacao) {
		$this->sql = "select idsituacao_para from obz_ordem_workflow_relacionamentos where idsituacao_de = ".$this->retornarEscapeStringQuery($idsituacao) . " and ativo = 'S' ";
		$this->limite = -1;
		$this->ordem_campo = "idsituacao_para";
		$this->groupby = "idsituacao_para";
		return $this->retornarLinhas();
	}

	function autorizacaoStatus($idusuario) {


		$this->Set("campos","oc.*, DATE_FORMAT(oc.data_cad, '%m') as mes_orcamento, DATE_FORMAT(oc.data_cad, '%Y') as ano_orcamento	");
		$linha = $this->Retornar();


		$usuarioGestorOuComite = $this->usuarioAdmGestor($idusuario);

		$donoinstituicao = $this->retornarDonoInstituicaoCentro($linha['idcentro_custo'],$linha['idsindicato']);

		$valorOrcado = $this->orcadoResto($linha['idsindicato'],
										  $linha['idescola'],
										  $linha['idcentro_custo'],
										  $linha['idcategoria'],
										  $linha['idsubcategoria'],
										  $linha['mes_orcamento'],
										  $linha['ano_orcamento']);

		$porcentagemMais  = (($linha['valor']*100)/$valorOrcado) - 100;
		$porcentagemMais  = round($porcentagemMais,2);


		//DONO, GESTOR E USUARIOS DO COMITE
		if($porcentagemMais <=5 and
					($donoinstituicao['iddono'] == $idusuario or
						$usuarioGestorOuComite['gestor_centro_custo'] == 'S' or
							$usuarioGestorOuComite['comite_obz'] == 'S'
					 )
		){
			return true;

		//GESTOR E USUARIOS DO COMITE
		}elseif(5 < $porcentagemMais  and $porcentagemMais <=10 and
					($usuarioGestorOuComite['gestor_centro_custo'] == 'S' or
							$usuarioGestorOuComite['comite_obz'] == 'S'
					 )
		){
			return true;
		//USUARIOS DO COMITE
		}elseif(10 < $porcentagemMais and $usuarioGestorOuComite['comite_obz'] == 'S'){
			return true;

		}

		return false;

	}


	function aprovarReprovar() {

		if($this->post['qual'] == 'autorizar'){

			$this->sql = "SELECT idsituacao FROM obz_ordem_workflow WHERE autorizado = 'S' and ativo = 'S' LIMIT 1";
			$situacao = $this->retornarLinha($this->sql);

		}elseif($this->post['qual'] == 'naoautorizar'){

			$this->sql = "SELECT idsituacao FROM obz_ordem_workflow WHERE nao_autorizado = 'S' and ativo = 'S' LIMIT 1";
			$situacao = $this->retornarLinha($this->sql);

		}

		$this->post["situacao_para"] = $situacao['idsituacao'];

		$this->sql = "UPDATE obz_ordem_compra
							SET idsituacao_status='".$situacao['idsituacao']."',
								idmotivo = ".$this->post['idmotivo'].",
								justificativa = '".$this->post['justificativa']."',
								data_status = NOW(),
								idusuario_status = ".$this->idusuario."
					 	    WHERE idordemdecompra = ".$this->id;
		$this->executaSql($this->sql);

		$retorno = $this->AlterarSituacao();
		return $retorno;
	}

	function AlterarSituacao() {

		$this->retorno = array();

		$this->sql = "SELECT * FROM obz_ordem_compra WHERE idordemdecompra = ".intval($this->id);
		$linhaAntiga = $this->retornarLinha($this->sql);

		if($this->VerificaPreRequesito($linhaAntiga["idsituacao"],$this->post["situacao_para"])){

			$situacaoCancelada = $this->retornarSituacaoCancelada();

			$this->sql = "update obz_ordem_compra set idsituacao='".$this->post["situacao_para"]."'";

			if($situacaoCancelada['idsituacao'] == $this->post["situacao_para"]){

				if(trim($this->post["motivo_cancelamento"]) == ''){
					$this->retorno["sucesso"] = false;
					$this->retorno["mensagem"] = "mensagem_erro_cancelar";
					return $this->retorno;
				}

				$this->sql .= " , motivo_cancelamento = '".$this->retornarEscapeStringQuery($this->post["motivo_cancelamento"])."' ";
			}

			$this->sql .= " where idordemdecompra = ".intval($this->id);
			$salvar = $this->executaSql($this->sql);

			$this->sql = "SELECT * FROM obz_ordem_compra WHERE idordemdecompra = ".intval($this->id);
			$linhaNova = $this->retornarLinha($this->sql);

			$this->addHistorico(intval($this->id), intval($this->idusuario), $linhaAntiga["idsituacao"], $this->post['situacao_para'], 'S');

			$this->retorno["sucesso"] = true;
			$this->retorno["mensagem"] = "mensagem_situacao_sucesso";

			$this->monitora_oque = 2;
			$this->monitora_qual = $this->id;
			$this->monitora_dadosantigos = $linhaAntiga;
			$this->monitora_dadosnovos = $linhaNova;
			$this->Monitora();

		} else {


			$this->retorno["sucesso"] = false;
			$this->retorno["mensagem"] = "mensagem_situacao_erro_prerequesitos";
		}

		return $this->retorno;
	}


	function VerificaPreRequesito($de,$para) {

		$this->sql = "select idrelacionamento from obz_ordem_workflow_relacionamentos where idsituacao_de = ".$de." and idsituacao_para = ".$para." and ativo = 'S'";
		$relacionamento = $this->retornarLinha($this->sql);


		$this->sql = "select
						owa.idopcao
					  from
						obz_ordem_workflow_acoes owa
					  where
						owa.idrelacionamento = ".$relacionamento["idrelacionamento"]." and
						owa.ativo = 'S'";
		$this->limite = -1;
		$this->ordem_campo = "owa.idopcao";
		$resultado = $this->executaSql($this->sql);

		while($acao = mysql_fetch_assoc($resultado)) {
			foreach($GLOBALS['workflow_parametros_obz_ordem'] as $op) {
			  if($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "prerequisito") {
				$preRequisitos[] = $acao;
			  }
			}
		}

		if(count($preRequisitos) > 0) {
		  $this->sql = "select * from obz_ordem_compra where idordemdecompra = ".intval($this->id);
		  $atendimento = $this->retornarLinha($this->sql);
		  foreach($preRequisitos as $ind => $preRequisito) {
			switch($preRequisito["idopcao"]) {
			  //Ter uma resposta pública
			  case 1:
				 // return false;
			  break;
			}
		  }
		}
		return true;
	}


	function uploadFile($file, $campoAux){
		$extensao = strtolower(strrchr($file["name"], "."));
		$nome_servidor = date("YmdHis")."_".uniqid().$extensao;

		if(move_uploaded_file($file["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/".$campoAux["pasta"]."/".$nome_servidor)) {
			return $nome_servidor;
		} else
			return false;
	}

	function RetornarSituacoesWorkflow(){
		$this->sql = "SELECT * FROM obz_ordem_workflow WHERE ativo = 'S'";
		$this->ordem_campo = "ordem";
		$this->ordem = "asc";
		$this->limite = -1;
		$retorno = $this->retornarLinhas();
		$this->retorno = NULL;

		foreach($retorno as $ind => $var){
			$this->retorno[$var["idsituacao"]] = $var;
		}

		return $this->retorno;
	}

	function TotalOrdemDecompraAguardando(){
		$aguardandosituacao = $this->retornarSituacaoAguardando();


		$this->sql = 'SELECT COUNT(oc.idordemdecompra) as total
					FROM obz_ordem_compra oc
				WHERE oc.ativo = "S"  ';

		if($aguardandosituacao['idsituacao']){
			$this->sql .= ' AND oc.idsituacao = '.$aguardandosituacao['idsituacao'].' ';
		}

		if($_SESSION['adm_gestor_instituicao'] != 'S'){

			$this->sql .= ' AND oc.idsindicato in ('.$_SESSION['adm_sindicatos'].')';

			$this->sql .= '
				AND (
						EXISTS (
								SELECT * FROM centros_custos_sindicatos WHERE idcentro_custo = oc.idcentro_custo and ativo="S" and idsindicato in ('.$_SESSION['adm_sindicatos'].')
						) OR NOT EXISTS (
								SELECT * FROM centros_custos_sindicatos WHERE idcentro_custo = oc.idcentro_custo and ativo="S" LIMIT 1
						)
					)
			';

			$this->sql .= 'AND (
						EXISTS (
								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = oc.idsubcategoria and ativo="S" and idsindicato in ('.$_SESSION['adm_sindicatos'].')
						) OR NOT EXISTS (
								SELECT idsubcategoria FROM categorias_subcategorias_sindicatos WHERE idsubcategoria = oc.idsubcategoria and ativo="S" LIMIT 1
						)
			)';

		}
		if($_SESSION['adm_gestor_cfc'] != 'S'){
			$this->sql .= ' and oc.idescola in ('.$_SESSION['adm_cfcs'].')';
		}

		$retorno = $this->retornarLinha($this->sql);
		$retorno['idsituacao'] = $aguardandosituacao['idsituacao'];
		return $retorno;

	}

	function retornarSituacaoAutorizado() {
		$sql = 'SELECT idsituacao FROM obz_ordem_workflow WHERE autorizado = "S" and ativo = "S" LIMIT 1 ';
		return $this->retornarLinha($sql);
	}

	function retornarSituacaoAguardando() {
		$sql = 'SELECT idsituacao FROM obz_ordem_workflow WHERE aguardando_autorizacao = "S" and ativo = "S" LIMIT 1 ';
		return $this->retornarLinha($sql);
	}

	function retornarSituacaoNaoAutorizado() {
		$sql = 'SELECT idsituacao FROM obz_ordem_workflow WHERE nao_autorizado = "S" and ativo = "S" LIMIT 1 ';
		return $this->retornarLinha($sql);
	}

	function retornarSituacaoCancelada() {
		$sql = 'SELECT idsituacao FROM obz_ordem_workflow WHERE cancelado = "S" and ativo = "S" LIMIT 1 ';
		return $this->retornarLinha($sql);
	}



}

?>
