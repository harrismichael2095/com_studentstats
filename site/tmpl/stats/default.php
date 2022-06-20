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

// get the userid that was selected
$uri = Uri::getInstance();
$id = $uri->getVar('id');
Log::add($id);
$user = JFactory::getUser($id); 

// query to see which dates the student was present for
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
    $present_rows .= '<tr>';
    $present_rows .= '<td>' . "Attendance Record : " . $row['id'] .'</td>';
    $present_rows .= '<td>' . $row['date_created'] . '</td>';
    $present_rows .= '</tr>';
}

// query to see which dates the student was absent for
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
    $absent_rows .= '<tr>';
    $absent_rows .= '<td>' . "Attendance Record : " . $row['id'] .'</td>';
    $absent_rows .= '<td>' . $row['date_created'] . '</td>';
    $absent_rows .= '</tr>';
}

// Stats Section 
// More Stats forumals will go here as we see what Oroji wants.
// Github Testing
// Present percentage
$present_percent = number_format($num_present/$num_rows*100, 2, '.', "");

// Absent percentage 
$absent_percent = number_format($num_absent/$num_rows*100, 2, '.', "");
?>

<h2> <?php echo $user->name. ' Statistics' ?>   </h2>
<p>
    <?php echo 'Student Attended ' .$num_present. ' of the ' .$num_rows. ' Meetings'; ?> 
</p>
<p>
    <?php echo 'Present Percentage: ' .$present_percent. '%';?>
</p>
<p>
    <?php echo 'Student Missed ' .$num_absent. ' of the ' .$num_rows. ' Meetings'; ?> 
</p>
<p>
    <?php echo 'Absent Percentage: ' .$absent_percent. '%';?>
</p>
<table class="table">
    <tr>
        <th>Present Records</th>
        <th>Date</th>
    </tr>
    <?php echo $present_rows; ?>
    <tr>
        <th>Absent Records</th>
        <th>Date</th>
    </tr>
    <?php echo $absent_rows; ?>
</table>
