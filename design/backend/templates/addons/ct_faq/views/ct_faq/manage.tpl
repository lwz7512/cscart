{** Copyright (c) 2012 CartTuning (www.carttuning.com). All rights reserved. **}

{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="category_tree_form" class="{if ""|fn_check_form_permissions}cm-hide-inputs{/if}">
<div class="items-container multi-level">
	{if $categories_tree}
		{include file="addons/ct_faq/views/ct_faq/components/faq_categories_tree.tpl" header="1" parent_id=$category_id}
	{else}
		<p class="no-items">{__("no_items")}</p>
	{/if}
</div>

{capture name="select_fields_to_edit"}

	<p>{__("text_select_fields2edit_note")}</p>
	{include file="views/categories/components/categories_select_fields.tpl"}

	<div class="tools-container">
		{include file="buttons/save_cancel.tpl" but_text=__("modify_selected") but_meta="cm-process-items" but_name="dispatch[categories.store_selection]" cancel_action="close"}
	</div>
{/capture}
{include file="common/popupbox.tpl" id="select_fields_to_edit" text=__("select_fields_to_edit") content=$smarty.capture.select_fields_to_edit}

    {capture name="adv_buttons"}
        {capture name="add_new_picker"}
            {include file="addons/ct_faq/views/ct_faq/update_category_popup.tpl" mod='add'}
        {/capture}
        {include file="common/popupbox.tpl" id="add_new_faq_category" text=__("ct_faq_new_category") title=__("ct_faq_new_category") content=$smarty.capture.add_new_picker act="general" icon="icon-plus"}
  {/capture}

    {capture name="buttons"}
        {if $categories_tree}
            {capture name="tools_list"}
                    <li>{btn type="delete_selected" dispatch="dispatch[ct_faq.m_delete]" form="category_tree_form"}</li>
                    <li>{assign var="view_uri" value="ct_faq.view"}
                        <a target="_blank" class="tool-link" title="{$view_uri|fn_url:'C':'http':'&':$smarty.const.DESCR_SL}" href="{$view_uri|fn_url:'C':'http':'&':$smarty.const.DESCR_SL}">{__("preview")}</a></li>
            {/capture}
            {dropdown content=$smarty.capture.tools_list}
            {include file="buttons/save_cancel.tpl" but_name="dispatch[ct_faq.m_update]"  but_role="submit-link" but_target_form="category_tree_form" save=1}
        {/if}
    {/capture}
</form>

{/capture}

{*include file="common_templates/mainbox.tpl" title=__("ct_faq_categories") content=$smarty.capture.mainbox tools=$smarty.capture.tools select_languages=true*}

{include file="common/mainbox.tpl" title=__("ct_faq_categories") content=$smarty.capture.mainbox adv_buttons=$smarty.capture.adv_buttons  buttons=$smarty.capture.buttons select_languages=true tools=$smarty.capture.view_tools}
