<?php
error_reporting(E_ALL);

class FTP_client{
    // set variabel
    private $connectionID;
    private $loginOk = false;
    private $messageArray = array();

    public function connect ($_server, $_ftpUser, $_ftpPassword, $_isPassive = false){
        // set dan cek koneksi
        if($this->connectionID = ftp_connect($_server)){
            // login 
            $loginResult = ftp_login($this->connectionID, $_ftpUser, $_ftpPassword);
            
            // set passive mode (default off/false)
            ftp_pasv($this->connectionID, $_isPassive);

            // cek koneksi
            if(!$this->connectionID || !$loginResult){
                $this->logMessage('Koneksi ke FTP gagal!');
                $this->logMessage('Attempted to connect to ' . $_server . ' for user ' . $_ftpUser, true);
                return false;
            }else{
                $this->logMessage('Terhubung ke ' . $_server . ', dengan user ' . $_ftpUser);
                $this->loginOk = true;
                return true;
            }
        }else{
            $this->logMessage("Gagal koneksi ke FTP");
        }
    }

    public function uploadFile($_fileFrom, $_fileTo){
        // set transfer mode
        $asciiArray = array('txt', 'csv');
        $extension = explode('.', $_fileFrom["name"]);

        // fix error terpisah
        $extension = end($extension);

        // mode default
        $mode = FTP_BINARY;
        // mode ASCII
        if (in_array($extension, $asciiArray)) {
            $mode = FTP_ASCII;      
        }

        // upload file
        $upload = ftp_put($this->connectionID, $_fileTo, $_fileFrom["tmp_name"], $mode);

        // cek upload status
        if(!$upload){
            $this->logMessage('FTP upload has failed!');
            return false;
        }else{
            $this->logMessage('Berhasil upload: "' . $_fileFrom["name"] . '" ke server FTP sebagai: "' . $_fileTo.'"');
            return true;
        }
    }

    public function makeDir($_directory)
    {
        // mencoba membuat directory
        if(ftp_mkdir($this->connectionID, $_directory)){
            $this->logMessage('Directory "' . $_directory . '" created successfully');
            return true;
        } else {
            // gagal
            $this->logMessage('Failed creating directory "' . $_directory . '"');
            return false;
        }
    }

    public function changeDir($directory){
        if(ftp_chdir($this->connectionID, $directory)){
            return true;
        }else{ 
            $this->logMessage('Couldn\'t change directory');
            return false;
        }
    }

    public function pwd(){
        return ftp_pwd($this->connectionID);
    }

    public function getDirListing($_par = '-la'){
        // listing current dir
        return ftp_nlist($this->connectionID, $_par.' '.$this->pwd());
    }

    public function downloadFile($_fileFrom, $_fileTo){
        // set transfer mode
        $asciiArray = array('txt', 'csv');
        $extension = explode('.', $_fileFrom["name"]);

        // fix error terpisah
        $extension = end($extension);
        
        // mode default
        $mode = FTP_BINARY;
        // mode ASCII
        if (in_array($extension, $asciiArray)) {
            $mode = FTP_ASCII;      
        }

        // try to download from ftp server
        if(ftp_get($this->connectionID, $_fileTo, $_fileFrom, $mode, 0)){
            $this->logMessage('Berhasil download file');
            return true;
        }else{
            $this->logMessage('Gagal download file');
            return false;
        }
    }

    public function close(){
        return ftp_close($this->connectionID);
    }

    public function logMessage($_mess){
        $this->messageArray[] = $_mess;   
    }

    public function getMessages(){
        return $this->messageArray;
    }
}