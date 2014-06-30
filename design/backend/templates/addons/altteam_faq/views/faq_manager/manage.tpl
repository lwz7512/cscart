{capture name="tabsbox"}

    <div id="content_{$faq_object_types.$object_type}">

        <form action="{""|fn_url}" method="POST" name="update_faqs_form">
            <input type="hidden" name="redirect_url" value="{$config.current_url}" />
            {include file="addons/altteam_faq/views/components/faq_container.tpl" faq_data=$faq_data}
        </form>

        <!--content_{$faq_object_types.$object_type}-->
    </div>

{/capture}

{capture name="mainbox"}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$faq_object_types.$object_type track=true}
{/capture}

{capture name="buttons"}
    {if $faq_data}
        {capture name="tools_list"}
            <li>{btn type="delete_selected" dispatch="dispatch[faq.delete_faqs]" form="update_faqs_form"}</li>
        {/capture}
        {dropdown content=$smarty.capture.tools_list}

        {include file="buttons/save.tpl" but_name="dispatch[faq.update_faqs]" but_role="submit-link" but_target_form="update_faqs_form"}
    {/if}
{/capture}

{capture name="add_new_faq_faq"}

    <form action="{""|fn_url}" method="post" name="add_faq_form" class="form-horizontal form-edit ">
        <input type ="hidden" name="faq_data[thread_id]" value="{$faq.thread_id}" />
        <input type ="hidden" name="redirect_url" value="{$config.current_url}&amp;selected_section=faq" />

        <div class="add-new-object-group">
            <div class="tabs cm-j-tabs">
                <ul class="nav nav-tabs">
                    <li id="tab_add_faq" class="cm-js active"><a>{__("general")}</a></li>
                </ul>
            </div>

            <div class="cm-tabs-content" id="content_tab_add_faq">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label cm-required" for="faq_message_name">{__("name")}:</label>
                        <div class="controls">
                            <input type="text" name="faq_message[name]" id="faq_message_name" value="{if $auth.user_id}{$user_info.firstname} {$user_info.lastname}{/if}" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="faq_message_email" class="control-label cm-email">{__("email")}:</label>
                        <div class="controls">
                            <input type="text" name="faq_message[email]" id="faq_message_email" value="" size="40" class="span5" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="faq_message_emai_product">{$lang.product}:</label>

                        <div class="controls" id="faq_message_emai_product">
                            {include file="pickers/products/picker.tpl" input_name="selected_ids" data_id="added_products" item_ids=$selected_ids type="links"}
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="faq_message_message" class="control-label">{__("question")}:</label>
                        <div class="controls">
                            <textarea name="faq_message[message]" id="faq_message_message" class="span5" cols="70" rows="8"></textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="buttons-container">
            {include file="buttons/save_cancel.tpl" but_text=$lang.add_faq but_name="dispatch[faq.add_faq]" cancel_action="close"}
        </div>
    </form>

{/capture}

{*{if $faq_data}*}
{capture name="adv_buttons"}
    {include file="common/popupbox.tpl" id="add_new_faq_faq" text=__("new_faq") content=$smarty.capture.add_new_faq_faq  act="general" title=__("add_faq") icon="icon-plus"}
{/capture}
{*{/if}*}

{include file="common/mainbox.tpl"
        title=__("faq")
        content=$smarty.capture.mainbox
        title_extra=$smarty.capture.title_extra
        buttons=$smarty.capture.buttons
        adv_buttons=$smarty.capture.adv_buttons}