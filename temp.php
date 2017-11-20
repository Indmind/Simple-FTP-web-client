<?php

require("./ftp_cred.php");

$return = [
    "ok" => false,
    "connected" => false
];

if(isset($_POST['action'])){
    switch($_POST['action']){
        case "showDir":
            //showDir();    
        break;
    }
}

// konek ke ftp
if($ftp->connect(FTP_HOST, FTP_USER, FTP_PASS)){
    $return->connected = true;
}

// function showDir(){
//     // mendapt list file dari ftp
//     $data = $ftp->getList();

//     // $data = [
//     //     "ok" => true,
//     //     'dir' => '/',
//     //     'list' => [
//     //         [
//     //             "name" => "hello  world"
//     //         ],
//     //         [
//     //             "name" => "hello  bro"
//     //         ],
//     //         [
//     //             "name" => "anjay ahay  world"
//     //         ],
//     //         [
//     //             "name" => "mak mak world"
//     //         ],
//     //     ],
//     // ];
//     turn($data);
// }

function turn($data){
    echo json_encode($data ?: $return);
}
turn($return);
