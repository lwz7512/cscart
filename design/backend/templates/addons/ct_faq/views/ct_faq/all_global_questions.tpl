{include file="views/profiles/components/profiles_scripts.tpl"}
{capture name="mainbox"}
    {capture name="tabsbox"}
        <form action="{$index_script}" method="post"  name="requests_form" class="form-horizontal form-edit">
            {assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
            {include file="common/pagination.tpl" save_current_page=true div_id=$smarty.request.content_id}
            <input type="hidden" name="selected_section" value="{$smarty.request.selected_section}"/>

            <table  width="100%" class="table table-middle">
                <tbody>
                <tr class="cm-first-sibling">
                    <th class="center" width="1%"><input type="checkbox" name="check_all" value="Y" class="checkbox cm-check-items" /></th>
                    <th width="1%">&nbsp;</th>
                    <th width="53%">{__("ct_faq_questions")}</th>
                    <th width="15%">&nbsp;</th>
                    <th width="10%">&nbsp;</th>
                    <th width="20%">&nbsp;</th>
                </tr>
                </tbody>
                {foreach from=$global_questions item="var" name="fe_f"}
                    {assign var="num" value=$smarty.foreach.fe_f.iteration}
                    <tbody class="hover" id="box_feature_variants_{$var.question_id}">
                    <tr class="cm-first-sibling {cycle values="table-row, "}">
                        <td class="center" width="1%">
                            <input type="checkbox" name="ct_loaded_faq_ids[]" value="{$var.question_id}" class="checkbox cm-item" />
                        </td>
                        <td class="center" width="1%">
                            {$num}
                        </td>
                        <td class="rise " width="53%">
                            <div class="form-field2">
                                <input type="text"  value="{$var.question}" style="width:500px;"/>
                            </div>
                        </td>
                        <td width="15%">
                            {__("ct_faq_assigned_for")}[{$var.count}]
                        </td>
                        <td width="10%">
                            {include file="common/select_popup.tpl" id="`$var.question_id`_faq" status=$var.status hidden=false object_id_name="question_id" table="faq_global_questions"}
                        </td>
                        <td width="20%">
                            {capture name="tools_list"}
                                <li>{btn type="list" text=__("edit") href="ct_faq.update_global_question&question_id=`$var.question_id`"}
                                </li>
                                <li>{btn type="list" class="cm-confirm" text=__("delete") href="ct_faq.delete_global_question&amp;question_id=`$var.question_id`"}</li>
                            {/capture}
                            <div class="hidden-tools right">
                                {dropdown content=$smarty.capture.tools_list}
                            </div>
                        </td>
                    </tr>
                    </tbody>
                {/foreach}
            </table>
            {include file="common/pagination.tpl"  div_id=$smarty.request.content_id}
            {capture name="buttons"}
                {if $global_questions}
                    {capture name="tools_list"}
                        <li>{btn type="delete_selected" dispatch="dispatch[ct_faq.delete_selected_global_questions]" form="requests_form"}</li>
                    {/capture}
                    {dropdown content=$smarty.capture.tools_list}
                 {/if}
            {/capture}
        </form>


    {/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name=$controller active_tab=$smarty.request.selected_section track=true}
{/capture}
{include file="common/mainbox.tpl" title=__("ct_faq_edit_group_questions") content=$smarty.capture.mainbox title_extra=$smarty.capture.title_extra select_languages=true}