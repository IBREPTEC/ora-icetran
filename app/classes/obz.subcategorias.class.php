<?php
class SubCategoriasOBZ extends Core
{
    function ListarTodas()
    {
        $this->sql = "select
					" . $this->campos . "
				  from
					usuarios_adm u
					left outer join usuarios_adm_perfis p on (u.idperfil = p.idperfil)
				  where
					u.ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "u.idusuario";
        return $this->retornarLinhas();
    }

    function InsereSubCentro($idsubcategoria,$idcentro_custo , $idsindicato){
        $sql = "INSERT INTO
                    obz_subcategorias_centros
                    (idsubcategoria , idcentro_custo , idsindicato , data_cad , ativo)
                VALUES
                    ( ".$idsubcategoria." , ".$idcentro_custo." , ".$idsindicato." , NOW() , 'S' )";

        $retorno = $this->executaSql($sql);

        return $retorno;

    }

    function RetornaSubCentro($idsubcategoria){
        $sql = "SELECT cc.* , osc.idsubcategoria_centro FROM
                    obz_subcategorias_centros osc
                INNER JOIN
                    centros_custos cc ON (osc.idcentro_custo = cc.idcentro_custo)
                WHERE
                    osc.ativo = 'S'
                AND
                    osc.idsubcategoria = ".$idsubcategoria;

        $query = $this->executaSql($sql);

        while($aux = mysql_fetch_assoc($query)){
            $retorno[] = $aux;
        }
        return $retorno;

    }

    function RetornaSubCentroInstituicao($idsubcategoria , $idsindicato){
        $sql = "SELECT
                    cc.* , i.nome_abreviado as nome_sindicato , osc.idsindicato , osc.idsubcategoria_centro
                FROM
                    obz_subcategorias_centros osc
                INNER JOIN
                    centros_custos cc ON (osc.idcentro_custo = cc.idcentro_custo)
                INNER JOIN
                        sindicatos i ON (osc.idsindicato = i.idsindicato)
                WHERE
                    osc.ativo = 'S'
                AND
                    osc.idsindicato = ".$idsindicato."
                AND
                    osc.idsubcategoria = ".$idsubcategoria;

        $this->ordem_campo = null;


        $query = $this->executaSql($sql);

        while($aux = mysql_fetch_assoc($query)){
            $retorno[] = $aux;
        }
        return $retorno;



    }

    function RetornaSubCentroInstituicaoOLD($idsubcategoria , $idsindicato){
        $sql = "SELECT cc.* , cci.idsindicato , osc.idsubcategoria_centro FROM
                    obz_subcategorias_centros osc
                INNER JOIN
                    centros_custos cc ON (osc.idcentro_custo = cc.idcentro_custo)
                INNER JOIN
                    centros_custos_sindicatos cci ON (cci.idcentro_custo = cc.idcentro_custo)
                INNER JOIN
                    sindicatos i ON (i.idsindicato = cci.idsindicato)
                WHERE
                    osc.ativo = 'S'
                AND
                    cci.idsindicato = ".$idsindicato."
                AND
                    osc.idsubcategoria = ".$idsubcategoria;

        $query = $this->executaSql($sql);

        while($aux = mysql_fetch_assoc($query)){
            $retorno[] = $aux;
        }
        return $retorno;

    }

    function RemoveSubCentro($id){
        $sql = "UPDATE
                    obz_subcategorias_centros
                SET
                    ativo = 'N'
                WHERE
                    ativo = 'S'
                AND
                    idsubcategoria_centro = ".$id;

        $query = $this->executaSql($sql);

        while($aux =mysql_fetch_assoc($query)){
            $retorno[] = $aux;
        }
        return $retorno;

    }

    public function retornarInstituicoes($idsubcategoria)
    {
        if (!is_numeric($idsubcategoria)) {
            throw new InvalidArgumentException('Parâmetro $idCategoria deve conter um valor numérico');
        }

        $this->sql = 'SELECT
                        csi.*,i.nome as nome_sindicato ,i.nome_abreviado
                      FROM
                        categorias_subcategorias_sindicatos csi
                      INNER JOIN
                        sindicatos i ON (csi.idsindicato = i.idsindicato)
                      WHERE
                        csi.ativo = "S"
                      AND
                        i.ativo = "S"
                      AND
                        i.ativo_painel = "S"
                      AND
                        csi.idsubcategoria = ' . $idsubcategoria;

        $this->limite = -1;
        $this->ordem_campo = null;

        $retornar = $this->retornarLinhas();




        return $retornar;
    }

    function BuscarCentrosDeCusto()
    {
        $this->sql = "SELECT
                cc.nome as value , cc.idcentro_custo as 'key'
            FROM
                centros_custos_sindicatos cci
            INNER JOIN
                centros_custos cc ON ( cc.idcentro_custo = cci.idcentro_custo)
            WHERE
                cc.nome LIKE '%" . $_GET["tag"] . "%'
            AND
                cci.ativo = 'S'
            AND
                cc.ativo = 'S'
            AND
					not exists (
					  select
						osc.idsubcategoria_centro
					  from
						obz_subcategorias_centros osc
					  where
						osc.idcentro_custo = cc.idcentro_custo and  osc.ativo = 'S' and
						osc.idsubcategoria = '" . intval($this->id) . "' and
                        osc.idsindicato = '" . $_GET["idsindicato"] . "'
					)
            AND
                cci.idsindicato = ".$_GET["idsindicato"];

        $this->limite = -1;
        $this->ordem_campo = "cc.nome";
        $this->groupby = "cc.idcentro_custo";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }

    function Retornar()
    {
        $this->sql = "SELECT
                        r.nome as nome, s.tipo_despesas ,r.idunidade,s.idsubcategoria
                      FROM
                        obz_subcategorias s
                      LEFT JOIN obz_unidade_rateio r ON (s.idunidade = r.idunidade)
                      INNER JOIN categorias_subcategorias sub ON (s.idsubcategoria = sub.idsubcategoria)
                      WHERE
                        sub.idsubcategoria = " . $this->id;
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        $this->monitora_qual = $this->post['idusuario'];
        return $this->SalvarDados();
    }

    function Modificar()
    {
        return $this->SalvarDados();
    }

    public function verificaExistencia($idsubcategoria)
    {
      $sql = "SELECT * FROM obz_subcategorias WHERE idsubcategoria = {$idsubcategoria}";
      $dado = $this->retornarLinha($sql);

      if($dado["idsubcategoria"]){
          return true;
      }

      return false;
    }
}
