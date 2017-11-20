<?php

$fileName = $_GET['file'];
$fileURL = "ftp://indmind@localhost/$fileName";
$fileSize = filesize($fileURL);

header("Content-Type: application/octet-stream");
header("Content-Length: $fileSize");
header("Content-Transfer-Encoding: Binary");
header('Content-Disposition: attachment; filename="'.$fileName.'"');

readfile($fileURL);
