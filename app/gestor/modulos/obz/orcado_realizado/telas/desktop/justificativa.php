   
<table width="450" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"> 
      <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td><h2><?php echo $idioma["label_justificativa"]; ?></h2></td>
        </tr>  
        <tr>
          <td>
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
               <?php /*?> <td><?php echo $idioma["instituicao"]; ?>:<strong><?php echo $orcamento["instituicao"]; ?></strong></td><?php */?>
                <td colspan="2"><strong><?php echo $idioma["cfc"]; ?>:</strong> <?php echo $orcamento["nome_fantasia"]; ?></td>
              </tr>
             <?php /*?> <tr>
                <td><?php echo $idioma["categoria"]; ?>:<strong><?php echo $orcamento["categoria"]; ?></strong> </td>
                <td colspan="2"><?php echo $idioma["subcategoria"]; ?>:<strong><?php echo $orcamento["subcategoria"]; ?></strong></td>
              </tr>
               <tr>
                <td><?php echo $idioma["centro"]; ?>:<strong><?php echo $orcamento["centro"]; ?></strong></td>
                <td colspan="2"><?php echo $idioma["ano"]; ?>:<strong><?php echo $orcamento["ano"]; ?></strong> </td>
              </tr><?php */?>
          </table>
          </td>
        </tr>  
      </table><hr style="margin: 0 0 0 0; ">
      <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>?idsindicato=<?php echo $orcamento["idsindicato"]; ?>&idcentro_custo=<?php echo $orcamento["idcentro_custo"]; ?>&ano=<?php echo $orcamento["ano"]; ?>&idcategoria=<?php echo $orcamento["idcategoria"]; ?>&idsubcategoria=<?php echo $orcamento["idsubcategoria"]; ?>" method="post" class="form-horizontal" target="_parent">
        <input name="acao" type="hidden" value="salvarjustificativa">
        <input type="hidden" name="urlcaminho" value="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>?idsindicato=<?php echo $orcamento["idsindicato"]; ?>&idcentro_custo=<?php echo $orcamento["idcentro_custo"]; ?>&ano=<?php echo $orcamento["ano"]; ?>&idcategoria=<?php echo $orcamento["idcategoria"]; ?>&idsubcategoria=<?php echo $orcamento["idsubcategoria"]; ?>" />
        <input name="idorcamento" type="hidden" value="<?php echo $orcamento["idorcamento"]; ?>">
        <div style="height:280px; width:100%; overflow:auto;">
            <table border="0" cellpadding="5" cellspacing="0" style="width:95%;" align="left">
               <tr>  
                <td align="right" valign="middle">
                 <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                        <?php foreach($meses_idioma["pt_br"] as $ind => $val){?>
                        <tr>
                            <td><br><strong><?=$val.':'?></strong>
                                <textarea rows="5" style="width:100%" name="justificativa[<?=(int) $ind?>]" id="justificativa_<?=(int) $ind?>"><?php echo $orcamento["justificativa_".((int) $ind)]; ?></textarea>
                            </td>
                        </tr>
                        <?php }?>
                  </table>
                </td>
              </tr>
            </table>
        </div><br>
        <table border="0" cellpadding="0" cellspacing="0" align="left">
         <?php /*?> <tr>
            <td align="left" valign="middle" ><?= $idioma["salvar_mensagem"]; ?></td>
          </tr><?php */?>
          <tr>
            <td align="left" valign="middle" ><input class="btn" type="submit" value="<?= $idioma["salvar"]; ?>"></td>
          </tr>
        </table>
      </form> 
    </td>
  </tr>
</table>