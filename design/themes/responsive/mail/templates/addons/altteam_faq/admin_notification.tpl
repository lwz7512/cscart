{include file="common/letter_header.tpl"}

{__("hello")},<br /><br />

{$text}
<br /><br />
<b>{__("message")}</b>:&nbsp;{$message}<br /><br /><br />
{__("follow_link")}&nbsp;<a href="{$path}">{$path}</a><br />

{include file="common/letter_footer.tpl"}