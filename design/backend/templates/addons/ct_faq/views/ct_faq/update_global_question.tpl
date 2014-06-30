{include file="views/profiles/components/profiles_scripts.tpl"}
{capture name="mainbox"}
    {capture name="tabsbox"}
        <form action="{""|fn_url}" method="post" target="" name="requests_form" class="form-horizontal form-edit">
            {assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

            <input type="hidden" name="question_id" value="{$smarty.request.question_id}"/>

            <div id="content_questions">

                    <div class="control-group">
                        <label class="control-label" for="elm_description">{__("ct_faq_questions")}</label>
                        <div class="controls">
                        <input type="text" name="global_question[question]" value="{$global_question.question}" style="width:500px;" />
                    </div>
                        </div>
                    <div class="control-group">
                        <label class="control-label" for="elm_description">{__("answer")}</label>
                        <div class="controls">
                        <textarea id="elm_description" name="global_question[answer]" cols="55" rows="8" class="cm-wysiwyg  " style="width:auto;">{$global_question.answer}</textarea>
                    </div>
                        </div>
                    {include file="common/select_status.tpl" input_name="global_question[status]" id="question" obj=$global_question hidden=false}
            </div>
            <div id="content_products">
                <fieldset>
                    {include file="pickers/products/picker.tpl" type="links" input_name="ct_faq_products[product_ids]" no_item_text=__("no_products_selected") item_ids=$global_question.item_ids}
                </fieldset>
            </div>
            {capture name="buttons"}
                {include file="buttons/save_cancel.tpl" but_name="dispatch[ct_faq.update_global_question]"  but_role="submit-link" but_target_form="requests_form" save=1}
            {/capture}

        </form>

    {/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name=$controller active_tab=$smarty.request.selected_section track=true}
{/capture}
{include file="common/mainbox.tpl" title=$global_question.question content=$smarty.capture.mainbox buttons=$smarty.capture.buttons title_extra=$smarty.capture.title_extra}