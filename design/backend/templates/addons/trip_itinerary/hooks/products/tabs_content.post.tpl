{* trip itinerary setting tab *}

<style type="text/css">

    .activity-set {
        padding: 10px;
        margin: 10px;
        border:1px dashed #C0C0C0;
    }

</style>


<div id="content_trip_itinerary" class="hidden">

    {* itinerary title *}
    <div class="control-group">
        <label for="trip_itinerary_title" class="control-label">{__('itinerary_title')}:</label>
        <div class="controls">
            <input id="trip_itinerary_title" name="product_data[itinerary_title]" form="form" type="text" class="input-large" value="{$product_data.trip.itinerary_title}"/>
        </div>
    </div>
    {* itinerary days *}
    <div class="control-group">
        <label for="trip_itinerary_days" class="control-label">{__('itinerary_days')}:</label>
        <div class="controls">
            <input id="trip_itinerary_days" name="product_data[itinerary_days]" form="form" type="text" class="input-small" value="{$product_data.trip.itinerary_days}"/>
        </div>
    </div>

    {* button to add one day below *}
    <div class="control-group">
        <div class="controls">
            <input type="button" id="add_one_day" class="btn btn-primary" value="{__('itinerary_add_day')}"/>
        </div>
    </div>

</div><!--end of form-->

<script>


    /**
     * several day activity structure:
     *
     * .subheader
     * #activity_group_day_sequence  -->one day
     *   .activity-set  -->one activity
     *     .control-group
     *     .control-group
     *     .control-group
     *   .activity-set  -->one activity
     *     .control-group
     *     .control-group
     *     .control-group
     *   ...
     *
     * .subheader
     * #activity_group_day_sequence  -->one day
     *   .activity-set  -->one activity
     *     .control-group
     *     .control-group
     *     .control-group
     *   .activity-set  -->one activity
     *     .control-group
     *     .control-group
     *     .control-group
     *   ...
     *
     * ...
     */

    (function(_, $) {

        var day_sequence = 0;

        var one_day_it_tmpl =  '<div class="control-group">';
            one_day_it_tmpl +=   '<label class="control-label day">{__('the_x_day')}</label>';//day label
            one_day_it_tmpl +=   '<div class="controls">';
            one_day_it_tmpl +=     '<input type="text" class="input-medium" style="width: 480px" placeholder="one day summary"/>&nbsp;';
            one_day_it_tmpl +=     '<input type="button" class="btn add-activity" value="{__('itinerary_add_activity')}"/>';
            one_day_it_tmpl +=    '</div>';
            one_day_it_tmpl += '</div>';

        var sub_header_tmpl =  '<h4 class="subheader hand" data-toggle="collapse" >';
            sub_header_tmpl +=   '{__('details')}';
            sub_header_tmpl +=   '<span class="exicon-collapse"></span>';
            sub_header_tmpl += '</h4>';

        var collapse_container_tmpl =  '<div class="in collapse"></div>';//one day container

        var activity_tmpl =  '<div class="activity-set">';//activity container

            activity_tmpl +=   '<div class="control-group">';
            activity_tmpl +=     '<label class="control-label">{__('activity_time')}:</label>';
            activity_tmpl +=     '<div class="controls">';
            activity_tmpl +=       '<input orm="form" type="text" class="input-small" placeholder="HH:MM"/>';
            activity_tmpl +=     '</div>';
            activity_tmpl +=   '</div>';

            activity_tmpl +=   '<div class="control-group">';
            activity_tmpl +=     '<label class="control-label">{__('itinerary_activity_type')}:</label>';
            activity_tmpl +=     '<div class="controls">';
            activity_tmpl +=       '<select >';
            activity_tmpl +=         '<option selected="selected" value="p">Plane</option>';
            activity_tmpl +=         '<option value="s">Scenery</option>';
            activity_tmpl +=         '<option value="h">Hotel</option>';
            activity_tmpl +=         '<option value="h">Hotel</option>';
            activity_tmpl +=         '<option value="f">Food</option>';
            activity_tmpl +=         '<option value="o">Other</option>';
            activity_tmpl +=        '</select>';
            activity_tmpl +=     '</div>';
            activity_tmpl +=   '</div>';

            activity_tmpl +=   '<div class="control-group">';
            activity_tmpl +=     '<label class="control-label">{__('location')}:</label>';
            activity_tmpl +=     '<div class="controls">';
            activity_tmpl +=       '<input orm="form" type="text" class="input-medium" placeholder="longitude,latitude"/>';
            activity_tmpl +=     '</div>';
            activity_tmpl +=   '</div>';

            activity_tmpl +=   '<div class="control-group">';
            activity_tmpl +=     '<label class="control-label">{__('simple_description')}:</label>';
            activity_tmpl +=     '<div class="controls">';
            activity_tmpl +=       '<textarea id="simple_activity_desc" cols="55" rows="2" class="cm-wysiwyg input-medium"></textarea>';
            activity_tmpl +=     '</div>';
            activity_tmpl +=   '</div>';

            activity_tmpl +=   '<div class="control-group">';
            activity_tmpl +=     '<label class="control-label">{__('description')}:</label>';
            activity_tmpl +=     '<div class="controls">';
            activity_tmpl +=       '<textarea id="details_activity_desc" cols="55" rows="2" class="cm-wysiwyg input-large"></textarea>';
            activity_tmpl +=     '</div>';
            activity_tmpl +=   '</div>';

            //TODO, add picture uploader...

            activity_tmpl += '</div>';//end of activity

        var itinerary_container_selector = "#content_trip_itinerary";
        var new_subheader_selector = "#content_trip_itinerary .subheader:last";
        var new_day_container_selector = "#content_trip_itinerary .collapse:last";
        var new_day_label_selector = "#content_trip_itinerary .day:last";
        var add_activity_btn_selector = "#content_trip_itinerary .add-activity:last";

        $("#add_one_day").click(function(){

            day_sequence ++;

            //add one day container
            $(itinerary_container_selector).append(one_day_it_tmpl, sub_header_tmpl, collapse_container_tmpl);

            //set day sequence label
            var the_x_day = '{__('the_x_day')}'.replace('x', day_sequence.toString());
            $(new_day_label_selector).html(the_x_day);

            //make one day activities folding/collapsible...
            $(new_subheader_selector).attr('data-target', '#activity_group_'+day_sequence);
            $(new_day_container_selector).attr('id', 'activity_group_'+day_sequence);

            //save the day_container and button relationship to button !
            $(add_activity_btn_selector).data('target-id', 'activity_group_'+day_sequence);
            $(add_activity_btn_selector).data('day_sequence', day_sequence);

            //default to add one activity
            add_activity_tmpl('activity_group_'+day_sequence, day_sequence, 1);

            //find button, to dynamic add activity handler
            $(add_activity_btn_selector).click(function(){
                var self = $(this);

                var exist_activity_set_size = $("#"+self.data('target-id')+" .activity-set").size();

                add_activity_tmpl(self.data('target-id'), self.data('day_sequence'), exist_activity_set_size+1);

            });//end of add activity click


        });//end of add day click


        /**
         *
         * create an activity form in each day
         *
         * @param jq_cntr_id, activity-set container
         * @param day_seq, day sequence
         * @param activity_seq, activity sequence in each day
         */
        function add_activity_tmpl(jq_cntr_id, day_seq, activity_seq){
            //add activity container...
            $("#"+jq_cntr_id).append(activity_tmpl);

            //set activity-set to navigate
            var activity_id = 'activity-set_'+day_seq+'_'+activity_seq;
            //in current container
            $("#"+jq_cntr_id+" .activity-set:last").attr('id', activity_id);

            //set textarea to cEeditor
            $("#simple_activity_desc").attr('id', 'simple_act_desc_'+day_seq+'_'+activity_seq);
            $("#details_activity_desc").attr('id', 'detail_act_desc_'+day_seq+'_'+activity_seq);

            $('#simple_act_desc_'+day_seq+'_'+activity_seq).ceEditor('run');
            $('#detail_act_desc_'+day_seq+'_'+activity_seq).ceEditor('run');

            if(!$("#"+jq_cntr_id).hasClass('in'))//when collapsed
                $("#"+jq_cntr_id).collapse('show');//expand collapsed container


            scroll_to_new_activity(activity_id);

        }



        /**
         * auto scroll to bottom to show newly added content
         *
         * @param div_id, scroll target: new activity_set
         */
        function scroll_to_new_activity(div_id){

            var absolute_position = $("#"+div_id).offset();
            var move_distance = absolute_position.top;
            $("html,body").animate({
                scrollTop:move_distance
            },'slow');

        }




    }(Tygh, Tygh.$));

</script>