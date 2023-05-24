<div class="page-header"><h1><?php if(!$dados_imagem){?>Cadastrar<?php } else{ ?>Editar<?php }?></h1></div>
<br/>
<form method="post" enctype="multipart/form-data" class="form-horizontal">
    <input name="acao" type="hidden" value="<?php if(!$dados_imagem){ echo'salvar_imagem';}else{echo 'editar_imagem';}?>" />
    <input name="idava" type="hidden" value="<?=$url['3']?>" />
    <input name="idconteudo" type="hidden" value="<?=$url['5']?>" />
    <input name="idimagem" type="hidden" value="<?=$dados_imagem['idimagem']?>">


    <div class="control-group">

        <label class="control-label"><strong>Título</strong></label>
        <div class="controls">
            <input type="text" maxlength="100" value="<?=$dados_imagem['titulo']?>" name="titulo" id="titulo" class="span4">

        </div>
    </div>
    <div class="control-group">

        <label class="control-label"><strong>Url</strong></label>
        <div class="controls">
            <input type="text" maxlength="100" value="<?=$dados_imagem['link']?>" name="url" id="url" class="span4">

        </div>
    </div>
    <div class="control-group">

        <label class="control-label"><strong>Imagem</strong></label>
        <div class="controls">
            <?php if($dados_imagem){?>
                <input  type="file" name="arquivo" id="arquivo" value="<?php echo '/storage/avas_conteudos_imagem_exibicao/'.$dados_imagem['imagem_exibicao_servidor']; ?>"/>

            <?php  }else{?>
                <input required type="file" name="arquivo" id="arquivo" value="<?php echo '/storage/avas_conteudos_imagem_exibicao/'.$dados_imagem['imagem_exibicao_servidor']; ?>"/>
            <?php }?>
            <br/>
            <a  target="_blank" href="<?php echo '/storage/avas_conteudos_imagem_exibicao/'.$dados_imagem['imagem_exibicao_servidor']; ?>"><?=$dados_imagem['imagem_exibicao_nome']?></a>
        </div>
    </div>
    <div class="control-group">

        <label class="control-label"><strong>Ordem</strong></label>
        <div class="controls">
            <input type="number" name="ordem" id="ordem" value="<?=$dados_imagem['ordem']?>"/>

        </div>
    </div>

    <div class="form-actions">
        <input type="submit" class="btn btn-primary" value="<?= $idioma["btn_salvar"]; ?>">&nbsp;
    </div>
</form>