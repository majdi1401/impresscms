<?php
if (!defined("ICMS_ROOT_PATH")) die("ImpressCMS root path not defined");
/**
 *
 * @deprecated	Use icms_ipf_Handler, instead
 * @todo		Remove in version 1.4
 *
 */
class icms_ipf_ObjectHandler extends icms_ipf_Handler {
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('icms_ipf_Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
?>