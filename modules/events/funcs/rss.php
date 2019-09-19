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

$channel = [];
$items = [];

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];

$db->sqlreset()->select('id, time_start, time_end, title, alias, location, addtime')->order('id DESC')->limit(30);

$where = 'status = 1';

if (isset($array_op[1])) {
    $_catid = 0;
    foreach ($global_array_cat as $cat) {
        if ($cat['alias'] == $array_op[1]) {
            $_catid = $cat['catid'];
            break;
        }
    }

    if (!empty($_catid)) {
        $where .= ' AND catids LIKE \'%,' . $_catid . ',%\'';
    } else {
        header('location:' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true));
        die();
    }
}

$db->from(NV_PREFIXLANG . '_' . $module_data . '_rows')->where($where);

if ($module_info['rss']) {
    $result = $db->query($db->sql());
    while (list($id, $time_start, $time_end, $title, $alias, $location, $publtime) = $result->fetch(3)) {
        $h = nv_date('H', $time_start);
        $m = nv_date('i', $time_start);
        $d = nv_date('d/m/Y', $time_start);

        $time = ($h != '00' ? $h . ':' . $m . ' ' : '') . $d;

        if (!empty($time_end)) {
            $h = nv_date('H', $time_end);
            $m = nv_date('i', $time_end);
            $d = nv_date('d/m/Y', $time_end);

            $time .= ' - ' . ($h != '00' ? $h . ':' . $m . ' ' : '') . $d;
        }

        $items[] = [
            'title' => $title,
            'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $alias . $global_config['rewrite_exturl'], //
            'guid' => $module_name . '_' . $id,
            'description' => $time . (!empty($location) ? '. ' . $location : ''),
            'pubdate' => $publtime
        ];
    }
}

nv_rss_generate($channel, $items);
die();
