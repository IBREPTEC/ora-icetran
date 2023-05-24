<?php

// Array de configuração para a formulario
$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
        'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
        'campos' => array( // Campos do formulario
            array(
                'id' => 'form_nome',
                'nome' => 'titulo',
                'nomeidioma' => 'form_nome',
                'tipo' => 'input',
                'valor' => 'titulo',
                'validacao' => array('required' => 'nome_vazio'),
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),

            array(
                'id' => 'form_nome_link',
                'nome' => 'link_1',
                'nomeidioma' => 'form_nome_link',
                'tipo' => 'input',
                'valor' => 'link_1',
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),
			array(
                'id' => 'form_nome_link_2',
                'nome' => 'link_2',
                'nomeidioma' => 'form_nome_link_2',
                'tipo' => 'input',
                'valor' => 'link_2',
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true,

            ),
            array(
                'id' => 'descricao',
                'nome' => 'descricao',
                'nomeidioma' => 'form_descricao',
                'tipo' => 'text',
                'valor' => 'descricao',
                // 'validacao' => array('required' => 'descricao_vazio'),
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true,
            ),
            array(
                'id' => 'idpasta',
                'nome' => 'idpasta',
                'nomeidioma' => 'form_pasta',
                'tipo' => 'select',
                'sql' => 'SELECT * FROM videotecas_pastas WHERE ativo=\'S\'',
                'sql_valor' => 'idpasta',
                'sql_label' =>'nome',
                'valor' => 'idpasta',
                'validacao' => array('required' => 'pasta_vazio'),
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'tags',
                'nome' => 'tags',
                'nomeidioma' => 'form_tags',
                'tipo' => 'select',
                'sql' => 'SELECT b.nome as nome, c.idtagvideo FROM videotecas_tags_videos AS c INNER JOIN videotecas_tags AS b ON b.idtag=c.idtag WHERE ativo="S"',
                'sql_valor' => 'idtagvideo',
                'sql_label' =>'nome',
                'valor' => 'nome',
                'class' => 'span6',
                'banco' => true
            ),

            array(
                'id' => 'arquivo', // Id do atributo HTML
                'nome' => 'arquivo', // Name do atributo HTML
                'nomeidioma' => 'form_file', // Referencia a variavel de idioma
                'tipo' => 'file', // Tipo do input
                'class' => 'span6', //Class do atributo HTML
                "banco_campo" => "arquivo",
                "pasta" => "videoteca",
                "extensoes" => 'mp3|mp4|avi',
                "banco"=>true,
                "validacao" => array("formato_arquivo" => "video_invalido"),
                "ignorarsevazio" => true

            ),
            array(
                'id' => 'form_ativo_painel',
                'nome' => 'ativo_painel',
                'nomeidioma' => 'form_ativo_painel',
                'tipo' => 'select',
                'array' => 'ativo', // Array que popula o select
                'class' => 'span2',
                'valor' => 'ativo_painel',
                'validacao' => array('required' => 'ativo_vazio'),
                'ajudaidioma' => 'form_ativo_ajuda',
                'banco' => true,
                'banco_string' => true
            )


        )
    )
);


$config['formulario_editar'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto', // Titulo do formulario (referencia a variavel de idioma)
        'legendaidioma' => 'legendadadosdados', // Legenda do fomrulario (referencia a variavel de idioma)
        'campos' => array( // Campos do formulario
            array(
                'id' => 'form_nome',
                'nome' => 'titulo',
                'nomeidioma' => 'form_nome',
                'tipo' => 'input',
                'valor' => 'titulo',
                'validacao' => array('required' => 'nome_vazio'),
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),

            array(
                'id' => 'form_nome_link',
                'nome' => 'link_1',
                'nomeidioma' => 'form_nome_link',
                'tipo' => 'input',
                'valor' => 'link_1',
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),
			array(
                'id' => 'form_nome_link_2',
                'nome' => 'link_2',
                'nomeidioma' => 'form_nome_link_2',
                'tipo' => 'input',
                'valor' => 'link_2',
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true,

            ),
            array(
                'id' => 'descricao',
                'nome' => 'descricao',
                'nomeidioma' => 'form_descricao',
                'tipo' => 'text',
                'valor' => 'descricao',
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true,
            ),
            array(
                'id' => 'tags',
                'nome' => 'tags',
                'nomeidioma' => 'form_tags',
                'tipo' => 'select',
                'sql' => 'SELECT b.nome as nome, c.idtagvideo FROM videotecas_tags_videos AS c INNER JOIN videotecas_tags AS b ON b.idtag=c.idtag WHERE ativo="S"',
                'sql_valor' => 'idtagvideo',
                'sql_label' =>'nome',
                'valor' => 'nome',
                'class' => 'span6',
                'banco' => true
            ),
            array(
                'id' => 'idpasta',
                'nome' => 'idpasta',
                'nomeidioma' => 'form_pasta',
                'tipo' => 'select',
                'sql' => 'SELECT * FROM videotecas_pastas WHERE ativo=\'S\'',
                'sql_valor' => 'idpasta',
                'sql_label' =>'nome',
                'valor' => 'idpasta',
                'validacao' => array('required' => 'pasta_vazio'),
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'arquivo', // Id do atributo HTML
                'nome' => 'arquivo', // Name do atributo HTML
                'nomeidioma' => 'form_file', // Referencia a variavel de idioma
                'tipo' => 'file', // Tipo do input
                'class' => 'span6', //Class do atributo HTML
                "banco_campo" => "arquivo",
                "pasta" => "videoteca",
                "extensoes" => 'mp3|mp4|avi',
                "banco"=>true,
                "validacao" => array("formato_arquivo" => "video_invalido"),
                "ignorarsevazio" => true,
                "valor"=>'arquivo_nome'

            ),
            array(
                'id' => 'form_ativo_painel',
                'nome' => 'ativo_painel',
                'nomeidioma' => 'form_ativo_painel',
                'tipo' => 'select',
                'array' => 'ativo',
                'class' => 'span2',
                'valor' => 'ativo_painel',
                'validacao' => array('required' => 'ativo_vazio'),
                'ajudaidioma' => 'form_ativo_ajuda',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'duracao',
                'nome' => 'duracao',
                'tipo' => 'hidden',
                'valor' => 'return videoteca::getDuration((int) str_replace("/gestor/academico/videoteca/", "", $_SERVER["REQUEST_URI"]));',
                // 'sql_valor' => 'duracao',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'arquivo',
                'nome' => 'arquivo_nome',
                'tipo' => 'hidden',
                'valor' => 'arquivo_nome',
                'banco' => true,
                'banco_string' => true
            ),
        )
    )
);



$config['formulario_youtube_editar'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            array(
                'id' => 'form_nome',
                'nome' => 'titulo',
                'nomeidioma' => 'form_nome',
                'tipo' => 'input',
                'valor' => 'titulo',
                'validacao' => array('required' => 'nome_vazio'),
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'form_nome_link',
                'nome' => 'link_1',
                'nomeidioma' => 'form_nome_link',
                'tipo' => 'input',
                'valor' => 'link_1',
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true,
            ),
			array(
                'id' => 'form_nome_link_2',
                'nome' => 'link_2',
                'nomeidioma' => 'form_nome_link_2',
                'tipo' => 'input',
                'valor' => 'link_2',
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true,
			),
            array(
                'id' => 'descricao',
                'nome' => 'descricao',
                'nomeidioma' => 'form_descricao',
                'tipo' => 'text',
                'valor' => 'descricao',
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),

            array(
                'id' => 'idpasta',
                'nome' => 'idpasta',
                'nomeidioma' => 'form_pasta',
                'tipo' => 'select',
                'sql' => 'SELECT * FROM videotecas_pastas WHERE ativo=\'S\'',
                'sql_valor' => 'idpasta',
                'sql_label' =>'nome',
                'valor' => 'idpasta',
                'validacao' => array('required' => 'pasta_vazio'),
                'class' => 'span6',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'form_ativo_painel',
                'nome' => 'ativo_painel',
                'nomeidioma' => 'form_ativo_painel',
                'tipo' => 'select',
                'array' => 'ativo',
                'class' => 'span2',
                'valor' => 'ativo_painel',
                'validacao' => array('required' => 'ativo_vazio'),
                'ajudaidioma' => 'form_ativo_ajuda',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'arquivo',
                'nome' => 'arquivo_nome',
                'tipo' => 'hidden',
                'valor' => 'return videoteca::getFile((int) str_replace("/gestor/academico/videoteca/", "", $_SERVER["REQUEST_URI"]));',
                'banco' => true,
                'banco_string' => true
            ),
        )
    )
);