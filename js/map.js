var map;
var tweetList;
var marker = [];

function initMap() {
    // Map座標
    var mapPosition = {lat: 35.5554, lng: 139.7544};
    // Map描画要素
    var mapArea = document.getElementById('map');
    var mapOptions = {
        // 中央位置
        center: mapPosition,
        // ズーム値
        zoom: 14
    };
    // Map生成
    map = new google.maps.Map(mapArea, mapOptions);
	initMarker(tweetList);
}

// Marker生成
function initMarker(tweetList){
    var markerCount = 0
    
    // マップ中心Marker
    var markerPosition = {lat: 35.5554, lng: 139.7544};
    marker[markerCount] = new google.maps.Marker({
        position: markerPosition,
        map: map
    });
    
    // Tweet分Marker
    for (var item in tweetList) {
        var markerPosition = {lat: tweetList[item]['lat'], lng: tweetList[item]['lng']};
		marker[markerCount] = new google.maps.Marker({
    		position: markerPosition,
    		map: map
		});
        markerCount = ++markerCount;
    }
}