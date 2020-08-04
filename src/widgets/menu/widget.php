<?php

/**
 * A site menu
 * @package Menu Widget
 * @author  J Andrew Scott <jascott@programmer.net>
 */

use Plutonium\Application\Widget;
use Plutonium\Database\AbstractAdapter;

class MenuWidget extends Widget {
	public function render() {
		$table = Table::getInstance('pages', 'site');

		if ($pages = $table->find()) {
			$this->setRef('pages', $pages);

			$format = $this->_application->request->get('format', 'html');

			foreach ($pages as $page) {
				$page->url = PU_URL_BASE . FS . $page->slug . '.' . $format;
			}
		}

		return parent::render();
	}
}
