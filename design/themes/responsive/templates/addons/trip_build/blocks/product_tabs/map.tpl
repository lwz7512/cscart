{** block-description:trip_build_map **}
<style type="text/css">

    #baidu_map_container{
        height:400px;
        width: 600px;
        margin-top: 10px;
        margin-bottom: 20px;
    }

</style>

<div id="baidu_map_container"></div>

<div id="address">
    <span>{$product.trip.address}</span>
</div>
<script>

    (function(_, $) {

        var client_location = '{$product.trip.location}';

        if(!client_location) return;//no location not work

        $.ceEvent('on', 'ce.tab.show', function(data) {

            var lon = Number(client_location.split(',')[0]);
            var lat = Number(client_location.split(',')[1]);

            var map = new BMap.Map("baidu_map_container");          // 创建地图实例
            var point = new BMap.Point(lon, lat); // 创建点坐标，以景山公园为中心点
            map.centerAndZoom(point, 14); // 初始化地图，设置中心点坐标和地图级别
            var opts = {
                type: BMAP_NAVIGATION_CONTROL_LARGE
            };
            map.addControl(new BMap.NavigationControl(opts));

            var m_pt = new BMap.Point(lon, lat);
            var marker = new BMap.Marker(m_pt);
            map.addOverlay(marker);// 将标注添加到地图中

        });


    }(Tygh, Tygh.$));

</script>