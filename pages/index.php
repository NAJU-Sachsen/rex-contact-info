<?php

$addon = rex_addon::get('naju_contact_info');

$func = rex_get('func');
$group = rex_get('group');
if ($func == 'update' && $group) {
    $office = rex_post('office_name');
    $business_hours = rex_post('business_hours');
    $street = rex_post('street');
    $city = rex_post('city');
    $email = rex_post('email');
    $phone = rex_post('phone');
	$insta = rex_post('insta');
	$facebook = rex_post('facebook');
    $whatsapp = rex_post('whatsapp');
    $telegram = rex_post('telegram');
    $seo_title = rex_post('seo-title');
    $seo_description = rex_post('seo-description');

    $sql = rex_sql::factory();
    $sql->setTable('naju_contact_info');
    $sql->setWhere(['group_id' => $group]);
    $sql->setValues(['office_name' => $office, 'business_hours' => $business_hours,
            'street' => $street, 'city' => $city, 'email' => $email, 'phone' => $phone,
            'instagram' => $insta, 'facebook' => $facebook, 'whatsapp' => $whatsapp, 'telegram' => $telegram,
            'seo_title_prefix' => $seo_title, 'seo_description' => $seo_description]);
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
            c.business_hours,
            c.street,
            c.city,
            c.email,
            c.phone,
			c.instagram,
            c.facebook,
            c.whatsapp,
            c.telegram,
            c.seo_title_prefix,
            c.seo_description
        from naju_contact_info c
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
            c.business_hours,
            c.street,
            c.city,
            c.email,
            c.phone,
            c.instagram,
			c.facebook,
            c.whatsapp,
            c.telegram,
            c.seo_title_prefix,
            c.seo_description
        from naju_contact_info  c
        join naju_local_group   g   on c.group_id = g.group_id
        join naju_group_account a   on c.group_id = a.group_id
        where
            a.account_id = :id
EOSQL;
    $local_groups = rex_sql::factory()->setQuery($groups_query, ['id' => $user_id])->getArray();
}

$content .= '<div class="container-fluid" style="padding: 15px;"><div class="row">';

foreach ($local_groups as $group) {
    $content .= '<div class="col-md-4"><article class="panel panel-default">';
    $content .= '<header class="panel-heading"><h3 class="panel-title">' . rex_escape($group['group_name']) . '</h3></header>';
    $url_params = ['func' => 'update', 'group' => urlencode($group['group_id'])];
    $content .= '<div class="panel-body"><form method="post" action="' . rex_url::currentBackendPage($url_params) . '">';

    $content .= '
        <div class="form-group">
            <label for="office_name">Büro:</label>
            <input type="text" name="office_name" id="office_name" autocomplete="off"
                placeholder="Wie heißt das Büro?" class="form-control" value="' . rex_escape($group['office_name']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="business_hours">Öffnungszeiten:</label>
            <textarea name="business_hours" id="business_hours" class="form-control">' . rex_escape($group['business_hours']) . '</textarea>
        </div>';
    $content .= '
        <div class="form-group">
            <label for="street">Straße:</label>
            <input type="text" name="street" id="street" autocomplete="off"
                placeholder="Straße / Hausnummer" class="form-control" value="' . rex_escape($group['street']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="city">Stadt:</label>
            <input type="text" name="city" id="city" autocomplete="off"
                placeholder="Postleitzahl / Stadt" class="form-control" value="' . rex_escape($group['city']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="email">E-Mail:</label>
            <input type="email" name="email" id="email" autocomplete="off"
                placeholder="Email" class="form-control" value="' . rex_escape($group['email']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="phone">Telefon:</label>
            <input type="tel" name="phone" id="phone" autocomplete="off"
                placeholder="Telefon" class="form-control" value="' . rex_escape($group['phone']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="insta">Instagram Handle:</label>
            <input type="text" name="insta" id="insta" autocomplete="off"
                placeholder="@Naju_sachsen" class="form-control" value="' . rex_escape($group['instagram']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="facebook">Facebook-Seite:</label>
            <input type="url" name="facebook" id="facebook" autocomplete="off"
                placeholder="Link zur Facebook-Seite" class="form-control" value="' . rex_escape($group['facebook']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="whatsapp">Whatsapp-Kanal:</label>
            <input type="url" name="whatsapp" id="whatsapp" autocomplete="off"
                placeholder="Link zum Whatsapp-Kanal" class="form-control" value="' . rex_escape($group['whatsapp']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="telegram">Telegram-Kanal:</label>
            <input type="url" name="telegram" id="telegram" autocomplete="off"
                placeholder="Link zum Telegram-Kanal" class="form-control" value="' . rex_escape($group['telegram']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="seo-title">SEO-Titel:</label>
            <input type="text" name="seo-title" id="seo-title" autocomplete="off"
                placeholder="Seitentitel für Suchmaschinen" class="form-control" value="' . rex_escape($group['seo_title_prefix']) . '">
        </div>';
    $content .= '
        <div class="form-group">
            <label for="seo-description">SEO-Beschreibung:</label>
            <textarea name="seo-description" id="seo-description" autocomplete="off"
                placeholder="Beschreibung der Ortsgruppe für Suchmaschinen (wird von Seitenbeschreibungen überschrieben)"
                class="form-control">' .
                rex_escape($group['seo_description']) .
            '</textarea>
        </div>';
    $content .= '<div class="form-group pull-right"><button type="submit" class="btn btn-primary">Aktualisieren</button></div>';

    $content .= '</form></div>';
    $content .= '</article></div>';
}

$content .= '</div></div>';
$fragment->setVar('content', $content, false);
echo $fragment->parse('core/page/section.php');
