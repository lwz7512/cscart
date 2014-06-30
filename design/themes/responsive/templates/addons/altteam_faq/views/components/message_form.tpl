<div class="hidden" id="faq_post_message_{if $id}{$id}{else}new{/if}" title="{if $f_action == 'new_question'}{__("new_post")}{else}{__("add_answer")}{/if}">
    <form action="{""|fn_url}" method="post" class="posts-form " name="add_faq_form_{$id}" id="add_faq_post_form_{$id}">
        <input type ="hidden" name="faq_data[thread_id]" value="{$thread_id}" />
        <input type ="hidden" name="redirect_url" value="{$config.current_url}" />
        <input type="hidden" name="selected_section" value="faq" />
        <input type="hidden" name="faq_data[faq_id]" value="{$id}" />
        {* add object_id param for the first new post  *}
        <input type="hidden" name="object_id" value="{$product.product_id}" />

        <div id="new_faq_post_{$id}">

            <div class="control-group">
                <label for="dsc_faq_name_{$id}" class="control-label cm-required">
                    {__("your_name")}:
                </label>
                <div class="controls" style="margin: 10px 0 10px;">
                    <input type="text" id="dsc_faq_name_{$id}" name="faq_message[name]" value="{if $auth.user_id}{$user_info.firstname}&nbsp;{$user_info.lastname}{/if}" size="20" class="input-small" />
                </div>
            </div>

            {if $f_action == 'new_question'}
            <div class="control-group">
                <label for="dsc_faq_email_{$id}" class="control-label cm-email {if $addons.altteam_faq.mandatory_email == 'Y'}cm-required{/if}">
                    {__("your_email")}&nbsp;(<a class="cm-tooltip" title="{__("email_notification")}">?</a>):
                </label>
                <div class="controls" style="margin: 10px 0 10px;">
                    <input type="text" id="dsc_faq_email_{$id}" name="faq_message[email]" value="" size="50" class="input-text" />
                </div>
            </div>
            {/if}

            <div class="control-group">
                <label for="dsc_faq_message_{$id}" class="control-label cm-required">
                    {if $runtime.action == 'new_answer'}{__("your_answer")}{else}{__("your_question")}{/if}:
                </label>
                <div class="controls" style="margin: 10px 0 10px;">
                    <textarea id="dsc_faq_message_{$id}" name="faq_message[message]" class="input-textarea" rows="5" cols="72"></textarea>
                </div>
            </div>

            {include file="common/image_verification.tpl" option="use_for_discussion"}

        </div><!--new_faq_post_{$id}-->

        <div class="buttons-container">
            {include file="buttons/button.tpl" but_text=__("submit") but_role="submit" but_name="dispatch[faq.add_faq]"}
        </div>

    </form>
</div>