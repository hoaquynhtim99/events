<?php

/**
 * @Project EVENTS 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 12 Jun 2016 05:02:54 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

// Change alias
if ($nv_Request->isset_request('changealias', 'post')) {
    $title = $nv_Request->get_title('title', 'post', '');
    $id = $nv_Request->get_int('id', 'post', 0);

    $alias = change_alias($title);

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id !=' . $id . ' AND alias = :alias');
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn()) {
        $weight = $db->query('SELECT MAX(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows')->fetchColumn();
        $weight = intval($weight) + 1;
        $alias = $alias . '-' . $weight;
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $alias;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Change status
if ($nv_Request->isset_request('changestatus', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);

    if (empty($id))
        die("NO");

    $sql = "SELECT title, status FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id;
    $result = $db->query($sql);
    list($title, $status) = $result->fetch(3);

    if (empty($title))
        die('NO');
    $status = $status == 1 ? 0 : 1;

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET status = " . $status . " WHERE id = " . $id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    die("OK");
}

// Delete row
if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
    $id = $db->query($sql)->fetchColumn();

    if (empty($id))
        die('NO_' . $id);

    $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $id;
    include NV_ROOTDIR . '/includes/footer.php';
}

$page_title = $lang_module['list'];
$catid = $nv_Request->get_int('catid', 'get', 0);
$per_page = $nv_Request->get_int('per_page', 'get', 20);
$page = $nv_Request->get_int('page', 'get', 1);

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_rows');

if (!empty($catid)) {
    $db->where('catids LIKE \'%,' . $catid . ',%\'');
}

$num_items = $db->query($db->sql())->fetchColumn();

$db->select('*')->order('id DESC')->limit($per_page)->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

while ($row = $result->fetch()) {
    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
    $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id'];
    $row['status_render'] = $row['status'] ? ' checked="checked"' : '';

    $h = nv_date('H', $row['time_start']);
    $m = nv_date('i', $row['time_start']);
    $d = nv_date('d/m/Y', $row['time_start']);

    $row['time'] = ($h != '00' ? $h . ':' . $m . ' ' : '') . $d;

    if (!empty($row['time_end'])) {
        $h = nv_date('H', $row['time_end']);
        $m = nv_date('i', $row['time_end']);
        $d = nv_date('d/m/Y', $row['time_end']);

        $row['time'] .= ' - ' . ($h != '00' ? $h . ':' . $m . ' ' : '') . $d;
    }

    $xtpl->assign('ROW', $row);

    $xtpl->parse('main.loop');
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;catid=' . $catid;
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
