{** Copyright (c) 2012 CartTuning (www.carttuning.com). All rights reserved. **}
{if $mode!="add"}
<div id="content_faq">
    {include file="common/subheader.tpl" title=__("ct_faq_local")}
	<table  width="100%" class="table table-middle">
	<tbody>
	<tr class="cm-first-sibling">
		<th width="10%">{__("position_short")}</th>
		<th>{__("ct_faq_questions")}</th>
		{if $category_questions}<th width="15%">{__("status")}</th>{else}<th width="15%"></th>{/if}
		<th class="cm-extended-feature " width="15%"><img src="{$images_dir}/plus_minus.gif" width="13" height="12" border="0" name="plus_minus" id="on_st_{$id}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combinations-features-{$id}" /><img src="{$images_dir}/minus_plus.gif" width="13" height="12" border="0" name="minus_plus" id="off_st_{$id}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combinations-features-{$id}" /></th>
		<th width="5%">&nbsp;</th>
	</tr>
	</tbody>
	{foreach from=$category_questions item="var" name="fe_f"}
	{assign var="num" value=$smarty.foreach.fe_f.iteration}
	<tbody class="hover" id="box_feature_variants_{$var.question_id}">
	<tr class="cm-first-sibling {cycle values="table-row, "}">
		<td class="rise">
			<input type="hidden" name="category_questions[{$num}][question_id]" value="{$var.question_id}">
			<input type="text" name="category_questions[{$num}][position]" value="{$var.position}" size="4" class="input-micro" /></td>
		<td class="rise">
			<input type="text" name="category_questions[{$num}][question]" value="{$var.question}" style="width:500px;"/>
            </td>
		<td class="rise">{include file="common/select_popup.tpl" id=$var.question_id status=$var.status hidden=false object_id_name="question_id" table="faq_product"}</td>
		<td class="cm-extended-feature rise ">
			<img src="{$images_dir}/plus.gif" width="14" height="9" border="0" name="plus_minus" id="on_faq_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-features-{$id}" /><img src="{$images_dir}/minus.gif" width="14" height="9" border="0" name="minus_plus" id="off_faq_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-features-{$id}" /><a id="sw_faq_extra_feature_{$id}_{$num}" class="cm-combination-features-{$id}">{__("extra")}</a>
		</td>
		<td class="right nowrap rise">
			{include file="buttons/multiple_buttons.tpl" item_id="feature_variants_`$var.question_id`" only_delete="Y"}
		</td>
	</tr>
	<tr class="hidden" id="faq_extra_feature_{$id}_{$num}">
		<td colspan="5">
			<div class="control-group">
				<label  class="control-label" for="elm_description_{$id}_{$num}">{__("answer")}</label>
                <div class="controls">
				<textarea id="elm_description_{$id}_{$num}" name="category_questions[{$num}][answer]" cols="55" rows="8" class="cm-wysiwyg input-textarea-long">{$var.answer}</textarea>
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
			<input type="text" name="category_questions[{$num}][position]" value="" size="4" class="input-micro" /></td>
		<td class="rise">
			<input type="text" name="category_questions[{$num}][question]" value="" style="width:500px;" />
            </td>
		<td class="rise">&nbsp;</td>
		<td class="cm-extended-feature rise ">
			<img src="{$images_dir}/plus.gif" width="14" height="9" border="0" name="plus_minus" id="on_faq_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-features-{$id}" /><img src="{$images_dir}/minus.gif" width="14" height="9" border="0" name="minus_plus" id="off_faq_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-features-{$id}-{$num}" /><a id="sw_faq_extra_feature_{$id}_{$num}" class="cm-combination-features-{$id}-{$num}">{__("extra")}</a>
		</td>
		<td class="right rise">
			{include file="buttons/multiple_buttons.tpl" item_id="add_variants_for_existing_`$id`_`$num`" hide_clone=true}</td>
	</tr>
	<tr class="hidden" id="faq_extra_feature_{$id}_{$num}">
		<td colspan="5">
			<div class="control-group">
				<label  class="control-label" for="elm_description_{$id}_{$num}">{__("answer")}</label>
                <div class="controls">
				<textarea id="elm_description_{$id}_{$num}" name="category_questions[{$num}][answer]" cols="55" rows="8" style="width:auto;">{$var.description}</textarea>
			</div>
                </div>
	</td>
		
	</tr>
	</tbody>
	
	</table>
    {if $global_questions}
    {include file="common/subheader.tpl" title=__("ct_faq_global")}
    <table  width="100%" class="table table-middle">

        {foreach from=$global_questions item="var" name="fe_f"}
            {assign var="num" value=$smarty.foreach.fe_f.iteration}
            <input type="hidden" name="global_questions[{$num}][question_id]" value="{$var.question_id}">
            <tbody class="hover" id="box_feature_variants_{$var.question_id}">
            <tr class="cm-first-sibling {cycle values="table-row, "}">
                <td class="rise">
                        <input type="text" name="global_questions[{$num}][question]" value="{$var.question}" style="width:590px;" />
                </td>
                <td  width="10%">
                </td>
                <td class="cm-extended-feature rise "  width="15%">
                    <img src="{$images_dir}/plus.gif" width="14" height="9" border="0" name="plus_minus" id="on_faq_g_extra_features_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-features-{$id}" /><img src="{$images_dir}/minus.gif" width="14" height="9" border="0" name="minus_plus" id="off_faq_g_extra_features_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-features-{$id}" /><a id="sw_faq_g_extra_features_{$id}_{$num}" class="cm-combination-features-{$id}">{__("extra")}</a>
                </td>
                <td class="right nowrap rise" width="5%">
                        <a class="cm-confirm" href="{"products.delete_product_global_question&product_id=`$id`&question_id=`$var.question_id`"|fn_url}">{__("delete")}</a>
                </td>
            </tr>
            <tr class="hidden" id="faq_g_extra_features_{$id}_{$num}">
                <td colspan="5">
                    <div class="control-group">
                        <label  class="control-label" for="elm_description_{$id}_{$num}">{__("answer")}</label>
                        <div class="controls">
                        <textarea id="elm_description_{$id}_{$num}" name="global_questions[{$num}][answer]" cols="55" rows="8" class="cm-wysiwyg " >{$var.answer}</textarea>
                    </div>
                        </div>
                </td>
            </tr>
            </tbody>
        {/foreach}
    </table>
    {/if}
</div>
{/if}