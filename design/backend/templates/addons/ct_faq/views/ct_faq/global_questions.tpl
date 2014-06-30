{include file="views/profiles/components/profiles_scripts.tpl"}
{capture name="mainbox"}
    {capture name="tabsbox"}
        <form action="{""|fn_url}" method="post" name="requests_form" class="form-horizontal form-edit">
            {assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

            <input type="hidden" name="selected_section" value="{$smarty.request.selected_section}"/>

            <div id="content_questions">
                <table  width="100%" class="table table-middle">
                    <tbody>
                    <tr class="cm-first-sibling">
                        <th>{__("ct_faq_questions")}</th>
                        {if $category_questions}<th>{__("status")}</th>{else}<th></th>{/if}
                        <th class="cm-extended-feature "><img src="{$images_dir}/plus_minus.gif" width="13" height="12" border="0" name="plus_minus" id="on_st_{$id}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combinations-features-{$id}" /><img src="{$images_dir}/minus_plus.gif" width="13" height="12" border="0" name="minus_plus" id="off_st_{$id}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combinations-features-{$id}" /></th>
                        <th>&nbsp;</th>
                    </tr>
                    </tbody>
                    {foreach from=$category_questions item="var" name="fe_f"}
                        {assign var="num" value=$smarty.foreach.fe_f.iteration}
                        <tbody class="hover" id="box_feature_variants_{$var.question_id}">
                        <tr class="cm-first-sibling {cycle values="table-row, "}">
                            <td class="rise td-width">
                                    <input type="text" name="global_questions[{$num}][question]" value="{$var.question}" style="width:500px;" />
                            </td>
                            <td class="rise">{include file="common/select_popup.tpl" id=$var.question_id status=$var.status hidden=false object_id_name="question_id" table="faq_questions"}</td>
                            <td class="cm-extended-feature rise ">
                                <img src="{$images_dir}/plus.gif" width="14" height="9" border="0" name="plus_minus" id="on_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-features-{$id}" /><img src="{$images_dir}/minus.gif" width="14" height="9" border="0" name="minus_plus" id="off_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-features-{$id}" /><a id="sw_extra_feature_{$id}_{$num}" class="cm-combination-features-{$id}">{__("extra")}</a>
                            </td class="rise">
                            <td class="right nowrap rise">
                                {include file="buttons/multiple_buttons.tpl" item_id="feature_variants_`$var.question_id`" only_delete="Y"}
                            </td>
                        </tr>
                        <tr class="hidden" id="extra_feature_{$id}_{$num}">
                            <td colspan="5">
                                <div class="control-group">
                                    <label class="control-label" for="elm_description_{$id}_{$num}">{__("answer")}</label>
                                    <div class="controls">
                                    <textarea id="elm_description_{$id}_{$num}" name="global_questions[{$num}][answer]" cols="55" rows="8" class="cm-wysiwyg "  style="width:auto;">{$var.answer}</textarea>
                                </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    {/foreach}

                    {math equation="x + 1" assign="num" x=$num|default:0}
                    <tbody class="hover" id="box_add_variants_for_existing_{$id}_{$num}">
                    <tr>
                        <td class="rise td-width">
                                <input type="text" name="global_questions[{$num}][question]" value="" style="width:500px;" />
                        </td>
                        <td class="rise"></td>
                        <td class="cm-extended-feature rise ">
                            <img src="{$images_dir}/plus.gif" width="14" height="9" border="0" name="plus_minus" id="on_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-features-{$id}" /><img src="{$images_dir}/minus.gif" width="14" height="9" border="0" name="minus_plus" id="off_extra_feature_{$id}_{$num}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-features-{$id}-{$num}" /><a id="sw_extra_feature_{$id}_{$num}" class="cm-combination-features-{$id}-{$num}">{__("extra")}</a>
                        </td>
                        <td class="right rise">
                            {include file="buttons/multiple_buttons.tpl" item_id="add_variants_for_existing_`$id`_`$num`" hide_clone=true}</td>
                    </tr>
                    <tr class="hidden" id="extra_feature_{$id}_{$num}">
                        <td colspan="5">
                            <div class="control-group">
                                <label class="control-label" for="elm_description_{$id}_{$num}">{__("answer")}</label>
                                <div class="controls">
                                <textarea id="elm_description_{$id}_{$num}" name="global_questions[{$num}][answer]" cols="55" rows="8"  style="width:auto;">{$var.description}</textarea>
                            </div>
                                </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div id="content_products">
                <fieldset>
                    {include file="pickers/products/picker.tpl" type="links" input_name="ct_faq_products[product_ids]" no_item_text=__("text_all_items_included")|replace:"[items]":__("products")}
                </fieldset>
            </div>
            {capture name="buttons"}
                {include file="buttons/save_cancel.tpl" but_name="dispatch[ct_faq.add_group_of_questions]"  but_role="submit-link" but_target_form="requests_form" save=1}
            {/capture}
        </form>


    {/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox  active_tab=$smarty.request.selected_section track=true}
{/capture}
{include file="common/mainbox.tpl" title=__("ct_faq_group_questions") content=$smarty.capture.mainbox  buttons=$smarty.capture.buttons select_languages=true}