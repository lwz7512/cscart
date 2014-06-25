{capture name="mainbox"}
    <table width="100%" class="table table-middle">
        <thead>
            <tr>
                <th width="1%" class="left">
                    {include file="common/check_items.tpl"}
                </th>
                <th width="6%">
                    <a class="cm-ajax" href="#">{__("id")}</a>
                </th>
                <th width="25%">
                    <a class="cm-ajax" href="#">Product Name</a>
                </th>
                <th width="10%">
                    <a class="cm-ajax" href="#">Start Time</a>
                </th>
                <th width="10%">
                    <a class="cm-ajax" href="#">End Time</a>
                </th>
                <th width="10%">
                    <a class="cm-ajax" href="#">Create Time</a>
                </th>
                <th width="10%" class="nowrap">Action</th>
                <th width="10%" class="nowrap">Status</th>
            </tr>
        </thead>
        <tbody>
        {foreach from=$agendas item=agenda}
            {capture name="tools_items"}
                <li>{btn type="list" href="trip_agenda.update?agenda_id=`$agenda.agenda_id`" text=__("edit")}</li>
                <li>{btn type="list" href="trip_agenda.delete?agenda_id=`$agenda.agenda_id`" text=__("delete") class="cm-confirm" }</li>
            {/capture}
            <tr>
                <td><input type="checkbox" name="agenda_ids[]" value="" class="cm-item" /></td>
                <td>{$agenda.agenda_id}</td>
                <td class="nowrap">{$agenda.product}</td>
                <td>{$agenda.from_time}</td>
                <td>{$agenda.to_time}</td>
                <td>{$agenda.timestamp}</td>
                <td>
                    {dropdown content=$smarty.capture.tools_items}
                </td>
                <td>
                    {include file="common/select_popup.tpl"
                        id=$agenda.agenda_id
                        status=$agenda.status
                        object_id_name="agenda_id"
                        update_controller="trip_agenda"
                    }
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/capture}


{capture name="adv_buttons"}
    {include file="common/tools.tpl"
            tool_href="trip_agenda.add"
            prefix="top"
            hide_tools=true
            title=__("add_agenda")
            icon="icon-plus"
    }
{/capture}

{include file="common/mainbox.tpl"
        title=__("manage_agenda")
        adv_buttons=$smarty.capture.adv_buttons
        content=$smarty.capture.mainbox
        select_languages=true
}
