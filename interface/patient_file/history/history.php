<?php

/**
 * View history of a patient.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("history.inc.php");
require_once("$srcdir/options.inc.php");

use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Core\Header;
use OpenEMR\Menu\PatientMenuRole;
use OpenEMR\OeUI\OemrUI;

?>
<html>
<head>
    <title><?php echo xlt("History"); ?></title>
    <?php Header::setupHeader('common'); ?>

<script>
$(function () {
    tabbify();
});
<?php
    require_once("$include_root/patient_file/erx_patient_portal_js.php"); // jQuery for popups for eRx and patient portal
?>
</script>

<?php require_once("$srcdir/options.js.php"); ?> <!-- Don't include inside of script tags. Include already have.-->

<style>
<?php
// This is for layout font size override.
$grparr = array();
getLayoutProperties('HIS', $grparr, 'grp_size');
if (!empty($grparr['']['grp_size'])) {
    $FONTSIZE = round($grparr['']['grp_size'] * 1.333333);
    $FONTSIZE = round($FONTSIZE * 0.0625, 2);
    ?>
/* Override font sizes in the theme. */
#HIS .groupname {
  font-size: <?php echo attr($FONTSIZE); ?>rem;
}
#HIS .label {
  font-size: <?php echo attr($FONTSIZE); ?>rem;
}
#HIS .data {
  font-size: <?php echo attr($FONTSIZE); ?>rem;
}
#HIS .data td {
  font-size: <?php echo attr($FONTSIZE); ?>rem;
}
<?php } ?>
</style>
    <style>
        .collapsible {
            background-color: #777;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
        }

        .active, .collapsible:hover {
            background-color: #555;
        }

        .content {
            padding: 0 18px;
            display: none;
            overflow: hidden;
            background-color: #f1f1f1;
        }
    </style>
<?php
$arrOeUiSettings = array(
    'heading_title' => xl('History and Lifestyle'),
    'include_patient_name' => true,
    'expandable' => false,
    'expandable_files' => array(),//all file names need suffix _xpd
    'action' => "",//conceal, reveal, search, reset, link or back
    'action_title' => "",
    'action_href' => "",//only for actions - reset, link or back
    'show_help_icon' => true,
    'help_file_name' => "history_dashboard_help.php"
);
$oemr_ui = new OemrUI($arrOeUiSettings);
?>
</head>
<body>

<div id="container_div" class="<?php echo $oemr_ui->oeContainer();?> mt-3">
    <div class="row">
        <div class="col-12">
            <?php
            if (AclMain::aclCheckCore('patients', 'med')) {
                $tmp = getPatientData($pid, "squad");
                if ($tmp['squad'] && ! AclMain::aclCheckCore('squads', $tmp['squad'])) {
                    echo "<p>(" . xlt('History not authorized') . ")</p>\n";
                    echo "</body>\n</html>\n";
                    exit();
                }
            } else {
                echo "<p>(" . xlt('History not authorized') . ")</p>\n";
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
    if (AclMain::aclCheckCore('patients', 'med', '', array('write','addonly'))) {?>
        <div class="row">
            <div class="col-sm-12">
                <?php require_once("$include_root/patient_file/summary/dashboard_header.php");?>
            </div>
        </div>
        <?php
        $list_id = "history"; // to indicate nav item is active, count and give correct id
        $menuPatient = new PatientMenuRole();
        $menuPatient->displayHorizNavBarMenu();
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group">
                    <a href="history_full.php" class="btn btn-primary btn-edit" onclick="top.restoreSession()">
                        <?php echo xlt("Edit");?>
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
                    <?php display_layout_tabs('HIS', $result, ($result2 ?? '')); ?>
                </ul>
                <div class="tabContainer">
                    <?php display_layout_tabs_data('HIS', $result, ($result2 ?? '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <button class="collapsible">Therapeutic Questions Results</button>
        <div class="col-sm-12 mb-5 formcontent" style="margin-top: 20px;">
            <?php
                require_once "../../forms/LBF/report.php";
                $formname = 'LBF_Therapeutic';
                $enc = sqlQuery("select encounter from forms where pid = ? and formdir = ? order by id desc limit 1", [$_SESSION['pid'], $formname]);
                lbf_report($_SESSION['pid'], $enc['encounter'], 2, 24, $formname);
            ?>
        </div>
    </div>
    <div class="row">
        <button class="collapsible">Mental Status Exam Results</button>
        <div class="col-sm-12 mb-5 formcontent" style="margin-top: 20px;">
            <?php
                $statsFormName = 'LBF_MentalStatusExam';
                $enc = sqlQuery("select encounter from forms where pid = ? and formdir = ? order by id desc limit 1", [$_SESSION['pid'], $formname]);
                lbf_report($_SESSION['pid'], $enc['encounter'], 2, 25, $statsFormName);
            ?>
        </div>
    </div>
</div><!--end of container div -->
<?php $oemr_ui->oeBelowContainerDiv();?>
<script>
    var listId = '#' + <?php echo js_escape($list_id); ?>;
    $(function () {
        $(listId).addClass("active");
    });
</script>
<script>
    // Array of skip conditions for the checkSkipConditions() function.
    var skipArray = [<?php echo !empty($condition_str) ? js_escape($condition_str) : ''; ?>];
    checkSkipConditions();
</script>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
</script>
</body>
</html>
