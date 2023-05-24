<?php
class ClassificacaoDespesasOBZ extends Core
{
    function listarTodas()
    {
        $this->sql = "SELECT
                            " . $this->campos . "
                        FROM
                            obz_classificacao_despesa
                        WHERE
                            ativo = 'S'";

        $this->aplicarFiltrosBasicos();

//        if($_SESSION["adm_gestor_polo"] <> "S" && $this->idusuario){
//            $this->sql .= " and idclassificacao in (SELECT idclassificacao FROM obz_classificacao_despesa_regras WHERE
//        ativo = 'S' and idpolo in (" . $_SESSION["adm_polos"] . ")) ";
//        }

        $this->groupby = "idclassificacao";
        return $this->retornarLinhas();
    }

    function retornar()
    {
        $this->sql = "SELECT
                            " . $this->campos . "
                        FROM
                            obz_classificacao_despesa
                        WHERE
                            idclassificacao = '" . $this->id . "' AND
                            ativo = 'S'";
        return $this->retornarLinha($this->sql);
    }

    function cadastrar()
    {
        return $this->SalvarDados();
    }

    function modificar()
    {
        return $this->SalvarDados();
    }

    function remover()
    {
        return $this->RemoverDados();
    }

    function listarTodasRegras($idclassificacao)
    {
        $this->sql = "SELECT
                            " . $this->campos . "
                        FROM
                            obz_classificacao_despesa_regras ocdr
                            INNER JOIN escolas e ON (e.idescola = ocdr.idescola)
                            INNER JOIN centros_custos cc ON (cc.idcentro_custo = ocdr.idcentro_custo)
                            INNER JOIN categorias c ON (c.idcategoria = ocdr.idcategoria)
                            INNER JOIN categorias_subcategorias cs ON (cs.idsubcategoria = ocdr.idsubcategoria)
                        WHERE
                            ocdr.idclassificacao = '" . $idclassificacao . "' AND
                            ocdr.ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        $this->groupby = "ocdr.idregra";
        return $this->retornarLinhas();
    }

    function cadastrarRegra()
    {
        $this->monitora_onde = $this->config['monitoramento_regra']['onde'];//Seta o monitoramento da tabela
        $this->config['banco'] = $this->config['banco_regras'];//Seta o banco do CRUD
        $this->config['formulario'] = $this->config['formulario_regra'];//Seta o formulário de regras
        return $this->SalvarDados();
    }

    function removerRegra()
    {
        $this->monitora_onde = $this->config['monitoramento_regra']['onde'];//Seta o monitoramento da tabela
        $this->config['banco'] = $this->config['banco_regras'];//Seta o banco do CRUD
        return $this->RemoverDados();
    }

    //Lista Centros de custo da instituição de um polo
    function listarCentrosCustoJson($idescola)
    {
        $this->sql = 'SELECT
                            cc.idcentro_custo,
                            cc.nome
                        FROM
                            centros_custos cc
                            INNER JOIN centros_custos_sindicatos cci ON (cci.idcentro_custo = cc.idcentro_custo AND cci.ativo = "S")
                            INNER JOIN escolas e ON (e.idsindicato = cci.idsindicato)
                        WHERE
                            e.idescola = "' . $idescola . '" AND
                            cc.ativo = "S" AND
                            cc.ativo_painel = "S"
                        GROUP BY
                            cc.idcentro_custo';

        $this->limite = -1;
        $this->ordem_campo = 'cc.nome';
        $this->ordem = 'ASC';

        return json_encode($this->retornarLinhas());
    }

    //Lista Categorias da instituição de um polo
    function listarCategoriasJson($idpolo)
    {
        $this->sql = 'SELECT
                            c.idcategoria,
                            c.nome
                        FROM
                            categorias c
                            INNER JOIN categorias_subcategorias cs ON (cs.idcategoria = c.idcategoria AND cs.ativo = "S" AND cs.ativo_painel = "S")
                            INNER JOIN categorias_subcategorias_sindicatos csi ON (csi.idsubcategoria = cs.idsubcategoria AND csi.ativo = "S")
                            INNER JOIN escolas e ON (e.idsindicato = csi.idsindicato)
                        WHERE
                            e.idescola = "' . $idpolo . '" AND
                            c.ativo = "S" AND
                            c.ativo_painel = "S"
                        GROUP BY
                            c.idcategoria';

        $this->limite = -1;
        $this->ordem_campo = 'c.nome';
        $this->ordem = 'ASC';

        return json_encode($this->retornarLinhas());
    }

    //Lista Sub-categorias de uma categoria
    function listarSubCategoriasJson($idcategoria)
    {
        $this->sql = 'SELECT
                            cs.idsubcategoria,
                            cs.nome
                        FROM
                            categorias_subcategorias cs
                            INNER JOIN categorias c ON (c.idcategoria = cs.idcategoria AND c.ativo = "S" AND c.ativo_painel = "S")
                        WHERE
                            cs.idcategoria = "' . $idcategoria . '" AND
                            cs.ativo = "S" AND
                            cs.ativo_painel = "S"
                        GROUP BY
                            cs.idsubcategoria';

        $this->limite = -1;
        $this->ordem_campo = 'cs.nome';
        $this->ordem = 'ASC';

        return json_encode($this->retornarLinhas());
    }

    //Verifica a regra que a conta se enquadra e vincula ela com a mesma
    function classificarContaRegra($idconta)
    {
        $retorno = array();

        $this->executaSql('BEGIN');

        //Retorna o limite mensal do centro de custo
        $sql = 'SELECT
                    idescola,
                    idcentro_custo,
                    idcategoria,
                    idsubcategoria
                FROM
                    contas
                WHERE
                    idconta = "' . $idconta . '" AND
                    ativo = "S"';
        $conta = $this->retornarLinha($sql);

        if (!$conta['idcentro_custo']) {
            $sql = 'SELECT
                                idcentro_custo
                            FROM
                                contas_centros_custos
                            WHERE
                                idconta = "' . $idconta . '" AND
                                ativo = "S"
                            ORDER BY
                                porcentagem DESC
                            LIMIT 1';
            $contaCentroCustos = $this->retornarLinha($sql);
            $conta['idcentro_custo'] = $contaCentroCustos['idcentro_custo'];
        }

        //Busca a regra que a conta se enquadra
        $sql = 'SELECT
                    ocdr.idregra
                FROM
                    obz_classificacao_despesa_regras ocdr
                    INNER JOIN obz_classificacao_despesa ocd ON (ocd.idclassificacao = ocdr.idclassificacao AND ocd.ativo = "S" AND ocd.ativo_painel = "S")
                WHERE
                    ocdr.idescola = "' . $conta['idescola'] . '" AND
                    ocdr.idcentro_custo = "' . $conta['idcentro_custo'] . '" AND
                    ocdr.idcategoria = "' . $conta['idcategoria'] . '" AND
                    ocdr.idsubcategoria = "' . $conta['idsubcategoria'] . '" AND
                    ocdr.ativo = "S"';
        $regra = $this->retornarLinha($sql);

        //Se tiver regra que a conta se enquadra, irá classficiá-la nessa regra
        if ($regra['idregra']) {
            $idregra = '"' . $regra['idregra'] . '"';
        } else {
            $idregra = 'NULL';
        }

        //Atualiza a regra da conta
        $sql = 'UPDATE
                    contas
                SET
                    idregra = ' . $idregra . '
                WHERE
                    idconta = "' . $idconta . '"';
        $this->executaSql($sql);

        $this->executaSql('COMMIT');

        $retorno['idregra'] = $regra['idregra'];

        return $retorno;
    }
}
