<?php

/**
 * @Project EVENTS 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 12 Jun 2016 05:02:54 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post,get', 0);
$error = '';

if (!empty($id)) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $id;
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
    }

    $array['hometext'] = nv_br2nl($array['hometext']);
    $array['bodytext'] = nv_editor_br2nl($array['bodytext']);
    $array['catids'] = array_filter(explode(',', $array['catids']));

    $page_title = $lang_module['edit'];
} else {
    $array = [
        'id' => 0,
        'post_id' => $admin_info['userid'],
        'catids' => [],
        'time_start' => 0,
        'time_end    ' => 0,
        'location' => '',
        'title' => '',
        'alias' => '',
        'images' => '',
        'hometext' => '',
        'bodytext' => '',
    ];

    $page_title = $lang_module['add'];
}

$accept = $nv_Request->get_int('accept', 'post', 0);

if ($nv_Request->isset_request('submit', 'post')) {
    $array['catids'] = $nv_Request->get_typed_array('catids', 'post', 'int', []);

    $array['time_start_h'] = $nv_Request->get_int('time_start_h', 'post', 0);
    $array['time_start_m'] = $nv_Request->get_int('time_start_m', 'post', 0);
    $array['time_start_d'] = $nv_Request->get_string('time_start_d', 'post', '');

    if (preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $array['time_start_d'], $m)) {
        $array['time_start'] = mktime($array['time_start_h'], $array['time_start_m'], 0, intval($m[2]), intval($m[1]), intval($m[3]));
    } else {
        $array['time_start'] = 0;
    }

    $array['time_end_h'] = $nv_Request->get_int('time_end_h', 'post', 0);
    $array['time_end_m'] = $nv_Request->get_int('time_end_m', 'post', 0);
    $array['time_end_d'] = $nv_Request->get_string('time_end_d', 'post', '');

    if (preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $array['time_end_d'], $m)) {
        $array['time_end'] = mktime($array['time_end_h'], $array['time_end_m'], 0, intval($m[2]), intval($m[1]), intval($m[3]));
    } else {
        $array['time_end'] = 0;
    }

    $array['location'] = $nv_Request->get_title('location', 'post', '', true);
    $array['title'] = $nv_Request->get_title('title', 'post', '', true);
    $array['alias'] = $nv_Request->get_title('alias', 'post', '', true);
    $array['images'] = $nv_Request->get_title('images', 'post', '', false);
    $array['hometext'] = $nv_Request->get_textarea('hometext', '', NV_ALLOWED_HTML_TAGS);
    $array['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);

    $array['alias'] = empty($array['alias']) ? change_alias($array['title']) : change_alias($array['alias']);

    if (!empty($array['images'])) {
        $array['images'] = substr($array['images'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/images/'));
    }

    if (empty($array['title'])) {
        $error = $lang_module['content_error_title'];
    } elseif (empty($array['time_start'])) {
        $error = $lang_module['content_error_time_start'];
    } elseif (!empty($array['time_end']) and $array['time_start'] >= $array['time_end']) {
        $error = $lang_module['content_error_time_end'];
    } elseif (empty($array['hometext'])) {
        $error = $lang_module['content_error_hometext'];
    } elseif (empty($array['bodytext'])) {
        $error = $lang_module['content_error_bodytext'];
    } else {
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE alias = :alias' . ($array['id'] ? ' AND id != ' . $array['id'] : '');
        $sth = $db->prepare($sql);
        $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
        $sth->execute();
        $num = $sth->fetchColumn();

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE alias = :alias';
        $sth = $db->prepare($sql);
        $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
        $sth->execute();
        $num1 = $sth->fetchColumn();

        if (!empty($num) or !empty($num1)) {
            $error = $lang_module['content_error_alias'];
        } else {
            if (!$array['id']) {
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (post_id, catids, time_start, time_end, location, title, alias, images, hometext,
                bodytext, addtime, edittime, status ) VALUES (
                    ' . $admin_info['userid'] . ', :catids, :time_start, :time_end, :location, :title, :alias, :images, :hometext, :bodytext,
                    ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1
                )';
            } else {
                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
                    catids = :catids, time_start = :time_start, time_end = :time_end, location = :location, title = :title, alias = :alias, images = :images, hometext = :hometext,
                    bodytext = :bodytext, edittime = ' . NV_CURRENTTIME . ' WHERE id = ' . $array['id'];
            }

            $array['hometext'] = nv_nl2br($array['hometext']);
            $array['bodytext'] = nv_editor_nl2br($array['bodytext']);

            try {
                $sth = $db->prepare($sql);
                $sth->bindValue(':catids', empty($array['catids']) ? '' : ',' . implode(',', $array['catids']) . ',', PDO::PARAM_STR);
                $sth->bindParam(':time_start', $array['time_start'], PDO::PARAM_INT);
                $sth->bindParam(':time_end', $array['time_end'], PDO::PARAM_INT);
                $sth->bindParam(':location', $array['location'], PDO::PARAM_STR);
                $sth->bindParam(':title', $array['title'], PDO::PARAM_STR);
                $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
                $sth->bindParam(':images', $array['images'], PDO::PARAM_STR);
                $sth->bindParam(':hometext', $array['hometext'], PDO::PARAM_STR, strlen($array['hometext']));
                $sth->bindParam(':bodytext', $array['bodytext'], PDO::PARAM_STR, strlen($array['bodytext']));
                $sth->execute();

                if ($sth->rowCount()) {
                    if ($array['id']) {
                        nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit', 'ID: ' . $array['id'], $admin_info['userid']);
                    } else {
                        nv_insert_logs(NV_LANG_DATA, $module_name, 'Add', ' ', $admin_info['userid']);
                    }

                    $nv_Cache->delMod($module_name);
                    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                    die();
                } else {
                    $error = $lang_module['errorsave'];
                }
            } catch (PDOException $e) {
                $error = $lang_module['errorsave'];
            }
        }
    }
}

if (defined('NV_EDITOR'))
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

if (!empty($array['hometext']))
    $array['hometext'] = nv_htmlspecialchars($array['hometext']);
if (!empty($array['bodytext']))
    $array['bodytext'] = nv_htmlspecialchars($array['bodytext']);

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['bodytext'] = nv_aleditor('bodytext', '100%', '500px', $array['bodytext']);
} else {
    $array['bodytext'] = '<textarea style="width:100%;height:500px" name="bodytext">' . $array['bodytext'] . '</textarea>';
}

if (!empty($array['images'])) {
    $array['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $array['images'];
}

$array['time_start_d'] = !empty($array['time_start']) ? nv_date('d/m/Y', $array['time_start']) : '';
$array['time_start_h'] = !empty($array['time_start']) ? intval(nv_date('H', $array['time_start'])) : 0;
$array['time_start_m'] = !empty($array['time_start']) ? intval(nv_date('i', $array['time_start'])) : 0;

$array['time_end_d'] = !empty($array['time_end']) ? nv_date('d/m/Y', $array['time_end']) : '';
$array['time_end_h'] = !empty($array['time_end']) ? intval(nv_date('H', $array['time_end'])) : 0;
$array['time_end_m'] = !empty($array['time_end']) ? intval(nv_date('i', $array['time_end'])) : 0;

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('DATA', $array);
$xtpl->assign('UPLOADS_DIR', NV_UPLOADS_DIR . '/' . $module_upload . '/images');

if (empty($array['alias'])) {
    $xtpl->parse('main.getalias');
}

foreach ($global_array_cat as $cat) {
    $cat['checked'] = in_array($cat['catid'], $array['catids']) ? ' checked="checked"' : '';

    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
}

for ($i = 0; $i <= 23; $i++) {
    $hour = [
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected_start' => $i == $array['time_start_h'] ? ' selected="selected"' : '',
        'selected_end' => $i == $array['time_end_h'] ? ' selected="selected"' : '',
    ];

    $xtpl->assign('HOUR', $hour);
    $xtpl->parse('main.hour_start');
    $xtpl->parse('main.hour_end');
}

for ($i = 0; $i <= 59; $i++) {
    $min = [
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected_start' => $i == $array['time_start_m'] ? ' selected="selected"' : '',
        'selected_end' => $i == $array['time_end_m'] ? ' selected="selected"' : '',
    ];

    $xtpl->assign('MIN', $min);
    $xtpl->parse('main.min_start');
    $xtpl->parse('main.min_end');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
