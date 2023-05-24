<?php
if ($documentos) {
    $zip = new ZipArchive();
    $diretorio_zip = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_documentos/zipados/{$url['3']}.zip";
    $zip->open($diretorio_zip, ZipArchive::CREATE || ZipArchive::OVERWRITE);
    foreach ($documentos as $documento) {
        $diretorio_arquivo = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_documentos/" . $documento["arquivo_pasta"] . "/" . $documento["idmatricula"] . "/" . $documento["arquivo_servidor"];
        $zip->addFile($diretorio_arquivo, $documento["arquivo_servidor"]);
    }
    $zip->close();
    header("Content-Type: application/zip");
    header("Content-Length: " . filesize("{$url['3']}"));
    header("Content-Disposition: attachment; filename=" . basename("{$url['3']}.zip"));
    readfile($diretorio_zip);
} else {
    echo "<script>
    const alerta = alert('Matrícula não tem nenhum documento aprovado para baixar!')
    if(typeof alerta === 'undefined'){
    window.location.href=  'telas/{$config["tela_padrao"]}/index.php';
    }
    </script>";
}
