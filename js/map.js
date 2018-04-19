var map;
var tweetList;
var marker = [];
var infoWindow = [];
var mlat;
var mlong;

function initMap() {
    
	// sessionからの緯度経度
    mlat = window.sessionStorage.getItem(['mlat']);
    mlong = window.sessionStorage.getItem(['mlong']);
    
    // Map座標
    var mapPosition = {lat: parseFloat(mlat), lng: parseFloat(mlong)};
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
    var markerPosition = {lat: parseFloat(mlat), lng: parseFloat(mlong)};
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
			
			// 吹き出し記載情報(アイコン、TwitterID、Tweet日時)
        	content: '<div class="maptweet"><img src="'+tweetList[item]['icon']+'"><p id ="screen_name">'+tweetList[item]['screenName']+'</p><p>'+tweetList[item]['day']+'</p></div>'
			
  		});
		markerEvent(markerCount);
        markerCount = ++markerCount;
    }
}


function markerEvent(markerCount){
	
	// マウスオーバー
	marker[markerCount].addListener('mouseover',function(){
		infoWindow[markerCount].open(map,marker[markerCount])
	});
	// マウスアウト
    marker[markerCount].addListener('mouseout', function(){
         infoWindow[markerCount].close();
    });
	
	// マウスクリック
	marker[markerCount].addListener('click', function(){
		
		// POSTデータを送信
		var form = document.createElement('form');
    	document.body.appendChild(form);
    	var input = document.createElement('input');
    	input.setAttribute('type','hidden');
		
    	input.setAttribute('name', 'searchConditions[0]');
		input.setAttribute('value' , 'screenName');
		form.appendChild(input);
		input = input.cloneNode(false);
		
		input.setAttribute('name', 'searchConditions[1]');
    	input.setAttribute('value' , document.getElementById('screen_name').innerHTML);
    	form.appendChild(input);
		
    	form.setAttribute('action', './php/Controller.php');
    	form.setAttribute('method' , 'post');
    	form.submit(); 
    });
}
