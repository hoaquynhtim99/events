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

$url = [];
$cacheFile = NV_LANG_DATA . '_sitemap_' . NV_CACHE_PREFIX . '.cache';
$pa = NV_CURRENTTIME - 7200;

if (($cache = $nv_Cache->getItem($module_name, $cacheFile)) != false and filemtime(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/' . $cacheFile) >= $pa) {
    $url = unserialize($cache);
} else {
    $db->sqlreset()->select('id, addtime, alias')->from(NV_PREFIXLANG . '_' . $module_data . '_rows')->where('status=1')->order('addtime DESC')->limit(1000);
    $result = $db->query($db->sql());

    $url = [];

    while (list($id, $publtime, $alias) = $result->fetch(3)) {
        $url[] = [
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $alias . $global_config['rewrite_exturl'],
            'publtime' => $publtime
        ];
    }

    $cache = serialize($url);
    $nv_Cache->setItem($module_name, $cacheFile, $cache);
}

nv_xmlSitemap_generate($url);
die();
