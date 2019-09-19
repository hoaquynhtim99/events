<?php

/**
 * @Project EVENTS 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 12 Jun 2016 05:02:54 GMT
 */

if (!defined('NV_IS_MOD_RSS')) {
    die('Stop!!!');
}

$rssarray = [];

$sql = "SELECT catid, title, alias FROM " . NV_PREFIXLANG . "_" . $mod_data . "_cat ORDER BY weight ASC";
//$rssarray[] = array( 'catid' => 0, 'parentid' => 0, 'title' => '', 'link' => '');

$list = $nv_Cache->db($sql, '', $mod_name);
foreach ($list as $value) {
    $value['parentid'] = 0;
    $value['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=" . $mod_info['alias']['rss'] . "/" . $value['alias'];
    $rssarray[] = $value;
}
