<?php

    namespace clearos\apps\webfile_manager;
class Elfinder
{
    public $next_dir;
    public $prev_dir;
    public $dir_now;
    public $dir_nav = array();
    static $site_folder = array();
    static $user_dir; 

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
<<<<<<< HEAD
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
    public function setDir_nav(string $dir)
    {
        $this->dir_nav[] = $dir;
        $this->setDir_now();
    }

    /**
     * Get the value of dir_now
     */ 
    public function getDir_now()
    {
        return $this->dir_nav[$this->dir_now];
    }

    /**
     * Set the value of dir_now
     *
     * @return  self
     */ 
    public function setDir_now()
    {
        $keys = array_keys();
        $this->dir_now = end($keys);
    }
     /**
     * @param string
     * @return array
     */
    public static function subdir($path)
    {
        $sub_dirs = array();
        $sub_dirs = glob($path . '*' , GLOB_ONLYDIR);
        return $sub_dirs;
    }

    /**
     * Get the value of next_dir
     */ 
    public function getNext_dir()
    {
        return $this->next_dir;
    }

    /**
     * Get the value of prev_dir
     */ 
    public function getPrev_dir()
    {
        return $this->prev_dir;
    }

    /**
     * Set the value of next_dir
     *
     * @return  self
     */ 
    public function setNext_dir($next_dir)
    {
        $this->next_dir = $next_dir;
    }

    /**
     * Set the value of prev_dir
     *
     * @return  self
     */ 
    public function setPrev_dir($prev_dir)
    {
        $this->prev_dir = $prev_dir;
    }

    /**
     * Get the value of Current directory
     *
     */ 
    public function getCurrentDir(){
        return $this->dir_nav[$this->dir_now];
    }

    /**
     * Get the value of directory from dir_nav by index
     *
     */ 
    public function getDirByIndex(int $index = 0){
        return $this->dir_nav[$index];
    }

    /**
     * Get mime type of file
     * @param string $file
     * @return string
     * 
     */ 
    public function mimetypechecker(string $file){
        return mime_content_type($file);
    }
}
