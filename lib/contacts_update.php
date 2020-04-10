<?php

class naju_contacts_update
{
    public static function inflateMissingLocalGroups()
    {
        $new_groups_query = <<<EOSQL
            select group_id
            from naju_local_group
            where group_id not in (select group_id from naju_contact_info)
EOSQL;

        $sql = rex_sql::factory()->setQuery($new_groups_query);
        $missing_groups = $sql->getArray();

        // if there are no missing groups we are done already
        if (!$missing_groups) {
            return;
        }

        $values = '';
        foreach ($missing_groups as $group) {
            $values .= '(' . $sql->escape($group['group_id']) . '),';
        }

        $values = substr($values, 0, -1);

        $create_query = "insert into  naju_contact_info (group_id) values $values";
        rex_sql::factory()->setQuery($create_query);
    }
}