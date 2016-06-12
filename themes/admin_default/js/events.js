/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_change_weight_res(res) {
    var r_split = res.split("_");
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
        clearTimeout(nv_timer);
    } else {
        window.location.href = window.location.href;
    }
    return;
}

function nv_change_cat_weight(catid) {
    var nv_timer = nv_settimeout_disable('change_weight_' + catid, 5000);
    var new_weight = $('#change_weight_' + catid).val();
    $.post(
        script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 
        'changeweight=1&catid=' + catid + '&new_weight=' + new_weight, function(res) {
        nv_change_weight_res(res);
    });
    return;
}

function nv_del_cat(catid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(
            script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(),
            'delete=1&catid=' + catid, function(res) {
            var r_split = res.split("_");
            if (r_split[0] == 'OK') {
                window.location.href = window.location.href;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function get_alias(id){
    var title = strip_tags(document.getElementById('idtitle').value);
    if (title != '') {
        $.post(
            script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 
            'changealias=1&title=' + encodeURIComponent(title) + '&id=' + id, function(res) {
            if (res != "") {
                document.getElementById('idalias').value = res;
            } else {
                document.getElementById('idalias').value = '';
            }
        });
    }
}

function nv_change_status( id ){
    var nv_timer = nv_settimeout_disable( 'change_status' + id, 4000 );
    $.post(
        script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 
        'changestatus=1&id=' + id, function(res){
            if( res != 'OK' ){
                alert( nv_is_change_act_confirm[2] );
                window.location.href = window.location.href;
            }
    });
}


function nv_del_row(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(
            script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            'delete=1&id=' + id, function(res) {
            var r_split = res.split("_");
            if (r_split[0] == 'OK') {
                window.location.href = window.location.href;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
}

function get_cat_alias(catid){
    var title = strip_tags(document.getElementById('idtitle').value);
    if (title != '') {
        $.post(
            script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 
            'changealias=1&title=' + encodeURIComponent(title) + '&catid=' + catid, function(res) {
            if (res != "") {
                document.getElementById('idalias').value = res;
            } else {
                document.getElementById('idalias').value = '';
            }
        });
    }
}

$(document).ready(function(){
    $("#select-image").click(function(){
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=post-image&alt=&path=" + $(this).data('path') + "&type=image&currentpath=" + $(this).data('path'), "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });
});