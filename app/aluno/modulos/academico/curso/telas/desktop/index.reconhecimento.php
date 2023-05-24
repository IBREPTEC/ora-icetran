<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php incluirLib("head", $config, $usuario); ?>
</head>
<body>
	
<style>
    #alerta-orientacao-reconhecimento{
        z-index: 9999;
        width: 100vw;
        top: 50%;
        position:fixed;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }

    @media  (max-width: 900px) {
        #alerta-orientacao-reconhecimento {
            width: 87vw;
        }


</style>

<div id="div_3"></div>

<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->

<div class="content">
    <div class="row-fluid">
        <!-- Disciplinas -->
        <div class="span12">
            <div class="row-fluid show-grid box-bg">
                <div class="top-box box-azul">
                    <h1><?= mb_strtoupper($idioma['titulo']); ?></h1>
                    <i class="icon-book"></i>
                </div>
                <h2 class="ball-icon">&bull;</h2>
                <div class="clear"></div>
                <div class="row-fluid">
                    <div class="span12 m-box-table" >
                        <p><?= str_replace('[[ALUNO]]', $_SESSION['cliente_nome'], $idioma['ola']); ?></p>
                        <p><?= $idioma['descricao']; ?></p>
                        <p><?= $idioma['aviso_celular']; ?></p>
                        <p><?= $idioma['aviso_computador']; ?></p>

                        <h3>RECONHECIMENTO FACIAL EM 5 PASSOS</h3>
                        <ol>
                            <li>O formato do rosto, olhos e bocas devem estar visíveis durante o registro, por isso certifique-se de estar em um<br> ambiente bem iluminado sem
                                objetos grandes ou outras pessoas aparecendo na tela de captura.</li>
                            <li>Centralize o seu rosto na janela de captura, estando ereto ou sentado, e mantenha os olhos bem abertos.</li>
                            <li>Não fique muito longe da câmera ou em  posição inclinada.</li>
                            <li>Siga o exemplo correto e clique em 'tirar foto'.</li>
                            <li>Após registrar. Clique em <strong>enviar foto</strong> no botão à esquerda para salvá-la.</li>

                        </ol>
                        <!--                        <b style="color: red">Atenção:</b>-->
                        <!--                        <p>Caso não tenha sucesso, tente novamente, corrigindo a posição do rosto se necessário.<br>-->
                        <!--                        A primeira foto registrada se tornará a sua foto padrão dentro do sistema e, nas capturas sequentes,<br> um novo envio será comparado sempre ao primeiro registro,-->
                        <!--                        por isso é importante manter o mesmo <br> posicionamento de rosto e adereços (Ex: óculos).</p>-->
                        <img src="/assets/img/Biometria_ICETRAN.jpg" style="width: 40%" >

                            <div class="div-fotos" style="width: 40%;float:right;" >

                                <input type="hidden" name="act" id="act" value="cadastrar">
                                <input type="hidden" name="acao" id="acao" value="cadastrarFoto">
                                <input type="hidden" name="funcao" id="funcao" value="reconhecimento">
                                <center>
									<div class="" style="max-width: 480px; width:100%; height:480px;">
                                        <p  class="no-mobile" id="titulo_foto"><?= $idioma['titulo_foto']; ?></p>

                                        <h3 hidden id="txt_camera_desabilitada">A câmera esta desabilitada no seu navegador, favor habilitar, para prosseguir.</h3>

                                        <br>

                                        <video id="video" class="video" height="400" width="480" playsinline autoplay muted loop style="max-width: 480px; width:100%; transform: scaleX(-1);"></video>
                                        <canvas width="640" height="600" style="display: none; max-width: 480px; width:100%; transform: scaleX(-1);">
                                </canvas>
                                        <button  class="btn btn-azul" id="snapshot" style="float: left;" onclick="snapshot(event)"><?= $idioma['botao_snapshot']; ?></button>

                                        <button class="btn btn-azul" id="limpar" style="float: left;" onclick="limparSnapshot()"><?= $idioma['botao_limpar']; ?></button>
                                        <button class="btn btn-azul" id="enviar" style="float: right;" onclick="enviar(this)"><?= $idioma['botao_enviar']; ?></button>
                                    </div>



                                </center>
                            </div>


                    </div>




            </div>
        </div>
        <!-- /Disciplinas -->
    </div>
</div>

<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
<script>
    disparaAlertaOrientacaoReconhecimento("Olá! A seguir temos algumas orientações para a foto de reconhecimento facial, é importante que as siga com bastante atenção pois esta foto será utilizada como base para os reconhecimentos futuros aqui na plataforma.\nCaso ainda possua alguma dúvida ou dificuldade para tirar a foto, favor entrar em contato conosco através do telefone: "+ "<?php echo $config['telefone']?>");

    function disparaAlertaOrientacaoReconhecimento(texto){
        if($("#div_3").find("div_3#alerta-orientacao-reconhecimento").length==0){
            $("#div_3").append("<div id='alerta-orientacao-reconhecimento' class='alert alert-warning alert-dismissible '  role='alert'> <div id='alerta-orientacao-reconhecimento-texto' style='font-size: 10px'></div> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> </div>");


            var div = document.getElementById('alerta-orientacao-reconhecimento-texto').innerText = texto;
        }

    }
</script>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/js/validation.js"></script>
<link rel="stylesheet" href="/assets/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/assets/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<link rel="stylesheet" href="/assets/plugins/facebox/src/facebox.css" type="text/css" media="screen" />
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>



<script>


    $('#limpar').hide();
    $('#enviar').hide();

    var video = document.querySelector('video');
    var canvas = window.canvas = document.querySelector('canvas');
    var button = document.querySelector('button');

    function snapshot()
    {

        canvas.getContext('2d').drawImage(video, 0, 0);

        $(video).hide();
            $(canvas).show();
            $('#enviar').show();
            $('#limpar').show();

    }


    function limparSnapshot()
    {
        $(video).show();
        $(canvas).hide();
        $('#limpar').hide();
        $('#enviar').hide();
        $('#snapshot').show();
    }

    function enviar(el) {
        $(el).attr('disabled','disabled');
        // canvas.width = video.videoWidth;
        // canvas.height = video.videoHeight;

        var idmatricula = <?= $idmatricula; ?>;
        var dataURL = canvas.toDataURL();
        var act = document.getElementById('act').value;
        var acao = document.getElementById('acao').value;
        var funcao = document.getElementById('funcao').value;
        var blobBin = atob(dataURL.split(',')[1]);
        var array = [];
        for(var i = 0; i < blobBin.length; i++) {
            array.push(blobBin.charCodeAt(i));
        }
        var file = new Blob([new Uint8Array(array)], {type: 'image/png'});
        var url = (window.URL || window.webkitURL).createObjectURL(file);
        var data = new FormData();
        data.append('file', file);
        data.append('idmatricula', idmatricula);
        data.append('act', act);
        data.append('acao', acao);
        data.append('funcao', funcao);
        <?php
            if($_GET['opLogin'] == 'sair'){
        ?>
                data.append('saindoSistema', 'S');
        <?php
            }
        ?>

        if(act == 'comparar'){
            var id = document.getElementById('id').value;
            data.append('id', id);
            var local = "index.php";
        }else
        {
            var local = "";
        }

        $.ajax({
            url :  "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/index.reconhecimento.php'?>",
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
        }).done(function(respond){
            <?php
                if ($_GET['opLogin'] == "sair") {
            ?>
                    alert('<?= $idioma['biometria_logout']; ?>')
                    window.location.href = "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '?opLogin=inatividade'?>";
            <?php
                } else {
            ?>
                    if( respond === "true" ){
                        alert('<?= $idioma['alert_sucesso']; ?>');
                        window.location.href = local;
                    } else if( respond === "false" ){
                        alert('<?= $idioma['alert_warning']; ?>');
                        window.location.href = local;
                    } else if( respond === "limite_datavalid" ){
                        alert('<?= $idioma['alert_limite_datavalid']; ?>');
                        window.location.href = local;
                    }else if(respond == 'liberacao_temporaria_datavalid'){
                        alert('<?= sprintf($idioma['alert_liberacao_temporaria_datavalid'], ucwords(strtolower(explode(' ', $usuario['nome'])[0]))); ?>');
                        window.location.href = local;
                    } else if (respond === "biometria falsa") {
                        alert('<?= $idioma['alert_warning']; ?>');
                        window.location.href = local;
                    }
            <?php
                }
            ?>

        });

    };

    var constraints = {
        audio: false,
        video: {
            width:
            480,
            height:
            480,
            facingMode: "user"
        },
    };
    liberarFoto()


    function handleSuccess(stream) {
        $('.circular_image').removeAttr('hidden')
        window.stream = stream; // make stream available to browser console
        video.srcObject = stream;
    }

    function handleError(error) {
        alert('Câmera está desabilitada, para continuar ligue a câmera.')
        $('#snapshot').hide()
        $('#titulo_foto').hide()
        $('#txt_camera_desabilitada').show()

    }
    function ligarCamera() {
            navigator.mediaDevices.getUserMedia(constraints).
            then(handleSuccess).catch(handleError);
    }


    function liberarFoto() {
         ligarCamera()

    }

</script>

</body>
</html>