<?php

class MenuWidget extends Plutonium_Widget_Abstract {
	public function display() {
		global $registry;
		
		$database = $registry->database;
		
		$table = $database->quoteSymbol('mod_site_pages');
		
		$sql = "SELECT * FROM $table";
		
		if (($result = $database->query($sql)) !== false) {
			$pages  = $result->fetchAll('object');
			$result->close();
			
			$this->setRef('pages', $pages);
			
			$request =& Plutonium_Request::getInstance();
			$format  =  $request->get('format', 'html');
			
			foreach ($pages as $page) {
				$page->url = P_BASE_URL . '/index.php/site/pages/details/'
						   . $page->id . ':' . $page->slug . '.' . $format;
			}
		}
		
		return parent::display();
	}
}
