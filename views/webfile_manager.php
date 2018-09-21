<link rel="stylesheet" href=" <?php echo base_url('webfile_manager'); ?>/assets/css/style.css"/>
<script type="text/javascript" src="<?php echo base_url('webfile_manager'); ?>/assets/js/navigation.js"></script>
<link href=" <?php echo base_url('webfile_manager'); ?>/assets/css/ui.fancytree.css" rel="stylesheet">
  <script src=" <?php echo base_url('webfile_manager'); ?>/assets/js/jquery.fancytree-all-deps.js"></script>
  <!-- Initialize the tree when page is loaded -->
  <script type="text/javascript">
    $(function(){  // on page loaded
      // Create the tree inside the <div id="tree"> element.
      $("#tree").fancytree({
  source: [
    {title: "Node 1", key: "1"},
    {title: "Folder 2", key: "2", folder: true, children: [
      {title: "Node 2.1", key: "3"},
      {title: "Node 2.2", key: "4"}
    ]}
  ]
    });
  });
  </script>
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
echo row_open();
echo "<p id='navigation_url'></p>";
echo '<div id="top-nav">';
foreach ($actions as $action) {
    echo '<a title="' . $action['title'] . '"><img src="' . base_url("webfile_manager") . '/assets/img/icons/' . $action['icon'] . '" width="25px"/> </a>';
}
  echo form_input('search',null,'placeholder = "Search"');
echo '</div>';
echo row_close();
echo "<div class='col-sm-2 col-md-2 col-lg-2 col-xs-2' id='tree' role='menu'>
          </div>";
echo "<div class='col-sm-10 col-md-10 col-lg-10 col-xs-10' id='main-content'>

      </div>";?>
<link rel="stylesheet" href=" <?php echo base_url('webfile_manager'); ?>/assets/css/style.css"/>
<script src=" <?php echo base_url('webfile_manager'); ?>/assets/js/tmbIcon.js"></script>
<script src=" <?php echo base_url('webfile_manager'); ?>/assets/js/path_url.js"></script>
<script src=" <?php echo base_url('webfile_manager'); ?>/assets/js/user_dir.js"></script>
