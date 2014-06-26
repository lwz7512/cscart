{* add agenda select input *}

{* if has product in stock, and agenda is set then show it *}
{if $product_amount && !empty($product.agenda)}
    <div class="cm-reload-{$obj_prefix}{$obj_id}" id="agenda_update_{$obj_prefix}{$obj_id}">
        <label class="ty-control-group__label" for="agenda_select_{$obj_prefix}{$obj_id}">Agenda:</label>
        <select name="product_data[{$obj_id}][agenda]" id="qty_count_{$obj_prefix}{$obj_id}">
        {foreach from=$product.agenda item="var"}
            <option value="{$var.agenda_id}">{$var.from_time}</option>
        {/foreach}
        </select>
    </div>
{/if}