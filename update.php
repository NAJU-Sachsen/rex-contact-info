<?php

$t_contact_info = 'naju_contact_info';

if (rex_version::compare($this->getVersion(), '0.0.2', '<')) {
	// Update to 0.0.2 from something older
	rex_sql_table::get($t_contact_info)
		->ensureColumn(new rex_sql_column('instagram', 'varchar(120)', false, ''))
		->ensureColumn(new rex_sql_column('facebook', 'varchar(120)', false, ''))
		->alter();
}

if (rex_version::compare($this->getVersion(), '0.0.3', '<')) {
	// Update to 0.0.3 from something older
	rex_sql_table::get($t_contact_info)
		->ensureColumn(new rex_sql_column('business_hours', 'varchar(255)', $default = ''))
		->alter();
}

if (rex_version::compare($this->getVersion(), '0.0.4', '<')) {
	// Update to 0.0.4 from something older
	rex_sql_table::get($t_contact_info)
		->ensureColumn(new rex_sql_column('whatsapp', 'varchar(120)', false, ''))
		->ensureColumn(new rex_sql_column('telegram', 'varchar(120)', false, ''))
		->alter();
}
