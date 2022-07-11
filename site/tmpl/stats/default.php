<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_studentstats
 *
 * @copyright   Copyright (C) 2020 John Smith. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;

// get the UserID of student selected from Stats Page
$uri = Uri::getInstance();
$id = $uri->getVar('id');
Log::add($id);
$user = JFactory::getUser($id); 



// query to see which dates the student was Present
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__attendance_reports');
$query->where('JSON_CONTAINS(present,' . $db->quote($id) .')');
$db->setQuery((string) $query);
$results = $db->loadAssocList();
$num_rows = count($results);
$num_present = count($results);
$present_rows = '';
foreach ($results as $row) {
    //echo "<p>" . $row['id'] . ", " . $row['date_created'] . "<br></p>";
    $present_rows .= '<tr class="table-success">';
    $present_rows .= '<td>' . "Attendance Record : " . $row['id'] .'</td>';
    $present_rows .= '<td>' . $row['date_created'] . '</td>';
    $present_rows .= '</tr>';
}



// query to see which dates the student was absent 
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__attendance_reports');
$query->where('JSON_CONTAINS(absent,' . $db->quote($id) .')');
$db->setQuery((string) $query);
$results = $db->loadAssocList();
$num_rows += count($results);
$num_absent = count($results);
$absent_rows = '';
foreach ($results as $row) {
    //echo "<p>" . $row['id'] . ", " . $row['date_created'] . "<br></p>";
    $absent_rows .= '<tr class="table-danger">';
    $absent_rows .= '<td>' . "Attendance Record : " . $row['id'] .'</td>';
    $absent_rows .= '<td>' . $row['date_created'] . '</td>';
    $absent_rows .= '</tr>';
}



// query to see which dates the student was Late 
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__attendance_reports');
$query->where('JSON_CONTAINS(late,' . $db->quote($id) .')');
$db->setQuery((string) $query);
$results = $db->loadAssocList();
$num_rows += count($results);
$num_late = count($results);
$late_rows = '';
foreach ($results as $row) {
    //echo "<p>" . $row['id'] . ", " . $row['date_created'] . "<br></p>";
    $late_rows .= '<tr class="table-warning">';
    $late_rows .= '<td>' . "Attendance Record : " . $row['id'] .'</td>';
    $late_rows .= '<td>' . $row['date_created'] . '</td>';
    $late_rows .= '</tr>';
}



//////////////////// Stats Section ///////////////////////////

// Present percentage 
$present_percent = number_format($num_present/$num_rows*100, 2, '.', "");

// Absent percentage 
$absent_percent = number_format($num_absent/$num_rows*100, 2, '.', "");

// Late percentage
$late_percent = number_format($num_late/$num_rows*100, 2, '.', "");
?>



<style>
    .table {
        background: #fff;
        border-radius: 5px;
    }
</style>



<? ////////////////  Web Page Display  //////////////////////////?>






<h2> <?= $user->name; ?>   </h2>

<p> 
    <strong>
    <?php echo 'Attended ' .$num_present. ' Meetings'; ?> 
    </strong>
</p>

<p> 
    <strong>
    <?php echo 'Missed ' .$num_absent. ' Meetings'; ?> 
    </strong>
</p>

<p> 
    <strong>
    <?php echo 'Late to ' .$num_late. ' Meetings'; ?> 
    </strong>
</p>


<table class="table" id="table">
    <tr class="table-success">
    <td> Present Percentage </td>
    <td><?php echo$present_percent;?></td>
    </tr>
    <tr class="table-danger">
    <td> Absent Percentage </td>
    <td><?php echo$absent_percent;?></td>
    </tr>
    <tr class="table-warning">
    <td> Late Percentage </td>
    <td><?php echo$late_percent;?></td>
    </tr>
    </table>



<table class="table" id="table">
    <tr>
        <th>Present Records</th>
        <th>Date</th>
    </tr>
    <?php echo $present_rows; ?>
    </table>

    <table class="table" id="table">
    <tr>
        <th>Absent Records</th>
        <th>Date</th>
    </tr>
    <?php echo $absent_rows; ?>
    </table>


    <table class="table" id="table">
    <tr>
        <th>Late Records</th>
        <th>Date</th>
    </tr>
    <?php echo $late_rows; ?>
    </table>
