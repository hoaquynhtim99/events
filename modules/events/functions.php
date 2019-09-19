<?php

/**
 * @Project EVENTS 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 12 Jun 2016 05:02:54 GMT
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_MOD_EVENTS', true);

$catid = 0;
$global_array_cat = [];
$global_array_cat_alias = [];
$array_mod_title = [];

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

// Xac dinh RSS
if ($module_info['rss']) {
    $rss[] = [
        'title' => $module_info['custom_title'],
        'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss']
    ];
}

foreach ($global_array_cat as $cat) {
    $rss[] = [
        'title' => $cat['title'],
        'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $cat['alias']
    ];
    $global_array_cat_alias[$cat['alias']] = $cat['catid'];
}

$page = 1;
$per_page = 15;

if ($op == 'main' and isset($array_op[0])) {
    if (isset($array_op[1])) {
        $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
    } elseif (preg_match("/^page\-([0-9]+)$/i", $array_op[0], $m)) {
        $page = intval($m[1]);
    } elseif (isset($global_array_cat_alias[$array_op[0]])) {
        if (isset($array_op[1])) {
            if (preg_match("/^page\-([0-9]+)$/i", $array_op[1], $m)) {
                $page = intval($m[1]);
            } else {
                $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
                nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
            }
        }

        $catid = $global_array_cat_alias[$array_op[0]];
        $op = 'viewcat';
    } else {
        $op = 'detail';
    }
}

unset($global_array_cat_alias);
