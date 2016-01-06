<?php
namespace FW\User;

class Access {
	static $roles = [];
	static $groups = [];
	static function isAuth() {
		return (!empty(User::$data['id']));
	}

	static function getRoles() {
		if(!self::isAuth())
			return false;

		if(!count($roles)) {
			$res = q("
				SELECT `role_name`
				FROM `fw_users2roleslist`
				WHERE `user_id` = ".(int)User::$data['id']."
			");
			if($res->num_rows) {
				while($row = $res->fetch_assoc()) {
					self::$roles[$row['role_name']] = true;
				}
			} else {
				self::$roles['guest'] = true;
			}
		}

		return true;
	}

	static function isRole($role) {
		if(!self::isAuth())
			return false;

		$role = (string)$role;

		if(!count(self::$roles)) {
			self::getRoles();
		}

		if(!isset(self::$roles[$role])) {
			self::$roles[$role] = false;
		}

		return self::$roles[$role];
	}

	static function isAllow($group,$action,$full = false) {
		if(!self::isAuth())
			return false;

		if(isset(self::$groups[$group]['*'])) {
			return true;
		}

		if($full && isset(self::$groups[$group]['full'])) {
			if(!isset(self::$groups[$group][$action])) {
				return false;
			}
			return self::$groups[$group][$action];
		} elseif(!$full && isset(self::$groups[$group][$action])) {
			return self::$groups[$group][$action];
		}

		if(!count(self::$roles)) {
			self::getRoles();
		}

		if(isset(self::$roles['guest'])) {
			return false;
		}

		if($full) {
			self::$groups[$group]['full'] = true;
			$s_action = '';
		} else {
			$s_action = "AND `action` IN ('".es($action)."','*')";
		}
		$res = q("
			SELECT `action`
			FROM `fw_users_role2action`
			WHERE `role` IN (".implode(',',self::$roles).")
			  AND `group` = '".es($group)."'
			  ".$s_action."
		");
		while($row = $res->fetch_assoc()) {
			self::$groups[$group][$row['action']] = true;
		}
		if(!isset(self::$groups[$group][$action])) {
			self::$groups[$group][$action] = false;
		}

		if(isset(self::$groups[$group]['*'])) {
			return true;
		}

		return self::$groups[$group][$action];
	}
}
