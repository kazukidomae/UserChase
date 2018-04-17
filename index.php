<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>UserChase</title>
        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <link rel="stylesheet" type="text/css" href="css/top.css">        
    </head>
    <body>
        <div class="wrapper">
            <h1>UserChase</h1>
            
            <!-- twitter -->
            <form action="" method="post">
           		<input type="text" name = "twitterId">
                <input type="text" name = "twitterTag">
                <select>
                	<option>空港を選択</option>
                	<option>羽田</option>
                    <option>成田</option>
                </select>
                <input type="submit" value="検索">
            </form>
            <!-- GoogleMap -->
            <div id="map"></div>
            
            <!-- Twittter -->
            <?php
				require "php/GetTweet.php";
				$getTweet = new GetTweet();
				// 緯度経度指定指定Tweet
				$tweets = $getTweet->geocodeDesignation();
				
				if (isset($tweets) && empty($tweets->errors)) {
    				$tweets = $tweets->statuses;
					$markerList = array(); 
					
    				echo '<dl>';
    				foreach ($tweets as $val) {
        				echo '<dt>'.date('Y-m-d H:i:s', strtotime($val->created_at)).' ['. $val->user->name.']'.'</dt>';
						echo '<dd><img src="'.$val->user->profile_image_url.'"/></dd>';
        				echo '<dd>'.$val->text.'</dd>';
						
						if(!is_null($val->geo->coordinates[0]) || !is_null($val->geo->coordinates[1])){
							// 座標情報
							echo '<dd>'.$val->geo->coordinates[0].'</dd>';
							echo '<dd>'.$val->geo->coordinates[1].'</dd>';
                            
                            // Marker表示情報
							$markerList += array($val->user->name=>array('icin'=>$val->user->profile_image_url,'lat'=>$val->geo->coordinates[0],'lng'=>$val->geo->coordinates[1]));
						}
    				}
    			echo '</dl>';
				} 
				else {
    				echo 'つぶやきはありません。';
				}
				$markerJson = json_encode($markerList);
			?>
            <script type="text/javascript">
				var tweetList = JSON.parse('<?php echo $markerJson; ?>');
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
        </div>
    </body>
</html>