{if $runtime.company_id && "ULTIMATE"|fn_allowed_for || "MULTIVENDOR"|fn_allowed_for}
	{include file="common/subheader.tpl" title=__("faq") target="#acc_faq_type"}
	<div id="acc_faq_type" class="collapse in">
    	<fieldset>
			{include file="addons/altteam_faq/views/allow_faq.tpl" prefix="product_data" object_id=$product_data.product_id object_type="P" title=__("faq_title_product") non_editable=false}
    	</fieldset>
	</div>
{/if}