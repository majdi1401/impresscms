<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use ImpressCMS\Core\IPF\AbstractDatabaseModel;
use ImpressCMS\Core\View\Form\Elements\CheckboxElement;
use ImpressCMS\Core\View\Form\Elements\LabelElement;
use ImpressCMS\Core\View\Form\Elements\TextElement;
use ImpressCMS\Core\View\Form\Elements\TrayElement;

/**
 * Form control creating an hidden field for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 * @todo	Remove the hardcoded height attribute, line breaks, styles
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 * @package	ICMS\IPF\Form\Elements
 */
class ImageElement extends TrayElement {
	/**
	 * Constructor
	 * @param	AbstractDatabaseModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$var = $object->getVarInfo($key);
		$control = $object->getControl($key);

		$object_imageurl = $object->getImageDir();
		parent::__construct($var['form_caption'], ' ');

		if (isset($objectArray['image'])) {
			$objectArray['image'] = str_replace('{ICMS_URL}', ICMS_URL, $objectArray['image']);
		}

		if ($object->getVar($key, 'e') && (strpos($object->getVar($key, 'e'), 'http') === 0 || strpos($object->getVar($key, 'e'), '{ICMS_URL}') === 0)) {
			$this->addElement(new LabelElement('', "<img src='" . str_replace('{ICMS_URL}', ICMS_URL, $object->getVar($key, 'e')) . "' alt='' /><br/><br/>"));
		} elseif ($object->getVar($key, 'e')) {
			$this->addElement(new LabelElement('', "<a rel='lightbox' title='" . $object_imageurl . $object->getVar($key, 'e')
				. "' href='" . $object_imageurl . $object->getVar($key, 'e')
				. "' ><img class='acp_object_imageurl' src='" . $object_imageurl . $object->getVar($key, 'e')
				. "' alt='" . $object_imageurl . $object->getVar($key, 'e') . "' height='150' /></a><br/><br/>"));
		}

		$this->addElement(new FileUploadElement($object, $key));

		if (!isset($control['nourl']) || !$control['nourl']) {
			$this->addElement(new LabelElement('<div style="padding-top: 8px; font-size: 80%;">' . _CO_ICMS_URL_FILE_DSC . '</div>', ''));
			$this->addElement(new LabelElement('', '<br />' . _CO_ICMS_URL_FILE));
			$this->addElement(new TextElement('', 'url_' . $key, 50, 500));
		}
		if (!$object->isNew()) {
			$this->addElement(new LabelElement('', '<br /><br />'));
			$delete_check = new CheckboxElement('', 'delete_' . $key);
			$delete_check->addOption(1, '<span style="color:red;">' . _CO_ICMS_DELETE . '</span>');
			$this->addElement($delete_check);
		}
	}
}