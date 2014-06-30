{if $faq && !$faq.is_empty}
{*{script src="js/picker.js"}*}
{assign var="faq_data" value=$faq.thread_id|fn_get_faqs_data:$smarty.request.page}

{*{$allow_save = false}

{if ($discussion.object_type != "M" || !$runtime.company_id) && "discussion.update"|fn_check_view_permissions}
{$allow_save = true}
{/if}*}
<div class="{if $allow_save || 1}cm-no-hide-input{else}cm-hide-inputs{/if}" id="content_faq">
{*<div class="cm-hide-save-button hidden" id="content_faq">*}
<div class="clearfix">
    <div class="buttons-container buttons-bg pull-right">
{*    {if $allow_save}*}
			{include file="common/popupbox.tpl" id="add_new_faq" link_text=__("new_faq") act="general" link_class="cm-dialog-switch-avail"}
            {if $faq_data}
                {$show_save_btn = true scope = root}
				{capture name="tools_list"}
					<li>{btn type="delete_selected" dispatch="dispatch[faq.delete_faqs]" form="update_faqs_form"}</li>
				{/capture}
				{dropdown content=$smarty.capture.tools_list}
            {/if}
{*    {/if}*}
    </div>
</div><br>

{capture name="add_new_faq"}
	<div class="tabs cm-j-tabs">
        <ul class="nav nav-tabs">
            <li id="tab_add_post" class="cm-js cm-active active"><a>{__("general")}</a></li>
        </ul>
    </div>
	
	<div class="cm-tabs-content" id="content_tab_add_faq">
		<input type ="hidden" name="faq_data[thread_id]" value="{$faq.thread_id}" />
		<input type ="hidden" name="redirect_url" value="{$config.current_url}&amp;selected_section=faq" />

		<div class="control-group">
			<label for="faq_message_name" class="control-label cm-required">{__("name")}:</label>
			<div class="controls">
				<input type="text" name="faq_message[name]" id="faq_message_name" value="{if $auth.user_id}{$user_info.firstname} {$user_info.lastname}{/if}" />
			</div>
		</div>

		<div class="control-group">
			<label for="faq_message_email" class="control-label cm-email">{__("email")}:</label>
			<div class="controls">
				<input type="text" name="faq_message[email]" id="faq_message_email" value="" size="40" class="span5" />
			</div>
		</div>

		<div class="control-group">
			<label for="faq_message_message" class="control-label">{__("question")}:</label>
			<div class="controls">
				<textarea name="faq_message[message]" id="faq_message_message" class="span5" cols="70" rows="8"></textarea>
			</div>
		</div>
	</div>
	<div class="buttons-container">
		{include file="buttons/save_cancel.tpl" but_text=__("add_faq") but_name="dispatch[faq.add_faq]" cancel_action="close" hide_first_button=false}
	</div>
{/capture}
{include file="common/popupbox.tpl" id="add_new_faq" text=__("new_post") content=$smarty.capture.add_new_faq act="fake"}

{include file="addons/altteam_faq/views/components/faq_container.tpl" faq_data=$faq_data}
</div>
{/if}