<ul class="nav nav-tabs">
	<? if($url[3] == "cadastrar") { ?>
		<!-- <li class="active"><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/cadastrar"><?= $idioma["tab_cadastrar"]; ?></a></li> -->
	<? } else { ?>
		<li<? if($url[4] == "editar") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/editar"><?= $idioma["tab_editar"]; ?></a></li>
				<li<? if($url[4] == "email_sindicatos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/email_sindicatos"><?= $idioma["tab_associar_sindicato"]; ?></a></li>
		<li<? if($url[4] == "email_cursos") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/email_cursos"><?= $idioma["tab_associar_curso"]; ?></a></li>
		<li<? if($url[4] == "email_ofertas") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/email_ofertas"><?= $idioma["tab_associar_oferta"]; ?></a></li>
		<li<? if($url[4] == "remover") { ?> class="active"<? } ?>><a href="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/<?= $url[3]; ?>/remover"><?= $idioma["tab_remover"]; ?></a></li>               
	<? } ?>
</ul>