<?php

/**
 * @Project EVENTS 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 12 Jun 2016 05:02:54 GMT
 */

if (!defined('NV_MOD_EVENTS')) {
    die('Stop!!!');
}

$page_title = $global_array_cat[$catid]['title'];
$key_words = $module_info['keywords'];

$catalias = $global_array_cat[$catid]['alias'];

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $catalias;
$base_url_rewrite = nv_url_rewrite($base_url, true);
$page_url_rewrite = $page ? nv_url_rewrite($base_url . '/page-' . $page, true) : $base_url_rewrite;
$request_uri = $_SERVER['REQUEST_URI'];

if (!($home or $request_uri == $base_url_rewrite or $request_uri == $page_url_rewrite or NV_MAIN_DOMAIN . $request_uri == $base_url_rewrite or NV_MAIN_DOMAIN . $request_uri == $page_url_rewrite)) {
    $redirect = '<meta http-equiv="Refresh" content="3;URL=' . $base_url_rewrite . '" />';
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
}

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_rows')->where('status = 1 AND catids LIKE \'%' . $catid . '%\'');

$num_items = $db->query($db->sql())->fetchColumn();

$db->select('id, catids, time_start, title, alias, location, images')->order('time_start DESC')->limit($per_page)->offset(($page - 1) * $per_page);

$result = $db->query($db->sql());
$array = [];

while ($row = $result->fetch()) {
    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
    $row['cat'] = [];

    $catids = array_filter(explode(',', $row['catids']));

    foreach ($catids as $_catid) {
        if (isset($global_array_cat[$_catid])) {
            $row['cat'][] = [
                'title' => $global_array_cat[$_catid]['title'],
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$_catid]['alias']
            ];
        }
    }

    $array[] = $row;
}

if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

$generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
$contents = nv_main_theme($array, $generate_page, $global_array_cat[$catid]['title']);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
