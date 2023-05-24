<?php
	include("../classes/obz.ordemdecompra.class.php");
	include_once("../classes/usuarios.class.php");
	include("config.php");
	include("config.formulario.php");
	include("config.listagem.php");

	//Incluimos o arquivo com variaveis padrão do sistema.
	include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");


	$linhaObj = new OrdemDeCompra();
	$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");

	$linhaObj->Set("idusuario",$usuario["idusuario"]);
	$linhaObj->Set("config",$config);
	$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);
	$linhaObj->Set("idgestor",$usuario['idusuario']);


	if($_POST["acao"] == "salvar"){

		$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

		$linhaObj->Set("post", $_POST);
		$salvar = $linhaObj->Cadastrar();
		if($salvar["sucesso"]){
			$linhaObj->Set("pro_mensagem_idioma","cadastrar_sucesso");
			$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]);
			$linhaObj->Processando();
			exit;
		}

	} elseif($_POST["acao"] == "alterarSituacao") {

		  $linhaObj->Set("id",(int)$url[3]);
		  $linhaObj->Set("post",$_POST);

		  if($linhaObj->autorizacaoStatus($usuario['idusuario'])){

			  $alterar = $linhaObj->AlterarSituacao();

			  if($alterar["sucesso"]){
				$linhaObj->Set("pro_mensagem_idioma",$alterar["mensagem"]);
				$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
				$linhaObj->Processando();
			  } else {
				$mensagem["erro"] = $alterar["mensagem"];
			  }
		  } else {
				$mensagem["erro"] = 'mensagem_erro_autorizacao';
		  }

	}elseif($_POST['acao'] == 'mudarstatus' and ($_POST['qual'] == 'autorizar' or $_POST['qual'] == 'naoautorizar')) {

		$linhaObj->Set("id",(int)$url[3]);
		$linhaObj->Set("post",$_POST);

		if($linhaObj->autorizacaoStatus($usuario['idusuario'])){

			$alterar = $linhaObj->aprovarReprovar();

			if($alterar["sucesso"]){
				$linhaObj->Set("pro_mensagem_idioma",$alterar["mensagem"]);
				$linhaObj->Set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$url[3]."/".$url[4]."");
				$linhaObj->Processando();
			} else {
				$mensagem["erro"] = $alterar["mensagem"];
			}
		} else {
				$mensagem["erro"] = 'mensagem_erro_autorizacao';
		}

	}

	if($url[3] == "cadastrar" or is_numeric($url[3])){

		if($url[4] == "json" && $url[3] == "cadastrar"){
			include("idiomas/".$config["idioma_padrao"]."/json.php");
			include("telas/".$config["tela_padrao"]."/json.php");
			exit();
		}

		if($url[3] == "cadastrar") {
			$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2");

			$instituicoes = $linhaObj->Listarsindicatos();

			include("idiomas/".$config["idioma_padrao"]."/formulario.php");
			include("telas/".$config["tela_padrao"]."/formulario.php");
			exit();
		} else {

			$linhaObj->Set("ordem_campo","idsituacao");
			$linhaObj->Set("ordem","asc");
			$situacaoWorkflow = $linhaObj->RetornarSituacoesWorkflow();

			$linhaObj->Set("idusuario",$usuario["idusuario"]);
			$linhaObj->Set("id",(int)$url[3]);
			$linhaObj->Set("campos","oc.*,
									DATE_FORMAT(oc.data_cad, '%m') as mes_orcamento,
									DATE_FORMAT(oc.data_cad, '%Y') as ano_orcamento,
									ua.idusuario,
									i.nome_abreviado as sindicato,
									e.nome_fantasia as cfc,
									c.nome as categoria,
									cc.nome as centrodecusto,
									sc.nome as subcategoria,
									ua.nome as usuario,
									ua2.nome as usuario_status,
									ow.nome as situacao,
									ow.cor_bg as situacao_cor_bg,
									ow.cor_nome as situacao_cor_nome,
									ow.autorizado,
									ow.nao_autorizado,
									ow.cancelado,
									ow2.nome as situacao_status,
									ow2.cor_bg as situacao_cor_bg_status,
									ow2.cor_nome as situacao_cor_nome_status,
									ow2.autorizado as autorizado_status,
									ow2.nao_autorizado as nao_autorizado_status,
									moc.nome as motivo
							");
			$linha = $linhaObj->Retornar();
			if($linha) {
				switch ($url[4]) {
					case "opcoes":
						$obj = new Atendimentos();
						$respostas = $obj->retornaRespostas($linha['idatendimento']);

						$pessoa = new Pessoas();
						$pessoa->Set("id",$linha['idpessoa']);
						$pessoaDados = $pessoa->Retornar();
						$visualizadores = $obj->retornarQuemVisualiza($linha['idatendimento']);
						$ultimoAtendente = $obj->retornaUltimaInteracaoAtendente($linha['idatendimento']);
						$ultimaInteracao = $obj->retornaUltimaInteracao($linha['idatendimento']);

						include("idiomas/".$config["idioma_padrao"]."/opcoes.php");
						include("telas/".$config["tela_padrao"]."/opcoes.php");
						break;
					case "visualiza":

						$situacoes_workflow_relacionamento = $linhaObj->retornarRelacionamentosWorkflow($linha['idsituacao']);
						$array_situacoes = array();

						if($linha['autorizado'] !=  'S' and $linha['nao_autorizado'] !=  'S' and $linha['cancelado'] !=  'S') {


							if($linhaObj->autorizacaoStatus($usuario['idusuario']))
								foreach($situacoes_workflow_relacionamento as $sit)
										$array_situacoes[] = $sit['idsituacao_para'];


							$motivos = $linhaObj->listarMotivosOrdem();

						}


						$historicos = $linhaObj->retornarHistorico();
						$arquivos = $linhaObj->retornaArquivosOrdem($linha['idordemdecompra']);


						include("idiomas/".$config["idioma_padrao"]."/visualiza.php");
						include("telas/".$config["tela_padrao"]."/visualiza.php");
						break;
					case "json":
						$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");
						include("idiomas/".$config["idioma_padrao"]."/json.php");
						include("telas/".$config["tela_padrao"]."/json.php");
						break;
					case "cancelar":
						$situacaoCancelada = $linhaObj->retornarSituacaoCancelada();
						include("idiomas/".$config["idioma_padrao"]."/visualiza.cancelar.php");
						include("telas/".$config["tela_padrao"]."/visualiza.cancelar.php");
						break;
					case "download":
						$arquivo = $linhaObj->retornaArquivo($url[3], $url[5]);
						include("telas/".$config["tela_padrao"]."/download.php");
						break;
					default:
					   header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
					   exit();
				}

			} else {
			   header("Location: /".$url[0]."/".$url[1]."/".$url[2]."");
			   exit();
			}

		}

	} else {


		$linhaObj->Set("pagina",$_GET["pag"]);
		if(!$_GET["ord"]) $_GET["ord"] = "desc";
		$linhaObj->Set("ordem",$_GET["ord"]);
		if(!$_GET["qtd"]) $_GET["qtd"] = 30;
		$linhaObj->Set("limite",(int)$_GET["qtd"]);
		if(!$_GET["cmp"]) $_GET["cmp"] = $config["banco"]["primaria"];
		$linhaObj->Set("ordem_campo",$_GET["cmp"]);
		$linhaObj->Set("campos","oc.*,
								ua.idusuario,
								i.nome_abreviado as sindicato,
								e.nome_fantasia as cfc,
								c.nome as categoria,
								cc.nome as centrodecusto,
								sc.nome as subcategoria,
								ua.nome as usuario,
								ow.nome as situacao,
								ow.cor_bg as situacao_cor_bg,
								ow.cor_nome as situacao_cor_nome");
		$dadosArray = $linhaObj->ListarTodas();
		include("idiomas/".$config["idioma_padrao"]."/index.php");
		include("telas/".$config["tela_padrao"]."/index.php");

	}

?>
