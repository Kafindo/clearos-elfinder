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
        while ($item = readdir($folder)) {
            if (is_dir($site_folder_path . $item) && ($item != ".") && ($item != ".."))
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
            $user_dir .= get_current_user() . '/';
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
        $sub_dirs = glob($path . '*', GLOB_ONLYDIR);
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

    public function getCurrentDir()
    {
        return $this->dir_nav[$this->dir_now];
    }

    /**
     * Get the value of directory from dir_nav by index
     *
     */
    public function getDirByIndex(int $index = NULL)
    {
        return $this->dir_nav[$index];
    }

    /**
     * Get mime type of file
     * @param string $file
     * @return string
     *
     */ 

    public function mimetypechecker(string $file)
    {
        return mime_content_type($file);
    }

    /**
     * @param $path
     * @return array|bool
     */
    public static function get_properties($path)
    {
        $infos = array();
        if (is_dir($path)) {
            $file = new \SplFileInfo($path);
            $infos = array(
                'folder_name' => $file->getFilename(),
                'size' => $file->getSize(),
                'owner' => $file->getOwner(),
                'permission' => $file->getPerms()
            );
            return $infos;
        } else if (is_file($path)) {
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
        } else {
            return false;
        }
    }


    /**
     * Check the file permissions
     *
     * @param string $file
     * @return array
     */
    public function fileperms(string $file)
    {
        $perm = substr(sprintf("%o", fileperms("test.txt")), -3);
        $check = ["READ", "WRITE", "EXECUTE"];
        $return = NULL;
        if ($perm[0] == 7) {
            $return = $check;
        } elseif ($perm[0] == 6) {
            $return = [$check[0], $check["1"]];
        } elseif ($perm[0] = 5) {
            $return = [$check[0], $check[2]];
        } elseif ($perm[0] == 4) {
            $return = $check[0];
        } elseif ($perm[0] == 3) {
            $return = [$check[1], $check[2]];
        } elseif ($perm[0] == 2) {
            $return = [$check[1]];
        } elseif ($perm[0] == 1) {
            $return = [$check[2]];
        } else {
            $return = null;
        }
        return $return;

    }

    public static function volumes()
    {
        $ouutput = array();
        exec('fdisk -l', $ouutput);
        return $ouutput;
    }

    /**
     * Open a directory
     *
     * @param string $path
     * @return array|bool
     */
    public static function open_dir(string $path)
    {
        if (is_dir($path)) {
            $directory = opendir($path);
            $contents = array();
            while ($item = readdir($directory)) {
                if (($item != ".") && ($item != ".."))
                    $contents[] = $item;
            }
            return $contents;
        } else {
            return false;
        }
    }
    
    /**
     * upload a file
     *
     * @return bool
     */
    function upload_file()
    {
        $target_dir = "/root/";
        if (get_current_user() != root)
            $target_dir = "/home/" . get_current_user() . "/uploads/";
        // Load helper
        // -----------
        $this->load->helper(array('form', 'url'));

        exec("sudo chmod -R 777 " . $target_dir);

        // Setup configuration
        // -------------------
        $config['upload_path'] = $target_dir;
        $config['allowed_types'] = '*';
        //$config['max_size']      = 10000;

        // Loading library
        // ---------------
        $this->load->library('upload', $config);

        // Trying upload
        // -------------
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            return false;
        } else {
            $data = array('upload_data' => $this->upload->data());
            return true;
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
}
