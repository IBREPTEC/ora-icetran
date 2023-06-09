<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php incluirLib("head",$config,$usuario); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
  background-color: #FFF !important;
  background-image:none;
  padding-top:0px !important;
}

body {
  min-width: 300px;
}
.container-fluid {
  min-width: 300px;
}
</style>
</head>
<body>   
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td align="left"> 
      <table width="300" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td><h2><?php echo $idioma["cancelamento_label"]; ?></h2></td>
        </tr>  
      </table>
      <form action="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/visualiza" method="post" class="form-horizontal" target="_parent" onSubmit="return confirmaAlteracao()">
        <input name="acao" type="hidden" value="alterarSituacao">
        <input name="situacao_para" type="hidden" value="<?php echo $situacaoCancelada["idsituacao"]; ?>">
        <table border="0" cellpadding="5" cellspacing="0" style="width:100%;margin-top:10px" align="left">
          <tr>
            <td align="left" valign="top"><?= $idioma["motivo_cancelamento"]; ?></td>
          </tr>
          <tr>  
            <td align="right" valign="middle">
              <textarea rows="5" style="width:100%" name="motivo_cancelamento" id="motivo_cancelamento"></textarea>
            </td>
          </tr>
        </table>
        <table border="0" cellpadding="5" cellspacing="0" align="left">
          <tr>
            <td align="left" valign="middle" ><?= $idioma["executar_mensagem"]; ?></td>
          </tr>
          <tr>
            <td align="left" valign="middle" ><input class="btn" type="submit" value="<?= $idioma["executar"]; ?>"></td>
          </tr>
        </table>
      </form> 
    </td>
  </tr>
</table>
<script type="text/javascript">
  function confirmaAlteracao() {
	if(document.getElementById('motivo_cancelamento').value == ''){
		document.getElementById('motivo_cancelamento').focus();
		alert('<?= $idioma["motivo_confirma"]; ?>');
		return false;
	}
	var confirma = confirm('<?= $idioma["cancelamento_confirma"]; ?>');
	if(confirma) {
	  return true;
	} else {
	  return false;
	}
  }
</script>    
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/assets/plugins/facebox/src/facebox.js"></script>
<script src="/assets/js/jquery.maskMoney.js"></script>
<script src="/assets/js/validation.js"></script>
<script src="/assets/js/jquery.maskedinput_1.3.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-transition.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-alert.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-modal.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-dropdown.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-scrollspy.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tab.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-tooltip.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-popover.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-button.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-collapse.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-carousel.js"></script>
<script src="/assets/bootstrap_v2/js/bootstrap-typeahead.js"></script>
<script src="/assets/js/mousetrap.min.js"></script>
<script src="/assets/js/construtor.js"></script>
<div style="display:none;"><img src="/assets/img/ajax_loader.png" width="64" height="64" /></div>
</body>
</html>