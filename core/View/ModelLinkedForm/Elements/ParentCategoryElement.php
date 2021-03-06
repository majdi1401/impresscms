<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\IPF\AbstractDatabaseModel;
use ImpressCMS\Core\ObjectTree;

/**
 * Form control creating a parent category selectbox for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class ParentCategoryElement extends \ImpressCMS\Core\View\Form\Elements\SelectElement {
	/**
	 * Constructor
	 * @param	AbstractDatabaseModel    $object   reference to target object
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$category_title_field = $object->handler->identifierName;

		$addNoParent = $object->controls[$key]['addNoParent'] ?? true;
		$criteria = new CriteriaCompo();
		$criteria->setSort('weight, ' . $category_title_field);
		$category_handler = icms_getModuleHandler('category', $object->handler->_moduleName);
		$categories = $category_handler->getObjects($criteria);

		$mytree = new ObjectTree($categories, 'category_id', 'category_pid');
		parent::__construct($object->getVarInfo($key, 'form_caption'), $key, $object->getVar($key, 'e'));

		$ret = array();
		$options = $this->getOptionArray($mytree, $category_title_field, 0, $ret, '');
		if ($addNoParent) {
			$newOptions = array('0' => '----');
			foreach ($options as $k => $v) {
				$newOptions[$k] = $v;
			}
			$options = $newOptions;
		}
		$this->addOptionArray($options);
	}

	/**
	 * Get options for a category select with hierarchy (recursive)
	 *
	 * @param \ImpressCMS\Core\IPF\ObjectTree  $tree       Tree instance
	 * @param string  $fieldName    The fieldname to get the option array for
	 * @param int     $key          the key to get the optionarray for
	 * @param string  $prefix_curr  the prefix
	 * @param array   $ret          passed return array
	 *
	 * @return array  $ret          the constructed option array
	 */
	private function getOptionArray($tree, $fieldName, $key, &$ret, $prefix_curr = '') {
		if ($key > 0) {
			$value = $tree->_tree[$key]['obj']->getVar($tree->_myId);
			$ret[$key] = $prefix_curr . $tree->_tree[$key]['obj']->getVar($fieldName);
			$prefix_curr .= '-';
		}

		if (isset($tree->_tree[$key]['child']) && !empty($tree->_tree[$key]['child'])) {
			foreach ($tree->_tree[$key]['child'] as $childkey) {
				$this->getOptionArray($tree, $fieldName, $childkey, $ret, $prefix_curr);
			}
		}
		return $ret;
	}
}