<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use ImpressCMS\Core\IPF\AbstractDatabaseModel;

/**
 * Form control creating a Date Picker element for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class DateElement extends \ImpressCMS\Core\View\Form\Elements\DateElement {
	/**
	 * Constructor
	 * @param	AbstractDatabaseModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		parent::__construct($object->getVarInfo($key, 'form_caption'), $key, 15, $object->getVar($key, 'e'));
	}
}