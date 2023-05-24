<?php

class Reconhecimento extends Core {

    const URL_DETECT = 'https://brazilsouth.api.cognitive.microsoft.com/face/v1.0/detect';
    private $probabDefault;
    private $headers = array('Content-Type' => 'application/json','Ocp-Apim-Subscription-Key' => '3c90bc90874140dc84700889a9e3c776');
    function __construct() {
        $this->probabDefault = $GLOBALS['config']['datavalid']['probabDefault'];
    }

    public function requestDetect(HTTP_Request2 $request, array $parameters, array $body)
    {
        $url = $request->getUrl();
        $request->setConfig(array(
            'ssl_verify_peer'   => false,
            'ssl_verify_host'   => false
        ));
        $request->setHeader($this->headers);
        $url->setQueryVariables($parameters);
        $request->setMethod(HTTP_Request2::METHOD_POST);
        //body
        $request->setBody(json_encode($body));

        try
        {
            $response = $request->send();
            return $response->getBody();
        }
        catch (HttpException $ex)
        {
            return $ex;
        }
    }

    public function retornaImagemPrincipal($idMatricula)
    {
        $this->sql = "SELECT * FROM matriculas_reconhecimentos
                    WHERE ativo = 'S' AND ativo_painel = 'S' AND idmatricula = " . $idMatricula;

        return $this->retornarLinha($this->sql);
    }

    public function retornaImagensPrincipaisDatavalid($idMatricula)
    {
        $this->sql = "SELECT
                    mr.idfoto,
                    mr.foto,
                    mr.json,
                    mr.ativo_painel,
                    mr.data_cad,
                    mr.probabilidade_datavalid,
                    mr.ip
                    FROM matriculas_reconhecimentos AS mr
                    WHERE mr.ativo = 'S'
                    AND mr.probabilidade_datavalid IS NOT NULL
                    AND mr.idmatricula = " . $idMatricula;
        $this->limite = -1;
        $this->ordem_campo = "mr.data_cad";
        $this->ordem = "DESC";
        $this->groupby = "mr.idfoto";
        return $this->retornarLinhas($this->sql);
    }

    public function retornarTodasComparacoes($idMatricula)
    {
        $this->sql = "SELECT
            mr.idfoto,
            mr.foto as principal,
            mr.data_cad as dt_principal,
            mcf.idfoto,
            mcf.foto as comparacao,
            mcf.data_cad as dt_comparacao,
            mcf.ip,
            rf.*
            FROM reconhecimento_fotos rf
            INNER JOIN matriculas_reconhecimentos mr ON
            (mr.idfoto = rf.idfoto_principal)
            INNER JOIN matriculas_comparacoes_fotos mcf ON
            (mcf.idfoto = rf.idfoto_comparacao)
            WHERE rf.ativo = 'S'
            AND rf.ativo_painel = 'S'
            AND rf.idmatricula = " . $idMatricula;

        $this->limite = -1;
        $this->groupby = "idreconhecimento";

        return $this->retornarLinhas($this->sql);
    }

    public function retornarTodasComparacoesDatavalid($idMatricula)
    {
        $this->sql = "SELECT
            mcf.foto,
            mcf.json,
            mcf.ativo_painel,
            mcf.data_cad,
            mcf.probabilidade_datavalid,
            mcf.ip
            FROM matriculas_comparacoes_fotos AS mcf
            WHERE mcf.ativo = 'S'
            AND mcf.probabilidade_datavalid IS NOT NULL
            AND mcf.idmatricula = " . $idMatricula;
        $this->limite = -1;
        $this->ordem_campo = "mcf.data_cad";
        $this->ordem = "DESC";
        $this->groupby = "mcf.idfoto";
        return $this->retornarLinhas($this->sql);
    }

    public function verificaSeReconheceu($idMatricula, $idRota)
    {
        $this->sql = "SELECT * ";
        $this->sql .= "FROM reconhecimento_fotos";
        $this->sql .= " WHERE idmatricula = " . $idMatricula;
        $this->sql .= " AND idobjetorota = " . $idRota;
        $this->sql .= " AND ativo = 'S'";
        $this->sql .= " AND ativo_painel = 'S'";
        $this->sql .= " AND resultado = 'S'";

        $this->groupby = "idreconhecimento";

        return $this->retornarLinhas($this->sql);
    }

    public function registrarImagemPrincipal()
    {

        $pessoasObj = new Pessoas();
        $matriculaObj = new Matriculas();

        $diretorio = $_SERVER['DOCUMENT_ROOT'] . '/storage/matriculas_reconhecimentos/';
        $nome_servidor = date("YmdHis") . "_" . uniqid() . '.png';

        $dadosMat = $matriculaObj->getMatricula($_POST['idmatricula']);
        if($dadosMat['limite_datavalid'] == 0){
            echo "limite_datavalid";
            return false;
        }

        $curso = $matriculaObj->RetornarCurso();
        $dadosSindicato = $matriculaObj->retornarSindicato();
        $pessoasObj->Set("campos","p.*");
        $pessoasObj->Set("id",$dadosMat['idpessoa']);
        $dadosPessoa = $pessoasObj->retornar();

        if($curso['usar_datavalid'] == 'S' && $dadosSindicato['usar_datavalid'] == 'S')
        {
            $datavalObj = new DataValid();
            $probab = $this->getSimilaridade($_POST['idmatricula'], $_FILES);
            $respDatavalid = $datavalObj->validaPFBiometriaFacial($dadosPessoa, $_FILES['file']['tmp_name']);
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $diretorio . $nome_servidor)) {
            $this->sql = "INSERT INTO matriculas_reconhecimentos";
            $this->sql .= " SET";
            $this->sql .= " data_cad = NOW()";
            $this->sql .= ", foto = '" . $nome_servidor . "'";
            $this->sql .= ", tamanho = '" . $_FILES['file']['size'] . "'";
            $this->sql .= ", extensao = '" . $_FILES['file']['type'] . "'";
            $this->sql .= ", ip = '" . $_SERVER['REMOTE_ADDR'] . "'";
            $this->sql .= ", probabilidade_datavalid = '" . floatval($probab) . "'";
            $this->sql .= ", ativo = 'S'";
            $this->sql .= ", ativo_painel = 'S'";
            $this->sql .= ", idmatricula = " . $_POST['idmatricula'];
            $this->sql .= ", json = '" . $respDatavalid ."'";
            $this->executaSql($this->sql);
            $_POST['idimagem'] = mysql_insert_id();
            if($curso['usar_datavalid'] == 'S' && $dadosSindicato['usar_datavalid'] == 'S'){
                if ($probab >= $this->probabDefault) {
                    echo "true";
                    return true;
                } else if (json_decode($respDatavalid)->biometria->disponivel == false) {
                    echo "biometria falsa";
                    $this->reduzir_tentativas($dadosMat);
                    return false;
                }
                else {
                    $this->removerImagemPrincipal();
                    $this->reduzir_tentativas($dadosMat);
                    return false;
                }
            }else{
                echo "true";
                return true;
            }

        }

    }

    public function removerImagemPrincipal()
    {
        $this->sql = "UPDATE matriculas_reconhecimentos";
        $this->sql .= " SET ativo_painel = 'N'";
        $this->sql .= " WHERE idfoto = " . $_POST['idimagem'];

        if($this->executaSql($this->sql)){
            $retorno['sucesso'] = true;
            return $retorno;
        }
    }

    public function getSimilaridade($idMatricula,$files){
        $datavalObj = new DataValid();
        $pessoasObj = new Pessoas();
        $matriculaObj = new Matriculas();
        $dadosMat = $matriculaObj->getMatricula($idMatricula);
        $pessoasObj->Set("campos","p.*");
        $pessoasObj->Set("id",$dadosMat['idpessoa']);
        $dadosPessoa = $pessoasObj->retornar();
        $respDatavalid = json_decode($datavalObj->validaPFBiometriaFacial($dadosPessoa,$files['file']['tmp_name']));
        return $respDatavalid->biometria_face->similaridade;
    }

    public function compararFotos($useDV = false)
    {
        $diretorio = $_SERVER['DOCUMENT_ROOT'] . '/storage/matriculas_comparacoes_fotos/';
        $nome_servidor = date("YmdHis") . "_" . uniqid() . '.png';

        $pessoasObj = new Pessoas();
        $matriculaObj = new Matriculas();
        $dadosMat = $matriculaObj->getMatricula($_POST['idmatricula']);
        if($dadosMat['limite_datavalid'] == 0){
            echo "limite_datavalid";
            return false;
        }

        $dadosSindicato = $matriculaObj->retornarSindicato();
        $curso = $matriculaObj->RetornarCurso();
        $pessoasObj->Set("campos","p.*");
        $pessoasObj->Set("id",$dadosMat['idpessoa']);
        $dadosPessoa = $pessoasObj->retornar();

        if($curso['usar_datavalid'] == 'S' && $dadosSindicato['usar_datavalid'] == 'S')
        {
            // Coletando o JSON do DataValid
            $datavalObj = new DataValid();
            $respDatavalid = $datavalObj->validaPFBiometriaFacial($dadosPessoa, $_FILES['file']['tmp_name']);
            #Se página requer validação por datavalid checa a similaridade da foto
            if ($useDV == true) {
                $probab = $this->getSimilaridade($_POST['idmatricula'], $_FILES);
            }
        }

        //ARMAZENA IMAGEM NO BANCO
        if (move_uploaded_file($_FILES['file']['tmp_name'], $diretorio . $nome_servidor)) {
            $this->sql = "INSERT INTO matriculas_comparacoes_fotos";
            $this->sql .= " SET";
            $this->sql .= " data_cad = NOW()";
            $this->sql .= ", foto = '" . $nome_servidor . "'";
            $this->sql .= ", tamanho = '" . $_FILES['file']['size'] . "'";
            $this->sql .= ", extensao = '" . $_FILES['file']['type'] . "'";
            $this->sql .= ", ip = '" . $_SERVER['REMOTE_ADDR'] . "'";
            $this->sql .= ", ativo = 'S'";
            $this->sql .= ", json = '" . $respDatavalid . "'";
            if ($useDV == true && $curso['usar_datavalid'] == 'S' && $dadosSindicato['usar_datavalid'] == 'S') {
                $this->sql .= ", probabilidade_datavalid = '" . $probab . "'";

                if ($probab >= $this->probabDefault) {
                    $this->sql .= ", ativo_painel = 'S'";
                } else {
                    $this->sql .= ", ativo_painel = 'N'";
                }
            } else {
                $this->sql .= ", ativo_painel = 'S'";
            }
            $this->sql .= ", idmatricula = " . $_POST['idmatricula'];
            $this->executaSql($this->sql);
            $idFotoComparacao = mysql_insert_id();
        } else {
            $this->reduzir_tentativas($dadosMat);
            return false;
        }
        if ($useDV == true && $curso['usar_datavalid'] == 'S' && $dadosSindicato['usar_datavalid'] == 'S') {
            if ($probab < $this->probabDefault) {
                $this->reduzir_tentativas($dadosMat);
                return false;
            }
            echo 'S';
            return true;
        }
        $imagemMatricula = $this->retornaImagemPrincipal($_POST['idmatricula']);
        $urlImagemMatricula = $GLOBALS['config']['urlSistema'] . '/api/get/imagens/matriculas_reconhecimentos/480/480/' . $imagemMatricula['foto'] . '?reconhecimento=1';
        $urlImagemComparacao = $GLOBALS['config']['urlSistema'] . '/api/get/imagens/matriculas_comparacoes_fotos/480/480/' . $nome_servidor . '?reconhecimento=1';

        //REQUISITA O AZURE PARA NO METODO FACE-DETECT PARA RETORNAR O FACEID E ATRIBUTOS.
        $resultado = $this->detect($urlImagemMatricula);

        //REQUISITA O AZURE PARA NO METODO FACE-DETECT PARA RETORNAR O FACEID E ATRIBUTOS.
        $resultado2 = $this->detect($urlImagemComparacao);


        $retorno['sucesso'] = false;
        $sucesso = 'N';
        $resultadoComparacao = 'N';
        $matriculaLiberada = in_array(
            $_POST['idmatricula'],
            $GLOBALS['config']['reconhecimento']['matriculas_liberadas']
        );
        if ($resultado[0]['faceId'] && $resultado2[0]['faceId']) {
            $parameters = array();
            $request = new Http_Request2('https://brazilsouth.api.cognitive.microsoft.com/face/v1.0/verify');
            $body = array("faceId1" => $resultado[0]['faceId'], "faceId2" => $resultado2[0]['faceId']);
            $result_request = $this->requestDetect($request, $parameters, $body);
            $resultado4 = $result_request;
            $resultado3 = json_decode($resultado4, true);

            $retorno['sucesso'] = true;
            $retorno['identical'] = $resultado3['isIdentical'];
            $retorno['confidence'] = $resultado3['confidence'];
            $sucesso = 'S';
            if ($matriculaLiberada || $resultado3['confidence'] >= $GLOBALS['config']['reconhecimento']['range_minimo'])
                $resultadoComparacao = 'S';
        } elseif ($matriculaLiberada) {
            $retorno['sucesso'] = true;
            $sucesso = 'S';
            $resultadoComparacao = 'S';
        }

        //ARMAZENA RESPOSTA NO BANCO
        try {
            $this->sql = "INSERT INTO reconhecimento_fotos";
            $this->sql .= " SET";
            $this->sql .= " data_cad = NOW()";
            $this->sql .= ", face_id = '" . $resultado2[0]['faceId'] . "'";
            $this->sql .= ", face_att_age = " . intval($resultado2[0]['faceAttributes']['age']);
            $this->sql .= ", face_att_gender = '" . $resultado2[0]['faceAttributes']['gender'] . "'";
            $this->sql .= ", idmatricula = " . $_POST['idmatricula'];
            if (!empty($_POST['idobjetorota'])) {
                $this->sql .= ", idobjetorota = " . $_POST['idobjetorota'];
            }
            $this->sql .= ", ativo = 'S'";
            $this->sql .= ", ativo_painel = 'S'";
            $this->sql .= ", idfoto_principal = " . $imagemMatricula['idfoto'];
            $this->sql .= ", idfoto_comparacao = " . $idFotoComparacao;
            $this->sql .= ", sucesso = '" . $sucesso . "'";
            $this->sql .= ", resultado = '" . $resultadoComparacao . "'";
            $this->sql .= ", confidence = " . floatval($resultado3['confidence']);
            $this->sql .= ", isIdentical = '" . $resultado3['isIdentical'] . "'";
            $this->sql .= ", json = '" . $resultado4 . "'";
            $this->executaSql($this->sql);
        } catch (PDOException $e) {
            echo $this->sql . "<br>" . $e->getMessage();
        }

        echo $resultadoComparacao;
        if($resultadoComparacao == "N"){
            $this->reduzir_tentativas($dadosMat);
            return false;
        }
        return true;
    }

    private function detect($url)
    {
        $parameters = [
            'returnFaceId' => 'true',
            'returnFaceLandmarks' => 'false',
            'returnFaceAttributes' => 'age,gender'
        ];
        $request = new Http_Request2(self::URL_DETECT);
        $body = [
            'url' => $url
        ];
        return json_decode($this->requestDetect($request, $parameters, $body), true);
    }

    private function reduzir_tentativas($dadosMat)
    {
        $this->sql = "UPDATE matriculas SET limite_datavalid = (limite_datavalid - 1) where idmatricula = ".
            $dadosMat['idmatricula'];
        $this->executaSql($this->sql);
    }

}

?>
