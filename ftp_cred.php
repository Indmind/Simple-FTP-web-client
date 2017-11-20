<?php
define("FTP_HOST", '127.0.0.1');
define("FTP_USER", 'indmind');
define("FTP_PASS", 'infiniteLoop');

include('./ftp_class.php');

// membuat objek FTP_client
$ftp = new FTP_client();
