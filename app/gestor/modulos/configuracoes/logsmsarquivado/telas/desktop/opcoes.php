  <section id="global">
	<div class="page-header">
    	<h1><?php echo $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["pagina_subtitulo"]; ?></small></h1>
  	</div>
  	<ul class="breadcrumb">
    	<li><? echo $idioma["usuario_selecionado"]; ?></li>
    	<li class="active"><strong><?php echo $linha["nome"]; ?></strong></li>
  	</ul>

      <ul class="nav nav-tabs nav-stacked">
		
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|2", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/editar"> <i class="icon-edit"></i> <? echo $idioma["editar"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["editar"]; ?></a>
              <?php } ?>        
          </li>
		  
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|4", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/email_cursos"> <i class="icon-edit"></i> <? echo $idioma["email_cursos"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["email_cursos"]; ?></a>
              <?php } ?>        
          </li>
		  
		  <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|7", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/email_ofertas"> <i class="icon-edit"></i> <? echo $idioma["email_ofertas"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-edit icon-white"></i> <? echo $idioma["email_ofertas"]; ?></a>
              <?php } ?>        
          </li>
        
          <li>
              <? if($linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|3", NULL)){ ?>
                  <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?php echo $linha["idemail"]; ?>/remover"> <i class="icon-remove"></i> <? echo $idioma["remover"]; ?></a>
              <? } else { ?>
                  <a href="javascript:void(0)" data-original-title="<?= $idioma["opcao_permissao"] ?>"  data-placement="left" rel="tooltip" style="color:#999;"> <i class="icon-remove icon-white"></i> <? echo $idioma["remover"]; ?></a>
              <?php } ?>      
          </li>
        
      </ul>    
  </section>