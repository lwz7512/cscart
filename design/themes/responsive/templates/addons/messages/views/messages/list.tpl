
<table class="ty-table ty-orders-search">
    <thead>
        <tr>
            <th style="width: 70%"><a >Content</a></th>
            <th style="width: 10%"><a >Sender</a></th>
            <th style="width: 13%"><a >Time</a></th>
            <th style="width: 7%"><a >Status</a></th>
        </tr>
    </thead>
    {foreach from=$messages item="o"}
        <tr>
            <td class="ty-orders-search__item">
                <a href="{"messages.view?msg_id=`$o.msg_id`"|fn_url}">
                    <strong>{$o.content|truncate:60:"...":true}</strong>
                </a>
            </td>
            <td class="ty-orders-search__item">
                <strong>
                    {$o.sender_first_name} {$o.sender_last_name}
                </strong>
            </td>
            <td class="ty-orders-search__item">
                {$o.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
            </td>
            <td class="ty-orders-search__item">
                <strong>
                    {if $o.status==0}
                        Unread
                    {else}
                        Readed
                    {/if}
                </strong>
            </td>
        </tr>
    {/foreach}
</table>