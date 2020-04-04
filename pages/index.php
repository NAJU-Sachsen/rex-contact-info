<?php

$addon = rex_addon::get('naju_contact_info');

$func = rex_get('func');
$group = rex_get('group');
if ($func == 'update' && $group) {
    $office = rex_post('office_name');
    $street = rex_post('street');
    $city = rex_post('city');
    $email = rex_post('email');
    $phone = rex_post('phone');

    $sql = rex_sql::factory();
    $sql->setTable('naju_contact_info');
    $sql->setWhere(['group_id' => $group]);
    $sql->setValues(['office_name' => $office, 'street' => $street, 'city' => $city, 'email' => $email, 'phone' => $phone]);
    $sql->update();
}

naju_contacts_update::inflateMissingLocalGroups();

echo rex_view::title('Kontakt-Informationen');
$fragment = new rex_fragment();
$content = '';

if (rex::getUser()->isAdmin()) {
    $groups_query = <<<EOSQL
        select
            c.group_id,
            g.group_name,
            c.office_name,
            c.street,
            c.city,
            c.email,
            c.phone
        from
            naju_contact_info c
                join naju_local_group g on c.group_id = g.group_id
    EOSQL;
    $local_groups = rex_sql::factory()->setQuery($groups_query)->getArray();
} else {
    $user_id = rex::getUser()->getId();
    $groups_query = <<<EOSQL
        select
            c.group_id,
            g.group_name,
            c.office_name,
            c.street,
            c.city,
            c.email,
            c.phone
        from
            naju_contact_info c
                join naju_local_group g on c.group_id = g.group_id
                join naju_group_account a on c.group_id = a.group_id
        where
            a.account_id = :id
    EOSQL;
    $local_groups = rex_sql::factory()->setQuery($groups_query, ['id' => $user_id])->getArray();
}

$content .= '<div class="container-fluid" style="padding: 15px;"><div class="row">';

foreach ($local_groups as $group) {
    $content .= '<div class="col-md-4"><article class="panel panel-default">';
    $content .= '<header class="panel-heading"><h3 class="panel-title">' . htmlspecialchars($group['group_name']) . '</h3></header>';
    $content .= '<div class="panel-body"><form method="post" action="' . rex_url::currentBackendPage(['func' => 'update', 'group' => urlencode($group['group_id'])]) . '">';
    
    $content .= '
        <div class="form-group">
            <label for="office_name">Büro:</label>
            <input type="text" name="office_name" id="office_name" autocomplete="off"
                placeholder="Wie heißt das Büro?" class="form-control" value="' . htmlspecialchars($group['office_name']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="street">Straße:</label>
            <input type="text" name="street" id="street" autocomplete="off"
                placeholder="Straße / Hausnummer" class="form-control" value="' . htmlspecialchars($group['street']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="city">Stadt:</label>
            <input type="text" name="city" id="city" autocomplete="off"
                placeholder="Postleitzahl / Stadt" class="form-control" value="' . htmlspecialchars($group['city']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="email">E-Mail:</label>
            <input type="email" name="email" id="email" autocomplete="off"
                placeholder="Email" class="form-control" value="' . htmlspecialchars($group['email']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="phone">Telefon:</label>
            <input type="tel" name="phone" id="phone" autocomplete="off"
                placeholder="Telefon" class="form-control" value="' . htmlspecialchars($group['phone']) . '">
        </div>';
    $content .= '<div class="form-group pull-right"><button type="submit" class="btn btn-primary">Aktualisieren</button></div>';

    $content .= '</form></div>';
    $content .= '</article></div>';
}

$content .= '</div></div>';
$fragment->setVar('content', $content, false);
echo $fragment->parse('core/page/section.php');
