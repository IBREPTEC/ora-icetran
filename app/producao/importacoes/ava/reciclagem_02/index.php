<?php
/* Informa o nível dos erros que serão exibidos */
error_reporting(E_ALL);

/* Habilita a exibição de erros */
ini_set("display_errors", 1);


header('Content-Type: text/html; charset=utf-8');
require '../../../../especifico/inc/config.banco.php';

$arquivo = "ava_reciclagem_2.csv";
$csv = file($arquivo);
unset($csv[0]);

$estrutura = array();

foreach ($csv as $linha => $valor) {

    $dados = explode(";", trim($valor));
    if (!$dados[3]) {
        $dados[3] = "DIVISOR";
        $dados[4] = $dados[2];
    }
    $estrutura[$dados[0]][$dados[8]][$dados[2]][$dados[3]][] = array("nome" => $dados[4], "arquivo" => $dados[5], "id" => $dados[7], "tipo" => $dados[6]);
}

$ordemTematica = 10;
$ordemConteudo = 0;

foreach ($estrutura as $idava => $ava) {
    foreach ($ava as $idrota => $rota) {

        //limpaRota($idrota);			
        foreach ($rota as $tematica => $tematicas) {
            foreach ($tematicas as $unidade => $conteudos) {
                foreach ($conteudos as $ind => $conteudo) {
                    if ($unidade == "DIVISOR") {
                        if ($conteudo["id"]) {
                            processaDivisor($idrota, $ordemTematica, $conteudo);
                            $ordemTematica = $ordemTematica + 10;
                            $ordemConteudo = 0;
                        }
                    } else {
                        //retornaConteudo($conteudo["pasta"], $conteudo["arquivo"]);
                        if ($conteudo["id"]) {
                            $ordemConteudo++;
                            processaConteudo($idrota, ($ordemTematica - 10) + $ordemConteudo, $conteudo);
                        }
                    }
                }
            }
        }
    }
}

function limpaRota($idrota) {
    $sql = "update avas_rotas_aprendizagem_objetos set ativo = 'N' where idrota_aprendizagem = '$idrota' ";
    //$executa = mysql_query($sql) or die(mysql_error());
    if ($executa) {
        echo "<br><br> -> Rota $idrota foi limpa -> objetos afetatos: " . mysql_affected_rows() . " -> $sql<br>";
    }
}

function processaDivisor($idrota, $ordem, $conteudo) {

    $sql = "update avas_objetos_divisores set
					ativo = 'S'
					where idobjeto_divisor = '" . $conteudo["id"] . "'";
    // , ordem = $ordem
    // nome = '".utf8_encode($conteudo["nome"])."',
    $executa = mysql_query($sql) or die(mysql_error());
    if ($executa) {
        echo "-------> Divisor modificado (" . $conteudo["id"] . ") -> $sql<br>";
    }

    $sql = "select * from avas_rotas_aprendizagem_objetos where idrota_aprendizagem = '$idrota' and tipo = 'objeto_divisor' and idobjeto_divisor = '" . $conteudo["id"] . "' ";
    $verifica = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($verifica) == 1) {

        $sql = "update avas_rotas_aprendizagem_objetos set
						  idrota_aprendizagem = $idrota, 
						  ativo = 'S',
						  tipo = 'objeto_divisor'
						  where idobjeto_divisor = '" . $conteudo["id"] . "'";
        // ordem = $ordem,
        $executa = mysql_query($sql) or die(mysql_error());
        if ($executa) {
            echo "----> Objeto divisor modificado (" . $conteudo["id"] . ") -> $sql<br>";
        }
    } elseif (mysql_num_rows($verifica) > 1) {
        echo "----> Objeto divisor: <strong style='color:red'>ERRO</strong><br>";
    } else {
        $sql = "insert into avas_rotas_aprendizagem_objetos set
						  idrota_aprendizagem = $idrota, 
						  data_cad = now(),
						  ativo = 'S',
						  tipo = 'objeto_divisor',
						  idobjeto_divisor = '" . $conteudo["id"] . "'";
        // ordem = $ordem,
        $executa = mysql_query($sql) or die(mysql_error());
        if ($executa) {
            echo "----> Objeto divisor adicionado (" . mysql_insert_id() . ") -> $sql<br>";
        }
    }
}

function processaConteudo($idrota, $ordem, $conteudo) {

    //retornaConteudo($pasta, $arquivo)
    $sql = "update avas_conteudos set
					
					ativo = 'S',
					conteudo = '" . mysql_real_escape_string(retornaConteudo(null, $conteudo["arquivo"])) . "'
					where idconteudo = '" . $conteudo["id"] . "'";
    // ordem = $ordem
    // nome = '" . utf8_encode($conteudo["nome"]) . "', 
    $executa = mysql_query($sql) or die(mysql_error());
    if ($executa) {
        echo "-------> Conteudo modificado (" . $conteudo["id"] . ") -> <br>";
    }


    $sql = "select * from avas_rotas_aprendizagem_objetos where idrota_aprendizagem = '$idrota' and tipo = 'conteudo' and idconteudo = '" . $conteudo["id"] . "' ";
    $verifica = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($verifica) == 1) {

        $sql = "update avas_rotas_aprendizagem_objetos set
						  idrota_aprendizagem = $idrota, 
						  ativo = 'S',
						  tipo = 'conteudo'
						  where idconteudo = '" . $conteudo["id"] . "'";
        // ordem = $ordem,
        $executa = mysql_query($sql) or die(mysql_error());
        if ($executa) {
            echo "----> Objeto conteudo modificado (" . $conteudo["id"] . ") -> $sql<br>";
        }
    } elseif (mysql_num_rows($verifica) > 1) {
        echo "----> Objeto conteudo: <strong style='color:red'>ERRO</strong><br>";
    } else {
        $sql = "insert into avas_rotas_aprendizagem_objetos set
						  idrota_aprendizagem = $idrota, 
						  data_cad = now(),
						  ativo = 'S',
						  tipo = 'conteudo',
						  idconteudo = '" . $conteudo["id"] . "'";
        // ordem = $ordem,
        $executa = mysql_query($sql) or die(mysql_error());
        if ($executa) {
            echo "----> Objeto conteudo adicionado (" . mysql_insert_id() . ") -> $sql<br>";
        }
    }
}

function retornaConteudo($pasta, $arquivo) {

    ///  producao/producao/importacoes/ava
    $caminho_arquivo = "../../../../discovirtual/avas/reciclagem_condutores_2/" . $arquivo;
    $conteudo = file_get_contents($caminho_arquivo);

    //[[aluno][primeiro_nome]]

    $retira = array(
        '<!DOCTYPE html>',
        '<html lang="pt-br">',
        '<head>',
        '<link rel="stylesheet" type="text/css" href="http://ibrep.alfamaoraculo.com.br/assets/min/aplicacao.aluno.min.css">',
        '<meta charset="UTF-8">',
        '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">',
        '</head>',
        '<body>',
        '<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>',
        '<script src="http://ibrep.alfamaoraculo.com.br/assets/aluno_novo/js/prefixfree.min.js"></script>',
        '<script src="http://ibrep.alfamaoraculo.com.br/assets/aluno_novo/js/respond.min.js"></script>',
        '<script src="http://ibrep.alfamaoraculo.com.br/assets/aluno_novo/bootstrap/js/bootstrap.min.js"></script>',
        '<script src="http://ibrep.alfamaoraculo.com.br/assets/aluno_novo/js/main.js"></script>',
        '<script src="http://ibrep.alfamaoraculo.com.br/assets/aluno_novo/js/svgcheckbx.js"></script>',
        '</body>',
        '</html>'
    );
    $conteudo = str_replace($retira, "", $conteudo);

    $modifica_de = array(
        'src="assets/',
        'href="assets/',
        "src='assets/",
        "href='assets/",
        '<div class="container">'
    );
    $modifica_por = array(
        'src="/discovirtual/avas/reciclagem_condutores_2/assets/',
        'href="/discovirtual/avas/reciclagem_condutores_2/assets/',
        "src='/discovirtual/avas/reciclagem_condutores_2/assets/",
        "href='/discovirtual/avas/reciclagem_condutores_2/assets/",
        '<div class="container-fluid">'
    );
    $conteudo = str_replace($modifica_de, $modifica_por, $conteudo);

    return $conteudo;
}

/*
	echo "<pre>";
		print_r($estrutura);
	echo "</pre>";
*/		