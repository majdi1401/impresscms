<?php
/**
 * Adding CAPTCHA
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through CaptchaElement::setMode(), take it
 * 2 Elseif mode is set though captcha/config.php, take it
 * 3 Else, take "text"
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

namespace ImpressCMS\Core\View\Form\Elements;

use ImpressCMS\Core\View\Form\AbstractFormElement;
use ImpressCMS\Core\View\Form\Elements\Captcha\Image;

/**
 * CAPTCHA form element
 *
 * Usage
 *
 * For form creation:
 * Add form element where proper: $form->addElement(new \ImpressCMS\Core\Form\Elements\CaptchaElement($caption, $name, $skipmember, ...);
 *
 * For verification:
 * $icmsCaptcha = CaptchaElement::instance();
 * if (!$icmsCaptcha->verify()) {
 *   echo $icmsCaptcha->getMessage();
 *   ...
 * }
 *
 * @package	ICMS\Form\Elements
 */
class CaptchaElement extends AbstractFormElement {
	private $_captchaHandler;

	/**
	 * @param string	$caption	Caption of the form element, default value is defined in captcha/language/
	 * @param string	$name		Name for the input box
	 * @param boolean	$skipmember	Skip CAPTCHA check for members
	 * @param int		$numchar	Number of characters in image mode, and input box size for text mode
	 * @param int		$minfontsize	Minimum font-size of characters in image mode
	 * @param int		$maxfontsize	Maximum font-size of characters in image mode
	 * @param int		$backgroundtype	Background type in image mode: 0 - bar; 1 - circle; 2 - line; 3 - rectangle; 4 - ellipse; 5 - polygon; 100 - generated from files
	 * @param int		$backgroundnum	Number of background images in image mode
	 *
	 */
	public function __construct($caption = '', $name = 'icmscaptcha', $skipmember = null,
			$numchar = null, $minfontsize = null, $maxfontsize = null, $backgroundtype = null,
			$backgroundnum = null
	) {
		$this->_captchaHandler = & Image::instance();
		$this->_captchaHandler->init(
			$name, $skipmember, $numchar, $minfontsize, $maxfontsize, $backgroundtype, $backgroundnum
		);
		if (!$this->_captchaHandler->active) {
			$this->setHidden();
		} else {
			$caption = !empty($caption)?$caption:$this->_captchaHandler->getCaption();
			$this->setCaption($caption);
		}
	}

	/**
	 * Sets the Config
	 *
	 * @param   string $name Config Name
	 * @param   string $val Config Value
	 *
	 * @return  CaptchaElement
	 */
	public function setConfig($name, $val) {
		return $this->_captchaHandler->setConfig($name, $val);
	}

	/**
	 * @inheritDoc
	 */
	public function render() {
		if (!$this->isHidden()) {
			return $this->_captchaHandler->render();
		}
	}
}

