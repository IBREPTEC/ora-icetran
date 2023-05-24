<section id="global">
    <div class="page-header">
        <h1 style="font-size: 18px;"><?php echo $idioma["titulo"]; ?> &nbsp;</h1>
    </div>
    <p><?php echo $linha["nome"] . " - " . $instituicao["nome"]; ?></p>
    <form method="post">
        <input type="hidden" name="acao" value="associar_usuario">
        <input type="hidden" name="idsindicato" value="<?php echo $url[5]?>">
        <div class="control-group">
            <label class="control-label" for="responsavel">
                <?php echo $idioma["responsavel"]; ?>
            </label>
            <div class="controls">
                <select name="responsavel" id="responsavel" class="span5">
                    <option value=""></option>
                    <?php foreach ($usuarios_instituicao as $usuario) { ?>
                            <option <?php if($usuario["idusuario"] == $jaAdicionados["idresponsavel"]){ ?> selected="selected" <?php } ?> value="<?php echo $usuario["idusuario"] ?>"><?php echo $usuario["nome"] ?></option>
                        <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="dono">
                <?php echo $idioma["dono"]; ?>
            </label>
            <div class="controls">
                <select id="dono" name="dono" class="span5">
                    <option value=""></option>
                    <?php foreach ($usuarios_instituicao as $usuario) { ?>
                            <option <?php if($usuario["idusuario"] == $jaAdicionados["iddono"]){ ?> selected="selected" <?php } ?> value="<?php echo $usuario["idusuario"] ?>"><?php echo $usuario["nome"] ?></option>
                        <?php } ?>
                </select>
            </div>
        </div>
        <br>
        <span class="pull-right">
            <input type="submit" class="btn btn-primary" value="<?php echo $idioma["btn_salvar"]; ?>">
        </span>
    </form>
</section>