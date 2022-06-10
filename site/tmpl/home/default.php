<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_studentstats
 *
 * @copyright   Copyright (C) 2022 Michael Harris. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);
    echo "<script>console.log('".$output."');</script>";
}

$groupId = 11;
$access = new JAccess();
$members = $access->getUsersByGroup($groupId);

$rows = '';
$users = [];
foreach ($members as $id) {
    $user = JFactory::getUser($id);
    array_push($users, $user);
    $href = JURI::current() . '?view=stats&id=' . $user->id;
    $rows .= '<tr>';
    $rows .= '<td>' . $user->name . '</td>';
    $rows .= '<td>' . $user->lastvisitDate . '</td>';
    $rows .= '<td>' . '<a class="btn btn-primary btn-sm" href="'. $href .'">select </a> '. '</td>';
    $rows .= '</tr>';
}

?>

<style>
    .section {
        margin-bottom: 20px;
        padding: 10px;
    }
</style>

<div class="section">
    <h2>Student Stats</h2>
</div>

    <h3>Select a Student</h3>
    <table class="table"> 
        <tr>
        <th>Name</th>
        <th>Last Login</th>
        <th>Select</th>
    </tr>
      <?php echo $rows; ?>
</table>
    



