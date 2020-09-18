<?php

if (rex_string::versionCompare($this->getVersion(), '0.0.2', '>=')) {
	// Update from 0.0.1 to something newer
	rex_sql_table::get(rex::getTable('naju_contact_info'))
		->ensureColumn(new rex_sql_column('instagram', 'varchar(120)', false, ''))
		->ensureColumn(new rex_sql_column('facebook', 'varchar(120)', false, ''))
		->alter();
}
