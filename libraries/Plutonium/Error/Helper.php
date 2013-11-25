<?php

class Plutonium_Error_Helper {
	protected static $_levels   = null;
	protected static $_handlers = array();

	public static function register($level, $handler) {
		if (is_null(self::$_levels)) {
			self::$_levels = E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE;
			set_error_handler(array(__CLASS__, 'trigger'), self::$_levels);
		}

		if ($level == $level & self::$_levels || $level === null) {
			if (function_exists($handler)) {
				self::$_handlers[] = array (
					'level'   => $level,
					'handler' => $handler
				);
			} elseif (class_exists($handler)) {
				self::$_handlers[] = new $handler();
			}
		}
	}

	public static function trigger($level, $message) {
		foreach (self::$_handlers as $handler) {
			if (is_object($handler)) {
				if (method_exists($handler, 'handle')) {
					$handler->handle($level, $message);
				}
			} elseif (is_array($handler)) {
				if ($handler['level'] == $level &&
					function_exists($handler['handler'])) {
					call_user_func($handler['handler'], $level, $message);
				}
			}
		}
	}

	public static function triggerError($message) {
		self::trigger(E_USER_ERROR, $message);
	}

	public static function triggerWarning($message) {
		self::trigger(E_USER_WARNING, $message);
	}

	public static function triggerNotice($message) {
		self::trigger(E_USER_NOTICE, $message);
	}
}

?>