<?php
class Sindicatos_OBZ extends Core
{
    function ListarTodas()
    {
        $this->sql = "SELECT
                            " . $this->campos . "
                        FROM 
                            sindicatos i
                            INNER JOIN mantenedoras m ON (i.idmantenedora = m.idmantenedora) 
                        WHERE 
                            i.ativo = 'S'";

        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === '0') and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= " AND " . $campo[1] . " = '" . $valor . "' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " AND " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " AND DATE_FORMAT(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

//        if($_SESSION["adm_gestor_instituicao"] <> "S") {
//            $this->sql .= " AND i.idsindicato IN (".$_SESSION["adm_instituicoes"].") ";
//        }

        $this->groupby = "i.idsindicato";
        return $this->retornarLinhas();
    }

    function Retornar()
    {
        $this->sql = "SELECT
                            " . $this->campos . "
                        FROM 
                            sindicatos i
                            INNER JOIN mantenedoras m ON (i.idmantenedora = m.idmantenedora)
                            LEFT OUTER JOIN obz_sindicatos oi ON (oi.idsindicato = i.idsindicato)
                        WHERE 
                            i.ativo = 'S' AND
                            i.idsindicato = '" . $this->id . "'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        $this->monitora_qual = $this->post['idsindicato'];
        return $this->SalvarDados();
    }

    function Modificar()
    {
        return $this->SalvarDados();
    }

    public function verificaExistencia($idsindicato)
    {
        $sql = 'SELECT idsindicato FROM obz_sindicatos WHERE idsindicato = "' . $idsindicato . '"';
        $dado = $this->retornarLinha($sql);

        if ($dado["idsindicato"]){
            return true;
        }

        return false;
    }
}