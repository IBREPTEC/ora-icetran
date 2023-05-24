<!-- Topo curso -->
<div class="content">
    <div class="row-fluid">
        <div class="span6 tile-align">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $informacoes['link_pagina_anterior']; ?>">
                        <?php echo $idioma[$informacoes['pagina_anterior']]; ?>
                    </a> <span class="divider">/</span>
                </li>
                <li class="active">
                    <?php if($informacoes['curso_nome']){ 
                         echo $informacoes['curso_nome']; 
                     }else{ 
                         echo $idioma[$informacoes['pagina']]; 
                     } ?>
                </li>
            </ul>
        </div>
        <div class="span6 extra-progress tile-align out-bar">
            <p id="porcentagem" class="p-bar"><?php echo sprintf($idioma['andamento_curso'], number_format($informacoes['porcentagem'],2,',','.')); ?></p>
            <div class="progress progress-striped active">
                <?php 
                $porcentagem_ava = 100;
                if($informacoes['porcentagem_ava'])
                    $porcentagem_ava = $informacoes['porcentagem_ava'];
                ?>
                <div id="barra_porcentagem" class="bar" style="width:<?php echo $informacoes['porcentagem']; ?>%;<?php if($informacoes['porcentagem'] >= $porcentagem_ava) { ?>background-color:#228B22;<?php } else { ?>background-color:#ff8f5d;<?php } ?>"></div>
            </div>          
        </div>
    </div>
</div>
<!-- /Topo curso -->
<?php echo getModalOraculo('avaModal',"Aviso",'');?>    
<?php if(isset($GLOBALS['url'][4])) { ?>
<script>
    const createClock = setInterval(contabilizaAcesso, 60000); // 60 segundos
    function contabilizaAcesso() {
        // -> /aluno/academico/curso/39/8
        var avaModal = $('#avaModal');
        var acessoBloqueado = $('#acessoBloqueado');
        if (!avaModal.hasClass('in') && acessoBloqueado.length == 0){
        console.log('Contabilizando o acesso...');
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var retorno = this.response;
            if (retorno.erro){
                avaModal.find('.modal-body').html(" <div class=\"content\"> <div class=\"box-bg\"> <div style=\"text-align:center;padding: 1em;\"> <img  src=\"/assets/img/"+retorno.erro[0]+"\" alt=\"Acesso bloqueado\"/><div class=\"description-item\"> <h1>"+retorno.erro[1]+"</h1> </div> </div> </div> </div>");
                avaModal.modal('show');
                avaModal.on('hidden.bs.modal', function (e) {
                    document.location.reload(true);
                })
            }               
          }
        };
        xhttp.responseType = 'json';
        xhttp.open("GET", "/aluno/academico/curso/<?= $GLOBALS['url'][3]; ?>/<?= $GLOBALS['url'][4]; ?>?contabiliza=acesso", true);
        xhttp.send();
        }
    }
</script>
<?php } ?>