{capture name="mainbox"}
    <form class="form-horizontal form-edit {$form_class} " action="{""|fn_url}" method="post" id="agenda_update_form" enctype="multipart/form-data"> {* company update form *}

        <input type="hidden" name="agenda_id" value="{$id}" />
        <!--for calendar use in func.js-->
        <input type="hidden" id="company_id" name="company_id" value="{$runtime.company_id|default:0}" />

        {* choose product *}
        <div class="control-group">
            <label class="control-label cm-required" for="elm_agenda_product">Choose Product:</label>
            <div class="controls">
                <input type="hidden" name="agenda_data[product_id]" id="elm_agenda_product" value="{$agenda.product_id}" />
                {* the drop down list is scrollerable, and more than 6 row displayed *}
                {include id="product_selector"
                        file="common/ajax_select_object.tpl"
                        data_url="products.get_vendor_product_list?company_id={$runtime.company_id|default:0}"
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

    <!--bootstrap calendar-->
    <div class="container">
        <div class="page-header">
            <div class="pull-right form-inline">
                <div class="btn-group">
                    <button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
                    <button class="btn" data-calendar-nav="today">Today</button>
                    <button class="btn btn-primary" data-calendar-nav="next">Next >></button>
                </div>
                <div class="btn-group">
                    <button class="btn btn-warning" data-calendar-view="year">Year</button>
                    <button class="btn btn-warning active" data-calendar-view="month">Month</button>
                    <button class="btn btn-warning" data-calendar-view="week">Week</button>
                    <button class="btn btn-warning" data-calendar-view="day">Day</button>
                </div>
            </div>

            <h3></h3>
            <small>To see example with events navigate to march 2013</small>
        </div>

        <div class="row">
            <div class="span11">
                <div id="trip_calendar" ></div>
            </div>
            <div class="span1"></div>
        </div>
    </div>
    <!--end of bootstrap calendar-->

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
