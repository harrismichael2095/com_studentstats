<?php
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/**
 * @package     Joomla.Site
 * @subpackage  com_studentstats
 *
 * @copyright   Copyright (C) 2022 Michael Harris. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>


<?php

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__attendance_reports');
$db->setQuery((string) $query);
$results = $db->loadAssocList();
$num_rows = count($results);

// Prevents divide by 0 error when there are no reports
if ($num_rows == 0)
    $num_rows = 1;



$groupId = 11;
$access = new JAccess();
$members = $access->getUsersByGroup($groupId);
$num_students = count($members);
$rows = '';
$users = [];
foreach ($members as $id) {
    $db = JFactory::getDbo();
    $user = JFactory::getUser($id);
    array_push($users, $user);

    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__attendance_reports');
    $query->where('JSON_CONTAINS(present,' . $db->quote($id) .')');
    $db->setQuery((string) $query);
    $results = $db->loadAssocList();
    $num_present = count($results);

    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__attendance_reports');
    $query->where('JSON_CONTAINS(absent,' . $db->quote($id) .')');
    $db->setQuery((string) $query);
    $results = $db->loadAssocList();
    $num_absent = count($results);

    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__attendance_reports');
    $query->where('JSON_CONTAINS(late,' . $db->quote($id) .')');
    $db->setQuery((string) $query);
    $results = $db->loadAssocList();
    $num_late = count($results);

    // Present percentage 
    $present_percent = number_format($num_present/$num_rows*100, 2, '.', "");

    // Absent percentage 
    $absent_percent = number_format($num_absent/$num_rows*100, 2, '.', "");

    // Late percentage
    $late_percent = number_format($num_late/$num_rows*100, 2, '.', "");

    $rows .= '<tr>';
    
    if($present_percent<60.00)
        {   $rows .= '<td  class = "table-danger">' . $user->name . '</td>';
            $rows .= '<td  class = "table-danger">' . $num_present. '</td>';
            $rows .= '<td  class = "table-danger">' . $num_absent.  '</td>';
            $rows .= '<td  class = "table-danger">' . $num_late.    '</td>';
            $rows .= '<td  class = "table-danger">' . $present_percent. '%</td>';
            $rows .= '<td  class = "table-danger">' . $absent_percent.  '%</td>';
            $rows .= '<td  class = "table-danger">' . $late_percent.    '%</td>';
            $rows .= '</tr>';  
        }
        else
        {
            $rows .= '<td class = "table-success">' . $user->name . '</td>';
            $rows .= '<td class = "table-success">' . $num_present. '</td>';
            $rows .= '<td class = "table-success">' . $num_absent.  '</td>';
            $rows .= '<td class = "table-success">' . $num_late.    '</td>';
            $rows .= '<td class = "table-success">' . $present_percent. '%</td>';
            $rows .= '<td class = "table-success">' . $absent_percent.  '%</td>';
            $rows .= '<td class = "table-success">' . $late_percent.    '%</td>';
            
        }
        $rows .= '</tr>';

    // Used after loop to get the averages 
    $present += $num_present;
    $absent += $num_absent;
    $late += $num_late;
    $present_count_percent += $present_percent;
    $absent_count_percent  += $absent_percent;
    $late_count_percent    += $late_percent;

}




// Average amount of times a student is present
$average_present = number_format($present/$num_students, 2, '.', "");

// Average amount of times a student is absent 
$average_absent = number_format($absent/$num_students, 2, '.', "");

// Average amount of times a student is  late
$average_late = number_format($late/$num_students, 2, '.', "");



// Average Present percentage
$average_present_percentage = number_format($present_count_percent/$num_students, 2, '.', "");

// Average absent percentage
$average_absent_percentage = number_format($absent_count_percent/$num_students, 2, '.', "");

// Average late percentage
$average_late_percentage = number_format($late_count_percent/$num_students, 2, '.', "");
?>


<style>
    #HASH {
        display: flex;
        justify-content: space-between;
    }

    .table {
        background: #fff;
        border-radius: 5px;
    }
</style>

<div id="HASH">
<h2>Attendance Statistics</h2>
<a class="btn btn-primary" href="<?php echo JURI::current(); ?>?view=home">Individual Statistics</a>
</div>
<table class="table" id="table">
    <tr>
        <th>Name</th>
        <th class = "table-success"># of times present</th>
        <th class=  "table-danger"> # of times absent</th>
        <th class=  "table-warning"># of times late</th>
        <th class = "table-success">Present Percentage</th>
        <th class=  "table-danger"> Absent  Percentage</th>
        <th class=  "table-warning">Late Percentage</th>
    </tr>
    <?php echo $rows; ?>
    <td><strong>Average</strong></td>
    <td class = "table-success"> <strong><?php echo '' .$average_present;?> </td></strong>
    <td class = "table-danger">  <strong><?php echo '' .$average_absent;?> </td></strong>
    <td class = "table-warning"> <strong><?php echo '' .$average_late;?> </td></strong>
    <td class = "table-success"> <strong><?php echo '' .$average_present_percentage.'%'?> </td></strong>
    <td class = "table-danger">  <strong><?php echo '' .$average_absent_percentage.'%'?> </td></strong>
    <td class = "table-warning"> <strong><?php echo '' .$average_late_percentage.'%'?> </td></strong>
</table>
