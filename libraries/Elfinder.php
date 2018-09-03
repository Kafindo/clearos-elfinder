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
    static $ERRORS = array(); 

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
    public function getDirByIndex(int $index = NULL){
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

    /**
     *
     *
     */
    public static function get_properties($path)
    {
        $infos = array();
        if(is_dir($path))
        {
            $file = new \SplFileInfo($path);
            $infos = array(
                'folder_name' => $file->getFilename(),
                'size' => $file->getSize(),
                'owner' => $file->getOwner(),
                'permission' => $file->getPerms()
            );
            return $infos;
        }
        else if (is_file($path))
        {
            $file = new \SplFileInfo($path);
            $infos = array('file_name' => $file->getFilename(),
                'file_size' => $file->getSize(),
                'last_access_time' => $file->getATime(),
                'last_modified_time' => $file->getMTime(),
                'type' => $file->getType(),
                'owner' => $file->getOwner(),
                'permission' => $file->getPerms()
            );
            return $infos;
        }
        else
        {
            return false;
        }
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
        $return = NULL;
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


    public function zipdl($folder){
        $files = $this->subdir($folder);
        $p = explode("/", $folder);
        $p = end($p);
        $zipname = $p.'.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFile($file);
        }
        $zip->close();
        return $zipname;

        //TODO: Add this on views
        // header('Content-Type: application/zip');
        // header("Content-Disposition: attachment; filename=".$zipname);
        // header('Content-Length: ' . filesize($zipname));
        // header("Location: ".$zipname);
    }

    public static function volumes()
    {
        $ouutput = array();
        exec('fdisk -l',$ouutput);
        return $ouutput;
    }


    public static function open_dir(string $path)
    {
        if(is_dir($path))
        {
            $directory = opendir($path);
            $contents = array();
            while($item = readdir($directory)) {
                if(($item != ".") && ($item != ".."))
                    $contents[] = $item;
            }
            return $contents;
        }
        else
        {
            return false;
        }
    }

    public function chmod (string $filename ,int $mode){
        return chmod ($filename , $mode);
    }

    public function mkdir(string $dirName, $rights = 0777){
        $dirs = explode('/', $dirName);
        $dir='';
        if (is_array($dirs)) {
            foreach ($dirs as $part) {
                $dir.=$part.'/';
                if (!is_dir($dir) && strlen($dir)>0)
                    mkdir($dir, $rights);
            }
        } else {
            mkdir($dir, $rights);
        }
    }

    public function mkfile(string $file, string $path = null){
        if ($path != null && is_dir($path)) {
            if (!is_file(realpath($path."/".$file))) {
                $handle = fopen(realpath($path."/".$file), 'w');
                $v = true;
            }else{
                $v = false;
            }
        } else {
            $handle = fopen(realpath($path."/".$file), 'w');
            $v = true;
        }
        fclose($handle);
        return $v;
    }

    public function rename (string $oldname ,string $newname){
        if (is_file($newname)) {
            return false;
        } elseif(is_dir($newname)){
            return null;
        }else {
            rename($oldname, $newname);
            return true;
        }
        
    }

    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    function unlink ($filename) {
        if (is_link ($filename)) {
            $sym = @readlink ($filename);
            if ( $sym ) {
                return is_writable ($filename) && @unlink ($filename);
            }
        }
    
        if ( realpath ($filename) && realpath ($filename) !== $filename ) {
            return is_writable ($filename) && @unlink (realpath ($filename));
        }
    
        return is_writable ($filename) && @unlink ($filename);
    }


    /**
     * Get the value of ERRORS
     */ 
    public function getERRORS()
    {
        return $this->ERRORS;
    }

    /**
     * Set the value of ERRORS
     *
     * @return  self
     */ 
    public function setERRORS($ERRORS)
    {
        $this->ERRORS = $ERRORS;
    }
}
