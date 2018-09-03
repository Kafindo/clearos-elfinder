<?php

namespace clearos\apps\webfile_manager;
include_once("vendor/autoload.php");
use \FtpClient\FtpClient as FtpClient;

class FTP extends FtpClient
{
    public function __construct($connection = null){
        parent::__construct($connection);
    }
}