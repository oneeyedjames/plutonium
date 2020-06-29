<?php

use Plutonium\Application\Model;

class FeedsModel extends Model {
	public function validate(&$data) {
		$data['updated'] = gmdate('Y-m-d H:i:s');

		if (!isset($data['id']))
			$data['created'] = $data['updated'];

		return true;
	}
}
