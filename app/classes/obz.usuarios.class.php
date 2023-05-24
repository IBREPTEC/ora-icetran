<?php
class Usuarios_OBZ extends Core
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


    function Retornar()
    {
        $this->sql = "select
					" . $this->campos . "
				  from
					usuarios_adm u
					left outer join usuarios_adm_perfis p ON (u.idperfil = p.idperfil) 
					left outer join cidades c ON (c.idcidade = u.idcidade)
					left outer join estados e ON (e.idestado = c.idestado)
                    left outer join obz_usuarios_adm oua ON (u.idusuario = oua.idusuario)
				  where 
					u.ativo = 'S' and 
					u.idusuario = '" . $this->id . "'";
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
    
    public function verificaExistencia($idusuario)
    {
      $sql = "SELECT * FROM obz_usuarios_adm WHERE idusuario = {$idusuario}";
      $dado = $this->retornarLinha($sql);
      
      if($dado["idusuario"]){
          return true;
      }
      
      return false;
    }
}