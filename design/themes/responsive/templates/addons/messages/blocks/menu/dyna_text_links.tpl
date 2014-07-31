{** block-description:dyna_text_links **}

<ul class="ty-text-links">
    {foreach from=$items item="menu"}
        <li class="ty-text-links__item ty-level-{$menu.level|default:0}{if $menu.active} ty-text-links__active{/if}">
            <a id="message-box" class="ty-text-links__a" {if $menu.href}href="{$menu.href|fn_url}"{/if}>{$menu.item}</a>
        </li>
    {/foreach}
</ul>

<script type="text/javascript">
    (function(_, $) {

        $(document).ready(function() {

            $.ceAjax('request', fn_url('messages.count'), {
                callback: function(data) {
                    if(data['count_unread']>0){
                        $('#message-box').append('('+data['count_unread']+')');
                    }
                }
            });//end of ajax call

        });//end of ready handler

    }(Tygh, Tygh.$));//end of module
</script>