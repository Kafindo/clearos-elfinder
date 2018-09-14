<?php

/**
 * CLEAROS Web File Manager controller.
 *
 * @category   Apps
 * @package    CLEAROS_Web_File_Manager
 * @subpackage Views
 * @author     Your name <your@e-mail>
 * @copyright  2013 Your name / Company
 * @license    Your license
 */

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////
use clearos\apps\webfile_manager\Elfinder as Elfinder;
/**
 * CLEAROS Web File Manager controller.
 *
 * @category   Apps
 * @package    CLEAROS_Web_File_Manager
 * @subpackage Controllers
 * @author     Your name <your@e-mail>
 * @copyright  2013 Your name / Company
 * @license    Your license
 */

class Webfile_manager extends ClearOS_Controller
{
    /**
     * CLEAROS Web File Manager default controller.
     *
     * @return view
     */

     function __construct()
     {
        parent::__construct();         
        $this->load->library('webfile_manager/Elfinder');
     }

    function index()
    {
        Elfinder::setUser_dir();
        var_dump(Elfinder::getUser_dir());
        $this->page->view_form('webfile_manager', NULL, lang('webfile_manager_app_name'));
    }

    public function execute(string $cmd=null, array $params = null){
    
        $this->load->library('webfile_manager/Elfinder');
        $elfinder= new Elfinder();
        $classRef = new ReflectionClass($elfinder);
        $methodR=$classRef->getMethod($cmd);

        if ($methodR->isStatic()){
           if( is_object(Elfinder::$cmd())){
               $recup=Elfinder::$cmd();
               var_dump($recup);
           }else{
               Elfinder::$cmd();
               echo("Not return static function ");
           }
        }else{
            if( is_object($elfinder->$cmd())){
               $recup=$elfinder->$cmd();
                var_dump($recup);
            }else{
                $elfinder->$cmd();
                echo("Not return function");
            }
        }
    }
    public function assets(){
        $this->load->helper('file');        
        $file = str_replace("webfile_manager/assets/","",uri_string());
        if (is_file(realpath(__DIR__."/../views/".$file))) {
            header("Content-type: ".get_mime_by_extension($file));
            $this->load->view($file);            
        }else{
            show_404();
        }
    }
}
