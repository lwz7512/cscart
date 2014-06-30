{** block-description:faq **}
{if $questions || $global_questions}
<div id="content_ct_faq_tab">

    {if $display_style_product == "static"}
        {include file="addons/ct_faq/blocks/product_tabs/themes/static.tpl"}
    {elseif $display_style_product == "side"}
        {include file="addons/ct_faq/blocks/product_tabs/themes/side.tpl"}
    {elseif $display_style_product == "grey"}
        {include file="addons/ct_faq/blocks/product_tabs/themes/grey.tpl"}
    {elseif $display_style_product == "blue"}
        {include file="addons/ct_faq/blocks/product_tabs/themes/blue.tpl"}
    {elseif $display_style_product == "line"}
        {include file="addons/ct_faq/blocks/product_tabs/themes/line.tpl"}
    {elseif $display_style_product == "shadow"}
        {include file="addons/ct_faq/blocks/product_tabs/themes/shadow.tpl"}
    {elseif $display_style_product == "bubble"}
        {include file="addons/ct_faq/blocks/product_tabs/themes/bubble.tpl"}
    {elseif $display_style_product == "slide"}
        {include file="addons/ct_faq/blocks/product_tabs/themes/slide.tpl"}
    {/if}


</div>
{/if}