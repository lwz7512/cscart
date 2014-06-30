{if ($runtime.company_id || $runtime.forced_company_id) && "ULTIMATE"|fn_allowed_for || "MULTIVENDOR"|fn_allowed_for}
	<div class="control-group">
		<label class="control-label" for="faq_type">{__("faq")}:</label>
		<div class="controls">
			<select name="update_data[faq_type]" id="faq_type">
				<option selected="selected" value="S">{__("please_select_one")}</option>
				<option value="E">{__("enabled")}</option>
				<option value="D">{__("disabled")}</option>
			</select>
		</div>
	</div>
{/if}