<?php
require("./ftp_cred.php");
require("./ftp_handler.php");

session_start();

$return = [
    "ok" => false,
    "connected" => false
];

// never use this on public server
ini_set('memory_limit', '-1');

// konek ke ftp
if($ftp->connect(FTP_HOST, FTP_USER, FTP_PASS)){
    $return['connected'] = true;
}

if(isset($_POST['upload'])){
    // upload
    $ftp->uploadFile($_FILES["fileToUpload"], "/".$_POST['customName']);
    $_SESSION['info'] =  "Berhasil upload file: '".$_FILES["fileToUpload"]["name"]."'";
    header('Location: ./');
}

if(isset($_POST['action'])){
    if($_POST['action'] == 'showDir'){
        $return['processed'] = true;
        $return['dir'] = $ftp->pwd();
        $return['ok'] = true;
        $return['list'] = $ftp->getDirListing();
        turn($return);
    }
}

if(isset($_GET['download'])){
    
    $fileName = str_replace("", "\\",$_GET['download']);
    
    //$ftp->downloadFile("/$fileName", $tempFile);

    header('Location: ./download.php?file='.$fileName);

    // Direct Download (not work)
    // header("Content-Type: application/octet-stream");
    // header("Content-Transfer-Encoding: Binary");
    // header("Content-disposition: attachment; filename=\"".$filePath."\"");
    // readfile($fileURL);
}

function turn($data){
    echo json_encode($data ?: $return);
}