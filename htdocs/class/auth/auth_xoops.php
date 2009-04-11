<?php
// $Id$
// auth_xoops.php - XOOPS authentification class 
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

/**
 * Authentification class for Native XOOPS
 *  
 * @package     kernel
 * @subpackage  auth
 * @author	    Pierre-Eric MENUET	<pemphp@free.fr>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class XoopsAuthXoops extends XoopsAuth {

	/**
	 * Authentication Service constructor
   * constructor
   * @param object $dao reference to dao object
	 */
	function XoopsAuthXoops (&$dao) {
		$this->_dao = $dao;
		$this->auth_method = 'xoops';
	}

	/**
	 *  Authenticate user
	 *
	 * @param string $uname
	 * @param string $pwd
	 *
	 * @return object {@link XoopsUser} XoopsUser object
   */	
	function authenticate($uname, $pwd = null)
	{
		$member_handler =& xoops_gethandler('member');
		if(strstr($uname, '@'))
		{
			$uname = icms_getUnameFromUserEmail($uname);
		}
		$user =& $member_handler->loginUser($uname, $pwd);
		$sess_handler =& xoops_gethandler('session');
		$sess_handler->securityLevel = 3;
		$sess_handler->check_ip_blocks = 2;
		$sess_handler->salt_key = XOOPS_DB_SALT;
		$sess_handler->enableRegenerateId = true;
		$sess_handler->icms_sessionOpen();
		if($user == false)
		{
			$sess_handler->destroy(session_id());
			$this->setErrors(1, _US_INCORRECTLOGIN);
		}
		return ($user);
	}
}

?>