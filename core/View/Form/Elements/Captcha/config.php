<?php
/**
 * CAPTCHA configuration
 * Xoops Frameworks addon
 *
 * based on Frameworks::captcha by Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through CaptchaELement::setMode(), take it
 * 2 Elseif mode is set though captcha/config.php, take it
 * 3 Else, take "text"
 *
 * @package	ICMS\Form\Elements\Captcha
 * @author	modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license 	https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GPLv2 or later license
 * @author	Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since	XOOPS
 */

return [
	'name' => 'icmscaptcha',
];
