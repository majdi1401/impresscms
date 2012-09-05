<?php
/**
 * Update permissions for a member group
 *
 * Target for the group permissions form to handle the POST data
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		Administration
 * @version		SVN: $Id$
 */

include '../../../include/cp_header.php';
$modid = isset($_POST['modid']) ? (int) ($_POST['modid']) : 0;

// we dont want system module permissions to be changed here
if ($modid <= 1 || !is_object(icms::$user) || !icms::$user->isAdmin($modid)) {
	redirect_header(ICMS_URL . '/', 1, _NOPERM);
	exit();
}
$module_handler = icms::handler('icms_module');
$module =& $module_handler->get($modid);
if (!is_object($module) || !$module->getVar('isactive')) {
	redirect_header(ICMS_URL . '/admin.php', 1, _MODULENOEXIST);
	exit();
}

$msg = array();

$member_handler = icms::handler('icms_member');
$group_list =& $member_handler->getGroupList();
if (is_array($_POST['perms']) && !empty($_POST['perms'])) {
	$gperm_handler = icms::handler('icms_member_groupperm');
	foreach ($_POST['perms'] as $perm_name => $perm_data) {
		if (FALSE != $gperm_handler->deleteByModule($modid, $perm_name)) {
			foreach ($perm_data['groups'] as $group_id => $item_ids) {
				foreach ($item_ids as $item_id => $selected) {
					if ($selected == 1) {
						// make sure that all parent ids are selected as well
						if ($perm_data['parents'][$item_id] != '') {
							$parent_ids = explode(':', $perm_data['parents'][$item_id]);
							foreach ($parent_ids as $pid) {
								if ($pid != 0 && !in_array($pid, array_keys($item_ids))) {
									// one of the parent items were not selected, so skip this item
									$msg[] = sprintf(_MD_AM_PERMADDNG, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>') . ' (' . _MD_AM_PERMADDNGP . ')';
									continue 2;
								}
							}
						}
						$gperm =& $gperm_handler->create();
						$gperm->setVar('gperm_groupid', $group_id);
						$gperm->setVar('gperm_name', $perm_name);
						$gperm->setVar('gperm_modid', $modid);
						$gperm->setVar('gperm_itemid', $item_id);
						if (!$gperm_handler->insert($gperm)) {
							$msg[] = sprintf(_MD_AM_PERMADDNG, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>');
						} else {
							$msg[] = sprintf(_MD_AM_PERMADDOK, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>');
						}
						unset($gperm);
					}
				}
			}
		} else {
			$msg[] = sprintf(_MD_AM_PERMRESETNG, $module->getVar('name') . '(' . $perm_name . ')');
		}
	}
}

$backlink = xoops_getenv("HTTP_REFERER");
if ($module->getVar('hasadmin')) {
	$adminindex = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : $module->getInfo('adminindex');
	if ($adminindex) {
		$backlink = ICMS_MODULES_URL . '/' . $module->getVar('dirname') . '/' . $adminindex;
	}
}
$backlink = $backlink ? $backlink : ICMS_URL . '/admin.php';

redirect_header($backlink, 2, implode("<br />", $msg));

