<?php

/**
 * @Project EVENTS 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 12 Jun 2016 05:02:54 GMT
 */

if (!defined('NV_MOD_EVENTS')) {
    die('Stop!!!');
}

$is_search = true;
$search = [];

if (isset($array_op[1])) {
    $db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_rows');

    $sql = ['status = 1'];

    if (preg_match("/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/", $array_op[1], $m)) {
        $page_title = $lang_module['search_by_day'] . ' ' . $array_op[1];
        $time = mktime(0, 0, 0, intval($m[2]), intval($m[1]), intval($m[3]));
        $sql[] = '( ( time_start <= ' . $time . ' AND time_end = 0 ) OR ( time_start >= ' . $time . ' AND time_end < ' . ($time + 86400) . ' ) )';
    } elseif (preg_match("/^(being|before|after)\-([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/", $array_op[1], $m)) {
        $time = mktime(0, 0, 0, intval($m[3]), intval($m[2]), intval($m[4]));

        if ($m[1] == 'being') {
            $page_title = $lang_module['search_being_day'] . ' ' . $m[2] . '/' . $m[3] . '/' . $m[4];

            $sql[] = 'time_start >= ' . $time;
            $sql[] = 'time_end < ' . ($time + 86400);
            $sql[] = 'time_end > 0 ';
        } elseif ($m[1] == 'before') {
            $page_title = $lang_module['search_before_day'] . ' ' . $m[2] . '/' . $m[3] . '/' . $m[4];

            $sql[] = 'time_end < ' . $time;
            $sql[] = 'time_end > 0';
        } else {
            $page_title = $lang_module['search_after_day'] . ' ' . $m[2] . '/' . $m[3] . '/' . $m[4];

            $sql[] = 'time_start >= ' . ($time + 86400);
        }
    } else {
        $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
    }

    if (isset($array_op[2])) {
        if (preg_match("/^page\-([0-9]+)$/i", $array_op[2], $m)) {
            $page = intval($m[1]);
        } else {
            $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
            nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
        }
    }

    $is_search = false;
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '/' . $array_op[1];
    $db->where(implode(' AND ', $sql));
} else {
    $page = $nv_Request->get_int('p', 'get', 1);

    $search['key'] = $nv_Request->get_title('q', 'get', '');
    $search['catid'] = $nv_Request->get_int('c', 'get', 0);

    if (empty($search['key']) and $nv_Request->isset_request('q', 'get')) {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true));
        die();
    }

    $request_uri = $_SERVER['REQUEST_URI'];
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

    if (!empty($search['key'])) {
        $base_url .= '&q=' . urlencode($search['key']);
    }
    if (!empty($search['catid'])) {
        $base_url .= '&c=' . $search['catid'];
    }

    $base_url_rewrite = nv_url_rewrite($base_url . ($page > 1 ? '&p=' . $page : ''), true);

    if ($request_uri != $base_url_rewrite and NV_MAIN_DOMAIN . $request_uri != $base_url_rewrite) {
        header('Location: ' . $base_url_rewrite);
        die();
    }

    // Check get data
    if ($search['catid'] > 0 and !isset($global_array_cat[$search['catid']])) {
        $redirect = '<meta http-equiv="Refresh" content="3;URL=' . $base_url_rewrite . '" />';
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
    }

    $db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_rows');

    $sql = ['status = 1'];

    $dbkey = $db->dblikeescape($search['key']);

    $sql[] = "(title LIKE '%" . $dbkey . "%' OR hometext LIKE '%" . $dbkey . "%')";

    if (!empty($search['catid'])) {
        $sql[] = "catids LIKE '%" . $search['catid'] . "%'";
    }

    $db->where(implode(' AND ', $sql));

    if (empty($search['key'])) {
        $page_title = $lang_module['search_title'];
    } else {
        $page_title = $search['key'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_module['search_title'];
    }
}

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

$key_words = $description = 'no';

$generate_page = nv_generate_page(['link' => $base_url, 'amp' => '&p='], $num_items, $per_page, $page);

$contents = nv_main_theme($array, $generate_page, $page_title, $search);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
