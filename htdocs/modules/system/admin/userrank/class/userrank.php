<?php
/**
 * ImpressCMS Userranks
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die ("ImpressCMS root path not defined");

icms_loadLanguageFile("system", "common");
icms_loadLanguageFile("system", "userrank", TRUE);

class SystemUserrank extends icms_ipf_Object {
	public $content = FALSE;

	function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar("rank_id", XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar("rank_title", XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_USERRANK_RANK_TITLE, _CO_ICMS_USERRANK_RANK_TITLE_DSC);
		$this->quickInitVar("rank_min", XOBJ_DTYPE_INT, TRUE, _CO_ICMS_USERRANK_RANK_MIN, _CO_ICMS_USERRANK_RANK_MIN_DSC);
		$this->quickInitVar("rank_max", XOBJ_DTYPE_INT, TRUE, _CO_ICMS_USERRANK_RANK_MAX, _CO_ICMS_USERRANK_RANK_MAX_DSC);
		$this->quickInitVar("rank_special", XOBJ_DTYPE_INT, TRUE, _CO_ICMS_USERRANK_RANK_SPECIAL, _CO_ICMS_USERRANK_RANK_SPECIAL_DSC);
		$this->quickInitVar("rank_image", XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_USERRANK_RANK_IMAGE, _CO_ICMS_USERRANK_RANK_IMAGE_DSC);

		$this->setControl("rank_special", "yesno");
		$this->setControl("rank_image", "image");
	}

	public function getVar($key, $format = "s") {
		if ($format == "s" && in_array($key, array())) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	public function getCloneLink() {
		$ret = '<a href="' . ICMS_URL . '/modules/system/admin.php?fct=userrank&amp;op=clone&amp;rank_id=' . $this->id() . '"><img src="' . ICMS_IMAGES_SET_URL . '/actions/editcopy.png" style="vertical-align: middle;" alt="' . _CO_ICMS_CUSTOMTAG_CLONE . '" title="' . _CO_ICMS_CUSTOMTAG_CLONE . '" /></a>';
		return $ret;
	}

	public function getRankPicture() {
		$ret = '<img src="' . $this->handler->getImageUrl() . $this->getVar("rank_image") . '" />';
		return $ret;
	}

	public function getRankTitle() {
		$ret = $this->getVar("rank_title");
		return $ret;
	}
}

class SystemUserrankHandler extends icms_ipf_Handler {
	public $objects = FALSE;

	public function __construct($db) {
		global $icmsConfigUser;
		parent::__construct($db, "userrank", "rank_id", "rank_title", "", "system");
		$this->table = $this->db->prefix("ranks");
		$this->enableUpload(array("image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png"), $icmsConfigUser["rank_maxsize"], $icmsConfigUser["rank_width"], $icmsConfigUser["rank_height"]);
	}

	public function getRank($rank_id = 0, $posts = 0) {
		$rank_id = (int) $rank_id;
		$posts = (int) $posts;

		$criteria = new icms_db_criteria_Compo();
		if ($rank_id != 0) {
			$criteria->add(new icms_db_criteria_Item("rank_id", $rank_id));
		} else {
			$criteria->add(new icms_db_criteria_Item("rank_min", $posts, "<="));
			$criteria->add(new icms_db_criteria_Item("rank_max", $posts, ">="));
			$criteria->add(new icms_db_criteria_Item("rank_special", "0"));
		}

		$ranks = $this->getObjects($criteria);
		if (count($ranks) != 1) {
			$rank = array(
				"id" => 0,
				"title" => "",
				"image" => ICMS_UPLOAD_URL . "blank.gif");
		} else {
			$rank = array(
				"id" => $rank_id,
				"title" => $ranks[0]->getVar("rank_title"),
				"image" => $this->getImageUrl() . $ranks[0]->getVar("rank_image"));
		}

		return $rank;
	}

	public function MoveAllRanksImagesToProperPath() {
		$sql = "SELECT rank_image FROM " . $this->table;
		$Query = $this->query($sql, FALSE);
		for ($i = 0; $i < count($Query); $i++) {
			$values[] = $Query[$i]["rank_image"];
		}

		foreach ($values as $value) {
			if (file_exists(ICMS_UPLOAD_PATH . "/" . $value)) {
				icms_core_Filesystem::copyRecursive(ICMS_UPLOAD_PATH . "/" . $value, $this->getImagePath() . $value);
			}
		}

		return TRUE;
	}
}