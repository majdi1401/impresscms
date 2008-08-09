<?php
/**
* All information in order to connect to database are going through here.
*
* Be careful if you are changing data's in this file.
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		Installer
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*//**
 * Xoops is not installed, redirect to the installer
 **/

// XOOPS is not installed yet.
if(! defined('XOOPS_INSTALL')){
    header('Location: install/index.php');
	exit();
}
?>