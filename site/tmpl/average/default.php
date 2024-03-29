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



// Prevents divide by 0 error when there are no reports
if ($num_rows == 0)
    $num_rows = 1;


//Returns all the ids belonging to the group name input
function getGroupId($groupName){
    $db = JFactory::getDBO();
    $db->setQuery($db->getQuery(true)
        ->select('*')
        ->from("#__usergroups")
    );
    $groups = $db->loadRowList();
    foreach ($groups as $group) {
        if ($group[4] == $groupName) // $group[4] holds the name of current group
            return $group[0]; // $group[0] holds group ID
    }
    return false;
}

$groupId = getGroupId('Student');
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
    $num_rows = count($results);
    $num_present = count($results);

    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__attendance_reports');
    $query->where('JSON_CONTAINS(absent,' . $db->quote($id) .')');
    $db->setQuery((string) $query);
    $results = $db->loadAssocList();
    $num_rows += count($results);
    $num_absent = count($results);

    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__attendance_reports');
    $query->where('JSON_CONTAINS(late,' . $db->quote($id) .')');
    $db->setQuery((string) $query);
    $results = $db->loadAssocList();
    $num_rows += count($results);
    $num_late = count($results);

    // Present percentage 
    $present_percent = number_format($num_present/$num_rows*100, 2, '.', "");

    // Absent percentage 
    $absent_percent = number_format($num_absent/$num_rows*100, 2, '.', "");

    // Late percentage
    $late_percent = number_format($num_late/$num_rows*100, 2, '.', "");

    $rows .= '<tr>';
    
    if($present_percent<60.00)
        {   $rows .= '<td class = "table-danger">' . $user->name . '</td>';
            $rows .= '<td>' . $num_present. '</td>';
            $rows .= '<td>' . $num_absent.  '</td>';
            $rows .= '<td>' . $num_late.    '</td>';
            $rows .= '<td  class = "table-danger">' . $present_percent. '%</td>';
            $rows .= '<td>' . $absent_percent.  '%</td>';
            $rows .= '<td>' . $late_percent.    '%</td>';
            $rows .= '</tr>';  
        }
        else
        {
            $rows .= '<td>' . $user->name . '</td>';
            $rows .= '<td>' . $num_present. '</td>';
            $rows .= '<td>' . $num_absent.  '</td>';
            $rows .= '<td>' . $num_late.    '</td>';
            $rows .= '<td>' . $present_percent. '%</td>';
            $rows .= '<td>' . $absent_percent.  '%</td>';
            $rows .= '<td>' . $late_percent.    '%</td>';
            $rows .= '</tr>'; 
            
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



if($average_present_percentage<60.00)
{
    $rows .= '<td  class = "table-danger"><strong>Average</strong></td>';
    $rows .= '<td  class = "table-danger">' .$average_present. '</td>';
    $rows .= '<td  class = "table-danger">' .$average_absent. '</td>';
    $rows .= '<td  class = "table-danger">' .$average_late.'</td>';
    $rows .= '<td  class = "table-danger">' .$average_present_percentage.'%</td>';
    $rows .= '<td  class = "table-danger">' .$average_absent_percentage.'% </td>';
    $rows .= '<td  class = "table-danger">' .$average_late_percentage.'%</td>';

}
else
{
    $rows .= '<td  class = "table-success"><strong>Average</strong></td>';
    $rows .= '<td  class = "table-success">' .$average_present. '</td>';
    $rows .= '<td  class = "table-success">' .$average_absent. '</td>';
    $rows .= '<td  class = "table-success">' .$average_late.'</td>';
    $rows .= '<td  class = "table-success">' .$average_present_percentage.'%</td>';
    $rows .= '<td  class = "table-success">' .$average_absent_percentage.'% </td>';
    $rows .= '<td  class = "table-success">' .$average_late_percentage.'%</td>';
}
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
        <th class = "table-success">Number of times present</th>
        <th class=  "table-danger"> Number of times absent</th>
        <th class=  "table-warning">Number of times late</th>
        <th class = "table-success">Present Percentage</th>
        <th class=  "table-danger"> Absent  Percentage</th>
        <th class=  "table-warning">Late Percentage</th>
    </tr>
    <?php echo $rows; ?>
   </table>
