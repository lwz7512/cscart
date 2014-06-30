{* $Id$ *}

<div class="control-group {if !$non_editable}cm-no-hide-input{/if}">
	<label class="control-label" for="elm_faq_type">{$title}:</label>
	{assign var="faq" value=$product_data.product_id|fn_get_faq:p}
	<div class="controls">
		<select name="{$prefix}[faq_type]" id="elm_faq_type" class="span5" >
			<option {if $faq.type == "E"}selected="selected"{/if} value="E">{__("enabled")}</option>
			<option {if $faq.type == "D" || !$faq}selected="selected"{/if} value="D">{__("disabled")}</option>
		</select>
	</div>
</div>