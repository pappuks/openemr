<?php
/**
 * View history of a patient.
 *
 * @license https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */



require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("history.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.js.php");

use OpenEMR\Core\Header;
use OpenEMR\Menu\PatientMenuRole;

?>
<html>
<head>
    <title><?php echo xl("History"); ?></title>
    <?php Header::setupHeader('common'); ?>

<script type="text/javascript">
$(document).ready(function(){
    tabbify();
});
<?php require_once("$include_root/patient_file/erx_patient_portal_js.php"); // jQuery for popups for eRx and patient portal ?>
</script>

<style type="text/css">
<?php
// This is for layout font size override.
$grparr = array();
getLayoutProperties('HIS', $grparr, 'grp_size');
if (!empty($grparr['']['grp_size'])) {
    $FONTSIZE = $grparr['']['grp_size'];
?>
/* Override font sizes in the theme. */
#HIS .groupname {
  font-size: <?php echo attr($FONTSIZE); ?>pt;
}
#HIS .label {
  font-size: <?php echo attr($FONTSIZE); ?>pt;
}
#HIS .data {
  font-size: <?php echo attr($FONTSIZE); ?>pt;
}
#HIS .data td {
  font-size: <?php echo attr($FONTSIZE); ?>pt;
}
<?php } ?>
</style>
</head>
<body class="body_top">

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (acl_check('patients', 'med')) {
                $tmp = getPatientData($pid, "squad");
                if ($tmp['squad'] && ! acl_check('squads', $tmp['squad'])) {
                    echo "<p>(".htmlspecialchars(xl('History not authorized'), ENT_NOQUOTES).")</p>\n";
                    echo "</body>\n</html>\n";
                    exit();
                }
            } else {
                echo "<p>(".htmlspecialchars(xl('History not authorized'), ENT_NOQUOTES).")</p>\n";
                echo "</body>\n</html>\n";
                exit();
            }

            $result = getHistoryData($pid);
            if (!is_array($result)) {
                newHistoryData($pid);
                $result = getHistoryData($pid);
            }
            ?>
        </div>
    </div>
    <?php
    if (acl_check('patients', 'med', '', array('write','addonly'))) {
        $header_title = xl('History and Lifestyle of');?>
        <div class="row">
            <div class="col-sm-12">
                <?php
                //require_once("../summary/dashboard_header.php");
                require_once("$include_root/patient_file/summary/dashboard_header.php");
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?php
                $list_id = "nav-list2"; // to indicate nav item is active, count and give correct id
                $menuPatient = new PatientMenuRole();
                $menuPatient->displayHorizNavBarMenu();
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group">
                    <a href="history_full.php" class="btn btn-default btn-edit" onclick="top.restoreSession()">
                        <?php echo htmlspecialchars(xl("Edit"), ENT_NOQUOTES);?>
                    </a>
                </div>
            </div>
        </div>
    <?php
    } ?>
    <div class="row"> 
        <div class="col-sm-12" style="margin-top: 20px;">
            <!-- Demographics -->
            <div id="HIS">
                <ul class="tabNav">
                    <?php display_layout_tabs('HIS', $result, $result2); ?>
                </ul>
                <div class="tabContainer">
                    <?php display_layout_tabs_data('HIS', $result, $result2); ?>
                </div>
            </div>
        </div>
    </div>
    
</div><!--end of container div -->
<?php
//home of the help modal ;)
//$GLOBALS['enable_help'] = 0; // Please comment out line if you want help modal to function on this page
if ($GLOBALS['enable_help'] == 1) {
    echo "<script>var helpFile = 'history_dashboard_help.php'</script>";
    require "$include_root/help_modal.php";
}
?>


<script type="text/javascript">
    // Array of skip conditions for the checkSkipConditions() function.
    var skipArray = [
        <?php echo $condition_str; ?>
    ];
    checkSkipConditions();
    
    var listId = '#' + '<?php echo text($list_id); ?>';
    $(document).ready(function(){
        $(listId).addClass("active");
    });

</script>

</body>
</html>
