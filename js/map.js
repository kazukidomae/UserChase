var map;

function initMap() {
    // Map座標
    var mapPosition = {lat: 35.5554, lng: 139.7544};
    // Map描画要素
    var mapArea = document.getElementById('map');
    var mapOptions = {
        // 中央位置
        center: mapPosition,
        // ズーム値
        zoom: 16
    };
	
    // Map生成
    map = new google.maps.Map(mapArea, mapOptions);
	initMarker(mapPosition);
}

// Marker生成
function initMarker(markerPosition){
	var marker = new google.maps.Marker({
    position: markerPosition,
    map: map
  });
}