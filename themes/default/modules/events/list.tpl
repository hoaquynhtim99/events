<!-- BEGIN: main -->

<!-- BEGIN: title -->
<div class="h1">{TITLE}</div>
<div class="gdl-divider gdl-border-x top mt15 mb15"><div class="scroll-top"></div></div>
<!-- END: title -->

<!-- BEGIN: search -->
<form method="get" action="{FORM_ACTION}">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
	<input type="hidden" name="{NV_OP_VARIABLE}" value="search"/>
	<div class="event-search mb20">
		<div class="row clearfix">
			<div class="columns six">
				<input class="form-control" type="text" name="q" value="{SEARCH.key}" placeholder="{LANG.search_q}"/>
			</div>
			<div class="columns four">
				<select class="form-control" name="c">
					<option value="0">{LANG.search_all_cat}</option>
					<!-- BEGIN: cat --><option value="{CAT.catid}"{CAT.selected}>{CAT.title}</option><!-- END: cat -->
				</select>
			</div>
			<div class="columns two">
				<input type="submit" class="btn btn-orange btn-block" value="{LANG.search}"/>
			</div>
		</div>
	</div>
</form>
<!-- END: search -->

<div class="event-list clearfix">
	<!-- BEGIN: loop -->
	<div class="item clearfix">
		<div class="img">
			<a href=""><img src="{ROW.images}" alt="{ROW.title}"/></a>
		</div>
		<h3><a href="{ROW.link}">{ROW.title}</a></h3>
		<p><strong>{ROW.time}</strong></p>
		<!-- BEGIN: location --><p>{ROW.location}</p><!-- END: location -->
		<!-- BEGIN: cat -->
		{LANG.cat}: 
		<ul class="ecat">
			<!-- BEGIN: loop -->
			<li><a href="{CAT.link}">{CAT.title}</a></li>
			<!-- END: loop -->
		</ul>
		<!-- END: cat -->
	</div>
	<!-- END: loop -->
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">{GENERATE_PAGE}</div>
<!-- END: generate_page -->
<!-- END: main -->