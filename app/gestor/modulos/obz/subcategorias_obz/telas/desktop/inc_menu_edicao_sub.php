<ul class="nav nav-tabs">
    <li <?php if ($url[4] == "editarsubcategoria") { ?> class="active"<?php } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editarsubcategoria"><?= $idioma["tab_editar"]; ?></a>
    </li>
    <li <?php if ($url[4] == "centro_custo") { ?> class="active"<?php } ?>><a
            href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/centro_custo"><?= $idioma["tab_centro"]; ?></a>
    </li>
</ul>