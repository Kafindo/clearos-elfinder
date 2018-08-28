<?php

    namespace clearos\apps\webfile_manager;
class Elfinder
{
    public $dir_nav = array();
    static $site_folder = array();
    static  $user_dir;

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
     * Get the value of user_dir
     */ 
    public static function getUser_dir()
    {
        return self::$user_dir;
    }

    /**
     * Set the value of user_dir
     *
     * @return  self
     */ 
    public static function setUser_dir()
    {
        $user_dir = "/home/";
        $u = posix_getpwnam(get_current_user());
        if ($u == FALSE) {
            $user_dir = "../files/";
        } elseif ($u["uid"] == 1000) {
            $user_dir = "/home/";
        } else {
            $user_dir .= get_current_user().'/';
        }
        self::$user_dir = realpath(self::$user_dir);
    }

    /**
     * Get the value of dir_nav
     */ 
    public function getDir_nav()
    {
        return $this->dir_nav;
    }

    /**
     * Set the value of dir_nav
     *
     * @return  self
     */ 
    public function setDir_nav($dir)
    {
        $this->dir_nav[] = $dir;
    }
}
