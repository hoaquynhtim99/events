<?php

/**
 * @Project EVENTS 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 12 Jun 2016 05:02:54 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$submenu['content'] = $lang_module['add'];
$submenu['cat'] = $lang_module['cat'];

$allow_func = [
    'main',
    'content',
    'cat'
];
