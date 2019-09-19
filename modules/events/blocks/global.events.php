<?php

/**
 * @Project EVENTS 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 12 Jun 2016 05:02:54 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_new_events')) {
    /**
     * nv_new_events()
     *
     * @param mixed $block_config
     * @param mixed $mod_data
     * @return
     */
    function nv_new_events($block_config, $mod_data)
    {
        global $module_array_cat, $site_mods, $module_info, $db, $module_config, $global_config, $lang_global, $module_name;

        $module = $block_config['module'];
        $mod_file = $site_mods[$module]['module_file'];

        $this_day = mktime(0, 0, 0, date('n'), date('j'), date('Y'));

        $array_block_news = [];
        $db->sqlreset()->select('time_start, time_end, title, alias')->from(NV_PREFIXLANG . '_' . $mod_data . '_rows')->order('time_start ASC')->where('status = 1 AND time_start >=' . $this_day)->limit(7);

        $events = $db->query($db->sql())->fetchAll();
        $num_events = sizeof($events);

        if ($num_events < 7) {
            $db->sqlreset()->select('time_start, time_end, title, alias')->from(NV_PREFIXLANG . '_' . $mod_data . '_rows')->order('time_start DESC')->where('status = 1 AND time_start <' . $this_day)->limit(7 - $num_events);

            $events_old = $db->query($db->sql())->fetchAll();

            $events = array_merge_recursive($events, $events_old);
        }

        foreach ($events as $event) {
            list($time_start, $time_end, $title, $alias) = array_values($event);

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

            $array_block_news[] = [
                'title' => $title,
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $alias . $global_config['rewrite_exturl'],
                'pubDateY' => nv_date('Y', $time_start),
                'pubDateM' => nv_date('d/m', $time_start),
                'data_other_1' => $time
            ];
        }

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $mod_file . '/block.new_event.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $mod_file . '/block.new_event.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block.new_event.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file);
        $xtpl->assign('LANG', $lang_global);

        if ($module != $module_name) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/css/' . $mod_file . '.css')) {
                $block_css = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/css/' . $mod_file . '.css')) {
                $block_css = $global_config['site_theme'];
            } else {
                $block_css = 'default';
            }
            $xtpl->assign('BLOCK_CSS', $block_css);
            $xtpl->parse('main.css');

            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/js/' . $mod_file . '.js')) {
                $block_js = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/js/' . $mod_file . '.js')) {
                $block_js = $global_config['site_theme'];
            } else {
                $block_js = 'default';
            }
            $xtpl->assign('BLOCK_JS', $block_js);
            $xtpl->parse('main.js');
        }

        $i = 1;
        foreach ($array_block_news as $array_news) {
            $xtpl->assign('ROW', $array_news);
            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods;

    $module = $block_config['module'];

    if (isset($site_mods[$module])) {
        $mod_data = $site_mods[$module]['module_data'];
        $content = nv_new_events($block_config, $mod_data);
    }
}
