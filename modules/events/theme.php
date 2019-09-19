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

/**
 * nv_main_theme()
 *
 * @param mixed $array
 * @param mixed $generate_page
 * @param string $title
 * @param array $search
 * @return
 */
function nv_main_theme($array, $generate_page, $title = '', $search = [])
{
    global $module_file, $lang_module, $module_info, $module_upload;

    $xtpl = new XTemplate('list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

    if (!empty($title)) {
        $xtpl->assign('TITLE', $title);
        $xtpl->parse('main.title');
    }

    $images_default = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image-available.jpg';

    foreach ($array as $row) {
        if (!empty($row['images']) and is_file(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/images/' . $row['images'])) {
            $row['images'] = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $module_upload . '/images/' . $row['images'];
        } elseif (!empty($row['images']) and is_file(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $row['images'])) {
            $row['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $row['images'];
        } else {
            $row['images'] = $images_default;
        }

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

        if (!empty($row['location'])) {
            $xtpl->parse('main.loop.location');
        }

        if (!empty($row['cat'])) {
            foreach ($row['cat'] as $cat) {
                $xtpl->assign('CAT', $cat);
                $xtpl->parse('main.loop.cat.loop');
            }

            $xtpl->parse('main.loop.cat');
        }

        $xtpl->parse('main.loop');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    if (!empty($search)) {
        global $global_array_cat, $module_name;

        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php');
        $xtpl->assign('MODULE_NAME', $module_name);
        $xtpl->assign('SEARCH', $search);

        foreach ($global_array_cat as $cat) {
            $cat['selected'] = $cat['catid'] == $search['catid'] ? ' selected="selected"' : '';

            $xtpl->assign('CAT', $cat);
            $xtpl->parse('main.search.cat');
        }

        $xtpl->parse('main.search');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_detail_theme()
 *
 * @param mixed $row
 * @return
 */
function nv_detail_theme($row)
{
    global $module_file, $lang_module, $module_info, $module_upload;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

    $images_default = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image-available.jpg';
    if (!empty($row['images']) and is_file(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/images/' . $row['images'])) {
        $row['images'] = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $module_upload . '/images/' . $row['images'];
    } elseif (!empty($row['images']) and is_file(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $row['images'])) {
        $row['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $row['images'];
    } else {
        $row['images'] = $images_default;
    }

    $row['addtime'] = nv_date("l, d/m/Y", $row['addtime']);
    $row['edittime'] = nv_date("d/m/Y", $row['edittime']);

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

    if (!empty($row['location'])) {
        $xtpl->parse('main.location');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_info_theme()
 *
 * @param mixed $message
 * @param mixed $link
 * @param string $type
 * @return
 */
function nv_info_theme($message, $link, $type = 'info')
{
    global $module_file, $lang_module, $module_info;

    $xtpl = new XTemplate('info.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MESSAGE', $message);
    $xtpl->assign('LINK', $link);

    if ($type == 'error') {
        $xtpl->parse('main.error');
    } else {
        $xtpl->parse('main.info');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
