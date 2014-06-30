{** Copyright (c) 2012 CartTuning (www.carttuning.com). All rights reserved. **}

{if $display_faq == "static"}
    {include file="addons/ct_faq/views/ct_faq/themes/static.tpl"}
{elseif $display_faq == "side"}
    {include file="addons/ct_faq/views/ct_faq/themes/side.tpl"}
{elseif $display_faq == "grey"}
    {include file="addons/ct_faq/views/ct_faq/themes/grey.tpl"}
{elseif $display_faq == "blue"}
    {include file="addons/ct_faq/views/ct_faq/themes/blue.tpl"}
{elseif $display_faq == "line"}
    {include file="addons/ct_faq/views/ct_faq/themes/line.tpl"}
{elseif $display_faq == "shadow"}
    {include file="addons/ct_faq/views/ct_faq/themes/shadow.tpl"}
{elseif $display_faq == "bubble"}
    {include file="addons/ct_faq/views/ct_faq/themes/bubble.tpl"}
{elseif $display_faq == "slide"}
    {include file="addons/ct_faq/views/ct_faq/themes/slide.tpl"}
{/if}

