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



// get the userid that was selected
$uri = Uri::getInstance();
$id = $uri->getVar('id');
debug_to_console($id);


// find errors
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);
    echo "<script>console.log('".$output."');</script>";
}



// find the number of attendance records for stats
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__attendance_reports');
$db->setQuery((string) $query);
$num_rows = $db->loadAssocList();
$j = 0;
$rows = '';
foreach ($num_rows as $row) {
    $j++;
}



// query to see which dates the student was present for
$query = $db->getQuery(true);
$query->select('id,present,absent,date_created');
$query->from('#__attendance_reports');
$query->where('JSON_CONTAINS(present,' . $db->quote($id) .')');
$db->setQuery((string) $query);


$results = $db->loadAssocList();
$i = 0;
$rows = '';
foreach ($results as $row) {

    //echo "<p>" . $row['id'] . ", " . $row['date_created'] . "<br></p>";
    $rows .= '<tr>';
    $rows .= '<td>' . "Attendance Record : " . $row['id'] .'</td>';
    $rows .= '<td>' . $row['date_created'] . '</td>';
    $rows .= '</tr>';
    $i++;
}



// query to see which dates the student was absent for
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__attendance_reports');
$query->where('JSON_CONTAINS(absent,' . $db->quote($id) .')');
$db->setQuery((string) $query);
$num_rows = $db->loadAssocList();
$k = 0;
$absent = '';
foreach ($num_rows as $row) {
    //echo "<p>" . $row['id'] . ", " . $row['date_created'] . "<br></p>";
    $absent .= '<tr>';
    $absent .= '<td>' . "Attendance Record : " . $row['id'] .'</td>';
    $absent .= '<td>' . $row['date_created'] . '</td>';
    $absent .= '</tr>';
    $k++;
}





// Stats Section 
// More Stats forumals will go here as we see what Oroji wants.


// Present percentage
$attendance = $i/$j*100;

// Absent percentage 
$attendance2 = $k/$j*100;


?>

<h2>View Stats</h2>

<table class="table">
    
    <tr>
        <th>Present Records</th>
        <th>Date</th>
    </tr>

    <?php   echo 'Student Attended ' .$i. ' of the ' .$j. ' Meetings';
            echo "<br>";
            echo 'Present Percentage : ' .$attendance. '%';
            echo $rows; 
    ?>

    <tr>
        <th>Absent Records</th>
        <th>Date</th>
    </tr>

    <?php   echo "<br>";
            echo "<br>";
            echo 'Student Missed  ' .$k. ' of the ' .$j. ' Meetings';
            echo "<br>";
            echo 'Absent Percentage : ' .$attendance2. '%'; echo $absent; echo "<br><br>"; 
    ?>
     

</table>
