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
        // Load dependencies
        //------------------

        $this->lang->load('webfile_manager');

        // Load views
        //-----------

        $this->page->view_form('webfile_manager', NULL, lang('webfile_manager_app_name'));
    }

    public function assets(){
        $file = str_replace("webfile_manager/assets/","",uri_string());
        if (is_file(realpath(__DIR__."/".$file))) {
            header("Content-type: ".mime_content_type($file));
            $this->load->view($file);            
        }
    }
}
