<?php
class Polos extends Core {

    function ListarTodas() {
        $this->sql = "select
                    ".$this->campos."
                  from
                    polos p
                    inner join sindicatos i on (p.idsindicato = i.idsindicato)
                  where
                    p.ativo = 'S'";
        if($this->idsindicato){
            $this->sql .= " AND p.idsindicato = $this->idsindicato";
        }
//
//    if($_SESSION['adm_gestor_polo'] != 'S'){
//        $this->sql .= ' AND p.idpolo in ('.$_SESSION['adm_polos'].')';
//    }
        $this->aplicarFiltrosBasicos();

        $this->groupby = "p.idpolo";
        $this->ordem_campo = "p.ativo_painel";
        $this->ordem = 'asc';
        return $this->retornarLinhas();
    }


    function Retornar() {
        $this->sql = "select
                    ".$this->campos."
                  from
                    polos p
                    inner join sindicatos i on (p.idsindicato = i.idsindicato)
                  where
                    p.ativo = 'S' and
                    p.idpolo = '".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar() {
        if($this->post["documento_tipo"] == "cnpj") {
            $this->config["formulario"][0]["campos"][3]["validacao"] = $this->config["formulario"][0]["campos"][4]["validacao"];
            $this->post["documento"] = $this->post["documento_cnpj"];

            unset($this->post["documento_cnpj"]);
            unset($this->config["formulario"][0]["campos"][4]);
        } else {
            unset($this->post["documento_cnpj"]);
            unset($this->config["formulario"][0]["campos"][4]);
        }
        return $this->SalvarDados();
    }

    function Modificar() {
        unset($this->config["formulario"][0]["campos"][2]);
        unset($this->config["formulario"][0]["campos"][3]);
        unset($this->config["formulario"][0]["campos"][4]);
        return $this->SalvarDados();
    }

    function Remover() {
        return $this->RemoverDados();
    }

    //FUNCOES DE CONTATO DO POLO
    function ListarTiposContatos() {
        $this->retorno = array();
        $this->sql = "SELECT * FROM tipos_contatos where ativo = 'S' order by nome asc";
        $seleciona = $this->executaSql($this->sql);
        while(($tipo = $this->retornarFetchAssocQuery($seleciona)) !== false) {
            $this->retorno[] = $tipo;
        }
        return $this->retorno;
    }

    function ListarContatos() {
        $this->sql = "SELECT ".$this->campos." FROM
                            polos_contatos c
                            INNER JOIN tipos_contatos tc ON (c.idtipo = tc.idtipo)
                        where c.ativo = 'S' and c.idpolo = ".intval($this->id);

        $this->groupby = "c.idpolo";
        return $this->retornarLinhas();
    }

    function adicionarContato() {

        if (! $this->post["idtipo"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_tipo_vazio';
            return $this->retorno;
        }

        if (! $this->post["valor"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_valor_vazio';
            return $this->retorno;
        }

        $this->retorno = array();
        $this->sql = "insert into polos_contatos set
                        data_cad=now(), ativo='S', idpolo='".$this->id."', idtipo='".$this->post["idtipo"]."', valor='".$this->post["valor"]."'";
        $cadastrar = $this->executaSql($this->sql);
        if($cadastrar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 70;
            $this->monitora_qual = $this->retornarLastInsertIdQuery();
            $this->Monitora();
        } else {
            $this->retorno["sucesso"] = false;
        }
        return $this->retorno;
    }

    function RemoverContato() {
        $this->sql = "update polos_contatos set ativo='N' where idcontato='".intval($this->post["remover"])."' and idpolo = ".intval($this->id);
        if($this->executaSql($this->sql)){
            $remover["sucesso"] = true;
            $this->monitora_oque = 3;
            $this->monitora_onde = 70;
            $this->monitora_qual = $this->post["remover"];
            $this->Monitora();
        } else {
            $remover["sucesso"] = false;
        }

        return $remover;

    }

    function ListarPolosPorCidade()
    {
        $cidades = array();

        $this->sql = 'SELECT
                        c.idcidade,
                        c.nome
                    FROM
                        polos p
                        INNER JOIN cidades c ON (p.idcidade = c.idcidade)
                    WHERE
                        p.ativo = "S"
                    GROUP BY c.idcidade, c.nome';

        $this->ordem_campo = 'c.nome';
        $this->ordem = 'ASC';
        $this->limite = -1;
        $cidades = $this->retornarLinhas();

        foreach ($cidades as $ind => $cidade) {
            $this->sql = 'SELECT
                            idpolo,
                            nome_fantasia
                        FROM
                            polos
                        WHERE
                            idcidade = '.$cidade['idcidade'].' AND
                            ativo = "S"';
            $this->ordem_campo = 'nome_fantasia';
            $this->ordem = 'ASC';
            $this->limite = -1;
            $cidades[$ind]['polos'] = $this->retornarLinhas();
        }

        return $cidades;
    }

    public function listarPolossindicatos(array $sindicatos)
    {
        $this->sql = "SELECT idpolo, nome_fantasia as nome FROM polos WHERE ativo = 'S' AND ativo_painel = 'S' AND idsindicato IN (" . implode(', ', $sindicatos) . ")";

        if($_SESSION['adm_gestor_polo'] != 'S' && $this->idvendedor == null){
            $this->sql .= ' and idpolo in ('.$_SESSION['adm_polos'].')';
        }

        $this->limite = -1;
        $this->ordem_campo = 'nome_fantasia';
        $this->ordem = 'ASC';
        return  $this->retornarLinhas();
    }

    public function retornarTodosPolos()
    {
        $this->sql = "SELECT idpolo, nome_fantasia FROM polos WHERE ativo = 'S' AND ativo_painel = 'S'";
        $this->limite = -1;
        $this->ordem_campo = 'nome_fantasia';
        $this->ordem = 'ASC';
        return  $this->retornarLinhas();
    }
}
