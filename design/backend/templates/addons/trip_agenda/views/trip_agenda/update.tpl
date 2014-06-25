{capture name="mainbox"}
    <form class="form-horizontal form-edit {$form_class} " action="{""|fn_url}" method="post" id="agenda_update_form" enctype="multipart/form-data"> {* company update form *}

        <input type="hidden" name="agenda_id" value="{$id}" />

        {* choose product *}
        <div class="control-group">
            <label class="control-label cm-required" for="elm_agenda_product">Choose Product:</label>
            <div class="controls">
                <input type="hidden" name="agenda_data[product_id]" id="elm_agenda_product" value="{$agenda.product_id}" />
                {* the drop down list is scrollerable, and more than 6 row displayed *}
                {include id="product_selector"
                        file="common/ajax_select_object.tpl"
                        data_url="products.get_vendor_product_list?company_id=1"
                        text=$agenda.product|default:__("none")
                        result_elm="elm_agenda_product"
                }
            </div>
        </div>

        {* choose period *}
        <div class="control-group">
            <label class="control-label cm-required" >From date - To date:</label>

            <div class="controls">
                {* from date *}
                {include file="common/calendar.tpl"
                        date_id="agenda_f_date"
                        date_name="agenda_time_from"
                        date_val=$agenda.from_time
                }

                    &nbsp;&nbsp;-&nbsp;&nbsp;

                {* to date *}
                {include file="common/calendar.tpl"
                        date_id="agenda_t_date"
                        date_name="agenda_time_to"
                        date_val=$agenda.to_time
                }
            </div><!--end of controls-->
        </div><!--end of control-group-->

    </form>


    {* TODO, ... add full calendar to see all the agenda *}


{/capture}

{** Form submit section **}
{capture name="buttons"}
    {if $id}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[trip_agenda.update]" but_target_form="agenda_update_form" save=$id}
    {else}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[trip_agenda.add]" but_target_form="agenda_update_form"}
    {/if}
{/capture}
{** /Form submit section **}

{if $id}
    {include file="common/mainbox.tpl"
    title=__("edit_agenda")
    select_languages=true
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
    }
{else}
    {include file="common/mainbox.tpl"
            title=__("add_agenda")
            select_languages=true
            content=$smarty.capture.mainbox
            buttons=$smarty.capture.buttons
    }
{/if}
