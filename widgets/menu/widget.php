<?php

/**
 * A site menu
 * @package Menu Widget
 * @author  J Andrew Scott <jascott@programmer.net>
 */
class MenuWidget extends Plutonium_Widget {
	public function display() {
		$database = Plutonium_Database_Adapter::getInstance();

		$table = $database->quoteSymbol('mod_site_pages');

		$sql = "SELECT * FROM $table";

		if (($result = $database->query($sql)) !== false) {
			$pages  = $result->fetchAll('object');
			$result->close();

			$this->setRef('pages', $pages);

			$format = $this->_application->request->get('format', 'html');

			foreach ($pages as $page) {
				$page->url = PU_URL_BASE . '/index.php/site/pages/details/'
						   . $page->id . ':' . $page->slug . '.' . $format;
			}
		}

		return parent::display();
	}
}
