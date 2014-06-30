{** Copyright (c) 2012 CartTuning (www.carttuning.com). All rights reserved. **}

{if $parent_id}
<div class="hidden" id="cat_{$parent_id}">
{/if}
{foreach from=$categories_tree item=category}
<table width="100%" class="table table-middle" style="margin-bottom:0px;">
    {if $runtime.company_id == $category.company_id || $runtime.company_id == "0"}
{if $header && !$parent_id}
{assign var="header" value=""}
<tr>
	<th class="center" width="3%">
		<input type="checkbox" name="check_all" value="Y" title="{__("check_uncheck_all")}" class="checkbox cm-check-items" /></th>
	<th width="5%">{__("position_short")}</th>
	<th width="57%">
		{if $show_all && !$smarty.request.b_id}
		<div class="float-left">
			<img src="{$images_dir}/plus_minus.gif" width="13" height="12" border="0" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" id="on_cat" class="hand cm-combinations{if $expand_all} hidden{/if}" />
			<img src="{$images_dir}/minus_plus.gif" width="13" height="12" border="0" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" id="off_cat" class="hand cm-combinations{if !$expand_all} hidden{/if}" />
		</div>
		{/if}
		{__("name")}
	</th>
	<th class="left" width="15%">{__("ct_faq_questions")}</th>
	<th width="10%">{__("status")}</th>
	<th width="10%">&nbsp;</th>
</tr>
{/if}
<tr {if $category.level > 0}class="multiple-table-row"{/if}>
   	{math equation="x*14" x=$category.level|default:"0" assign="shift"}
	<td class="center" width="3%">
		<input type="checkbox" name="category_ids[]" value="{$category.category_id}" class="checkbox cm-item" />
    </td>
	<td width="5%">
		<input type="text" name="categories_data[{$category.category_id}][position]" value="{$category.position}" size="3" class="input-micro " />
    </td>
	<td width="57%">
	{strip}
		<span class="strong" style="padding-left: {$shift}px;">
			{if $category.has_children || $category.subcategories}
				{if $show_all}
					<img src="{$images_dir}/plus.gif" width="14" height="9" border="0" alt="{__("expand_sublist_of_items")}" title="{__("expand_sublist_of_items")}" id="on_cat_{$category.category_id}" class="hand cm-combination {if $expand_all}hidden{/if}" />
				{else}
					<img src="{$images_dir}/plus.gif" width="14" height="9" border="0" alt="{__("expand_sublist_of_items")}" title="{__("expand_sublist_of_items")}" id="on_cat_{$category.category_id}" class="hand cm-combination" onclick="if (!$('#cat_{$category.category_id}').children().get(0)) jQuery.ajaxRequest('{"categories.manage?category_id=`$category.category_id`"|fn_url:'A':'rel':'&'}', {$ldelim}result_ids: 'cat_{$category.category_id}'{$rdelim})" />
				{/if}
				<img src="{$images_dir}/minus.gif" width="14" height="9" border="0" alt="{__("collapse_sublist_of_items")}" title="{__("collapse_sublist_of_items")}" id="off_cat_{$category.category_id}" class="hand cm-combination{if !$expand_all || !$show_all} hidden{/if}" />&nbsp;
			{/if}
			<a href="{"ct_faq.manage_questions?category_id=`$category.category_id`"|fn_url}"{if $category.status == "N"} class="manage-root-item-disabled"{/if}{if !$category.subcategories}  class="normal"{/if} >{$category.category}</a>{if $category.status == "N"}&nbsp;<span class="small-note">-&nbsp;[{__("disabled")}]</span>{/if}
		</span>
	{/strip}
        {include file="views/companies/components/company_name.tpl" object=$category}
	</td>
	<td width="15%" class="nowrap left">
		<a href="{"ct_faq.manage_questions?category_id=`$category.category_id`"|fn_url}" class="num-items"><span>&nbsp;{$category.product_count}&nbsp;</span></a>&nbsp;
		{include file="buttons/button.tpl" but_text=__("add") but_href="ct_faq.manage_questions?category_id=`$category.category_id`" but_role="add"}
	</td>
	<td width="10%">
		{include file="common/select_popup.tpl" id=$category.category_id status=$category.status hidden=true object_id_name="category_id" table="faq_categories"}
	</td>
	<td width="10%">
        {capture name="tools_list"}
            <li>{btn type="list" text=__("edit") href="ct_faq.update_category&category_id=`$category.category_id`"}
               </li>
            <li>{btn type="list" class="cm-confirm" text=__("delete") href="ct_faq.delete&amp;category_id=`$category.category_id`"}</li>
        {/capture}
        <div class="hidden-tools right">
            {dropdown content=$smarty.capture.tools_list}
        </div>
	</td>
</tr>
    {/if}
</table>
{if $category.has_children || $category.subcategories}
	<div{if !$expand_all} class="hidden"{/if} id="cat_{$category.category_id}">
	{if $category.subcategories}
		{include file="views/categories/components/categories_tree.tpl" categories_tree=$category.subcategories parent_id=false}
	{/if}
	<!--cat_{$category.category_id}--></div>
{/if}
{/foreach}
{if $parent_id}<!--cat_{$parent_id}--></div>{/if}