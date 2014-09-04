<style type="text/css">

    #baidu_map_container{
        height:600px;
        width: 850px;
        margin-left: 50px;
    }

</style>

<div id="content_trip_build" class="hidden">
    {include file="common/subheader.tpl" title="Supplementary Info" target="#trip_build_products_hook"}
    <div id="trip_build_products_hook" class="in collapse">
        <!--product address-->
        <div class="control-group">
            <label for="product_description_address" class="control-label">Address:</label>
            <div class="controls">
                <input id="product_description_address" name="product_data[address]" form="form" type="text" class="input-large" value="{$product_data.trip.address}"/>
            </div>
        </div>
        <!--other properties-->

        <!--product characteristics-->
        <div class="control-group">
            <label for="elm_product_characteristics" class="control-label">Characteristics:</label>
            <div class="controls">
                <textarea id="elm_product_characteristics" name="product_data[characteristics]" cols="55" rows="2" class="cm-wysiwyg input-large">{$product_data.trip.characteristics}</textarea>
            </div>
        </div>
        <!--product attentions-->
        <div class="control-group">
            <label for="elm_product_attentions" class="control-label">Notes:</label>
            <div class="controls">
                <textarea id="elm_product_attentions" name="product_data[notes]" cols="55" rows="2" class="cm-wysiwyg input-large">{$product_data.trip.notes}</textarea>
            </div>
        </div>
        <!--product must known-->
        <div class="control-group">
            <label for="elm_product_must_known" class="control-label">Must Known:</label>
            <div class="controls">
                <textarea id="elm_product_must_known" name="product_data[must_known]" cols="55" rows="2" class="cm-wysiwyg input-large">{$product_data.trip.must_known}</textarea>
            </div>
        </div>
    </div>
    <!--end of supplementary info-->

    <!-- map add form-->
    {include file="common/subheader.tpl" title="Map Info" target="#trip_build_map_hook"}
    <div id="trip_build_map_hook" class="in collapse">

        <!--open map and add marker-->
        <div class="control-group">
            <label for="product_map_lonlat" class="control-label">Geographic Position:</label>
            <div class="controls">
                <input id="product_map_lonlat" name="product_data[location]" form="form" type="text" class="input-medium" value="{$product_data.trip.location}"/>
                <input type="button" id="add_marker" class="btn btn-primary" value="Add Marker"/>
            </div>
        </div>
        <!--search by name-->
        <div class="control-group {$no_hide_input_if_shared_product}">
            <label for="product_address_map" class="control-label">Search By Name:</label>
            <div class="controls">
                <input id="location_search_key" form="form" type="text" class="input-medium" value=""/>
                <input type="button" id="search_name_btn" class="btn btn-primary" value="Search"/>
                {*<img id="search_status" src="/images/map/loading.gif" style="display: none">*}
                <span id="search_status" style="display: none; margin-left: 20px;">loading...</span>
            </div>
        </div>
        <!--search by location-->
        <div class="control-group {$no_hide_input_if_shared_product}">
            <label for="location_search_location" class="control-label">Search By Location:</label>
            <div class="controls">
                <input id="location_search_location" form="form" type="text" class="input-medium" placeholder="longitude,latitude"/>
                <input type="button" id="search_location_btn" class="btn btn-primary" value="Search"/>
            </div>
        </div>
        <!--map container-->
        <div class="control-group">
            <div id="baidu_map_container"></div>
        </div>
    </div>

    <!--more ...-->
</div><!--end of content_trip_build-->
<script>

    (function(_, $) {

        var map, mkrTool;
        var client_location = '{$client_location}';
        var default_center = '116.404, 39.915';//以景山公园为中心点
        if(client_location.length==0) {
            client_location = default_center;
        }
        var lon = Number(client_location.split(',')[0]);
        var lat = Number(client_location.split(',')[1]);

        //create baidu map while switch to show this tab
        /*$.ceEvent('on', 'ce.tab.show', function(data) {

            //Use Baidu Map default in China, use Google map in future...
            map = new BMap.Map("baidu_map_container");          // 创建地图实例
            var point = new BMap.Point(lon, lat);  // 创建点坐标，以景山公园为中心点
            map.centerAndZoom(point, 14);                 // 初始化地图，设置中心点坐标和地图级别
            var opts = {
                type: BMAP_NAVIGATION_CONTROL_LARGE
            };
            map.addControl(new BMap.NavigationControl(opts));

            var icon = new BMap.Icon("/images/map/marker_flag.png", new BMap.Size(32, 32));
            mkrTool = new BMapLib.MarkerTool(map, {
                autoClose: true,
                followText: "点击地图添加标注",
                icon: icon
            });
            mkrTool.addEventListener("markend", function(e) {
                var lat_lng = e.marker.getPosition();
                $("#product_map_lonlat").val(lat_lng['lng']+','+lat_lng['lat']);//show location in form
            });

            //show already added marker
            if($("#product_map_lonlat").val()){
                var lon_lat = $("#product_map_lonlat").val();
                var m_lon = Number(lon_lat.split(',')[0]);
                var m_lat = Number(lon_lat.split(',')[1]);
                var m_pt = new BMap.Point(m_lon, m_lat);
                var marker = new BMap.Marker(m_pt,{
                    icon: icon
                });        // 创建标注
                map.addOverlay(marker);// 将标注添加到地图中
                mkrTool['marker'] = marker;//save to remove
            }

        });//end of ce.tab.show
        */


        $("#add_marker").click(function(){

            mkrTool.open();

        });//end of add marker click


        $("#search_name_btn").click(function (){
            var key_word = $("#location_search_key").val();
            if(!map || !key_word) return;

            var local = new BMap.LocalSearch(map, {
                renderOptions:{
                    map: map
                },
                onMarkersSet:function(pois){
                    for(var i in pois){
                        var marker = pois[i]['marker'];
                        var icon = new BMap.Icon("/images/map/marker_pod.png", new BMap.Size(32, 32));
                        marker.setIcon(icon);
                    }
                },
                onSearchComplete:function(results){
                    $("#search_status").hide();
                }
            });

            local.search(key_word);

            $("#search_status").show();

        });

        $("#search_location_btn").click(function(){
            var location = $("#location_search_location").val();

            if(location.indexOf(',')==-1) return;
            var lon_lat = location.split(',');
            if(!Number(lon_lat[0]) || !Number(lon_lat[1])) return;

            var point = new BMap.Point(Number(lon_lat[0]), Number(lon_lat[1]));  // 创建点坐标，以景山公园为中心点
            map.centerAndZoom(point, 14);                 // 初始化地图，设置中心点坐标和地图级别

        });


    }(Tygh, Tygh.$));

</script>
