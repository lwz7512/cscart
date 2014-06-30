{if $runtime.company_id && "ULTIMATE"|fn_allowed_for || "MULTIVENDOR"|fn_allowed_for}
	{include file="addons/altteam_faq/views/faq.tpl"}
{/if}