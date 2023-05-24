<?php
class Centros_Custos_OBZ extends Core {

    function ListarTodas() {
        $this->sql = "select ".$this->campos." from centros_custos where ativo = 'S'";

        if(is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if(($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if($campo[0] == 1) {
                        $this->sql .= " and ".$campo[1]." = '".$valor."' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif($campo[0] == 2)  {
                        $busca = str_replace("\\'","",$valor);
                        $busca = str_replace("\\","",$busca);
                        $busca = explode(" ",$busca);
                        foreach($busca as $ind => $buscar){
                            $this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
                        }
                    } elseif($campo[0] == 3)  {
                        $this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
                    }
                }
            }
        }

        $this->groupby = "idcentro_custo";
        return $this->retornarLinhas();
    }


    public function Retornar() {
        $this->sql = "select ".$this->campos." from centros_custos where ativo = 'S' and idcentro_custo = '".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    public function ListarSindicatosAssociados() {
        $this->sql = "select 
					".$this->campos." 
				  from
					centros_custos_sindicatos cci
					inner join sindicatos i ON (cci.idsindicato = i.idsindicato)
				  where 
					i.ativo = 'S' and 
					cci.ativo= 'S' and 
					cci.idcentro_custo = ".intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "i.nome";
        return $this->retornarLinhas();
    }
    public function associarUsuarioSindicato($idsindicato, $idcentro_custo, $iddono = NULL, $idresponsavel = NULL)
    {
        $dono = $this->verificaExistenciaDono($idsindicato, $idcentro_custo);
        if ($dono) {
            if ($dono["iddono"] != $iddono) {
                $this->retorno = $this->atualizarDono($idsindicato, $iddono, $idcentro_custo);
            }
        } else {
            $this->retorno = $this->inserirDono($idsindicato, $iddono, $idcentro_custo);
        }

        $responsavel = $this->verificaExistenciaResponsavel($idsindicato, $idcentro_custo);
        if ($responsavel) {
            if ($responsavel["idresponsavel"] != $idresponsavel) {
                $this->retorno = $this->atualizarResponsavel($idsindicato, $idresponsavel, $idcentro_custo);
            }
        } else {
            $this->retorno = $this->inserirResponsavel($idsindicato, $idresponsavel, $idcentro_custo);
        }
        return $this->retorno;
    }

    public function verificaExistenciaDono($idsindicato, $idcentro_custo)
    {
        $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
        return $this->retornarLinha($sql);
    }

    public function verificaExistenciaResponsavel($idsindicato, $idcentro_custo)
    {
        $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
        return $this->retornarLinha($sql);
    }

    public function inserirDono($idsindicato, $iddono, $idcentro_custo)
    {
        $sql = "INSERT INTO 
                    obz_sindicatos_usuarios_adm 
                SET 
                    idsindicato = $idsindicato,
                    iddono = '$iddono',
                    idcentro_custo = $idcentro_custo,   
                    data_cad = NOW()";

        if($this->executaSql($sql)){
            $linhaAntiga["idpresponsavel"] = "";
            $linhaAntiga["iddono"] = "";
            $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
            $linhaNova = $this->retornarLinha($sql);
            $this->retorno["sucesso"] = true;
            $this->monitora_onde = 295;
            $this->monitora_oque = 2;
            $this->monitora_qual = $linhaNova["idobzsindicatosato"];
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        }
        return $this->retorno;
    }

    public function atualizarDono($idsindicato, $iddono, $idcentro_custo)
    {
        if(empty($iddono)){
            $iddono = "NULL";
        }

        $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
        $linhaAntiga = $this->retornarLinha($sql);

        $sql = "UPDATE 
                    obz_sindicatos_usuarios_adm 
                SET 
                    iddono = $iddono
                WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";

        if($this->executaSql($sql)){
            $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
            $linhaNova = $this->retornarLinha($sql);
            $this->retorno["sucesso"] = true;
            $this->monitora_onde = 295;
            $this->monitora_oque = 2;
            $this->monitora_qual = $linhaNova["idobzsindicatos"];
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        }
        return $this->retorno;
    }

    public function inserirResponsavel($idsindicato, $idresponsavel, $idcentro_custo)
    {
        $sql = "INSERT INTO 
                    obz_sindicatos_usuarios_adm 
                SET 
                    idsindicato = $idsindicato,
                    idresponsavel = '$idresponsavel',
                    idcentro_custo = $idcentro_custo    
                    data_cad = NOW()";

        if($this->executaSql($sql)){
            $linhaAntiga["idpresponsavel"] = "";
            $linhaAntiga["iddono"] = "";
            $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
            $linhaNova = $this->retornarLinha($sql);
            $this->retorno["sucesso"] = true;
            $this->monitora_onde = 295;
            $this->monitora_oque = 2;
            $this->monitora_qual = $linhaNova["idobzsindicatos"];
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        }

        return $this->retorno;
    }

    public function atualizarResponsavel($idsindicato, $idresponsavel, $idcentro_custo)
    {
        if(empty($idresponsavel)){
            $idresponsavel = "NULL";
        }

        $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
        $linhaAntiga = $this->retornarLinha($sql);

        $sql = "UPDATE 
                    obz_sindicatos_usuarios_adm 
                SET 
                    idresponsavel = $idresponsavel
                WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";

        if($this->executaSql($sql)){
            $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
            $linhaNova = $this->retornarLinha($sql);
            $this->retorno["sucesso"] = true;
            $this->monitora_onde = 295;
            $this->monitora_oque = 2;
            $this->monitora_qual = $linhaNova["idobzsindicatos"];
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();
        }
        return $this->retorno;
    }

    public function retornarDonoResponsavel($idsindicato, $idcentro_custo)
    {
        $sql = "SELECT * FROM obz_sindicatos_usuarios_adm WHERE idsindicato = $idsindicato AND idcentro_custo = $idcentro_custo";
        return $this->retornarLinha($sql);
    }
}