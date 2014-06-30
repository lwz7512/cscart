{* $Id: extra.message.tpl 7341 2009-04-22 10:17:05Z zeke $ *}
{assign var="latest_faqs" value=""|fn_get_latest_faqs}
<div class="statistics-box communication">
	{include file="common/subheader_statistic.tpl" title=__("latest_faq")}
	<div class="statistics-body">
	{if $latest_faqs}
	<div id="stats_faq">
	
	{foreach from=$latest_faqs item=message}
	{assign var="o_type" value=$message.object_type}
	{assign var="object_name" value=$o_type|fn_get_object_name}
	{assign var="review_name" value="faq_title_$object_name"}
	<div class="{cycle values=" ,manage-post"} posts">
		<div class="clear">			
			<div class="float-right">
			<a class="tool-link valign" href="{$message.object_data.url}">{__("edit")}</a>
			{include file="buttons/button.tpl" but_role="delete_item" but_href="$index_script?dispatch=index.delete_message&amp;message_id=`$message.message_id`" but_meta="cm-ajax cm-confirm" but_rev="stats_faq"}
			</div>
			
			{__($object_name)}:&nbsp;<a href="{$message.object_data.url}">{$message.object_data.description|truncate:70}</a>
			<span class="lowercase">&nbsp;{__("comment_by")}</span>&nbsp;{$message.name}
		</div>
			<div class="scroll-x">{$message.message}</div>
		
		<div class="clear">
		<div class="float-left"><strong>{__("ip_address")}:</strong>&nbsp;{$message.ip_address}</div>
		
		{include file="addons/altteam_faq/views/components/dashboard_status.tpl" message=$message}
		</div>
	</div>
	{/foreach}
	<!--stats_faq--></div>
	{else}
	<p class="no-items">{__("no_items")}</p>
	{/if}
	</div>
</div>
