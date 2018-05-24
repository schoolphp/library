<?php
namespace FW\Access;
class Access
{
	private static $allow = [];
	private static $groups = [];

	static private function getGroups($id) {
		if(!isset($groups[$id])) {
			$groups = [];
				$res = \q("
				SELECT `group_id`
				FROM `fw_users2groups`
				WHERE `user_id` = ".(int)$id."
			");
			while($row = $res->fetch_assoc()) {
				$groups[] = $row['group_id'];
			}
			self::$groups[$id] = $groups;
		}
		return self::$groups[$id];
	}


	static public function isAllowKeys($keys = [], $id = 0) {
		if(is_string($keys)) {
			$keys = [$keys];
			$count = 1;
		} else {
			$count = count($keys);
		}

		if(!$count) {
			return false;
		} elseif($count === 1 && isset(self::$allow[$id][$keys[0]])) {
			return self::$allow[$id][$keys[0]];
		}

		if(!$id && !empty(\User::$id)) {
			$id = \User::$id;
		}
		$groups = self::getGroups($id);
		if(!count($groups)) {
			return false;
		}

		$rights = [];
		$rights[] = "`right` = '*'";
		foreach($keys as $v) {
			$rights[] = "`right` = '".es($v)."'";
		}
		$res = \q("
			SELECT 1
			FROM `fw_users_groups_rights`
			WHERE `group_id` IN (".implode(',', $groups).") AND (
				".(implode(' OR ',$rights))."
			)
			LIMIT 1
		");
		if($res->num_rows) {
			if($count === 1) {
				self::$allow[$id][$keys[0]] = true;
			}
			return true;
		} else {
			if($count === 1) {
				self::$allow[$id][$keys[0]] = false;
			}
			return false;
		}
	}

	static public function isAdmin($id = 0) {
		$key = 'isAdmin';

		if(!$id && !empty(\User::$id)) {
			$id = \User::$id;
		}

		if(isset(self::$allow[$id]['key-'.$key])) {
			return self::$allow[$id]['key-'.$key];
		}

		$res = \q("
			SELECT 1
			FROM `fw_users2groups`
			WHERE `user_id` = ".(int)$id."
			  AND `group_id` = 1
		");
		return (bool)$res->num_rows;
	}

	static public function isAllowAdmin($key = '', $id = 0) {
		if(!$id && !empty(\User::$id)) {
			$id = \User::$id;
		}

		if(isset(self::$allow[$id]['key-'.$key])) {
			return self::$allow[$id]['key-'.$key];
		}

		$groups = self::getGroups($id);
		if(!count($groups)) {
			self::$allow[$id]['key-'.$key] = false;
			return false;
		}

		if(defined('ADMIN')) {

			$res = \q("
				SELECT 1
				FROM `fw_users_groups_rights`
				WHERE `group_id` IN (".implode(',', $groups).") AND (
				   `right` = '*'
				   ".(!empty($key) ? "OR `right` = '".es($key)."'" : '')."
				   OR `right` = '/admin/".es($_GET['_module'])."/*'
				   OR `right` = '/admin/".es($_GET['_module'])."/".es($_GET['_page'])."/*'
				   OR `right` = '".es($_GET['route'])."'
				)
				LIMIT 1
			");
			if($res->num_rows) {
				self::$allow[$id]['key-'.$key] = true;
				return true;
			} else {
				self::$allow[$id]['key-'.$key] = false;
				return false;
			}
		}

		return true;
	}
}