<link rel="stylesheet" href=" <?php echo base_url('webfile_manager'); ?>/assets/css/style.css"/>
<?php

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

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('webfile_manager');

///////////////////////////////////////////////////////////////////////////////
// Form
///////////////////////////////////////////////////////////////////////////////
var_dump($actions);die;
echo row_open();
echo '<div id="top-nav">';
foreach ($actions as $action)
{
    echo '<a title="'.$actions.'"><img src="'.base_url("webfile_manager").'/assets/img/svg/'.$action['icon'].'" width="25px"/> </a>';
}

echo '</div>';
echo row_close();
echo "<div class='col-sm-2 col-md-2 col-lg-2 col-xs-2' id='left-menu' role='menu'>
             
          </div>";
echo "<div class='col-sm-10 col-md-10 col-lg-10 col-xs-10' id='main-content'>
        
      </div>";

