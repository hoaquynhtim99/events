/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

$(document).ready(function(){
    $('#employ-fearch-form').submit(function(e){
        if( $(this).find('[name="q"]').val() == '' ){
            e.preventDefault();
            alert($(this).data('notice'));
        }else{
            return 1;
        }
    });    
    // Event calendar
    if( $('#event-calendar').length > 0 ){
        $('#event-calendar').delegate( '.load-ajax', 'click', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).data('url'),
                data: 'load_event_calendar=' + $(this).data('post'),
                success: function(e){
                    $('#event-calendar').html(e);
                }
            });
        });
    }
});