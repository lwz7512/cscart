{** Copyright (c) 2012 CartTuning (www.carttuning.com). All rights reserved. **}
{capture name="mainbox"}
{capture name="tabsbox"}
{assign var="modd" value=$mod}

{if $modd == "add"}
	{assign var="id" value="0"}
{else}
	{assign var="id" value=$faq_categories.faq_id}
	{assign var="object_id" value=$faq_category_data.category_id}
{/if}

<form action="{""|fn_url}" method="post" class="form-horizontal form-edit" name="faq_form_{$id}" enctype="multipart/form-data">
<input type="hidden" name="faq_category_data[category_id]" value="{$object_id}" />
<input type="hidden" name="faq_id" value="{$id}" />

<div id="content_detailed">
    <fieldset>
        <div class="control-group">
            <label class="control-label" for="elm_description_{$id}" class="cm-required">{__("category_name")}:</label>
            <div class="controls">
                <input type="text" name="faq_category_data[name]" id="elm_description_{$id}" size="60" class="input-text-large main-input" value="{$faq_category_data.name}" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="elm_position_{$id}">{__("position")}:</label>
            <div class="controls">
                <input type="text" name="faq_category_data[position]" id="elm_position_{$id}" size="3" class="input-text-short" value="{$faq_category_data.position}" />
            </div>
        </div>
        {if "ULTIMATE"|fn_allowed_for}
            {include file="views/companies/components/company_field.tpl"
            name="faq_company_id"
            id="faq_company_id"
            selected=$faq_category_data.company_id
            }
        {/if}
        {include file="common/select_status.tpl" input_name="faq_category_data[status]" id="faq_category_data" obj=$faq_category_data hidden=false}
    </fieldset>
</div>

    {capture name="buttons"}
        {if $modd == "add"}
            {include file="buttons/save_cancel.tpl" but_name="dispatch[ct_faq.add_category]" but_role="submit-link" but_target_form="faq_form_{$id}"}
        {else}
            {include file="buttons/save_cancel.tpl" but_name="dispatch[ct_faq.update_category]" but_role="submit-link" but_target_form="faq_form_{$id}" save=1}
        {/if}
    {/capture}

</form>
{/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name=$controller active_tab=$smarty.request.selected_section track=true}

{/capture}
{include file="common/mainbox.tpl" title=$faq_category_data.name buttons=$smarty.capture.buttons content=$smarty.capture.mainbox}
