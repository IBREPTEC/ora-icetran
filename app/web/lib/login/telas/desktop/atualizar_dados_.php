<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/2000/svg">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
</head>
<body>
    <?php incluirLib('topo', $config, $usuario); ?>
    <?php incluirTela('inc_passos', $config, $usuario); ?>

    <div class="container mt50">
        <?php incluirTela('inc_curso', $config, $informacoes); ?>

        <?php $class = ($informacoes['idcurso']) ? 'col-sm-8' : 'col-sm-12'; ?>
        <div class="<?= $class; ?> itemForm">
            <?php
            $erros = ($GLOBALS['pessoa']['erros']) ? $GLOBALS['pessoa']['erros'] : $GLOBALS['erros'];
            if (count($erros) > 0) {
                ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span><?= $idioma['form_erros']; ?></span>
                    <?php
                    foreach ($erros as $ind => $val) {
                        echo '<br />' . $idioma[$val];
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <h3><?= $idioma['dados_aluno']; ?></h3>
            <form id="form_cadastrar" method='post' class="form-guia">
                <?php
                if ($_SESSION['novaAcao'] == 'logar') {
                ?>
                    <input type="hidden" id="acao" name="acao" value="<?= ($url[3] != 'login') ? 'modificar' : 'modificar_logar'; ?>">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="acao" name="acao" value="<?= ($url[3] != 'login') ? 'cadastrar' : 'cadastrar_logar'; ?>">
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-nome'></label>
                            <input type='text' id='nome' name='nome' placeholder='<?= $idioma['form_nome']; ?>' value="<?= $_POST['nome']; ?>" class='input-alpha' maxlength='100' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-documento'></label>
                            <input type='text' id='documento' name='documento' placeholder='<?= $idioma['form_documento']; ?>' value="<?= $_POST['documento']; ?>" <?= ($_SESSION['novaAcao'] == 'criar_novo') ? '' : 'disabled'; ?> class='input-cpf' maxlength='14' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-email'></label>
                            <input type='text' id='email' name='email' placeholder='<?= $idioma['form_email']; ?>' value="<?= $_POST['email']; ?>" <?= ($_SESSION['novaAcao'] == 'criar_novo') ? '' : 'disabled'; ?> class='input-email' maxlength='100' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-sexo'></label>
                            <select id="sexo" name="sexo" class="input-select" required>
                                <option value="" class="select-placeholder"><?= $idioma['form_genero']; ?></option>
                                <option value="" selected><?= $idioma['form_genero']; ?></option>
                                <?php
                                foreach ($GLOBALS['sexo_config'][$GLOBALS['config']['idioma_padrao']] as $ind => $valor) {
                                    ?>
                                    <option value="<?= $ind; ?>" <?= ($ind == $_POST['sexo']) ? 'selected' : ''; ?> >
                                        <?= $valor; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-estado_civil'></label>
                            <select id="estado_civil" name="estado_civil" class="input-select" required>
                                <option value="" class="select-placeholder"><?= $idioma['form_estado_civil']; ?></option>
                                <option value="" selected><?= $idioma['form_estado_civil']; ?></option>
                                <?php
                                foreach ($GLOBALS['estadocivil'][$GLOBALS['config']['idioma_padrao']] as $ind => $valor) {
                                    ?>
                                    <option value="<?= $ind; ?>" <?= ($ind == $_POST['estado_civil']) ? 'selected' : ''; ?> >
                                        <?= $valor; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-data_nasc'></label>
                            <input type='text' id='data_nasc' name='data_nasc' placeholder='<?= $idioma['form_data_nasc']; ?>' value="<?= $_POST['data_nasc']; ?>" class='input-datebr' maxlength='10' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-nacionalidade'></label>
                            <input type='text' id='nacionalidade' name='nacionalidade' placeholder='<?= $idioma['form_nacionalidade']; ?>' value="<?= $_POST['nacionalidade']; ?>" class='input-alpha' maxlength='100' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-naturalidade'></label>
                            <input type='text' id='naturalidade' name='naturalidade' placeholder='<?= $idioma['form_naturalidade']; ?>' value="<?= $_POST['naturalidade']; ?>" class='input-alpha' maxlength='100' required>
                            <!-- <p class="help-block"><?= $idioma["form_naturalidade_ajuda"]; ?></p> -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-rg'></label>
                            <input type='text' id='rg' name='rg' placeholder='<?= $idioma['form_rg']; ?>' value="<?= $_POST['rg']; ?>" class='input-alpha' maxlength='20' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-rg_orgao_emissor'></label>
                            <input type='text' id='rg_orgao_emissor' name='rg_orgao_emissor' placeholder='<?= $idioma['form_rg_orgao_emissor']; ?>' value="<?= $_POST['rg_orgao_emissor']; ?>" class='input-alpha' maxlength='20' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-rg_data_emissao'></label>
                            <input type='text' id='rg_data_emissao' name='rg_data_emissao' placeholder='<?= $idioma['form_rg_data_emissao']; ?>' value="<?= $_POST['rg_data_emissao']; ?>" class='input-datebr' maxlength='10' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-rne'></label>
                            <input type='text' id='rne' name='rne' placeholder='<?= $idioma['form_rne']; ?>' value="<?= $_POST['rne']; ?>" class='input-alpha' maxlength='30'>
                            <!-- <p class="help-block"><?= $idioma["form_rne_ajuda"]; ?></p> -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-profissao'></label>
                            <input type='text' id='profissao' name='profissao' placeholder='<?= $idioma['form_profissao']; ?>' value="<?= $_POST['profissao']; ?>" class='input-alpha' maxlength='100' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-telefone'></label>
                            <input type='text' id='telefone' name='telefone' placeholder='<?= $idioma['form_telefone']; ?>' value="<?= $_POST['telefone']; ?>" class='input-tel' maxlength='15'>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-celular'></label>
                            <input type='text' id='celular' name='celular' placeholder='<?= $idioma['form_celular']; ?>' value="<?= $_POST['celular']; ?>" class='input-tel' maxlength='15' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-filiacao_mae'></label>
                            <input type='text' id='filiacao_mae' name='filiacao_mae' placeholder='<?= $idioma['form_filiacao_mae']; ?>' value="<?= $_POST['filiacao_mae']; ?>" class='input-alpha' maxlength='100' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-filiacao_pai'></label>
                            <input type='text' id='filiacao_pai' name='filiacao_pai' placeholder='<?= $idioma['form_filiacao_pai']; ?>' value="<?= $_POST['filiacao_pai']; ?>" class='input-alpha' maxlength='100' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-cep'></label>
                            <input type='text' id='cep' name='cep' placeholder='<?= $idioma['form_cep']; ?>' value="<?= $_POST['cep']; ?>" class='input-cep' maxlength='9' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-idestado'></label>
                            <select id="idestado" name="idestado" class="input-select" required>
                                <option value="" class="select-placeholder"><?= $idioma['form_idestado']; ?></option>
                                <option value="" selected><?= $idioma['form_idestado']; ?></option>
                                <?php
                                foreach ($estados as $ind => $valor) {
                                    ?>
                                    <option value="<?= $valor['idestado']; ?>" <?= ($valor['idestado'] == $_POST['idestado']) ? 'selected' : ''; ?> >
                                        <?= $valor['nome']; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-idcidade'></label>
                            <select id="idcidade" name="idcidade" class="input-select" required>
                                <option value="" class="select-placeholder"><?= $idioma['form_idcidade']; ?></option>
                                <option value="" selected><?= $idioma['form_idcidade']; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-idlogradouro'></label>
                            <select id="idlogradouro" name="idlogradouro" class="input-select" required>
                                <option value="" class="select-placeholder"><?= $idioma['form_idlogradouro']; ?></option>
                                <option value="" selected><?= $idioma['form_idlogradouro']; ?></option>
                                <?php
                                foreach ($logradouros as $ind => $valor) {
                                    ?>
                                    <option value="<?= $valor['idlogradouro']; ?>" <?= ($valor['idlogradouro'] == $_POST['idlogradouro']) ? 'selected' : ''; ?> >
                                        <?= $valor['nome']; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-endereco'></label>
                            <input type='text' id='endereco' name='endereco' placeholder='<?= $idioma['form_endereco']; ?>' value="<?= $_POST['endereco']; ?>" class='input-alpha' maxlength='100' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-bairro'></label>
                            <input type='text' id='bairro' name='bairro' placeholder='<?= $idioma['form_bairro']; ?>' value="<?= $_POST['bairro']; ?>" class='input-alpha' maxlength='100' required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-numero'></label>
                            <input type='text' id='numero' name='numero' placeholder='<?= $idioma['form_numero']; ?>' value="<?= $_POST['numero']; ?>" class='input-alpha' maxlength='10' required>
                            <!-- <p class="help-block"><?= $idioma["form_numero_ajuda"]; ?></p> -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class='lince-input'>
                            <label for='input-complemento'></label>
                            <input type='text' id='complemento' name='complemento' placeholder='<?= $idioma['form_complemento']; ?>' value="<?= $_POST['complemento']; ?>" class='input-alpha' maxlength='100'>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <input type="submit" value="<?= $idioma['btn_cadastrar']; ?>" class="btBox verde">
                    </div>

                    <!-- Anti-Spam -->
                    <input type='text' name='url-form' class='url-form' value=' ' />
                </div>
            </form>
        </div>
    </div>

    <?php incluirLib('rodape', $config, $usuario); ?>
</body>
<link media="screen" href="/assets/plugins/jquery.msg/jquery.msg.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.center.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery.msg/jquery.msg.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var idcidade = <?= (int) $_POST['idcidade']; ?>;
        $('#idestado ul li').click(function() {
            var estado = $(this).attr('data-select-value');
            if (estado) {
                $.getJSON(
                    '/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/login/json/',{
                        idestado: estado,
                        ajax: 'true'
                    },
                    function(json){
                        var options = ' <option value="" class="select-placeholder"><?= $idioma['form_idcidade']; ?></option>' +
                            '<option value="" selected><?= $idioma['form_idcidade']; ?></option>';

                        for (var i = 0; i < json.length; i++) {
                            var selected = '';
                            if(json[i].idcidade == idcidade) {
                                var selected = 'selected';
                            }

                            options += '<option value="' + json[i].idcidade + '" '+ selected +'>' + json[i].nome + '</option>';
                        }   
                        $("select[name='idcidade']").html(options);
                        regarregarLinceform('idcidade');
                    }
                );
            } else {
                var options = ' <option value="" class="select-placeholder"><?= $idioma['form_idcidade']; ?></option>' +
                            '<option value="" selected><?= $idioma['form_idcidade']; ?></option>';
                $("select[name='idcidade']").html(options);
                regarregarLinceform('idcidade');
            }
        });
        //Simula o clique no idestado, no valor passado
        clicarValor('idestado', $("select[name='idestado']").val());

        function buscarCEP(cep_informado) {
            $.msg({ 
                autoUnblock : true,
                clickUnblock : false,
                klass : 'white-on-black',
                content: 'Processando solicitação.',
                afterBlock : function() {
                    var self = this;
                    jQuery.ajax({
                        url: "/api/get/cep",
                        dataType: "json",
                        type: "POST",
                        data: {cep: cep_informado},
                        success: function(json) {
                            if (json.erro) {
                                    alert (json.erro);
                            } else {
                                $("select[name='idlogradouro'] option").each(function() {
                                    if (json.logradouro.includes($(this).text())) {
                                        $(this).attr("selected", 'selected');
                                    }
                                });
                                regarregarLinceform('idlogradouro');

                                $("input[name='endereco']").val(json.logradouro)
                                $("input[name='bairro']").val(json.bairro)

                                idcidade = $("select[name='idlogradouro'] option:selected").val();
                                $("select[name='idestado']").val(json.idestado);
                                clicarValor('idestado', json.idestado);
                            }

                            self.unblock();               
                        }    
                    }); 
                } 
            });
        }  

        $(document).ready(function(){ 
            $("input[name='cep']").blur(function() {
                buscarCEP($("input[name='cep']").val());
            }); 
        });  
    });
</script>
</html>
