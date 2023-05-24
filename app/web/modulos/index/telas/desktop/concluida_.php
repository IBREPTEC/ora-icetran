<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/2000/svg">
<head>
    <?php incluirLib('head', $config, $usuario); ?>
</head>
<body>
    <?php incluirLib('topo', $config, $usuario); ?>
    <?php incluirTela('inc_passos', $config, $usuario); ?>

    <div class="container mt50">
        <?php incluirTela('inc_curso', $config, $curso); ?>

        <div class="col-sm-8 itemForm">
            <h3><?= $idioma['agradecimento'] ?></h3>
            <p><?php printf($idioma['compra_concluida'], $usuario['nome']); ?></p>
            <p><span><?= $idioma['numero_matricula']; ?> <?= $idmatricula; ?></span></p>
            <p><span><?= $idioma['curso']; ?></span> <?= $curso['nome']; ?></p>
            <a href="/aluno" target="_blank" class="btBox verde"><?= $idioma['comecar_estudar']; ?></a>
        </div>
    </div>

    <?php incluirLib('rodape', $config, $usuario); ?>
</body>
</html>
