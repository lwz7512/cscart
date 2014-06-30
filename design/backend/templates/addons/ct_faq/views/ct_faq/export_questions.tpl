{include file="views/profiles/components/profiles_scripts.tpl"}
{capture name="mainbox"}

        <form action="{""|fn_url}" method="post" target="" name="requests_form" enctype="multipart/form-data">
            <table cellpadding="0" cellspacing="0" border="0" class="table" width="100%">
                <tr>
                    <th width="80%">{__("filename")}</th>
                    <th width="10%">{__("filesize")}</th>
                    <th width="10%">&nbsp;</th>
                </tr>
                {foreach from=$files name=file item=file}
                    {assign var="file_name" value=$file.name|escape:"url"}
                    <tr {cycle values="class=\"table-row\", "}>
                        <td>
                            <a href="{"ct_faq.get_file?filename=`$file_name`"|fn_url}">{$file.name}</a></td>
                        <td>
                            {$file.size|number_format}&nbsp;{__("bytes")}
                        </td>
                        <td class="nowrap">
                           {capture name="tools_list"}
                                <li>{btn type="list" text=__("download") href="ct_faq.get_file&filename=`$file_name`"}
                                </li>
                                <li>{btn type="list" class="cm-confirm" text=__("delete") href="ct_faq.delete_file&amp;filename=`$file_name`"}</li>
                            {/capture}
                            <div class="hidden-tools right">
                                {dropdown content=$smarty.capture.tools_list}
                            </div>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr class="no-items">
                        <td colspan="4"><p>{__("no_data")}</p></td>
                    </tr>
                {/foreach}
            </table>
            {capture name="buttons"}
                {include file="buttons/button.tpl" but_name="dispatch[ct_faq.export_questions]" but_target_form="requests_form" but_role="submit-link"  but_text=__("ct_faq_export")}
            {/capture}
        </form>
{/capture}
{include file="common/mainbox.tpl" title="" content=$smarty.capture.mainbox buttons=$smarty.capture.buttons title_extra=$smarty.capture.title_extra}