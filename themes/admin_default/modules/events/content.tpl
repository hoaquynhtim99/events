<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col class="w200"/>
            <tbody>
                <tr>
                    <td class="text-right text-strong">{LANG.cat}</td>
                    <td>
                        <div class="row event-content-cat-area">
                            <!-- BEGIN: cat -->
                            <div class="event-content-cat-item col-sm-6">
                                <label> <input type="checkbox" name="catids[]" value="{CAT.catid}"{CAT.checked}/> {CAT.title}</label>
                            </div>
                            <!-- END: cat -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.content_time_start}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
                    <td class="form-inline">
                        <select name="time_start_h" class="form-control w100">
                            <!-- BEGIN: hour_start --><option value="{HOUR.key}"{HOUR.selected_start}>{HOUR.title}</option><!-- END: hour_start -->
                        </select>
                        <select name="time_start_m" class="form-control w100">
                            <!-- BEGIN: min_start --><option value="{MIN.key}"{MIN.selected_start}>{MIN.title}</option><!-- END: min_start -->
                        </select>
                        <input type="text" name="time_start_d" value="{DATA.time_start_d}" class="form-control w250">
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.content_time_end}</td>
                    <td class="form-inline">
                        <select name="time_end_h" class="form-control w100">
                            <!-- BEGIN: hour_end --><option value="{HOUR.key}"{HOUR.selected_end}>{HOUR.title}</option><!-- END: hour_end -->
                        </select>
                        <select name="time_end_m" class="form-control w100">
                            <!-- BEGIN: min_end --><option value="{MIN.key}"{MIN.selected_end}>{MIN.title}</option><!-- END: min_end -->
                        </select>
                        <input type="text" name="time_end_d" value="{DATA.time_end_d}" class="form-control w250">
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.content_title}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
                    <td>
                        <input type="text" id="idtitle" name="title" value="{DATA.title}" class="form-control w500">
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.alias}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
                    <td>
                        <div class="input-group w500">
                            <input type="text" id="idalias" name="alias" value="{DATA.alias}" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="get_alias('{DATA.id}');"><i class="fa fa-retweet"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.content_location}</td>
                    <td>
                        <input type="text" name="location" value="{DATA.location}" class="form-control w500">
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.content_images}</td>
                    <td>
                        <div class="input-group w500">
                            <input type="text" id="post-image" name="images" value="{DATA.images}" class="form-control">
                            <span class="input-group-btn">
                                <button data-path="{UPLOADS_DIR}" id="select-image" class="btn btn-default" type="button"><i class="fa fa-file-image-o"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.content_hometext}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
                    <td>
                        <textarea name="hometext" class="form-control" rows="5">{DATA.hometext}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.content_bodytext}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
                    <td>
                        {DATA.bodytext}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="{DATA.id}">
                        <input type="submit" name="submit" value="{GLANG.submit}" class="btn btn-primary">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript">
$(document).ready(function(){
    $('[name="time_start_d"],[name="time_end_d"]').datepicker({
        showOn: "button",
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        showButtonPanel: true,
        showOn: 'focus'
    });
});
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<!-- BEGIN: getalias -->
<script type="text/javascript">
$(document).ready(function(){
    $("#idtitle").change(function(){
        get_alias('{DATA.id}');
    });
});
</script>
<!-- END: getalias -->
<!-- END: main -->