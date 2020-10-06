<?php

$t_contact_info = 'naju_contact_info';

if (rex_version::compare($this->getVersion(), '0.0.2', '>=')) {
	// Update from 0.0.1 to something newer
	rex_sql_table::get(rex::getTable($t_contact_info))
		->ensureColumn(new rex_sql_column('instagram', 'varchar(120)', false, ''))
		->ensureColumn(new rex_sql_column('facebook', 'varchar(120)', false, ''))
		->alter();
}

if (rex_version::compare($this->getVersion(), '0.0.3', '>=')) {
	// Update from 0.0.2 to something newer
	rex_sql_table::get(rex::getTable($t_contact_info))
		->ensureColumn(new rex_sql_column('business_hours', 'varchar(255)', $default = ''))
		->alter();
}
