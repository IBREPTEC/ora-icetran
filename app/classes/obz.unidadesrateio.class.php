<?php
class UnidadesRateioOBZ extends Core
{
    
    var $ativo_painel = null;
    
    
    function ListarTodas()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					obz_unidade_rateio
				  WHERE 
					ativo = 'S'";

        if($this->ativo_painel){
            $this->sql .= " ativo_painel = 'S' ";
        }
        
        $this->aplicarFiltrosBasicos();

        $this->groupby = "idunidade";
        return $this->retornarLinhas();
    }
    
    function Retornar()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					obz_unidade_rateio
				  WHERE 
					ativo = 'S' and 
					idunidade = '" . $this->id . "'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        return $this->SalvarDados(); 
    }
    
    function Remover()
    {
        return $this->RemoverDados(); 
    }

    function Modificar()
    {
        return $this->SalvarDados();
    }
    
    public function verificaExistencia($idunidade)
    {
      $sql = "SELECT * FROM obz_unidade_rateio WHERE idunidade = {$idunidade}";
      $dado = $this->retornarLinha($sql);
      
      if($dado["idunidade"]){
          return true;
      }
      
      return false;
    }
}