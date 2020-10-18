
create table if not exists naju_contact_info (
	group_id 				int(10) 			unsigned not null,
	office_name			varchar(50) 	default '',
	business_hours	varchar(255)	default '',
	street 					varchar(50) 	default '',
	city						varchar(50) 	default '',
	email 					varchar(30) 	default '',
	phone 					varchar(20) 	default '',
	instagram 			varchar(120) 	default '',
	facebook 				varchar(120) 	default '',
	whatsapp				varchar(120)	default '',
	telegram				varchar(120)	default '',

	primary key (group_id),
	foreign key fk_contact_group (group_id) references naju_local_group(group_id)
);
