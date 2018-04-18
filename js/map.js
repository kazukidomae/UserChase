var map;
var tweetList;
var marker = [];
var infoWindow = [];
var slat;
var slong;

function initMap() {
    
    slat = window.sessionStorage.getItem(['slat']);
    slong = window.sessionStorage.getItem(['slong']);
    
    console.log(slat);
    console.log(slong);
    
    
    // Map座標
    var mapPosition = {lat: parseFloat(slat), lng: parseFloat(slong)};
    // Map描画要素
    var mapArea = document.getElementById('map');
    var mapOptions = {
        // 中央位置
        center: mapPosition,
        // ズーム値
        zoom: 14,
		styles: [{
        featureType: 'all',
        elementType: 'all',
        stylers: [{
			// 色相
            hue: '#00ff00'
        }, {
			// 彩度
            saturation: -50
        }, {
			// 明度
            lightness: 0
        }, {
            gamma: 1
        }]
    }]
		
    };
    // Map生成
    map = new google.maps.Map(mapArea, mapOptions);
	initMarker(tweetList);
}

// Marker生成
function initMarker(tweetList){
    var markerCount = 0
    
    // マップ中心Marker
    var markerPosition = {lat: parseFloat(slat), lng: parseFloat(slong)};
    marker[markerCount] = new google.maps.Marker({
        position: markerPosition,
        map: map
    });
    
    // Tweet分Marker
    for (var item in tweetList) {
		
        var markerPosition = {lat: tweetList[item]['lat'], lng: tweetList[item]['lng']};
		marker[markerCount] = new google.maps.Marker({
    		position: markerPosition,
    		map: map,
			icon: {
				url: tweetList[item]['icon'],
				scaledSize: new google.maps.Size(45, 45)
			},
			optimized: false
		});
		
		// 吹き出し
		infoWindow[markerCount] = new google.maps.InfoWindow({
        	content: '<div class="maptweet"><img src="'+tweetList[item]['icon']+'"><p>'+tweetList[item]['name']+'</p><p>@'+item+'</p><p>'+tweetList[item]['day']+'</p></div>'
			
  		});
		markerEvent(markerCount);
        markerCount = ++markerCount;
    }

}

function markerEvent(markerCount){
	marker[markerCount].addListener('mouseover',function(){
		infoWindow[markerCount].open(map,marker[markerCount])
	});
	// mouseoutイベントを取得するListenerを追加
    marker[markerCount].addListener('mouseout', function(){
         infoWindow[markerCount].close();
    });
}
