<?php

class PagesController extends Plutonium_Module_Controller {
	public function defaultAction() {
		$view =& $this->getView();
		$view->setLayout('details');
		
		$database = Plutonium_Database_Helper::getAdapter();
		
		$field = $database->quoteSymbol('id');
		$table = $database->quoteSymbol('mod_site_pages');
		$where = $database->quoteSymbol('parent_id');
		
		$sql = "SELECT $field FROM $table WHERE $where = 0";
		
		if ($result = $database->query($sql)) {
			if (($id = $result->fetchResult()) !== false)
				$view->setVal('slug', $id);
		
			$result->close();
		}
	}
}
