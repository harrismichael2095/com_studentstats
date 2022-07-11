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
$groupId = 11;
$access = new JAccess();
$members = $access->getUsersByGroup($groupId);
$rows = '';
$users = [];
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
    $query->select('*');
    $query->from('reports');
    $query->where('id!='.$id);
    $results2 = $db->loadAssocList();
    $missed_rows = count($results2);
    $rows .= '<tr>';
    $rows .= '<td>' . $user->name . '</td>';
    $rows .= '<td>' . $num_rows . '</td>';
    $rows .= '<td>' . $missed_rows . '</td>';
    $rows .= '</tr>';
}
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
        <th>Submitted Reports</th>
        <th>Missed Reports</th>
        <th>Percentage</th>
    </tr>
    <?php echo $rows; ?>
</table>

