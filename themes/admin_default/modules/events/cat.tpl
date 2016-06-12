<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <colgroup>
            <col class="w100">
        </colgroup>
        <thead>
            <tr>
                <th>{LANG.order}</th>
                <th>{LANG.cat_title}</th>
                <th>{LANG.function}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <select id="change_weight_{ROW.catid}" onchange="nv_change_cat_weight('{ROW.catid}');" class="form-control">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td>{ROW.title}</td>
                <td class="text-center">
                    <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
                    <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_cat('{ROW.catid}');">{GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<a id="addedit" name="addedit"></a>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption>{CAPTION}</caption>
            <col class="w200"/>
            <tbody>
                <tr>
                    <td class="text-right text-strong">{LANG.title}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
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
                                <button class="btn btn-default" type="button" onclick="get_cat_alias('{DATA.catid}')"><i class="fa fa-retweet"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right text-strong">{LANG.cat_description}</td>
                    <td>
                        <input type="text" name="description" value="{DATA.description}" class="form-control w500">
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="catid" value="{DATA.catid}">
                        <input type="submit" name="submit" value="{GLANG.submit}" class="btn btn-primary">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- BEGIN: getalias -->
<script type="text/javascript">
$(document).ready(function(){
    $("#idtitle").change(function(){
        get_cat_alias('{DATA.catid}');
    });
});
</script>
<!-- END: getalias -->
<!-- END: main -->