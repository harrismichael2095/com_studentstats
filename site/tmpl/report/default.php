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
$query->select('DISTINCT weekStart');
$query->from('reports');
$db->setQuery((string) $query);
$results = $db->loadAssocList();
$num_reportWeeks = count($results);

// Prevents divide by 0 error when there are no report weeks
if ($num_reportWeeks == 0)
    $num_reportWeeks = 1;


$db = JFactory::getDbo();
$groupId = 11;
$access = new JAccess();
$members = $access->getUsersByGroup($groupId);
$num_students = count($members);
$rows = '';
$users = [];

// Prevents divide by 0 error when there are no students
if ($num_students == 0)
    $num_students = 1;

foreach ($members as $id) {
    $user = JFactory::getUser($id);
    array_push($users, $user);
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('reports');
    $query->where('id ='.$id);
    $db->setQuery((string) $query);
    $results = $db->loadAssocList();
    $num_rows = count($results);
    $num_missedReports = abs($num_rows - $num_reportWeeks);
    $report_percent = number_format($num_rows/$num_reportWeeks*100, 2, '.', "");
    $rows .= '<tr>';
    $rows .= '<td>' . $user->name . '</td>';
    $rows .= '<td>' . $num_rows . '</td>';
    $rows .= '<td>' . $num_missedReports . '</td>';
    $rows .= '<td>' . $report_percent . '</td>';
    $rows .= '</tr>';

    // Used after loop to get the averages 
    $submitted += $num_rows;
    $missed += $num_missedReports;
    $percentage += $report_percent;
    
}


// Average submitted reports for students 
$average_submitted = number_format($submitted/$num_students, 2, '.', "");

// Average missed reports for students 
$average_missed = number_format($missed/$num_students, 2, '.', "");

// Average report percentage for students.
$average_percent = number_format($percentage/$num_students, 2, '.', "");
?>


<style>
    .table {
        background: #fff;
        border-radius: 5px;
    }

</style>

<h2>Report Statistics</h2>
<table class="table" id="table">
    <tr>
        <th>Name</th>
        <th class = "table-success">Submitted Reports</th>
        <th class="table-danger">Missed Reports</th>
        <th>Percentage</th>
    </tr>
    <?php echo $rows; ?>
    <td><strong>Average</strong></td>
    <td class = "table-success"> Submitted: <?php echo '' .$average_submitted;?> </td>
    <td class = "table-danger"> Missed: <?php echo '' .$average_missed;?> </td>
    <td> Percentage: <?php echo ' ' .$average_percent;?> </td>
    </table>


