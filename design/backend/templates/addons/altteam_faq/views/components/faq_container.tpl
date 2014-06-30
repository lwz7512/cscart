{if $faq_data}
	{assign var="current_redirect_url" value="`$config.current_url|escape:url`&amp;selected_section=faq"}

	<input type="hidden" name="redirect_url" value="{$config.current_url}&amp;selected_section=faq" />
	
	{script src="js/addons/altteam_faq/faq.js"}

	{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

	<table width=100%>

	{foreach from=$faq_data item="faq_block" key="faq_id"}
		{if $faq_block}
		<tr>
			<td width=50% valign=top>
				<div class="post-item faq-questions">
					{include file="addons/altteam_faq/views/components/message.tpl" obj=$faq_block.question size='18' cols='30'}
					{if $faq_block.object_data.url}<a href="{$faq_block.object_data.url}">{$faq_block.object_data.description|truncate:70}</a>{/if}
				</div>
			</td>
			<td width=50% valign=top>
				{foreach from=$faq_block.answers item="faq_answer"}
					<div class="post-item faq-answer">
						{include file="addons/altteam_faq/views/components/message.tpl" obj=$faq_answer size='29' cols='30'}
					</div>
				{/foreach}
				
				<div class="post-item faq-answer">
					{include file="addons/altteam_faq/views/components/message.tpl" obj_id=$faq_id size='29' cols='30' new='y'}
				</div>
			</td>
		</tr>
		{/if}
	{/foreach}
	</table>
	
	{include file="common/pagination.tpl"}

{else}
	<p class="no-items">{__("no_data")}</p>
{/if}