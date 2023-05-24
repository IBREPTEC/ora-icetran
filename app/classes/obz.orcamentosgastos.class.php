<?php

class Orcamentos_Gastos_OBZ extends Core {

    public function retornarPolos() {


		$this->sql = "SELECT
                      oog.*,
                      e.nome_fantasia,
                      e.idescola
                  FROM
                      escolas e
                  INNER JOIN
                      obz_orcamentos_gastos oog
                          ON e.idescola = oog.idescola
                          AND e.ativo = 'S' AND e.ativo_painel = 'S'
                          AND oog.idsindicato = $this->idsindicato
                          AND oog.idcentro_custo = $this->idcentro_custo
                          AND oog.idcategoria = $this->idcategoria
                          AND oog.idsubcategoria = $this->idsubcategoria
                          AND e.idsindicato = $this->idsindicato
                          AND oog.ano = $this->ano
                  WHERE e.idsindicato = $this->idsindicato
                  AND e.ativo = 'S'
                  AND e.ativo_painel = 'S'
                
                  GROUP BY e.idescola";
        $this->limite = -1;
        $this->ordem_campo = false;
        $this->ordem = false;
        return $this->retornarLinhas();
    }


    public function cadastrarModificar() {

        foreach ($this->post["polos"] as $idescola => $dados) {

            $sql = "SELECT
                       idorcamento
                    FROM
                       obz_orcamentos_gastos
                    WHERE
                       idescola = $idescola
                       AND idsindicato = {$this->post["idsindicato"]}
                       AND ano = {$this->post["ano"]}
                       AND idcentro_custo = {$this->post["idcentro_custo"]}
                       AND idcategoria = {$this->post["idcategoria"]}
                       AND idsubcategoria = {$this->post["idsubcategoria"]}";


            $orcamento_sql = $this->executaSql($sql);

            if(!$orcamento_sql){
                return false;
            }

            $orcamento = mysql_fetch_assoc($orcamento_sql);


            if ($orcamento["idorcamento"]) {
                    $this->sql = "UPDATE
                                obz_orcamentos_gastos
                              SET
                                idescola = $idescola,
                                idsindicato = {$this->post["idsindicato"]},
                                ano = {$this->post["ano"]},
                                idcentro_custo = {$this->post["idcentro_custo"]},
                                idcategoria = {$this->post["idcategoria"]},
                                idsubcategoria = {$this->post["idsubcategoria"]},";

                } else {

                $this->sql = "INSERT INTO
                                obz_orcamentos_gastos
                              SET
                                idescola = $idescola,
                                idsindicato = {$this->post["idsindicato"]},
                                ano = {$this->post["ano"]},
                                idcentro_custo = {$this->post["idcentro_custo"]},
                                idcategoria = {$this->post["idcategoria"]},
                                idsubcategoria = {$this->post["idsubcategoria"]},";

                }
                $insereComValor = false;
                foreach ($dados as $indice => $valor) {

                    if ($indice == "descricao") {
                        if ($valor) {
                            $insereComValor = true;
                            $this->sql .= "descricao = '" . $this->retornarEscapeStringQuery($valor) . "', ";
                        } else {
                            $this->sql .= "descricao = NULL, ";
                        }
                    } elseif ($indice == "memorial") {
                        if ($valor) {
                            $insereComValor = true;
                            $this->sql .= "memorial = '" . $this->retornarEscapeStringQuery($valor) . "', ";
                        } else {
                            $this->sql .= "memorial = NULL, ";
                        }
                    } elseif ($indice == "idorcamento") {
                    } elseif ($indice != "mes_12") {
                        if ($valor) {
                            $insereComValor = true;
                            $valor = str_replace(array(".", ","), array("", "."), $valor);
                            $this->sql .= "$indice = $valor, ";
                        } else {
                            $this->sql .= "$indice = NULL, ";
                        }
                    } else {
                        if ($valor) {
                            $insereComValor = true;
                            $valor = str_replace(array(".", ","), array("", "."), $valor);
                            $this->sql .= "$indice = $valor";
                        } else {
                            $this->sql .= "$indice = NULL";
                        }
                    }
                }

                if ($orcamento['idorcamento']) {

                    $this->sql .= " WHERE idorcamento = {$orcamento['idorcamento']}";
                }
                    $this->monitora_dadosantigos = NULL;
                    $this->monitora_dadosnovos = NULL;
                    $monitora = false;


                if ($orcamento["idorcamento"]) {


                    $sql = "select * from obz_orcamentos_gastos where idorcamento = " . $orcamento['idorcamento'];
                    $this->monitora_dadosantigos = $this->retornarLinha($sql);
                    $monitora = true;
                    $executa = $this->retornarLinha($this->sql);
                    $this->sql = "select * from obz_orcamentos_gastos where idorcamento = " .$orcamento['idorcamento'];
                    $this->monitora_dadosnovos = $this->retornarLinha($this->sql);
                    $this->monitora_oque = 2;
                    $this->monitora_qual = $orcamento['idorcamento'];
                } else {

                    $executa = $this->executaSql($this->sql);
                    $sql_max_id = "select max(idorcamento) from obz_orcamentos_gastos";
                    $max = $this->retornarLinha($sql_max_id);
                    $this->monitora_qual =  $max['max(idorcamento)'];
                    $dadosAntigos = array("descricao" => "", "memorial" => "", "mes_1" => "", "mes_2" => "", "mes_3" => "", "mes_4" => "", "mes_5" => "", "mes_6" => "", "mes_7" => "", "mes_8" => "", "mes_9" => "", "mes_10" => "", "mes_11" => "", "mes_12" => "");
                    $this->monitora_dadosantigos = $dadosAntigos;
                    $this->sql = "select * from obz_orcamentos_gastos where idorcamento = " . $max['max(idorcamento)'];
                    $this->monitora_dadosnovos = $this->retornarLinha($this->sql);
                    $this->monitora_oque = 2;
                    $monitora = true;

            }
                if ($executa) {
                    $this->monitora_onde = 294;
                    if ($monitora && $insereComValor){
                        $this->Monitora();
                    }
                }

            $this->retorno["sucesso"] = true;
        }
        return $this->retorno;
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
                            cc.idcentro_custo, cc.nome                        FROM
                            centros_custos cc
                        INNER JOIN centros_custos_sindicatos cci
                                ON cci.idcentro_custo = cc.idcentro_custo AND cci.ativo = 'S'
                        WHERE
                        (
                          (SELECT
                              COUNT(1)
                            FROM
                              obz_sindicatos_usuarios_adm
                            WHERE idsindicato = cci.idsindicato AND
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
                        INNER JOIN categorias_subcategorias csc
                                ON csc.idcategoria = c.idcategoria AND c.ativo = 'S'
                        INNER JOIN  obz_subcategorias_centros osc
                                ON osc.idsubcategoria = csc.idsubcategoria AND osc.ativo = 'S'
                        INNER JOIN centros_custos cc
                                ON cc.idcentro_custo = osc.idcentro_custo
                        INNER JOIN sindicatos i
                                ON i.idsindicato = osc.idsindicato
                        WHERE osc.idsindicato = $idsindicato
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
                        INNER JOIN categorias_subcategorias csc
                                ON csc.idcategoria = c.idcategoria
                        INNER JOIN  obz_subcategorias_centros osc
                                ON osc.idsubcategoria = csc.idsubcategoria
                        INNER JOIN centros_custos cc
                                ON cc.idcentro_custo = osc.idcentro_custo
                        INNER JOIN sindicatos i
                                ON i.idsindicato = osc.idsindicato
                        WHERE osc.idsindicato = $idsindicato
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

    public function verificaPermissaoInstituicao($sindicatos, $idSindicatoGet)
    {
      if(!is_array($sindicatos)){
         return false;
      }

      foreach($sindicatos as $sindicato){
        if($sindicato["idsindicato"] == $idSindicatoGet){
          return true;
        }
      }
      return false;
    }

    public function verificaPermissaoCentroCusto($centros_custos, $idcentro_custoGet, $idusuario)
    {
      if(!is_array($centros_custos)){
         return false;
      }

      foreach($centros_custos as $escola){
        if($escola["idcentro_custo"] == $idcentro_custoGet){
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
