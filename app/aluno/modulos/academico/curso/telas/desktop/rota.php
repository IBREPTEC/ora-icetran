<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php incluirLib("head", $config, $usuario); ?>
</head>
<body>
	
<style>
    #alert-estudo{
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
        #alert-estudo {
            width: 87vw;
        }
    }



</style>
<div id="alert-estudo" class="alert alert-warning alert-dismissible hidden "  role="alert">
    <div id="alerta-estudo-completo" style="font-size: 10px"></div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<!-- Topo -->
<?php incluirLib("topo", $config, $informacoesTopoCurso); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content" style="position: relative;">
    <!-- Rota de aprendizagem -->
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->
        <div class="box-side box-bg">
            <!-- Rota Topo -->
            <div class="top-box box-white">
                <h1><?= $ava['disciplina']; ?></h1>
                <i class="icon-sort-by-attributes-alt"></i>
            </div>
            <h2 class="ball-icon">&bull;</h2>
            <!-- /Rota Topo -->
            <div class="clear"></div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="abox extra-align">
						<div hidden id="acessar_disciplina" class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Para acessar esta disciplina conclua as anteriores e suas avaliações.</div>
                        <?php if($_POST["msg"]) { ?>
                            <div class="alert alert-success fade in">
                                <a href="javascript:void(0);" class="close" data-dismiss="alert">×</a>
                                <strong style="font-style: normal;font-size: .8rem;"><?php echo $idioma[$_POST["msg"]]; ?></strong>
                            </div>
                        <?php } ?>
						<div id="alert-estudo" class="alert alert-info alert-dismissible hidden "  role="alert">
                            <div id="alerta-estudo-completo"></div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!-- Assunto -->
                        <?php
                        $contadorDeObjetoDivisor = 0;
                        $contadorReconhecimento = 0;
                        $pagina = 0;
                        $voceEstaAqui = false;
                        $ultimo = end($rotaDeAprendizagem);
                        foreach($rotaDeAprendizagem as $objetoRota) {
                        if ($objetoRota['tipo'] == 'reconhecimento') {
                            $contadorReconhecimento++;
                            continue;
                        }
                        if ($objetoRota['tipo'] == 'objeto_divisor') {
                        if ($contadorDeObjetoDivisor) {
                        ?>
                    </div></div></div>
            <?php
            }
            $contadorDeObjetoDivisor++;
            ?>
            <div class="" id="accordion2">
                <div>
                    <a class="" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?= $objetoRota['idobjeto']; ?>">
                        <div class="btn btn-medium btn-mob" style="background: #<?= $objetoRota['objeto']['cor_bg']; ?>;color: #<?= $objetoRota['objeto']['cor_letra']; ?>;"><?= $objetoRota['objeto']['nome']; ?></div>
                    </a>
                    <div id="collapse<?= $objetoRota['idobjeto']; ?>" class="list-timeline accordion-body collapse <?php if($contadorDeObjetoDivisor == 1) { ?>in<?php } ?>">
                        <?php
                        } elseif($contadorDeObjetoDivisor) {
                            $pagina++;
                            $contabilizado = $matriculaObj->verificaContabilizado($ava['idava'], 'objeto', $objetoRota['idobjeto']);

                            $onclickExercicio = null;
                            if ($objetoRota['tipo'] == 'exercicio') {
                                $onclickExercicio = 'onclick="alert(\'' . sprintf($idioma['realizar_exercicio'], $objetoRota['objeto']['nome']) . '\');"';
                            }

                            $bola = $contabilizado ? 'verde' : 'amarelo';

                            ?>
                            <div class="item-toggle">
                                <div><h2 class="color-ball-<?= $bola; ?>">•</h2></div>
                                <?php
                                //                                                if ($contabilizado || $ava['pre_requisito'] == 'N' || $pagina == 1 || $voceEstaAqui) {
                                ?>
                                <? if($ultimo['idobjeto'] == $objetoRota['idobjeto'] && (!$downloadsEbooksFeitos))
                                {?>
                                <a onclick="alert('<? echo $idioma['sem_download_ebooks'] ?>');" href="#" >
                                    <? } else { ?>
                                    <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $matricula['idmatricula']; ?>/<?= $url[4]; ?>/rota/<?= $pagina + $contadorReconhecimento; ?>#conteudo"
                                        <?= $onclickExercicio; ?>>
                                        <?php
                                        }
                                        ?>
                                        <h1><?= $objetoRota['objeto']['nome']; ?></h1>
                                    </a>
                                    <?php
                                    if ($voceEstaAqui) {
                                        ?>
                                        <span class="no-mobile">
                                            <h4><?= $idioma['voce_esta_aqui']; ?></h4>
                                        </span>
                                        <?php
                                    }
                                    ?>
                            </div>
                            <?php
                            if($objetoRota['idobjeto'] == $ultimo['idobjeto'])
                                $voceEstaAqui = true;
                            else
                                $voceEstaAqui = false;
                        }
                        } ?>
                    </div>
                </div>
            </div>
            <!-- Assunto -->
        </div>
    </div>
</div>
</div>
</div>
<!-- /Rota de aprendizagem -->
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario);?>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script type="text/javascript">
    inserirTexto();



function inserirTexto(){
   <?php
    $_GET['verificaDias'] = 2;
    if(array_key_exists('verificaDias', $_GET)){
        $dias = (int)$_GET['verificaDias'];
        $data = explode(' ', $matricula['data_primeiro_acesso'])[0];
        $dataMinima = date('Y/m/d', strtotime($data. " + {$_GET['verificaDias']} days"));
        $dataMinima = formataData(str_replace('/', '-', $dataMinima), 'br', 0);
   echo '<script>';
    document.getElementById('alert-estudo').classList.remove('hidden');
   echo '</script>';

   }
    ?>
}
	function disparaAlertaConcluirDisciplinas(){
        if($("#div_3").find("div_3#alerta-orientacao-reconhecimento").length==0){
            $("#div_3").append("<div id='alerta-orientacao-reconhecimento' class='alert alert-warning alert-dismissible '  role='alert'> <div id='alerta-orientacao-reconhecimento-texto' style='font-size: 10px'></div> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> </div>");


            var div = document.getElementById('alerta-orientacao-reconhecimento-texto').innerText = texto;
        }

    }
</script>

</body>
</html>
