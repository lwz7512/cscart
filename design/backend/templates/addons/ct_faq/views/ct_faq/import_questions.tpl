{include file="views/profiles/components/profiles_scripts.tpl"}
{capture name="mainbox"}

        <form action="{""|fn_url}" method="post" target="" class="form-horizontal form-edit" name="requests_form" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label" >{__("select_file")}:</label>
            <div class="controls">
            {include file="common/fileuploader.tpl" var_name="csv_file[0]" prefix=$p_id}
                </div>
        </div>

            {capture name="buttons"}
                {include file="buttons/button.tpl" but_name="dispatch[ct_faq.import_questions]" but_target_form="requests_form" but_role="submit-link"  but_text=__("ct_faq_import")}
            {/capture}
        </form>
{/capture}
{include file="common/mainbox.tpl" title="" content=$smarty.capture.mainbox buttons=$smarty.capture.buttons title_extra=$smarty.capture.title_extra}