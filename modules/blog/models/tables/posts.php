<?php

use Plutonium\Database\Table;

class PostsTable extends Table {
	public function validate(&$row) {
		$row->updated = strftime('%Y-%m-%d %H:%M:%S');

		if (!$row->id) $row->created = $row->updated;

		return true;
	}
}
