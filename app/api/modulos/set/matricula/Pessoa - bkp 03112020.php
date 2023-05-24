<?php

class Pessoa
{
    private $acessoBanco;
    public  $config;
    public  $id;
    public $campos = '*';
    public  $idusuario;

    public function __construct(Core $acessoBanco)
    {
        $this->acessoBanco = $acessoBanco;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    public function cadastrar($dados)
    {
        if (! $dados['documentoaluno'] || ! $dados['nome'] || ! $dados['email'] || ! $dados['celular'] || ! $dados['rg'] || ! $dados['data_nasc'] || ! $dados['cnh'] || ! $dados['categoria'] || ! $dados['cep'] || ! $dados['endereco'] || ! $dados['numero'] || ! $dados['bairro'] || ! $dados['estado'] || ! $dados['cidade'] || ! $dados['pais']) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_dados_corrompidos';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        if (! validaCPF($dados['documentoaluno'])) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_documento_invalido';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $estado = $this->retornarIdEstado($dados['estado']);
        if (! $estado) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_estado_invalido';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $cidade = $this->retornarIdCidade($dados['cidade'], $estado['idestado']);
        if (! $cidade) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_cidade_invalida';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $pais = $this->retornarIdPais($dados['pais']);
        if (! $pais) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_pais_invalido';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $dados['renach'] = empty($dados['renach']) ? 'NULL' : "'" . $dados['renach'] . "'";

        $this->acessoBanco->config = $this->config;

        $senha = senhaSegura(str_replace(array(".", "-","/"),"", $dados['documentoaluno']), $this->config["chaveLogin"]);

        $this->acessoBanco->sql = "INSERT INTO
                          pessoas
                      SET 
                          data_cad = now(), 
                          nome = '" . $dados['nome'] . "', 
                          documento = '".$dados['documentoaluno']."', 
                          data_nasc = '" . formataData($dados['data_nasc'], 'en', 0) . "',
                          email = '" . $dados['email'] . "', 
                          celular = '" . $dados['celular'] . "', 
                          rg = '" . $dados['rg'] . "',
                          cnh = '" . $dados['cnh'] . "',
                          categoria = '" . $dados['categoria'] . "',
                          cep = '" . $dados['cep'] . "',
                          endereco = '" . $dados['endereco'] . "',
                          numero = '" . $dados['numero'] . "',
                          bairro = '" . $dados['bairro'] . "',
                          idestado = '" . $estado['idestado'] . "',
                          idcidade = '" . $cidade['idcidade'] . "',
                          idpais = '" . $pais['idpais'] . "',
                          senha = '" . $senha . "',
                          ato_punitivo = " . $dados['renach'];

        $salvar = $this->acessoBanco->executaSql($this->acessoBanco->sql);

        if($salvar) {
            $retorno['idpessoa'] =  mysql_insert_id();
        } else {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_inserir_banco';
            $retorno['erros']['codigo'] = '500';
        }
        return $retorno;
    }

    public function modificar($dados)
    {
        if (! $dados['documentoaluno'] || ! $dados['nome'] || ! $dados['email'] || ! $dados['celular'] || ! $dados['rg'] || ! $dados['data_nasc'] || ! $dados['cnh'] || ! $dados['categoria'] || ! $dados['cep'] || ! $dados['endereco'] || ! $dados['numero'] || ! $dados['bairro'] || ! $dados['estado'] || ! $dados['cidade'] || ! $dados['pais']) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_dados_corrompidos';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        if (! validaCPF($dados['documentoaluno'])) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_documento_invalido';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $estado = $this->retornarIdEstado($dados['estado']);
        if (! $estado) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_estado_invalido';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $cidade = $this->retornarIdCidade($dados['cidade'], $estado['idestado']);
        if (! $cidade) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_cidade_invalida';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $pais = $this->retornarIdPais($dados['pais']);
        if (! $pais) {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_pais_invalido';
            $retorno['erros']['codigo'] = '400';
            return $retorno;
        }

        $dados['renach'] = empty($dados['renach']) ? 'NULL' : "'" . $dados['renach'] . "'";

        $this->acessoBanco->config = $this->config;

        $this->acessoBanco->sql = "UPDATE
                          pessoas
                      SET 
                          nome = '" . $dados['nome'] . "', 
                          documento = '".$dados['documentoaluno']."', 
                          data_nasc = '" . formataData($dados['data_nasc'], 'en', 0) . "',
                          email = '" . $dados['email'] . "', 
                          celular = '" . $dados['celular'] . "', 
                          rg = '" . $dados['rg'] . "',
                          cnh = '" . $dados['cnh'] . "',
                          categoria = '" . $dados['categoria'] . "',
                          cep = '" . $dados['cep'] . "',
                          endereco = '" . $dados['endereco'] . "',
                          numero = '" . $dados['numero'] . "',
                          bairro = '" . $dados['bairro'] . "',
                          idestado = '" . $estado['idestado'] . "',
                          idcidade = '" . $cidade['idcidade'] . "',
                          idpais = '" . $pais['idpais'] . "',
                          ato_punitivo = " . $dados['renach'] . "
                      WHERE idpessoa = '".$dados['idpessoa']."'";

        $salvar = $this->acessoBanco->executaSql($this->acessoBanco->sql);

        if ($salvar) {
            $retorno['idpessoa'] =  $dados['idpessoa'];
        } else {
            $retorno['erro'] = true;
            $retorno['erros']['mensagem'] = 'erro_inserir_banco';
            $retorno['erros']['codigo'] = '500';
        }
        return $retorno;
    }

    public function retornarIdPorCPF($cpf)
    {
        $this->acessoBanco->sql = "SELECT
                    idpessoa
                  FROM
                    pessoas
                  WHERE
                    documento = '" . $cpf . "'
                    AND ativo = 'S'
                  ORDER BY idpessoa DESC
                  LIMIT 1";

        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }

    private function retornarIdEstado($estado)
    {
        $this->acessoBanco->sql = "SELECT
                    idestado
                  FROM
                    estados
                  WHERE
                    nome LIKE '" . $estado . "'
                  LIMIT 1";

        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }

    private function retornarIdCidade($cidade, $idestado)
    {
        $this->acessoBanco->sql = "SELECT
                    idcidade
                  FROM
                    cidades
                  WHERE
                    nome LIKE '" . $cidade . "'
                    AND idestado = {$idestado}
                  LIMIT 1";

        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }

    private function retornarIdPais($pais)
    {
        $this->acessoBanco->sql = "SELECT
                    idpais
                  FROM
                    paises
                  WHERE
                    nome LIKE '" . $pais . "'
                  LIMIT 1";

        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql);
    }
}