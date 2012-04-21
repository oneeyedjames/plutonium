<?php

class FeedsTable extends Plutonium_Database_Table {
	public function validate(&$row) {
		$row->updated = strftime('%Y-%m-%d %H:%M:%S');
		
		if (!$row->id) $row->created = $row->updated;
		
		return TRUE;
	}
}
