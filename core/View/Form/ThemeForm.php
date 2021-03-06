<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Creates a form attribut styled by the theme
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
namespace ImpressCMS\Core\View\Form;

/**
 * Form that will output as a theme-enabled HTML table
 *
 * Also adds JavaScript to validate required fields
 *
 * @package	ICMS\Form
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class ThemeForm extends AbstractForm {
	/**
	 * Insert an empty row in the table to serve as a seperator.
	 *
	 * @param	string  $extra  HTML to be displayed in the empty row.
	 * @param	string	$class	CSS class name for <td> tag
	 */
	public function insertBreak($extra = '', $class = '') {
		$class = $class?" class='$class'":'';
		//Fix for $extra tag not showing
		if ($extra) {
			$extra = "<tr><td colspan='2' $class>$extra</td></tr>";
			$this->addElement($extra);
		} else {
			$extra = "<tr><td colspan='2' $class>&nbsp;</td></tr>";
			$this->addElement($extra);
		}
	}

	/**
	 * create HTML to output the form as a theme-enabled table with validation.
	 *
	 * @return	string
	 */
	public function render() {
		$ele_name = $this->getName();
		$ret = "<form id='" . $ele_name
				. "' name='" . $ele_name
				. "' action='" . $this->getAction()
				. "' method='" . $this->getMethod()
				. "' onsubmit='return xoopsFormValidate_" . $ele_name . "();'" . $this->getExtra() . ">
			<div class='xo-theme-form'>
			<table width='100%' class='outer table' cellspacing='1'>
			<tr><th colspan='2'>" . $this->getTitle() . '</th></tr>
		';
		$hidden = '';
		$class = 'even';
		foreach ($this->getElements() as $ele) {
			if (!is_object($ele)) {
				$ret .= $ele;
			} elseif (!$ele->isHidden()) {
				$ret .= "<tr valign='top' align='" . _GLOBAL_LEFT . "'>";
				$caption = $ele->getCaption();
				if ($caption !== '' || $caption === null) {
					$ret .= '<td class="head">';
				}
				if ($caption != '' && $caption !== null) {
					$ret .=
						"<div class='xoops-form-element-caption" . ($ele->isRequired()? '-required' : '') . "'>"
						. "<span class='caption-text'>{$caption}</span>";
						if ($desc = $ele->getDescription()) {
							$ret .= "<div class='xoops-form-element-help'>{$desc}</div>";
						}
					$ret .= "<span class='caption-marker'> *</span></div>";
				}
				if ($caption !== '' || $caption === null) {
					$ret .= '<td class="head">';
				}
				$ret .= "<td class='$class'>" . $ele->render() . "</td></tr>\n";
			} else {
				$hidden .= $ele->render();
			}
		}
		$ret .= "</table>\n$hidden\n</div>\n</form>\n";
		$ret .= $this->renderValidationJS(true);
		return $ret;
	}
}

