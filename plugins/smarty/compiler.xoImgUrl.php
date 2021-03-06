<?php
/**
 * xoImgUrl Smarty compiler plug-in
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GPLv2 or later license
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @package		xos_opal
 * @subpackage	xos_opal_Smarty
 * @since       2.0.14
 */

/**
 * Inserts the URL of a file resource customizable by themes
 *
 * This plug-in works like the smarty_compiler_xoAppUrl plug-in,
 * except that it is intended to generate the URL of resource files customizable by
 * themes.
 *
 * Here the current theme is asked to check if a custom version of the requested file exists, and
 * if one is found its URL is returned. Otherwise, the request will be passed to the
 * theme parents one by one. Ultimately, if no custom version has been found, the resource
 * default URL location will be returned.
 *
 * <b>Note:</b> the themes inheritance system can generate many filesystem accesses depending
 * on your themes configuration. Because of this, the use of the dynamic syntax with this plug-in
 * is not possible right now.
 */
function smarty_compiler_xoImgUrl($args, Smarty &$smarty)
{
	global $xoTheme;

	$argStr = trim($args[0]);
	$path = ( isset($xoTheme) && is_object( $xoTheme ) ) ? $xoTheme->resourcePath( $argStr ) : $argStr;
	return addslashes(icms::url($path));
}


