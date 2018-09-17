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
        //Elfinder::setUser_dir();        
        $this->page->view_form('webfile_manager', NULL, lang('webfile_manager_app_name'));
    }

    public function execute(string $cmd=null, array $params = null){
    
        $this->load->library('webfile_manager/Elfinder');
        $elfinder= new Elfinder();
        $classRef = new ReflectionClass($elfinder);
        $methodR=$classRef->getMethod($cmd);
    
        if ($methodR->isStatic()==true){
            $recup=Elfinder::$cmd($params);
            echo $recup;
        }else{
            $recup=$elfinder->$cmd($params);
            echo $recup;          
        }
        
        return null;
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
