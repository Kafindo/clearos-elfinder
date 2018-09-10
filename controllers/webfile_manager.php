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

    function index()
    {
        //

        // Load dependencies
        //------------------

        $this->lang->load('webfile_manager');
        $this->load->library('webfile_manager/Elfinder');

        // Action table
        //------------pn

        $data['actions'] = $this->Elfinder->getActions();
        // Load views
        //-----------

        $this->page->view_form('webfile_manager', $data, lang('webfile_manager_app_name'));
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
