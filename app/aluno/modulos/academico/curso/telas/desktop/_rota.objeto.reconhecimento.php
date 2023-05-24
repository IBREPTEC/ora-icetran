<?php include '/../../idiomas/pt_br/rota.objeto.reconhecimento.php'; ?>
<div class="row-fluid">
<h1>Reconhecimento</h1>
<hr>
    <div class="span12 m-box-table">
        <input type="hidden" name="act" id="act" value="cadastrar">
        <input type="hidden" name="acao" id="acao" value="compararFoto">
        <input type="hidden" name="funcao" id="funcao" value="reconhecimento">
        <br>
        <br>
        <center>
            <div class="" style="width: 480px; height:360px; margin-bottom: 50px;">
                <h3><?= $idioma['titulo_foto']; ?></h3>
                <br>
                <video autoplay  width="480" height="360"></video>
                <canvas width="480" height="360" style="display: none"></canvas>
                
                <br>
                    
                <button class="btn btn-azul" id="limpar" style="float: left;" onclick="limparSnapshot()">Tirar nova foto</button>
                <button class="btn btn-azul" id="snapshot" style="float: left;" onclick="snapshot()">Tirar foto</button>
                <button class="btn btn-azul" id="enviar" style="float: right;" onclick="enviar()">Enviar</button>
            </div>
        </center>
    </div>
</div>


<!-- /Conteudo -->
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
    canvas.width = 480;
    canvas.height = 360;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    $(video).hide();
    $(canvas).show();
    $('#enviar').show();
    $('#limpar').show();
    $('#snapshot').hide();
}

function limparSnapshot()
{
    $(video).show();
    $(canvas).hide();
    $('#limpar').hide();
    $('#enviar').hide();
    $('#snapshot').show();
}

function enviar() {
    var idmatricula = <?= $idmatricula; ?>;
    var idobjetorota = <?= $objeto['idobjeto']; ?>;
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
    data.append('idobjetorota', idobjetorota);
    data.append('act', act);
    data.append('acao', acao);
    data.append('funcao', funcao);

    if(act == 'comparar'){    
    var id = document.getElementById('id').value;
    data.append('id', id);
    var local = "index.php";
    }else
    {
    var local = "";
    }

    $.ajax({
    url :  "index.reconhecimento.php",
    type: 'POST',
    data: data,
    contentType: false,
    processData: false,
    }).done(function(respond){
        if(respond == 'S'){
            alert('Parabéns, o reconhecimento foi realizado com sucesso, você será redirecionado para sua aula.');
            <?php
            if ($objeto['objeto_proximo']['idobjeto']) { ?>
                window.location.href = "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $ava['idava'] . '/rota/' . ($url[6] + 1) . '#conteudo'; ?>";
            <?php } else { ?>
                window.location.href = "<?= $config["urlSistema"] . '/' . $url[0] . '/academico/curso/' . $matricula['idmatricula'] . '/' . $proximo . '/rota/1#conteudo'; ?>";
            <?php } ?>
        } else {
            alert('Por favor, tire outra foto, não foi possivel realizar o reconhecimento.');
            limparSnapshot();
            limparSnapshot();
        }
    });
};

var constraints = {
  audio: false,
  video: true
};

function handleSuccess(stream) {
  window.stream = stream; // make stream available to browser console
  video.srcObject = stream;
}

function handleError(error) {
  console.log('navigator.getUserMedia error: ', error);
}

navigator.mediaDevices.getUserMedia(constraints).
    then(handleSuccess).catch(handleError);

</script>

</body>
</html>