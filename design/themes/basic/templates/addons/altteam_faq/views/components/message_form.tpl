<div class="hidden" id="faq_post_message_{if $id}{$id}{else}new{/if}" title="{if $f_action == 'new_question'}{__("new_post")}{else}{__("add_answer")}{/if}">
<form action="{""|fn_url}" method="post" class="posts-form" name="add_faq_form_{$id}" id="add_faq_post_form_{$id}">
<input type ="hidden" name="faq_data[thread_id]" value="{$thread_id}" />
<input type ="hidden" name="redirect_url" value="{$config.current_url}" />
<input type="hidden" name="selected_section" value="faq" />
<input type="hidden" name="faq_data[faq_id]" value="{$id}" />

<div id="new_faq_post_{$id}">
	
<div class="control-group">
	<label for="dsc_faq_name_{$id}" class="cm-required">{__("your_name")}:</label>
	<input type="text" id="dsc_faq_name_{$id}" name="faq_message[name]" value="{if $auth.user_id}{$user_info.firstname}&nbsp;{$user_info.lastname}{/if}" size="50" class="input-text" />
</div>

{if $f_action == 'new_question'}
<div class="control-group">
	<label for="dsc_faq_email_{$id}" class="cm-email {if $addons.altteam_faq.mandatory_email == 'Y'}cm-required{/if}">{__("your_email")}&nbsp;(<a class="cm-tooltip" title="{__("email_notification")}">?</a>):</label> 
	<input type="text" id="dsc_faq_email_{$id}" name="faq_message[email]" value="" size="50" class="input-text" />
</div>
{/if}

<div class="control-group">
	<label for="dsc_faq_message_{$id}" class="cm-required">{if $runtime.action == 'new_answer'}{__("your_answer")}{else}{__("your_question")}{/if}:</label>
	<textarea id="dsc_faq_message_{$id}" name="faq_message[message]" class="input-textarea" rows="5" cols="72"></textarea>
</div>

{include file="common/image_verification.tpl" option="use_for_discussion"}

<!--new_faq_post_{$id}--></div>

<div class="buttons-container">
	{include file="buttons/button.tpl" but_text=__("submit") but_role="submit" but_name="dispatch[faq.add_faq]"}
</div>

</form>
</div>