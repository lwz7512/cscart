<div class="buttons-container">
	{include file="buttons/button.tpl" but_id="opener_new_faq_post" but_text=__("new_post") but_role="submit" but_target_id="faq_post_message_new" but_meta="cm-dialog-opener cm-dialog-auto-size"}
</div>

{include file="addons/altteam_faq/views/components/message_form.tpl" f_action='new_question' thread_id=$thread_id id=false}