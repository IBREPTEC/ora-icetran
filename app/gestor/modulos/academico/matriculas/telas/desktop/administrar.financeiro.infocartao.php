<section id="global">
  <div class="page-header"><h1><?= $idioma["opcoes"]; ?> &nbsp;<small><?= $idioma["opcoes_subtitulo"]; ?></small></h1></div>
  <ul class="breadcrumb">
    <li><?= $idioma["dados_cielo_status_transacao"]; ?></li>
    <li class="active">
      <strong>
        <?php if($pagamento["status_transacao"]) { 
          echo $idioma['cielo_status_transacao'][$pagamento["status_transacao"]]; 
        } else { 
          echo $idioma['cielo_status_transacao_nao_criada']; 
        } ?>
      </strong>
    </li>
    <br />
    <li><?= $idioma["dados_cielo_tid"]; ?></li>
    <li class="active"><strong><?= $orio_transacao["autorizacao"]; ?></strong></li>
    <br />
    <li><?= $idioma["dados_cielo_nsu"]; ?></li>
    <li class="active"><strong><?= $orio_transacao["nsu"]; ?></strong></li>
    <br />

  </ul>
</section>