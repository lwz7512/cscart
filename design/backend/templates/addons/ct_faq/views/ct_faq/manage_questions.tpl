{** Copyright (c) 2012 CartTuning (www.carttuning.com). All rights reserved. **}

{**  section **}
{assign var="id" value=0}
{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="cs_product_use_for_form" class="form-horizontal form-edit{if ""|fn_check_form_permissions} cm-hide-inputs{/if}" enctype="multipart/form-data" >
	<input type="hidden" name="category_id" value="{$category_id}" />
	<table width="100%" class="table table-middle">
	<tbody>
	<tr class="cm-first-sibling">
		<th>{__("position_short")}</th>
		<th>{__("ct_faq_questions")}</th>
		{if $category_questions}<th>{__("status")}</th>{else}<th></th>{/if}
		<th class="cm-extended-feature "><img src="{$images_dir}/plus_minus.gif" width="13" height="12" border="0" name="plus_minus" id="on_st_{$id}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combinations-features-{$id}" /><img src="{$images_dir}/minus_plus.gif" width="13" height="12" border="0" name="minus_plus" id="off_st_{$id}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combinations-features-{$id}" /></th>
		<th>&nbsp;</th>
	</tr>
	</tbody>
	{foreach from=$category_questions item="var" name="fe_f"}
	{assign var="num" value=$smarty.foreach.fe_f.iteration}
	<tbody class="hover" id="box_feature_variants_{$var.question_id}">
	<tr class="cm-first-sibling {cycle values="table-row, "}">
		<td class="rise">
				<input type="hidden" name="category_questions[{$num}][question_id]" value="{$var.question_id}">
				<input type="text" name="category_questions[{$num}][position]" value="{$var.position}" size="4" class="input-micro" />
		</td>
		<td class="rise td-width" >
				<input type="text" name="category_questions[{$num}][question]" value="{$var.question}" style="width:500px;" />
		</td>
		<td class="rise">{include file="common/select_popup.tpl" id=$var.question_id status=$var.status hidden=false object_id_name="question_id" table="faq_questions"}</td>
		<td class="cm-extended-feature rise ">
			<img src="{$images_dir}/plus.gif" width="14" height="9" border="0" name="plus_minus" id="on_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-features-{$id}" /><img src="{$images_dir}/minus.gif" width="14" height="9" border="0" name="minus_plus" id="off_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-features-{$id}" /><a id="sw_extra_feature_{$id}_{$num}" class="cm-combination-features-{$id}">{__("extra")}</a>
		</td class="rise">
		<td class="right nowrap rise">
			{include file="buttons/multiple_buttons.tpl" item_id="feature_variants_`$var.question_id`" only_delete="Y"}
		</td>
	</tr>
	<tr class="hidden" id="extra_feature_{$id}_{$num}">
		<td colspan="5">
			<div class="control-group">
				<label class="control-label" for="visible_{$id}_{$num}">{__("ct_visible")}:</label>
                <div class="controls">
				<input type="hidden" name="category_questions[{$num}][visible]" value="N" />
				<input type="checkbox" name="category_questions[{$num}][visible]" id="visible" value="Y" {if $var.visible == "Y"} checked="checked" {/if} class="checkbox" />
			</div>
                </div>
			<div class="control-group">
				<label class="control-label" for="anchor_{$id}_{$num}">{__("ct_anchor")}</label>
                <div class="controls">
				<input type="text" name="category_questions[{$num}][anchor]" value="{$var.anchor}" class="input-text-long cm-feature-value " />
			</div>
                </div>
			<div class="control-group">
				<label class="control-label" for="elm_description_{$id}_{$num}">{__("answer")}</label>
                <div class="controls">
				<textarea id="elm_description_{$id}_{$num}" name="category_questions[{$num}][answer]" cols="55" rows="8" class="cm-wysiwyg " style="width:auto;">{$var.answer}</textarea>
			</div>
                </div>

		</td>
	</tr>
	</tbody>
	{/foreach}

	{math equation="x + 1" assign="num" x=$num|default:0}
	<tbody class="hover" id="box_add_variants_for_existing_{$id}_{$num}">
	<tr>
		<td class="rise">
				<input type="text" name="category_questions[{$num}][position]" value="" size="4" class="input-micro" />
		</td>
		<td class="rise td-width" >
				<input type="text" name="category_questions[{$num}][question]" value="" style="width:500px;" />
		</td>
		<td class="rise"></td>
		<td class="cm-extended-feature rise ">
			<img src="{$images_dir}/plus.gif" width="14" height="9" border="0" name="plus_minus" id="on_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-features-{$id}" /><img src="{$images_dir}/minus.gif" width="14" height="9" border="0" name="minus_plus" id="off_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-features-{$id}-{$num}" /><a id="sw_extra_feature_{$id}_{$num}" class="cm-combination-features-{$id}-{$num}">{__("extra")}</a>
		</td>
		<td class="right rise">
			{include file="buttons/multiple_buttons.tpl" item_id="add_variants_for_existing_`$id`_`$num`" hide_clone=true}</td>
	</tr>
	<tr class="hidden" id="extra_feature_{$id}_{$num}">
		<td colspan="5">
			<div class="control-group">
				<label class="control-label" for="visible_{$id}_{$num}">{__("ct_visible")}:</label>
                <div class="controls">
				<input type="hidden" name="category_questions[{$num}][visible]" value="N" />
				<input type="checkbox" name="category_questions[{$num}][visible]" id="visible" value="Y" class="checkbox" />
			</div>
                </div>
			<div class="control-group hidden">
				<label class="control-label" for="anchor_{$id}_{$num}">{__("ct_anchor")}</label>
                <div class="controls">
				<input type="text" name="category_questions[{$num}][anchor]" value="" class="input-text-long cm-feature-value " />
			</div>
                </div>
			<div class="control-group">
				<label class="control-label" for="elm_description_{$id}_{$num}">{__("answer")}</label>
                <div class="controls">
				<textarea id="elm_description_{$id}_{$num}" name="category_questions[{$num}][answer]" cols="55" rows="8" style="width:auto;">{$var.description}</textarea>
			</div>
                </div>
		</td>
		
	</tr>
	</tbody>
	
	</table>

    {capture name="buttons"}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[ct_faq.m_update_question]"  but_role="submit-link" but_target_form="cs_product_use_for_form" save=$category_id}
    {/capture}

</form>

{/capture}
{include file="common/mainbox.tpl" title=$category_name content=$smarty.capture.mainbox buttons=$smarty.capture.buttons select_languages=true }
{** end section **}
