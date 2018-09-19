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
    static $TMP_DIR;
    public $actions = array(
    'copy' => array('icon' => 'copy.png', 'title' => 'Copy'),
    'paste' => array('icon' => 'paste.png', 'title' => 'Paste'),
    'previous' => array('icon' => 'previous.png','title' => 'Previous'),
    'next' => array('icon' => 'next.png', 'title' => 'Next'),
    'parent_folder' => array('icon' => 'parent_folder.png','title' => 'Parent folder'),
    'upload' => array('icon' => 'upload.png', 'title' => 'Upload'),
    'download' => array('icon' => 'download.png', 'title' => 'Download'),
    'extract' => array('icon' => 'extract.png', 'title' => 'Extract'),
    'compress' => array('icon' => 'compress.png', 'title' => 'Compress'),
    'download_compress' => array('icon' => 'download_compress.png', 'title' => 'Download And compress')
    );
    public $command = array( 'copy', 'paste','previous','next','parent_folder','download','upload','setSiteFolder',
    'getSiteFolder','getUser_dir','setUser_dir','getDir_nav','setDir_nav','getDir_now','setDir_now','subdir','getNext_dir',
    'getPrev_dir','setNext_dir','setPrev_dir','getCurrentDir','getDirByIndex','mimetypechecker','get_properties','volumes',
    'open_dir','upload_file','fileperms','zipdl','chmod','mkfile','rename','deleteDir','unlink','getERRORS','setERRORS',
    'getTMP_DIR','setTMP_DIR','tmbIcon','getActions','setActions','extract','compress','wnload_compressdo');
    
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
     * Get return Directory to Json Format
     * 
     */
    public static function getUser_dirJson(){
        $t=self::setUser_dir();
        $elfinder= new Elfinder();
        return json_encode($elfinder->open_dir2(array( self::getUser_dir())));
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
        } elseif ($u["uid"] == 0) {
            $user_dir = "/home/";
        } else {
            $user_dir .= '/'.get_current_user() . '/';
        }
        self::$user_dir = realpath($user_dir);
    
        return null;
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
    public function setDir_nav(array $params)
    {
        $dir = $params[0];
        $this->dir_nav[] = $dir;
        $this->setDir_now();
        return null;
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
        return null;
    }

    /**
     * @param string
     * @return array
     */
    public static function subdir(array $params)
    {
        $path = $params[0];
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
     *@param string $next_dir
     */
    public function setNext_dir(array $params)
    {
        $next_dir = $params[0];
        $this->next_dir = $next_dir;
        return null;
    }

    /**
     * Set the value of prev_dir
     *@param string $prev_dir
     */
    public function setPrev_dir(array $params)
    {
        $prev_dir = $params[0];
        $this->prev_dir = $prev_dir;
        return null;
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
    public static function get_properties(array $params)
    {
        $path = $params[0];
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
//    public function fileperms(string $file)
//    {
//        $perm = substr(sprintf("%o", fileperms("test.txt")), -3);
//        $check = ["READ", "WRITE", "EXECUTE"];
//        $return = NULL;
//        if ($perm[0] == 7) {
//            $return = $check;
//        } elseif ($perm[0] == 6) {
//            $return = [$check[0], $check["1"]];
//        } elseif ($perm[0] = 5) {
//            $return = [$check[0], $check[2]];
//        } elseif ($perm[0] == 4) {
//            $return = $check[0];
//        } elseif ($perm[0] == 3) {
//            $return = [$check[1], $check[2]];
//        } elseif ($perm[0] == 2) {
//            $return = [$check[1]];
//        } elseif ($perm[0] == 1) {
//            $return = [$check[2]];
//        } else {
//            $return = null;
//        }
//        return $return;
//
//    }

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
    public static function open_dir(array $params)
    {
        $path = $params[0];
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
    public static function open_dir2(array $params){
        $path=$params[0];
        if(is_dir ($path)){
            $file_folder=opendir($path);
            $contents=array();
            while($item =readdir($file_folder)){
                if (($item != ".") && ($item != ".."))
                {
                    if(is_dir($path."/".$item)){
                        $contents[]=array($item,"d");//d for directory
                    }else{
                        $contents[]=array($item,"f");// f for file
                    }
                }
            }
            
            return $contents;
        }else
        {
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
    public function fileperms(array $params){
        $file = $params[0];
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

    /**
     * @param string $folder
     * @return string
     */
    public function zipdl(array $params){
        $folder = $params[0];
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



    public function chmod (array $params = []){
        if(isset($params["filename"])){
            if (!isset($params['mode']))
                $params['mode'] = 777;
            elseif (!is_integer($params['mode']))
                $params['mode'] = 777;
            return chmod($params['filename'], $params['mode']);
        }else{
            return false;
        }
    }

    public function mkdir(array $params){
        $dirs = $params[0];
        $rights = 777;
        if(isset($params[1]))
            $rights = $params[1];
        if (!is_int($rights))
            $rights = 777;
        $dirs = explode('/', $dirName);
        $dir='';
        if (is_array($dirs)) {
            foreach ($dirs as $part) {
                $dir.=$part.'/';
                if (!is_dir($dir) && strlen($dir)>0)
                    return mkdir($dir, $rights);
            }
        } else {
           return mkdir($dirs, $rights);
        }
    }

    public function mkfile(array $params){
        $file = $params[0];
        $path = isset($params[1]) ? $params[1] : null;
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

    public function rename(array $params){
        if (!isset($params[0], $params[1]))
            return false; 
        $oldname = $params[0];
        $newname = $params[1];
        if (is_file($newname)) {
            return false;
        } elseif(is_dir($newname)){
            return null;
        }else {
            rename($oldname, $newname);
            return true;
        }
        
    }

    public static function deleteDir(array $params) {
        $dirPath = $params[0];
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

    function unlink (array $params) {
        $filename = $params[0];
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

    /**
     * Get the value of TMP_DIR
     */ 
    public function getTMP_DIR()
    {
        return $this->TMP_DIR;
    }

    /**
     * Set the value of TMP_DIR
     *
     * @return  self
     */ 
    public function setTMP_DIR($TMP_DIR)
    {
        $this->TMP_DIR = $TMP_DIR;

        return $this;
    }

    /**
     * Set the value of TMB_ICON
     *
     * @param string $file
     * @return  string $img
     */
    public function tmbIcon(array $params){
        $file = $params[0];
        $img = "nonformat.png";
        if (is_dir($file)) {
            $img = "folder.png";
        } elseif (is_file($file) && (pathinfo($file, PATHINFO_EXTENSION) != "")) {
            $img = pathinfo($file, PATHINFO_EXTENSION).".png";
        }
        return $img;
    }

        /**
         * @return array
         */
        public function getActions()
        {
            return $this->actions;
        }

        /**
         * @param array $actions
         */
        public function setActions(array $params)
        {
            $action = $params[0];
            $this->actions[] = $action;
        }

        public static function search(string $keyword){
   
            $pp = shell_exec("find /");

        }
}
?>