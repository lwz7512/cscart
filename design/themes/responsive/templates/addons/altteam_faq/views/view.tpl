<div id="content_faq">

{if $faq_d}
	{assign var="faq_data" value=$faq_d.thread_id|fn_get_faqs_data}
{/if}

<div id="faq_list">
{if $faq_data}

	{include file="common/pagination.tpl" id="pagination_contents_faq_`$object_id`" extra_url="&selected_section=faq" search=$faq.search}
	
	{foreach from=$faq_data key=key item=faq}
		<div class="faqs">
			<div class="question_container_{$key}">
				<p class="faq-message"><b>{$faq.question.message}</b></p>
				<div class="faq_support_string">
					<em>{if $faq.question.name}{__("asked_by")}&nbsp;{$faq.question.name}&nbsp;|&nbsp;{/if}{$faq.question.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}&nbsp;|&nbsp;<a id="splLink1" onclick="$('#answer_container_{$key}').toggle('slow');">{$key|fn_get_answers_count}&nbsp;{__("answer_s")}</a></em>
				</div>
			</div>
			{if $faq.answers}
			<div id="answer_container_{$key}" class="splCont" style="display: none;">
				{foreach from=$faq.answers item=faq_answer name=answers}
					<div class="faq_answer">
						<p class="faq-message">{$faq_answer.message}</p>
						<div class="faq_support_string">
							<em>{if $faq_answer.name}{__("answered_by")}&nbsp;{$faq_answer.name}&nbsp;|&nbsp;{/if}{$faq_answer.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</em>
						</div>
					</div>
				{/foreach}
				{if $addons.altteam_faq.customer_answer == 'Y'}
					{include file="buttons/button.tpl" but_id="opener_`$key`_faq_post" but_text=__("add_answer") but_role="submit" but_target_id="faq_post_message_`$key`" but_meta="cm-dialog-opener cm-dialog-auto-size"}
					{include file="addons/altteam_faq/views/components/message_form.tpl" action='add_answer' hidden='Y' id=$key thread_id=$faq_d.thread_id}
				{/if}
			</div>
			{elseif $addons.altteam_faq.customer_answer == 'Y'}
			<div id="answer_container_{$key}" class="splCont" style="display: none;">
				{include file="buttons/button.tpl" but_id="opener_`$key`_faq_post" but_text=__("add_answer") but_role="submit" but_target_id="faq_post_message_`$key`" but_meta="cm-dialog-opener cm-dialog-auto-size"}
				{include file="addons/altteam_faq/views/components/message_form.tpl" action='add_answer' hidden='Y' id=$key thread_id=$faq_d.thread_id}
			</div>
			{/if}
		</div>
	{/foreach}

	{include file="common/pagination.tpl" id="pagination_contents_faq_`$object_id`" extra_url="&selected_section=faq" search=$faq.search}
{else}
	<p class="no-items">{__("no_faq_found")}</p>
{/if}
<!--faq_list--></div>

{include file="addons/altteam_faq/views/components/new_question_form.tpl" thread_id=$faq_d.thread_id}

</div>