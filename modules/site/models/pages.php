<?php

use Plutonium\Application\Model;

use function Plutonium\Functions\slugify;

class PagesModel extends Model {
	public function validate(&$data) {
		if (empty($data['slug']))
			$data['slug'] = slugify($data['name']);

		return parent::validate($data);
	}
}