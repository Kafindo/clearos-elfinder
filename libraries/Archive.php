<?php

namespace clearos\apps\webfile_manager;
require_once "vendor/autoload.php";
use \wapmorgan\UnifiedArchive\UnifiedArchive as UnifiedArchive;
class Archive extends UnifiedArchive
{
    function __construct($fileName, $type)
    {
        parent::__construct($fileName, $type);
    }
}