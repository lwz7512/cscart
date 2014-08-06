{capture name="mainbox"}
    <form class="form-horizontal form-edit {$form_class} " action="{""|fn_url}" method="post" id="certificate_update_form" enctype="multipart/form-data">
        <input type="hidden" name="u_id" value="{$id}" />
        <input type="hidden" name="certificate_data[credits]" value="{$certificate.credits}" />
        <input type="hidden" name="certificate_data[create_time]" value="{$certificate.create_time}" />

        {* choose vendor *}
        <div class="control-group">
            <label class="control-label cm-required" for="elm_certificate_vendor">Choose Vendor:</label>
            <div class="controls">
                <input type="hidden" id="elm_certificate_vendor" name="certificate_data[vendor_id]" value="{$certificate.vendor_id}" />
                {* the drop down list is scrollerable, and more than 6 row displayed *}
                {include id="vendor_selector"
                    file="common/ajax_select_object.tpl"
                    data_url="certificate.get_vendor_list"
                    text=$certificate.vendor_name|default:__("none")
                    result_elm="elm_certificate_vendor"
                }
            </div>
        </div>

        {* fill certificate *}
        <div class="control-group">
            <label class="control-label " for="elm_certificate_check">Authorize Certificate:</label>
            <div class="controls">
                <input type="checkbox" id="elm_certificate_check" name="certificate_data[certificate]" value="1" {if $certificate.certificate == 1}checked="checked"{/if}>
            </div><!--end of controls-->
        </div><!--end of control-group-->

        {* fill grade *}
        <div class="control-group">
            <label class="control-label " for="elm_certificate_grade">Certificate:</label>
            <div class="controls">
                <input type="text" id="elm_certificate_grade" name="certificate_data[grade]" value="{$certificate.grade|default:'1'}" />
            </div><!--end of controls-->
        </div><!--end of control-group-->

    </form>
{/capture}


{** Form submit section **}
{capture name="buttons"}
    {if $id}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[certificate.update]" but_target_form="certificate_update_form" save=$id}
    {else}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[certificate.add]" but_target_form="certificate_update_form"}
    {/if}
{/capture}
{** /Form submit section **}

{if $id}
    {include file="common/mainbox.tpl"
    title=__("edit_certificate")
    select_languages=true
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
    }
{else}
    {include file="common/mainbox.tpl"
    title=__("add_certificate")
    select_languages=true
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
    }
{/if}

