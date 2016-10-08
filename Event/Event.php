<?php
namespace FW\Event;

class Event {
	static $class = '\FW\Event\EventsHandler';
	static $events = [];
	static $handler = false;
	static function setEventHandler($class) {
		self::$class = $class;
	}

	static function add($name,$function) {
		self::$events[$name] = $function;
	}
	static function remove($name) {
		unset(self::$events[$name]);
	}

	static function trigger($name,$args = false) {
		if(isset(self::$events[$name])) {
			call_user_func(self::$events[$name],$args);
			return true;
		}
		if(!self::$handler) {
			self::$handler = new self::$class;
		}
		self::$handler->$name($args);
	}
}
