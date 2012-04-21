<?php

class Plutonium_Parser_Theme extends Plutonium_Parser_Abstract {
	protected $_namespace = 'p';
	
	public function headTag($args) {
		$document =& Plutonium_Document_Helper::getDocument();
		
		return $document->getHeader();
	}
	
	public function moduleTag($args) {
		$response =& Plutonium_Response::getInstance();
		
		return $response->getModuleOutput();
	}
	
	public function widgetsTag($args) {
		$response =& Plutonium_Response::getInstance();
		
		return $response->getWidgetOutput($args['location']);
	}
	
	public function messageTag($args) {
		$session =& Plutonium_Session::getInstance();
		
		if ($message = $session->get('message', NULL)) {
			$session->del('message');
			return $message;
		}
		
		return '';
	}
}

?>