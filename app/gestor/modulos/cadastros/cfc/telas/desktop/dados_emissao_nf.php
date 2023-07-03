<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php incluirLib("head", $config, $usuario); ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/assets/plugins/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8"/>

        <style>
            .control {
                margin-left: 160px;
            }

            .d-none {
                display: none !important;
            }

            #btn_sem_numero {
                margin-left: 20px;
            }

            #btn_add_numero {
                margin-left: 20px;
            }
        </style>
    </head>
    <body>
        <? incluirLib("topo", $config, $usuario); ?>
        <? $dadosBanco = $linhaObj->retornaDadosNf($url[3]); ?>

        <div class="container-fluid">
            <section id="global">
                <div class="page-header">
                    <h1><?= $idioma["pagina_titulo"]; ?> &nbsp;
                        <small><?= $idioma["pagina_subtitulo"]; ?></small>
                    </h1>
                </div>
                <ul class="breadcrumb">
                    <li><a href="/<?= $url[0]; ?>"><?= $idioma["nav_inicio"]; ?></a> <span class="divider">/</span></li>
                    <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>"><?= $idioma["nav_modulo"]; ?></a> <span
                            class="divider">/</span></li>
                    <li><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"><?= $idioma["pagina_titulo"]; ?></a> <span
                            class="divider">/</span></li>
                    <li class="active"><?= $idioma["titulo_opcao"]; ?></li>
                    <span class="pull-right"
                        style="color:#999"><?= $idioma["hora_servidor"]; ?> <?= date("d/m/Y H\hi"); ?></span>
                </ul>
            </section>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box-conteudo">
                        <div class=" pull-right">
                            <a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>"
                                                    class="btn btn-small"><i
                                    class="icon-share-alt"></i> <?= $idioma["btn_sair"]; ?></a>
                        </div>
                        <h2 class="tituloEdicao"><?= $linha["nome_fantasia"]; ?> <small>(<?= $linha['email'] ?>)</small></h2>

                        <div class="tabbable tabs-left">
                            <?php incluirTela("inc_menu_edicao", $config, $usuario); ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_editar">
                                    <h2 class="tituloOpcao"><?= $idioma["titulo_opcao"]; ?></h2>

                                    <form id="dados_nf" method="post" enctype="multipart/form-data" class="form-horizontal">
                                        <fieldset id="fieldset_dados_emissao_nf">
                                            <div class="control-group">
                                                <label class="control-label" for="nome_emissao_nf"><strong>* <?= $idioma["nome_emissao_nf"]; ?> </strong> </label>
                                                <div class="control">
                                                    <input id="nome_emissao_nf" name="nome_emissao_nf" class="span6" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' ? print_r($dadosBanco['nome_nf']) : ""; ?>" required>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="select_tipo_documento_emissao_nf"><strong>* <?= $idioma["select_tipo_documento_emissao_nf"]; ?> </strong></label>
                                                <div class="control">
                                                    <select name="select_tipo_documento_emissao_nf" id="select_tipo_documento_emissao_nf" required>
                                                        <option value="">-----</option>
                                                        <option value="cnpj" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['tipo_doc_nf'] == 'cnpj' ? print_r('selected') : ""; ?>>CNPJ</option>
                                                        <option value="cpf" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['tipo_doc_nf'] == 'cpf' ? print_r('selected') : ""; ?>>CPF</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="control-group cnpj <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['tipo_doc_nf'] == 'cnpj' ? "" : print_r('d-none'); ?>">
                                                <label class="control-label" for="cnpj_emissao_nf"><strong>* <?= $idioma["cnpj_emissao_nf"]; ?> </strong></label>
                                                <div class="control">
                                                    <input id="cnpj_emissao_nf" name="cnpj_emissao_nf" class="span5" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['tipo_doc_nf'] == 'cnpj' ? print_r($dadosBanco['doc_nf']) : "" ; ?>">
                                                </div>
                                            </div>

                                            <div class="control-group cpf <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['tipo_doc_nf'] == 'cpf' ? "" : print_r('d-none'); ?>">
                                                <label class="control-label" for="cpf_emissao_nf"><strong>* <?= $idioma["cpf_emissao_nf"]; ?> </strong></label>
                                                <div class="control">
                                                    <input id="cpf_emissao_nf" name="cpf_emissao_nf" class="span5" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['tipo_doc_nf'] == 'cpf' ? print_r($dadosBanco['doc_nf']) : "" ; ?>">
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="insc_estadual_emissao_nf"><?= $idioma["insc_estadual_emissao_nf"]; ?> </label>
                                                <div class="control">
                                                    <input id="insc_estadual_emissao_nf" name="insc_estadual_emissao_nf" class="span6" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' ? print_r($dadosBanco['insc_est_nf']) : ""; ?>">
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="insc_municipal_emissao_nf"><?= $idioma["insc_municipal_emissao_nf"]; ?> </label>
                                                <div class="control">
                                                    <input id="insc_municipal_emissao_nf" name="insc_municipal_emissao_nf" class="span6" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' ? print_r($dadosBanco['insc_mun_nf']) : ""; ?>">
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="cep_emissao_nf"><strong>* <?= $idioma["cep_emissao_nf"]; ?> </strong></label>
                                                <div class="control">
                                                    <input id="cep_emissao_nf" name="cep_emissao_nf" class="span2" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' ? print_r($dadosBanco['cep_nf']) : ""; ?>" required>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="logradouro_emissao_nf"><strong>* <?= $idioma["logradouro_emissao_nf"]; ?> </strong></label>
                                                <div class="control">
                                                    <select name="logradouro_emissao_nf" id="logradouro_emissao_nf" class="span2" required>
                                                        <option value=""></option>
                                                        <option value="126" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '126' ? print_r('selected') : ""; ?>>10ª Rua</option>
                                                        <option value="35" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '35' ? print_r('selected') : ""; ?>>10ª Travessa</option>
                                                        <option value="127" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '127' ? print_r('selected') : ""; ?>>11ª Rua</option>
                                                        <option value="36" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '36' ? print_r('selected') : ""; ?>>11ª Travessa</option>
                                                        <option value="121" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '121' ? print_r('selected') : ""; ?>>12ª Rua</option>
                                                        <option value="37" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '37' ? print_r('selected') : ""; ?>>12ª Travessa</option>
                                                        <option value="38" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '38' ? print_r('selected') : ""; ?>>13ª Travessa</option>
                                                        <option value="39" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '39' ? print_r('selected') : ""; ?>>14ª Travessa</option>
                                                        <option value="40" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '40' ? print_r('selected') : ""; ?>>15ª Travessa</option>
                                                        <option value="128" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '128' ? print_r('selected') : ""; ?>>16ª Travessa</option>
                                                        <option value="41" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '41' ? print_r('selected') : ""; ?>>1ª Avenida</option>
                                                        <option value="129" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '129' ? print_r('selected') : ""; ?>>1ª Paralela</option>
                                                        <option value="42" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '42' ? print_r('selected') : ""; ?>>1ª Rua</option>
                                                        <option value="130" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '130' ? print_r('selected') : ""; ?>>1ª Subida</option>
                                                        <option value="12" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '12' ? print_r('selected') : ""; ?>>1ª Travessa</option>
                                                        <option value="43" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '43' ? print_r('selected') : ""; ?>>1ª Vila</option>
                                                        <option value="131" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '131' ? print_r('selected') : ""; ?>>1º Alto</option>
                                                        <option value="132" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '132' ? print_r('selected') : ""; ?>>1º Beco</option>
                                                        <option value="44" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '44' ? print_r('selected') : ""; ?>>2ª Avenida</option>
                                                        <option value="133" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '133' ? print_r('selected') : ""; ?>>2ª Paralela</option>
                                                        <option value="45" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '45' ? print_r('selected') : ""; ?>>2ª Rua</option>
                                                        <option value="134" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '134' ? print_r('selected') : ""; ?>>2ª Subida</option>
                                                        <option value="13" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '13' ? print_r('selected') : ""; ?>>2ª Travessa</option>
                                                        <option value="46" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '46' ? print_r('selected') : ""; ?>>2ª Vila</option>
                                                        <option value="135" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '135' ? print_r('selected') : ""; ?>>2º Alto</option>
                                                        <option value="136" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '136' ? print_r('selected') : ""; ?>>2º Beco</option>
                                                        <option value="47" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '47' ? print_r('selected') : ""; ?>>3ª Avenida</option>
                                                        <option value="137" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '137' ? print_r('selected') : ""; ?>>3ª Paralela</option>
                                                        <option value="48" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '48' ? print_r('selected') : ""; ?>>3ª Rua</option>
                                                        <option value="138" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '138' ? print_r('selected') : ""; ?>>3ª Subida</option>
                                                        <option value="14" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '14' ? print_r('selected') : ""; ?>>3ª Travessa</option>
                                                        <option value="49" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '49' ? print_r('selected') : ""; ?>>3ª Vila</option>
                                                        <option value="139" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '139' ? print_r('selected') : ""; ?>>3º Alto</option>
                                                        <option value="140" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '140' ? print_r('selected') : ""; ?>>3º Beco</option>
                                                        <option value="50" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '50' ? print_r('selected') : ""; ?>>4ª Avenida</option>
                                                        <option value="117" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '117' ? print_r('selected') : ""; ?>>4ª Rua</option>
                                                        <option value="141" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '141' ? print_r('selected') : ""; ?>>4ª Subida</option>
                                                        <option value="15" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '15' ? print_r('selected') : ""; ?>>4ª Travessa</option>
                                                        <option value="51" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '51' ? print_r('selected') : ""; ?>>4ª Vila</option>
                                                        <option value="52" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '52' ? print_r('selected') : ""; ?>>5ª Avenida</option>
                                                        <option value="118" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '118' ? print_r('selected') : ""; ?>>5ª Rua</option>
                                                        <option value="142" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '142' ? print_r('selected') : ""; ?>>5ª Subida</option>
                                                        <option value="53" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '53' ? print_r('selected') : ""; ?>>5ª Travessa</option>
                                                        <option value="54" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '54' ? print_r('selected') : ""; ?>>5ª Vila</option>
                                                        <option value="55" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '55' ? print_r('selected') : ""; ?>>6ª Avenida</option>
                                                        <option value="143" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '143' ? print_r('selected') : ""; ?>>6ª Rua</option>
                                                        <option value="144" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '144' ? print_r('selected') : ""; ?>>6ª Subida</option>
                                                        <option value="56" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '56' ? print_r('selected') : ""; ?>>6ª Travessa</option>
                                                        <option value="145" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '145' ? print_r('selected') : ""; ?>>7ª Rua</option>
                                                        <option value="57" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '57' ? print_r('selected') : ""; ?>>7ª Travessa</option>
                                                        <option value="58" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '58' ? print_r('selected') : ""; ?>>8ª Travessa</option>
                                                        <option value="146" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '146' ? print_r('selected') : ""; ?>>9ª Rua</option>
                                                        <option value="59" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '59' ? print_r('selected') : ""; ?>>9ª Travessa</option>
                                                        <option value="119" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '119' ? print_r('selected') : ""; ?>>Acampamento</option>
                                                        <option value="60" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '60' ? print_r('selected') : ""; ?>>Acesso</option>
                                                        <option value="153" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '153' ? print_r('selected') : ""; ?>>Adro</option>
                                                        <option value="81" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '81' ? print_r('selected') : ""; ?>>Aeroporto</option>
                                                        <option value="1" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '1' ? print_r('selected') : ""; ?>>Alameda</option>
                                                        <option value="16" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '16' ? print_r('selected') : ""; ?>>Alto</option>
                                                        <option value="168" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '168' ? print_r('selected') : ""; ?>>Antiga Estrada</option>
                                                        <option value="61" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '61' ? print_r('selected') : ""; ?>>Área</option>
                                                        <option value="82" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '82' ? print_r('selected') : ""; ?>>Área Especial</option>
                                                        <option value="120" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '120' ? print_r('selected') : ""; ?>>Artéria</option>
                                                        <option value="95" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '95' ? print_r('selected') : ""; ?>>Atalho</option>
                                                        <option value="2" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '2' ? print_r('selected') : ""; ?>>Avenida</option>
                                                        <option value="17" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '17' ? print_r('selected') : ""; ?>>Avenida Contorno</option>
                                                        <option value="169" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '169' ? print_r('selected') : ""; ?>>Avenida Marginal</option>
                                                        <option value="154" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '154' ? print_r('selected') : ""; ?>>Avenida Velha</option>
                                                        <option value="62" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '62' ? print_r('selected') : ""; ?>>Baixa</option>
                                                        <option value="170" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '170' ? print_r('selected') : ""; ?>>Balão</option>
                                                        <option value="3" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '3' ? print_r('selected') : ""; ?>>Beco</option>
                                                        <option value="165" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '165' ? print_r('selected') : ""; ?>>Belvedere</option>
                                                        <option value="83" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '83' ? print_r('selected') : ""; ?>>Bloco</option>
                                                        <option value="75" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '75' ? print_r('selected') : ""; ?>>Bosque</option>
                                                        <option value="4" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '4' ? print_r('selected') : ""; ?>>Boulevard</option>
                                                        <option value="155" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '155' ? print_r('selected') : ""; ?>>Buraco</option>
                                                        <option value="147" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '147' ? print_r('selected') : ""; ?>>Cais</option>
                                                        <option value="166" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '166' ? print_r('selected') : ""; ?>>Calçada</option>
                                                        <option value="63" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '63' ? print_r('selected') : ""; ?>>Caminho</option>
                                                        <option value="31" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '31' ? print_r('selected') : ""; ?>>Campo</option>
                                                        <option value="28" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '28' ? print_r('selected') : ""; ?>>Canal</option>
                                                        <option value="64" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '64' ? print_r('selected') : ""; ?>>Chácara</option>
                                                        <option value="122" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '122' ? print_r('selected') : ""; ?>>Circular</option>
                                                        <option value="84" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '84' ? print_r('selected') : ""; ?>>Colônia</option>
                                                        <option value="171" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '171' ? print_r('selected') : ""; ?>>Complexo Viário</option>
                                                        <option value="18" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '18' ? print_r('selected') : ""; ?>>Condomínio</option>
                                                        <option value="5" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '5' ? print_r('selected') : ""; ?>>Conjunto</option>
                                                        <option value="19" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '19' ? print_r('selected') : ""; ?>>Conjunto Mutirão</option>
                                                        <option value="111" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '111' ? print_r('selected') : ""; ?>>Corredor</option>
                                                        <option value="112" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '112' ? print_r('selected') : ""; ?>>Córrego</option>
                                                        <option value="148" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '148' ? print_r('selected') : ""; ?>>Descida</option>
                                                        <option value="96" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '96' ? print_r('selected') : ""; ?>>Desvio</option>
                                                        <option value="97" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '97' ? print_r('selected') : ""; ?>>Distrito</option>
                                                        <option value="167" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '167' ? print_r('selected') : ""; ?>>Elevada</option>
                                                        <option value="172" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '172' ? print_r('selected') : ""; ?>>Entrada Particular</option>
                                                        <option value="85" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '85' ? print_r('selected') : ""; ?>>Entre Quadra</option>
                                                        <option value="90" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '90' ? print_r('selected') : ""; ?>>Escada</option>
                                                        <option value="86" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '86' ? print_r('selected') : ""; ?>>Esplanada</option>
                                                        <option value="65" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '65' ? print_r('selected') : ""; ?>>Estação</option>
                                                        <option value="173" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '173' ? print_r('selected') : ""; ?>>Estacionamento</option>
                                                        <option value="98" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '98' ? print_r('selected') : ""; ?>>Estádio</option>
                                                        <option value="6" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '6' ? print_r('selected') : ""; ?>>Estrada</option>
                                                        <option value="174" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '174' ? print_r('selected') : ""; ?>>Estrada de Ligação</option>
                                                        <option value="175" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '175' ? print_r('selected') : ""; ?>>Estrada Estadual</option>
                                                        <option value="176" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '176' ? print_r('selected') : ""; ?>>Estrada Municipal</option>
                                                        <option value="177" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '177' ? print_r('selected') : ""; ?>>Estrada Particular</option>
                                                        <option value="99" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '99' ? print_r('selected') : ""; ?>>Estrada Velha</option>
                                                        <option value="66" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '66' ? print_r('selected') : ""; ?>>Favela</option>
                                                        <option value="67" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '67' ? print_r('selected') : ""; ?>>Fazenda</option>
                                                        <option value="68" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '68' ? print_r('selected') : ""; ?>>Feira</option>
                                                        <option value="113" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '113' ? print_r('selected') : ""; ?>>Ferrovia</option>
                                                        <option value="104" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '104' ? print_r('selected') : ""; ?>>Fonte</option>
                                                        <option value="156" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '156' ? print_r('selected') : ""; ?>>Forte</option>
                                                        <option value="76" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '76' ? print_r('selected') : ""; ?>>Galeria</option>
                                                        <option value="100" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '100' ? print_r('selected') : ""; ?>>Granja</option>
                                                        <option value="105" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '105' ? print_r('selected') : ""; ?>>Ilha</option>
                                                        <option value="69" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '69' ? print_r('selected') : ""; ?>>Jardim</option>
                                                        <option value="125" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '125' ? print_r('selected') : ""; ?>>Jardinete</option>
                                                        <option value="20" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '20' ? print_r('selected') : ""; ?>>Ladeira</option>
                                                        <option value="164" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '164' ? print_r('selected') : ""; ?>>Lago</option>
                                                        <option value="114" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '114' ? print_r('selected') : ""; ?>>Lagoa</option>
                                                        <option value="21" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '21' ? print_r('selected') : ""; ?>>Largo</option>
                                                        <option value="22" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '22' ? print_r('selected') : ""; ?>>Loteamento</option>
                                                        <option value="189" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '189' ? print_r('selected') : ""; ?>>Marina</option>
                                                        <option value="87" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '87' ? print_r('selected') : ""; ?>>Módulo</option>
                                                        <option value="178" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '178' ? print_r('selected') : ""; ?>>Monte</option>
                                                        <option value="157" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '157' ? print_r('selected') : ""; ?>>Morro</option>
                                                        <option value="88" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '88' ? print_r('selected') : ""; ?>>Núcleo</option>
                                                        <option value="158" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '158' ? print_r('selected') : ""; ?>>Parada</option>
                                                        <option value="149" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '149' ? print_r('selected') : ""; ?>>Paralela</option>
                                                        <option value="23" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '23' ? print_r('selected') : ""; ?>>Parque</option>
                                                        <option value="24" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '24' ? print_r('selected') : ""; ?>>Parque Municipal</option>
                                                        <option value="25" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '25' ? print_r('selected') : ""; ?>>Parque Residencial</option>
                                                        <option value="29 <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '29' ? print_r('selected') : ""; ?>">Passagem</option>
                                                        <option value="179" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '179' ? print_r('selected') : ""; ?>>Passagem Subterrânea</option>
                                                        <option value="91" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '91' ? print_r('selected') : ""; ?>>Passarela</option>
                                                        <option value="77" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '77' ? print_r('selected') : ""; ?>>Passeio</option>
                                                        <option value="115" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '115' ? print_r('selected') : ""; ?>>Pátio</option>
                                                        <option value="159" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '159' ? print_r('selected') : ""; ?>>Ponta</option>
                                                        <option value="92" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '92' ? print_r('selected') : ""; ?>>Ponte</option>
                                                        <option value="106" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '106' ? print_r('selected') : ""; ?>>Porto</option>
                                                        <option value="190" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '190' ? print_r('selected') : ""; ?>>Povoado</option>
                                                        <option value="7" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '7' ? print_r('selected') : ""; ?>>Praça</option>
                                                        <option value="180" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '180' ? print_r('selected') : ""; ?>>Praça de Esportes</option>
                                                        <option value="78" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '78' ? print_r('selected') : ""; ?>>Praia</option>
                                                        <option value="150" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '150' ? print_r('selected') : ""; ?>>Prolongamento</option>
                                                        <option value="30" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '30' ? print_r('selected') : ""; ?>>Quadra</option>
                                                        <option value="160" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '160' ? print_r('selected') : ""; ?>>Quinta</option>
                                                        <option value="79" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '79' ? print_r('selected') : ""; ?>>Ramal</option>
                                                        <option value="32" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '32' ? print_r('selected') : ""; ?>>Rampa</option>
                                                        <option value="107" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '107' ? print_r('selected') : ""; ?>>Recanto</option>
                                                        <option value="108" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '108' ? print_r('selected') : ""; ?>>Residencial</option>
                                                        <option value="161" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '161' ? print_r('selected') : ""; ?>>Reta</option>
                                                        <option value="33" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '33' ? print_r('selected') : ""; ?>>Retiro</option>
                                                        <option value="109" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '109' ? print_r('selected') : ""; ?>>Retorno</option>
                                                        <option value="181" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '181' ? print_r('selected') : ""; ?>>Rodo Anel</option>
                                                        <option value="8" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '8' ? print_r('selected') : ""; ?>>Rodovia</option>
                                                        <option value="182" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '182' ? print_r('selected') : ""; ?>>Rotatória</option>
                                                        <option value="70" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '70' ? print_r('selected') : ""; ?>>Rótula</option>
                                                        <option value="9" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '9' ? print_r('selected') : ""; ?>>Rua</option>
                                                        <option value="71" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '71' ? print_r('selected') : ""; ?>>Rua de Ligação</option>
                                                        <option value="80" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '80' ? print_r('selected') : ""; ?>>Rua de Pedestre</option>
                                                        <option value="183" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '183' ? print_r('selected') : ""; ?>>Rua Particular</option>
                                                        <option value="151" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '151' ? print_r('selected') : ""; ?>>Rua Velha</option>
                                                        <option value="93" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '93' ? print_r('selected') : ""; ?>>Servidão</option>
                                                        <option value="72" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '72' ? print_r('selected') : ""; ?>>Setor</option>
                                                        <option value="26" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '26' ? print_r('selected') : ""; ?>>Sítio</option>
                                                        <option value="116" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '116' ? print_r('selected') : ""; ?>>Subida</option>
                                                        <option value="27" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '27' ? print_r('selected') : ""; ?>>Terminal</option>
                                                        <option value="10" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '10' ? print_r('selected') : ""; ?>>Travessa</option>
                                                        <option value="184" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '184' ? print_r('selected') : ""; ?>>Travessa Particular</option>
                                                        <option value="152" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '152' ? print_r('selected') : ""; ?>>Travessa Velha</option>
                                                        <option value="89" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '89' ? print_r('selected') : ""; ?>>Trecho</option>
                                                        <option value="101" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '101' ? print_r('selected') : ""; ?>>Trevo</option>
                                                        <option value="73" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '73' ? print_r('selected') : ""; ?>>Túnel</option>
                                                        <option value="110" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '110' ? print_r('selected') : ""; ?>>Unidade</option>
                                                        <option value="162" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '162' ? print_r('selected') : ""; ?>>Vala</option>
                                                        <option value="102" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '102' ? print_r('selected') : ""; ?>>Vale</option>
                                                        <option value="185" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '185' ? print_r('selected') : ""; ?>>Vereda</option>
                                                        <option value="34" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '34' ? print_r('selected') : ""; ?>>Via</option>
                                                        <option value="186" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '186' ? print_r('selected') : ""; ?>>Via de Acesso</option>
                                                        <option value="187" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '187' ? print_r('selected') : ""; ?>>Via de Pedestre</option>
                                                        <option value="188" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '188' ? print_r('selected') : ""; ?>>Via Elevado</option>
                                                        <option value="123" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '123' ? print_r('selected') : ""; ?>>Via Expressa</option>
                                                        <option value="124" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '124' ? print_r('selected') : ""; ?>>Via Litoranea</option>
                                                        <option value="74" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '74' ? print_r('selected') : ""; ?>>Via Local</option>
                                                        <option value="94" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '94' ? print_r('selected') : ""; ?>>Viaduto</option>
                                                        <option value="103" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '103' ? print_r('selected') : ""; ?>>Viela</option>
                                                        <option value="11" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '11' ? print_r('selected') : ""; ?>>Vila</option>
                                                        <option value="163" <?php $dadosBanco['preenchidos'] == 'S' && $dadosBanco['logradouro_nf'] == '163' ? print_r('selected') : ""; ?>>Zigue-Zague</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="endereco_emissao_nf"><strong>* <?= $idioma["endereco_emissao_nf"]; ?> </strong></label>
                                                <div class="control">
                                                    <input id="endereco_emissao_nf" name="endereco_emissao_nf" class="span5" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' ? print_r($dadosBanco['endereco_nf']) : ""; ?>" required>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="bairro_emissao_nf"><strong>* <?= $idioma["bairro_emissao_nf"]; ?> </strong></label>
                                                <div class="control">
                                                    <input id="bairro_emissao_nf" name="bairro_emissao_nf" class="span6" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' ? print_r($dadosBanco['bairro_nf']) : ""; ?>" required>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label label_numero" for="numero_emissao_nf"><strong>* <?= $idioma["numero_emissao_nf"]; ?> </strong></label>
                                                <div class="control">
                                                    <input id="numero_emissao_nf" name="numero_emissao_nf" class="span2" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' ? print_r($dadosBanco['numero_nf']) : ""; ?>" required>

                                                    <input id="btn_sem_numero" type="button" class="btn" value="Sem Número">
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label label_complemento" for="complemento_emissao_nf"><?= $idioma["complemento_emissao_nf"]; ?></label>
                                                <div class="control">
                                                    <input id="complemento_emissao_nf" name="complemento_emissao_nf" class="span5" type="text" value="<?php $dadosBanco['preenchidos'] == 'S' ? print_r($dadosBanco['complemento_nf']) : ""; ?>">

                                                    <input id="btn_add_numero" type="button" class="btn d-none" value="Adicionar Número">
                                                </div>
                                            </div>
                                        </fieldset>

                                        <div class="form-actions">
                                            <input type="submit" class="btn btn-primary" value="Salvar Dados">

                                            <input type="reset" class="btn" onclick="MM_goToURL('parent','/gestor/cadastros/cfc');" value="Cancelar">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
        <script src="/assets/js/ajax.js"></script>
        <script src="/assets/plugins/jquery-ui/ui/jquery.ui.core.js"></script>
        <script src="/assets/plugins/jquery-ui/ui/jquery.ui.widget.js"></script>
        <script src="/assets/plugins/jquery-ui/ui/jquery.ui.datepicker.js"></script>
        <script src="/assets/plugins/jquery-ui/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
        <script src="/assets/plugins/password_force/password_strength_plugin.js"></script>
        <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
        <script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
        <script type="text/javascript">
            jQuery(function($) {
                function buscarCEP(cep_informado) {
                    $.msg({
                        autoUnblock : true,
                        clickUnblock : false,
                        klass : 'white-on-black',
                        content: 'Processando solicitação.',
                        afterBlock : function(){
                        var self = this;

                        jQuery.ajax({
                            url: "/api/get/cep",
                            dataType: "json", //Tipo de Retorno
                            type: "POST",
                            data: {cep: cep_informado},
                            success: function(json){
                            if (json.erro) {
                                alert (json.erro);
                            } else {
                                console.log(json);

                                $("input[name='endereco_emissao_nf']").val(json.logradouro);
                                $("input[name='bairro_emissao_nf']").val(json.bairro);

                                $("select[name='idlogradouro'] option").each(function() {
                                    var $this = $(this);
                                    var text = $this.text();

                                    if ($this.val() != "") {
                                        if (json.logradouro.includes(text.trim()) == true) {
                                            $this.attr("selected", 'selected');
                                        }
                                    }
                                });
                            }

                            self.unblock();
                            }
                        });
                        }
                    });
                }

                $(document).ready(function() {
                    $('#cep_emissao_nf').mask("99999-999");

                    $('#cnpj_emissao_nf').mask("99.999.999/9999-99");
                    $('#cpf_emissao_nf').mask("999.999.999-99");

                    $('#select_tipo_documento_emissao_nf').change(function() {
                        var value = $('#select_tipo_documento_emissao_nf').val();

                        if (value == 'cnpj') {
                            if (!$('.cpf').hasClass('d-none')) {
                                $('.cpf').addClass('d-none');
                            }

                            $('.cnpj').removeClass('d-none');
                            $('#cnpj_emissao_nf').mask("99.999.999/9999-99");
                            $('#cnpj_emissao_nf').attr("required", true);
                        } else if (value == 'cpf') {
                            if (!$('.cnpj').hasClass('d-none')) {
                                $('.cnpj').addClass('d-none');
                            }

                            $('.cpf').removeClass('d-none');
                            $('#cpf_emissao_nf').mask("999.999.999-99");
                            $('#cpf_emissao_nf').attr("required", true);
                        }
                    });

                    $('#btn_sem_numero').click(function() {
                        event.preventDefault();

                        $('#numero_emissao_nf').removeAttr('required');
                        $('#numero_emissao_nf').attr("disabled", true);
                        $('#numero_emissao_nf').val('');

                        $('#complemento_emissao_nf').attr("required", true);

                        $('#btn_sem_numero').addClass('d-none');
                        $('#btn_add_numero').removeClass('d-none');

                        $('.label_numero').html('<?= $idioma["numero_emissao_nf"]; ?>');
                        $('.label_complemento').html('<strong>* <?= $idioma["complemento_emissao_nf"]; ?> </strong>');
                    });

                    $('#btn_add_numero').click(function() {
                        event.preventDefault();

                        $('#complemento_emissao_nf').removeAttr('required');

                        $('#numero_emissao_nf').removeAttr('disabled');
                        $('#numero_emissao_nf').attr("required", true);

                        $('#btn_sem_numero').removeClass('d-none');
                        $('#btn_add_numero').addClass('d-none');

                        $('.label_complemento').html('<?= $idioma["complemento_emissao_nf"]; ?>');
                        $('.label_numero').html('<strong>* <?= $idioma["numero_emissao_nf"]; ?> </strong>');
                    });

                    $("input[name='cep_emissao_nf']").blur(function() {
                        buscarCEP($("input[name='cep_emissao_nf']").val());
                    });
                });
            });
        </script>
    </body>
</html>