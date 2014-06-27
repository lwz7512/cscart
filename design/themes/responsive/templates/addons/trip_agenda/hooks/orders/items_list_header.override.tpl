<thead>
<tr>
    <th class="ty-orders-detail__table-product">{__("product")}</th>
    {if !empty($order_info.agenda)}
        <th class="ty-orders-detail__table-price">{__("agenda")}</th>
    {/if}
    <th class="ty-orders-detail__table-price">{__("price")}</th>
    <th class="ty-orders-detail__table-quantity">{__("quantity")}</th>
    {if $order_info.use_discount}
        <th class="ty-orders-detail__table-discount">{__("discount")}</th>
    {/if}
    {if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
        <th class="ty-orders-detail__table-tax">{__("tax")}</th>
    {/if}
    <th class="ty-orders-detail__table-subtotal">{__("subtotal")}</th>
</tr>
</thead>