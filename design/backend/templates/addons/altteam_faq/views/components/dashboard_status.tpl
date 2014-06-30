{* $Id: dashboard_status.tpl 7321 2009-04-20 13:20:33Z angel $ *}

<div class="float-right nowrap right" id="message_{$message.message_id}">
	{include file="common/select_popup.tpl" id=$message.message_id status=$message.status hidden="" object_id_name="message_id" table="faq_messages" update_controller='index' items_status='A: `__("approved")`, D: `__("disapproved")`'}
	<strong>{$message.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</strong>&nbsp;-&nbsp;
<!--message_{$message.message_id}--></div>
