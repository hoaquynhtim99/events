<!-- BEGIN: main -->
<!-- BEGIN: js -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{BLOCK_JS}/js/{MODULE_FILE}.js"></script>
<!-- END: js -->
<!-- BEGIN: css -->
<link type="text/css" rel="stylesheet" href="{NV_BASE_SITEURL}themes/{BLOCK_CSS}/css/{MODULE_FILE}.css"/>
<!-- END: css -->
<ul class="notice">
    <!-- BEGIN: loop -->
    <li class="clearfix">
        <span class="wdate">
            <span class="d">{ROW.pubDateM}</span>
            <span class="y">{ROW.pubDateY}</span>
        </span>
        <a href="{ROW.link}">{ROW.title}</a><br />
        <strong>{ROW.data_other_1}</strong>
    </li>
    <!-- END: loop -->
</ul>
<!-- END: main -->
