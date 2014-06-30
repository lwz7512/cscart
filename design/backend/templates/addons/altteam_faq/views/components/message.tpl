{*{assign var="current_redirect_url" value=$config.current_url|escape:url}*}

{if $new}
	<span class="strong">{__("new_answer")}</span>
{/if}

<div class="summary">
	<input type="text" name="{if $new}faq_data[add_message][{$obj_id}][name]{else}faq_data[message][{$obj.message_id}][name]{/if}" value="{if $obj.name}{$obj.name}{else}{$user_info.firstname} {$user_info.lastname}{/if}" size="{$size}" class="input-hidden" />
	<textarea name="{if $new}faq_data[add_message][{$obj_id}][message]{else}faq_data[message][{$obj.message_id}][message]{/if}" class="input-hidden" cols="{$cols}" rows="5">{$obj.message nofilter}</textarea>
</div>

{if !$new}
<div class="tools">
    <div class="pull-left">
		<input type="checkbox" name="delete_faqs[{$obj.message_id}]" id="delete_checkbox_{$obj.message_id}" class="pull-left cm-item" value="Y" />

        <div class="hidden-tools pull-left cm-statuses">
                <span class="cm-status-a {if $obj.status == "D"}hidden{/if}">
                    <span class="label label-success">{__("approved")}</span>
                    <a class="cm-status-switch-faq icon-thumbs-down cm-tooltip" title="{__("disapprove")}" data-ca-status="D" data-ca-post-id="{$obj.message_id}"></a>
                </span>
				<span class="cm-status-d {if $obj.status == "A"}hidden{/if}">
                    <span class="label label-important">{__("not_approved")}</span>
                    <a class="cm-status-switch-faq icon-thumbs-up cm-tooltip" title="{__("approve")}" data-ca-status="A" data-ca-post-id="{$obj.message_id}"></a>
                </span>
				<a class="icon-trash cm-tooltip cm-confirm" href="{"faq.delete?message_id=`$obj.message_id`&redirect_url=`$current_redirect_url`"|fn_url}" title="{__("delete")}"></a>
		</div>
    </div>

    <div class="pull-right">
        <span class="muted">{$obj.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"} / {__("ip_address")}:&nbsp;{$obj.ip_address}</span>
    </div>
</div>
{/if}