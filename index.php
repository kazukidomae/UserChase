<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>UserChase</title>
        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <link rel="stylesheet" type="text/css" href="css/top.css">
        <link rel="stylesheet" type="text/css" href="css/map.css">       
    </head>
    
    <body>
        <div class="wrapper">
            <?php
            
                session_start();
                echo $_SESSION['lat']; 
            
                
                if(!isset($_SESSION['slat']) || !isset($_SESSION['slong'])){
                    $_SESSION['lat'] = '35.5493';
                    $_SESSION['long'] = '139.7798';
                }
                
            ?>
            <script>
                window.sessionStorage.setItem(['slat'],['<?php echo $_SESSION['lat']?>']);
                window.sessionStorage.setItem(['slong'],['<?php echo $_SESSION['long']?>']);
                console.log('通過');
                console.log(window.sessionStorage.getItem(['slat']));
                console.log(window.sessionStorage.getItem(['slong']));
                
            </script>
            
            <h1>UserChase</h1>
            
            <!-- twitter -->
            <form action="php/Controller.php" method="post">
           		<input type="text" name = "address">
                <input type="submit" value="検索">
            </form>
            <!-- GoogleMap -->
            <div id="map"></div>
            
            <!-- Twittter -->
            <?php
				require "php/GetTweet.php";
				$getTweet = new GetTweet();
				
				// 緯度経度指定指定Tweet
				$tweets = $getTweet->geocodeDesignation($_SESSION['lat'], $_SESSION['long']);
				if (isset($tweets) && empty($tweets->errors)) {
    				$tweets = $tweets->statuses;
					$markerList = array(); 
					
    				echo '<dl>';
    				foreach ($tweets as $val) {
						
						if(!is_null($val->geo->coordinates[0]) || !is_null($val->geo->coordinates[1])){
							echo '<dt>'.date('Y-m-d H:i:s', strtotime($val->created_at)).' ['. $val->user->name.']'.'</dt>';
							echo '<dd><img src="'.$val->user->profile_image_url.'"/></dd>';
							echo '<dd>'.$val->text.'</dd>';
							
							// 座標情報
							echo '<dd>'.$val->geo->coordinates[0].'</dd>';
							echo '<dd>'.$val->geo->coordinates[1].'</dd>';
							
                            // Marker表示情報
							$markerList += array($val->user->screen_name=>array("name"=>$val->user->name,"day"=>date('Y-m-d H:i:s', strtotime($val->created_at)),"icon"=>$val->user->profile_image_url,"lat"=>$val->geo->coordinates[0],"lng"=>$val->geo->coordinates[1]));	
						}
    				}
    				echo '</dl>';
				} 
				else {
    				echo 'つぶやきはありません。';
				}
				// replace + encode
				$markerJson = preg_replace("{\\\/}", "/",json_encode($markerList));
								
			?>
            <script type="text/javascript">
				var tweetList = JSON.parse('<?php echo $markerJson;?>');
			</script>
            <script type="text/javascript" src="js/map.js"></script>
            <script src=
                    <?php 
						// requireはGetTweetで宣言済み
                        $mapKey = new Key();
                        echo '"https://maps.googleapis.com/maps/api/js?key='.$mapKey->getGoogleMapApiKey().'&callback=initMap"';
                    ?> 
                    async>
            </script>
            
            <?php
				//require "php/GeoCode.php";
				//$code = new GeoCode();
                //print_r($code->getaddress('スカイツリー'));
			?>
            
        </div>
    </body>
</html>