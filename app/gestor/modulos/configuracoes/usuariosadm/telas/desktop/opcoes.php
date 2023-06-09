<section id="global">
    <div class="page-header">
        <h1><?php echo $idioma["opcoes"]; ?> &nbsp;
            <small><?php echo $idioma["opcoes_subtitulo"]; ?></small>
        </h1>
    </div>
    <ul class="breadcrumb">
        <li><?php echo $idioma["usuario_selecionado"]; ?></li>
        <li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
    </ul>
    <ul class="nav nav-tabs nav-stacked">
        <li>
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|2", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idusuario"]; ?>/editar"> <i
                        class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
            <? } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|6", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idusuario"]; ?>/sindicatos">
                    <i class="icon-th-list"></i> <? echo $idioma["sindicatos"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-th-list icon-white"></i> <? echo $idioma["sindicatos"]; ?></a>
            <?php } ?>
        </li>
		<li>
            <?php if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|10", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idusuario"]; ?>/cfcs">
                    <i class="icon-th-list"></i> <? echo $idioma["cfcs"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-th-list icon-white"></i> <? echo $idioma["cfcs"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|4", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idusuario"]; ?>/desativar_login">
                    <i class="icon-off"></i> <? echo $idioma["desativar_login"]; ?></a>
            <? } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-off icon-white"></i> <? echo $idioma["desativar_login"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|5", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idusuario"]; ?>/resetar_senha">
                    <i class="icon-ok-circle"></i> <? echo $idioma["resetar_senha"]; ?></a>
            <? } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-ok-circle icon-white"></i> <? echo $idioma["resetar_senha"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|9", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idusuario"]; ?>/emails"> <i
                        class="icon-off"></i> <? echo $idioma["emails"]; ?></a>
            <? } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-off icon-white"></i> <? echo $idioma["emails"]; ?></a>
            <?php } ?>
        </li>
        <li>
            <? if ($linhaObj->verificaPermissao($perfil["permissoes"], $url[2] . "|3", NULL)) { ?>
                <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idusuario"]; ?>/remover">
                    <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
            <?php } else { ?>
                <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"
                   data-placement="left" rel="tooltip" style="color:#999;"> <i
                        class="icon-remove icon-white"></i> <? echo $idioma["remover"]; ?></a>
            <?php } ?>
        </li>
    </ul>
</section>