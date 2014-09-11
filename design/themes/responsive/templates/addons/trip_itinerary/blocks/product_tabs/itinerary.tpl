{** block-description:trip_itinerary **}


<style type="text/css">


    .basefix:after,
    .layoutfix:after {
        clear: both;
        content: '.';
        display: block;
        height: 0;
        overflow: hidden;
    }

    .icon_b {
        display:inline-block;width:40px;height:40px;
    }

    .journey_title .icon_b_day,
    .icon_m,
    .icon_s {
        background-image: url(http://pic.c-ctrip.com/vacation_v2/group_travel/un_group_detail.png?140812.png);
        background-repeat: no-repeat;
    }
    .icon_s {
        display: inline-block;
        width: 16px;
        height: 16px;
        background-color: #fff;
        padding: 3px 0;
    }
    .icon_s_food, .icon_s_f {
        background-position: 0 -81px;
    }
    .icon_s_htl, .icon_s_h {
        background-position: -19px -81px;
    }
    .icon_s_plane, .icon_s_p {
        background-position: -40px -81px;
    }
    .icon_s_scene, .icon_s_s {
        background-position: -59px -81px;
    }
    .icon_s_shop, .icon_s_m {
        background-position: -79px -81px;
    }
    .icon_s_pay, .icon_s_a {
        background-position: -99px -81px;
    }
    .icon_s_free, .icon_s_e {
        background-position: -119px -81px;
    }
    .icon_s_traffic, .icon_s_t {
        background-position: -139px -81px;
    }
    .icon_s_other, .icon_s_o {
        background-position: -159px -81px;
    }
    .icon_s_berth, .icon_s_b {
        background-position: -180px -81px;
    }
    .icon_s_ship, .icon_s_i {
        background-position: -199px -81px;
    }
    .icon_s_end, .icon_s_d {
        background-position: -217px -85px;
        margin-top: 7px;
        padding: 0;
    }
    .icon_m {
        display: inline-block;
        width: 34px;
        height: 18px;
        line-height: 0;
        font-size: 0;
        vertical-align: middle;
    }
    .icon_m_plane {
        background-position: 0 -110px;
    }
    .icon_m_bus {
        background-position: -34px -110px;
    }
    .icon_m_car {
        background-position: -68px -110px;
    }
    .icon_m_ship {
        background-position: -102px -110px;
    }
    .icon_m_train {
        background-position: -136px -110px;
    }
    .journey_list {
        margin-left: 100px;
        border-left: 2px solid #eaeaea;
        margin-bottom: 20px;
    }
    .journey_list li {
        padding: 0 0 20px 30px;/* determine the vertical line position */
        zoom: 1;
        position: relative;
    }
    .journey_list .time_list {
        margin-left: -164px;
        width: 84px;
        text-align: right;
        position: absolute;
    }
    .journey_title {
        position: relative;
    }
    .journey_title .time_list {
        font: normal 16px/32px microsoft yahei;
        margin-top: -4px;/* align with icon right side */
    }
    .journey_title .icon_b_day {
        margin-left: -68px;
        font-style: normal;
        color: #fff;
        font-size: 14px;
        font-family: tahoma;
        text-align: center;
        line-height: 34px;
        width: 34px;
        background-position: -242px -100px;
        height: 40px;
        position: absolute;
    }
    .journey_title h4 {
        margin-bottom: 30px;/* vertical gap from bottom*/
        font: normal 20px microsoft yahei;
        height: 30px;
    }
    .journey_title h4 .seo_link {
        color: #333;
        text-decoration: none;
        cursor: text
    }
    .journey_title h4 span {
        display: inline-block;
        vertical-align: middle;
    }
    .journey_title h4 a {
        color: #333;
    }
    .journey_title h4 .icon_m {
        margin: 0 10px;
    }
    .journey_service {
        padding: 4px 10px;
        line-height: 22px;
        background: #f5f5f5;
        color: #666;
    }
    .journey_service strong {
        color: #333;
        margin-right: 15px;
    }
    .journey_service .htl_link {
        color: #666;
    }
    .journey_detail {
        padding-top: 15px;
        line-height: 22px;
        zoom: 1;
        border-bottom: 1px dotted #ccc;
    }
    .journey_detail .time_list {
        font-style: italic;
        font-size: 12px;
        font-family: tahoma, microsoft yahei;
        font-weight: bold;
        margin-top: -6px;/* align with icon right side */
    }
    .journey_detail .icon_s {
        margin-left: -59px;
        position: absolute;
    }
    .journey_detail dl {
        padding-bottom: 15px;
    }
    .journey_detail dd .journey_pic {
        margin-left: -20px;
        font-size: 0;
        position: relative;
    }
    .journey_detail dd .journey_pic img {
        margin: 12px 0 0 20px;
        width: 210px;
        height: 118px;
        vertical-align: top;
    }
    .journey_list .journey_end {
        padding-bottom: 0;
    }
    .journey_list .journey_end .journey_detail {
        padding-top: 0;
    }

    .js_simple_no_show {
        /*display: none;*/
    }

</style>

<div class="detail_content">

<ul class="journey_list basefix" id="js_detail_travelCtrip">

    {if $product.itinerary.children}
        {assign var="zero" value=1}
        {assign var="itinerary_days" value=$product.itinerary.children}
        {foreach from=$itinerary_days item="one_day"}
            {assign var="day_sequence" value=$zero++}
            <li>
                <div class="journey_title">
                    <h5 class="time_list">
                        {__('the_x_day')|replace:'x':$day_sequence}
                    </h5>
                    <i class="icon_b icon_b_day">D1</i>
                    <h4>
                        {$one_day.title}
                    </h4>
                </div>
                {foreach from=$one_day.children item="activity"}
                    <div class="journey_detail">
                        <h5 class="time_list">
                            <p>{$activity.time_to_do}</p>
                            <p></p>
                        </h5>
                        <i class="{'icon_s icon_s_'|cat:$activity.activity_type}"></i>
                        <dl>
                            <dt>
                                {$activity.simple_desc nofilter}
                            </dt>
                            <dd class="js_simple_no_show">
                                {$activity.detail_desc nofilter}
                            </dd>
                        </dl>
                    </div>
                {/foreach}
            </li>
        {/foreach}
        <li class="journey_end">
            <div class="journey_detail border_none">
                <i class="icon_s icon_s_end"></i>
                <p>以上行程时间安排可能会因天气、路况等原因做相应调整，敬请谅解。</p>
            </div>
        </li>
    {/if}

</ul>

</div>