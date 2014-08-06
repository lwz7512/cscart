{capture name="mainbox"}
    <table width="100%" class="table table-middle">
        <thead>
        <tr>
            <th width="5%">
                <a class="cm-ajax" href="#">{__("id")}</a>
            </th>
            <th width="15%">
                <a class="cm-ajax" href="#">Vendor Name</a>
            </th>
            <th width="10%">
                <a class="cm-ajax" href="#">Certificate</a>
            </th>
            <th width="10%">
                <a class="cm-ajax" href="#">Grade</a>
            </th>
            <th width="10%">
                <a class="cm-ajax" href="#">Credits</a>
            </th>
            <th width="15%">
                <a class="cm-ajax" href="#">Certificated Time</a>
            </th>
            <th width="15%">
                <a class="cm-ajax" href="#">Last Update Time</a>
            </th>
            <th width="10%" class="nowrap">Action</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$certificates item=certificate}
            {capture name="tools_items"}
                <li>{btn type="list" href="certificate.update?u_id=`$certificate.u_id`" text=__("edit")}</li>
                <li>{btn type="list" href="certificate.delete?u_id=`$certificate.u_id`" text=__("delete") class="cm-confirm" }</li>
            {/capture}
            <tr>
                <td>{$certificate.u_id}</td>
                <td>{$certificate.company}</td>
                <td>
                    {if $certificate.certificate}
                        <img src="{$images_dir}/addons/vendor_certificate/medal_gold_2.png">
                    {else}
                        -
                    {/if}
                </td>
                <td>{$certificate.grade}</td>
                <td>{$certificate.credits}</td>
                <td>{$certificate.create_time}</td>
                <td>{$certificate.timestamp}</td>
                <td>
                    {dropdown content=$smarty.capture.tools_items}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/capture}

{capture name="adv_buttons"}
    {include file="common/tools.tpl"
        tool_href="certificate.add"
        prefix="top"
        hide_tools=true
        title=__("add_certificate")
        icon="icon-plus"
    }
{/capture}

{include file="common/mainbox.tpl"
        title=__("manage_certificate")
        adv_buttons=$smarty.capture.adv_buttons
        content=$smarty.capture.mainbox
        select_languages=true
}
