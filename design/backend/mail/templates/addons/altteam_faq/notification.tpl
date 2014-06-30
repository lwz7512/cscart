{include file="common/letter_header.tpl"}

{__("dear")}&nbsp;{$user},<br /><br />

{__("text_new_faq_notification")}
<br /><br />

<b>{__("your_question")}</b>:&nbsp;{$question}<br /><br /><br /><br />
<b>{__("answer")}</b>:&nbsp;{$answer}<br /><br /><br /><br />
{__("product")}:&nbsp;<a href="{$object_data.url}">{$object_data.product}</a>

{include file="common/letter_footer.tpl"}