<?php
class MotivosOrdemDeCompraOBZ extends Core
{
    
    var $ativo_painel = null;
    
    
    function ListarTodas()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					obz_motivos_ordemcompra
				  WHERE 
					ativo = 'S'";

        if($this->ativo_painel){
            $this->sql .= " ativo_painel = 'S' ";
        }
        
        $this->aplicarFiltrosBasicos();

        $this->groupby = "idmotivo";
        return $this->retornarLinhas();
    }
    
    function Retornar()
    {
        $this->sql = "SELECT
					" . $this->campos . "
				  FROM
					obz_motivos_ordemcompra
				  WHERE 
					ativo = 'S' and 
					idmotivo = '" . $this->id . "'";
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
    
    public function verificaExistencia($idmotivo)
    {
      $sql = "SELECT * FROM obz_motivos_ordemcompra WHERE idmotivo = {$idmotivo}";
      $dado = $this->retornarLinha($sql);
      
      if($dado["idmotivo"]){
          return true;
      }
      
      return false;
    }
}