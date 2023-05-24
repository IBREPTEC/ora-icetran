<?php

class Orcamento_Realizado extends Core {


    function ListarTodas()
    {
        $this->sql = "SELECT
                        oog.*,
                        e.nome_fantasia,
                        c.nome as categoria,
                        cs.nome as subcategoria
                      FROM
                          obz_orcamentos_gastos oog
                          INNER JOIN escolas e ON oog.idescola = e.idescola
                          INNER JOIN categorias c ON oog.idcategoria = c.idcategoria
                          INNER JOIN categorias_subcategorias cs ON oog.idsubcategoria = cs.idsubcategoria
                      WHERE
                          oog.idsindicato = $this->idsindicato AND
                          e.idsindicato = $this->idsindicato AND
                          oog.idcentro_custo = $this->idcentro_custo AND
                          oog.idcategoria = $this->idcategoria AND
                          oog.idsubcategoria = $this->idsubcategoria AND
                          oog.ano = $this->ano";
        $this->limite = -1;
        $this->ordem_campo = false;
        $this->ordem = false;
        $dados = $this->retornarLinhas();

        if(is_array($dados)){

            //$sql = "select idsituacao from contas_workflow where cancelada = 'S' AND ativo = 'S' LIMIT 1";
            //$cancelado = $this->retornarLinha($sql);

            $sql = "select idsituacao from contas_workflow where pago = 'S' AND ativo = 'S' LIMIT 1";
            $pago = $this->retornarLinha($sql);

            $totalOrçado = 0;
            $totalRealizado = 0;
            foreach($dados as $ind => $val){


                $dados[$ind]['orcado'] = $val['mes_1'] + $val['mes_2'] +
                                         $val['mes_3'] + $val['mes_4'] +
                                         $val['mes_5'] + $val['mes_6'] +
                                         $val['mes_7'] + $val['mes_8'] +
                                         $val['mes_9'] + $val['mes_10'] +
                                         $val['mes_11'] + $val['mes_12'];

                $totalOrçado += $dados[$ind]['orcado'];

                $dados[$ind]['orcado'] = "R$ ".number_format($dados[$ind]['orcado'],2,",",".");

                $this->sql = 'SELECT
                                SUM(cc.valor) as total
                            FROM contas  c
                                INNER JOIN contas_centros_custos cc ON (c.idconta = cc.idconta)
                            WHERE
                                c.idsindicato = "'.$val['idsindicato'].'" AND
                                cc.idcentro_custo = "'.$val['idcentro_custo'].'" AND
                                c.idcategoria = "'.$val['idcategoria'].'" AND
                                c.idsubcategoria = "'.$val['idsubcategoria'].'" AND
                                DATE_FORMAT(data_vencimento,"%Y") = "'.$val['ano'].'" AND
                                c.ativo = "S" AND
                                cc.ativo = "S" ';

                /*if($cancelado['idsituacao']){
                    $this->sql .= ' AND c.idsituacao != '.$cancelado['idsituacao'];
                }*/

                if($pago['idsituacao']){
                    $this->sql .= ' AND c.idsituacao = '.$pago['idsituacao'];
                }
                $total = $this->retornarLinha($this->sql);
                $dados[$ind]['realizado'] = $total['total'];
                $totalRealizado += $dados[$ind]['realizado'];
                $dados[$ind]['realizado'] = "R$ ".number_format($dados[$ind]['realizado'],2,",",".");

            }
            $dados['total']['orcado'] = "<strong>R$ ".number_format($totalOrçado,2,",",".").'</strong>';
            $dados['total']['realizado'] = "<strong>R$ ".number_format($totalRealizado,2,",",".").'</strong>' ;
        }

        return $dados;
    }

	function retornarOrcamento(){
		$this->sql = "SELECT
                       oog.*, i.nome_abreviado as sindicato,  e.nome_fantasia,  c.nome as categoria , cs.nome as subcategoria, cc.nome as centro
                      FROM obz_orcamentos_gastos oog
						  INNER JOIN escolas e ON oog.idescola = e.idescola
						  INNER JOIN categorias c ON oog.idcategoria = c.idcategoria
						  INNER JOIN categorias_subcategorias cs ON oog.idsubcategoria = cs.idsubcategoria
              		  	  INNER JOIN sindicatos i ON oog.idsindicato = i.idsindicato
              		      INNER JOIN centros_custos cc ON oog.idcentro_custo = cc.idcentro_custo
                      WHERE  oog.idorcamento =  ".$this->id;

		return $this->retornarLinha($this->sql);
	}

	public function salvarJustificativa(){
		$retorno = array();
		//echo '<pre>'; print_r($_POST['justificativa'] );exit;
        $sql = "select * from obz_orcamentos_gastos where idorcamento = " . $this->idorcamento;
        $this->monitora_dadosantigos = $this->retornarLinha($sql);

		foreach($_POST['justificativa'] as $ind => $val){
			$result = false;

			if(trim($val)){
				$text = "'".$this->retornarEscapeStringQuery($val)."'";
			}else{
				$text = 'NULL';
			}

			$this->sql = "UPDATE obz_orcamentos_gastos SET justificativa_".$ind." = ".$text." where idorcamento = ".$this->idorcamento;
			$result = $this->executaSql($this->sql);
		}

        $this->sql = "select * from obz_orcamentos_gastos where idorcamento = " . $this->idorcamento;
        $this->monitora_dadosnovos = $this->retornarLinha($this->sql);

		if($result){
            $this->monitora_onde = 246;
            $this->monitora_oque = 2;
            $this->monitora_qual = $this->idorcamento;
            $this->Monitora();
			$retorno['sucesso'] = $result;
			$retorno['mensagem'] = "justificativa_sucesso";
		}else{
			$retorno['erros'][] = "justificativa_erro";
		}
		return $retorno;
	}

    public function retornarCentrosCusto($idsindicato, $idusuario)
    {
       $sql = "SELECT gestor_centro_custo FROM obz_usuarios_adm WHERE idusuario = $idusuario";
       $usuario = $this->retornarLinha($sql);
       if($usuario["gestor_centro_custo"] == "S"){
          $this->sql = "SELECT
                            cc.idcentro_custo, cc.nome
                        FROM
                            centros_custos cc
                        INNER JOIN centros_custos_sindicatos cci
                                ON cci.idcentro_custo = cc.idcentro_custo AND cci.ativo = 'S'
                        WHERE cci.idsindicato = $idsindicato
                            AND cc.ativo = 'S'
                            AND cc.ativo_painel = 'S'
                        UNION
                        SELECT
                            cc.idcentro_custo, cc.nome
                        FROM
                            centros_custos cc
                        WHERE (SELECT COUNT(1) FROM centros_custos_sindicatos WHERE idcentro_custo = cc.idcentro_custo) = 0
                        AND cc.ativo = 'S'
                        AND cc.ativo_painel = 'S'
                        GROUP BY cc.idcentro_custo";
       }else{
           $this->sql = "SELECT
                            cc.idcentro_custo, cc.nome
                        FROM
                            centros_custos cc
                        INNER JOIN centros_custos_sindicatos cci
                                ON cci.idcentro_custo = cc.idcentro_custo AND cci.ativo = 'S'
                        INNER JOIN obz_sindicatos_usuarios_adm oiua
                                ON oiua.idcentro_custo = cc.idcentro_custo AND cci.ativo = 'S'
                        WHERE cci.idsindicato = $idsindicato
                              AND (oiua.idresponsavel = $idusuario OR oiua.iddono = $idusuario)
                              AND cc.ativo = 'S'
                              AND cc.ativo_painel = 'S'
                        UNION
                        SELECT
                            cc.idcentro_custo, cc.nome
                        FROM
                            centros_custos cc
                        INNER JOIN centros_custos_sindicatos cci
                                ON cci.idcentro_custo = cc.idcentro_custo AND cci.ativo = 'S'
                        WHERE
                        (
                          (SELECT
                               COUNT(1)
                           FROM
                               obz_sindicatos_usuarios_adm
                           WHERE
                              idsindicato = cci.idsindicato AND
                              idcentro_custo = cc.idcentro_custo AND
                              ativo = 'S' AND
                              (
                                (
                                  (idresponsavel = 0 OR idresponsavel IS NULL)
                                  OR
                                  (iddono = 0 OR iddono IS NULL)
                                )
                              )
                          ) > 0
                        OR
                          (SELECT
                                COUNT(1)
                            FROM
                                obz_sindicatos_usuarios_adm
                            WHERE idsindicato = cci.idsindicato AND
                                  idcentro_custo = cc.idcentro_custo
                          ) = 0
                        )
                        AND cci.idsindicato = $idsindicato
                        AND cc.ativo = 'S'
                        AND cc.ativo_painel = 'S'
                        GROUP BY cc.idcentro_custo";
       }

       $this->groupby = "idcentro_custo";
       $this->limite = -1;
       $this->ordem_campo = "nome";
       $this->ordem = "ASC";
       //echo $this->sql;exit;
       $dados = $this->retornarLinhas();

       if($this->json){
           echo json_encode($dados);

           if(empty($dados)){
              return false;
           }
           return true;

       }else{
           return $dados;
       }
    }

    public function retornarCategorias($idsindicato, $idcentro_custo)
    {
        $this->sql = "SELECT
                          c.idcategoria, c.nome
                      FROM
                          categorias c
                              INNER JOIN
                          categorias_subcategorias csc ON csc.idcategoria = c.idcategoria
                              AND c.ativo = 'S'
                              INNER JOIN
                          obz_subcategorias_centros osc ON osc.idsubcategoria = csc.idsubcategoria
                              AND osc.ativo = 'S'
                              INNER JOIN
                          centros_custos cc ON cc.idcentro_custo = osc.idcentro_custo
                              INNER JOIN
                          sindicatos i ON i.idsindicato = osc.idsindicato
                      WHERE
                          osc.idsindicato = $idsindicato
                              AND osc.idcentro_custo = $idcentro_custo
                              AND c.ativo = 'S'
                              AND c.ativo_painel = 'S'
                              AND csc.ativo_painel = 'S'
                              AND csc.ativo = 'S'
                      GROUP BY c.idcategoria";

       $this->groupby = "c.idcategoria";
       $this->limite = -1;
       $this->ordem_campo = "c.nome";
       $this->ordem = "ASC";
       $dados = $this->retornarLinhas();
       if($this->json){
           echo json_encode($dados);
       }else{
           return $dados;
       }
    }

    public function retornarSubCategorias($idsindicato, $idcentro_custo, $idcategoria)
    {
        $this->sql = "SELECT
                          csc.idsubcategoria, csc.nome
                      FROM
                          categorias c
                              INNER JOIN
                          categorias_subcategorias csc ON csc.idcategoria = c.idcategoria
                              INNER JOIN
                          obz_subcategorias_centros osc ON osc.idsubcategoria = csc.idsubcategoria
                              INNER JOIN
                          centros_custos cc ON cc.idcentro_custo = osc.idcentro_custo
                              INNER JOIN
                          sindicatos i ON i.idsindicato = osc.idsindicato
                      WHERE
                          osc.idsindicato = $idsindicato
                              AND osc.idcentro_custo = $idcentro_custo
                              AND c.idcategoria = $idcategoria
                              AND csc.ativo = 'S'
                              AND osc.ativo = 'S'
                              AND c.ativo = 'S'
                              AND cc.ativo = 'S'
                              AND c.ativo_painel = 'S'
                              AND csc.ativo_painel = 'S'
                      GROUP BY csc.idsubcategoria";

       $this->groupby = "csc.idsubcategoria";
       $this->limite = -1;
       $this->ordem_campo = "csc.nome";
       $this->ordem = "ASC";
       $dados = $this->retornarLinhas();
       if($this->json){
           echo json_encode($dados);
       }else{
           return $dados;
       }
    }

    public function verificarPeriodoInstituicao($idsindicato)
    {
        $data = date("Y-m-d");
        $sql = "SELECT periodo_de, periodo_ate FROM obz_sindicatos WHERE idsindicato = $idsindicato";
        $sindicato = $this->retornarLinha($sql);

        if($sindicato["periodo_de"] && $sindicato["periodo_ate"]){
           return $this->verificaRange($sindicato["periodo_de"], $sindicato["periodo_ate"], $data);
        }else{
            return false;
        }
    }

    public function verificaRange($start_date, $end_date, $date_from_user)
    {
      $start_ts = strtotime($start_date);
      $end_ts = strtotime($end_date);
      $user_ts = strtotime($date_from_user);

      return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    public function verificaPermissaoInstituicao($sindicatos, $idsindicatoGet)
    {
      if(!is_array($sindicatos)){
         return false;
      }

      foreach($sindicatos as $sindicato){
        if($sindicato["idsindicato"] == $idsindicatoGet){
          return true;
        }
      }
      return false;
    }

    public function verificaPermissaoCentroCusto($centrosCustos, $idCentroCustoGet, $idusuario)
    {
      if(!is_array($centrosCustos)){
         return false;
      }

      foreach($centrosCustos as $centroCusto){
        if($centroCusto["idcentro_custo"] == $idCentroCustoGet){
          return true;
        }
      }

      $sql = "SELECT gestor_centro_custo FROM obz_usuarios_adm WHERE idusuario = $idusuario";
      $usuario = $this->retornarLinha($sql);

      if($usuario["gestor_centro_custo"] == "S"){
        return true;
      }

      return false;
    }

    public function verificaPermissaoCategorias($categorias, $idCategoriaGet)
    {
      if(!is_array($categorias)){
         return false;
      }

      foreach($categorias as $categoria){
        if($categoria["idcategoria"] == $idCategoriaGet){
          return true;
        }
      }
      return false;
    }

    public function verificaPermissaoSubCategorias($subCategorias, $idSubCategoriaGet)
    {
      if(!is_array($subCategorias)){
         return false;
      }

      foreach($subCategorias as $subCategoria){
        if($subCategoria["idsubcategoria"] == $idSubCategoriaGet){
          return true;
        }
      }
      return false;
    }
}
