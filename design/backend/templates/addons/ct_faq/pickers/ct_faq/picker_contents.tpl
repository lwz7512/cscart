{** Copyright (c) 2012 CartTuning (www.carttuning.com). All rights reserved. **}

{if !$smarty.request.extra}
<script type="text/javascript">
//<![CDATA[
(function(_, $) {
    _.tr('text_items_added', '{__("text_items_added")|escape:"javascript"}');

    $.ceEvent('on', 'ce.formpost_faq_form', function(frm, elm) {

        var banners = {};

        if ($('input.cm-item:checked', frm).length > 0) {
            $('input.cm-item:checked', frm).each( function() {
                var id = $(this).val();
                banners[id] = $('#faq_' + id).text();
            });

            {literal}
            $.cePicker('add_js_item', frm.data('caResultId'), banners, 'b', {
                '{banner_id}': '%id',
                '{banner}': '%item'
            });
            {/literal}

            $.ceNotification('show', {
                type: 'N',
                title: _.tr('notice'),
                message: _.tr('text_items_added'),
                message_state: 'I'
            });
        }

        return false;
    });

}(Tygh, Tygh.$));

//]]>
</script>
{/if}
</head>

<form action="{$smarty.request.extra|fn_url}" data-ca-result-id="{$smarty.request.data_id}" method="post" name="faq_form">

<table width="100%" class="table table-middle">
<thead>
<tr>
	<th>
        {include file="common/check_items.tpl"}</th>
	<th>{__("ct_faq_category_name")}</th>
</tr>
</thead>
{foreach from=$faq_categories item=category}
<tr >
	<td >
		<input type="checkbox" name="{$smarty.request.checkbox_name|default:"banners_ids"}[]" value="{$category.category_id}" class="checkbox cm-item" /></td>
	<td id="faq_{$category.category_id}" width="100%">{$category.name}</td>
</tr>
{foreachelse}
<tr class="no-items">
	<td colspan="2"><p>{__("no_items")}</p></td>
</tr>
{/foreach}
</table>

{if $faq_categories}
<div class="buttons-container">
	{include file="buttons/add_close.tpl" but_text=__("add_ct_faq") but_close_text=__("add_faq_and_close") is_js=$smarty.request.extra|fn_is_empty}
</div>
{/if}

</form>
