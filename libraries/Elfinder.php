<?php

    namespace clearos\apps\webfile_manager;
class Elfinder
{
    static $site_folder = array();

    public function __construct()
    {

    }

    /**
     * @param array $site_folder
     */
    public static function setSiteFolder()
    {

        $site_folder_path = "/var/www/virtual/";
        $site_folders = array();

        $folder = opendir($site_folder_path);
        while($item = readdir($folder)) {
            if(is_dir($site_folder_path.$item) && ($item != ".") && ($item != ".."))
                $site_folders[] = $item;
        }
        self::$site_folder = $site_folders;
    }

    /**
     * @return array
     */
    public static function getSiteFolder()
    {
        return self::$site_folder;
    }

    /**
     *
     * @param string
     * @return array
     */
    public static function subdir($path)
    {
        $sub_dirs = array();
        $sub_dirs = glob($path . '*' , GLOB_ONLYDIR);
        return $sub_dirs;
    }
}
