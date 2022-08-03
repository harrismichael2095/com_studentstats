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

$rows = '';
$users = [];
foreach ($members as $id) {
    $user = JFactory::getUser($id);
    array_push($users, $user);
    $href = JURI::current() . '?view=stats&id=' . $user->id;
    $rows .= '<tr>';
    $rows .= '<td>' . $user->name . '</td>';
    $rows .= '<td>' . date_create($user->lastvisitDate)->format('n/j/Y') . '</td>';
    $rows .= '<td>' . '<a class="btn btn-primary btn-sm" href="'. $href .'">select </a> '. '</td>';
    $rows .= '</tr>';
}

?>

<style>
    .section {
        margin-bottom: 20px;
    }

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
<h2>Select a Student</h2>
<a class="btn btn-primary" href="<?php echo JURI::current(); ?>?view=average">Back</a>
</div>

    <table class="table"> 
    <tr>
        <th>Name</th>
        <th>Last Login</th>
        <th>Select</th>
    </tr>
      <?php echo $rows; ?>
</table>
    
