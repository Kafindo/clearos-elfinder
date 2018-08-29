<?php

    namespace clearos\apps\webfile_manager;
class Elfinder
{
    public $dir_now;
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
        return $this->dir_now;
    }

    /**
     * Set the value of dir_now
     *
     * @return  self
     */ 
    public function setDir_now()
    {
        $this->dir_now = end($this->dir_nav);
    }


    /**
     * Check the file permissions
     *
     * @param string $file
     * @return array
     */
    public function fileperms(string $file){
        $perm = substr(sprintf("%o",fileperms("test.txt")),-3);
        $check = ["READ","WRITE","EXECUTE"];
        $return;
        if($perm[0] == 7){
            $return = $check;
        }elseif ($perm[0] == 6) {
            $return = [$check[0],$check["1"]];
        }elseif ($perm[0] = 5) {
            $return = [$check[0],$check[2]];
        }elseif ($perm[0] == 4) {
            $return = $check[0];
        }elseif ($perm[0] == 3) {
            $return = [$check[1], $check[2]];
        }elseif ($perm[0] == 2) {
            $return = [$check[1]];
        }elseif ($perm[0] == 1) {
            $return = [$check[2]];
        }else {
            $return = null;
        }
        return $return;

    }

    public function archivExtract($file){
        $ext = pathinfo($file,PATHINFO_EXTENSION);
        if ($ext == "rar") {
            $archive = RarArchive::open($file);
            if ($archive === false) return;
            $entries = $archive->getEntries();
            if ($entries === false) return;
            $archive->close();
            return (array) $entries;
        } elseif($ext == "tar"){
            try {
                $archive = new PharData($file);
            }
            catch (UnexpectedValueException $e) {
                return;
            }
            if ($archive->count() === 0) return;
            return $archive;
        } elseif($ext == "zip"){
            $archive = new ZipArchive;
            $valid = $archive->open($file);
            if ($valid !== true) return;
            if ($archive->numFiles === 0) return;
            return (array) $archive;
            $archive->close();
        }else {
            return;
        }
        
    }
}
