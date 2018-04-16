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
            <script type="text/javascript" src="js/map.js"></script>
            <script src=
                    <?php 
                        require "key/Key.php";
                        $mapKey = new Key();
                        echo '"https://maps.googleapis.com/maps/api/js?key='.$mapKey->getGoogleMapApiKey().'&callback=initMap"';
                    ?> 
                    async>
            </script>
            
            <!-- Twittter -->
            <?php
				require "php/twitteroauth.php";
                $twitterKey = new Key();
				$twObj = new TwitterOAuth($twitterKey->getTwitterApiKey(),
                                          $twitterKey->getTwitterApiSecret(),
                                          $twitterKey->getTwitterAccessToken(),
                                          $twitterKey->getTwitterAccessTokenSecret());
				$req = $twObj->OAuthRequest('https://api.twitter.com/1.1/search/tweets.json','GET',
    				array(
        				'lang' => 'ja',
        				'q' => '%23成田空港'
    				));
				$tweets = json_decode($req);
 
				if (isset($tweets) && empty($tweets->errors)) {
    				$tweets = $tweets->statuses;
    				echo '<dl>';
    				foreach ($tweets as $val) {
        				echo '<dt>'.date('Y-m-d H:i:s', strtotime($val->created_at)).' ['. $val->user->name.']'.'</dt>';
						echo '<dd><img src="'.$val->user->profile_image_url.'"/></dd>';
						//echo '<dd>'.$val->geo.'</dd>';
        				echo '<dd>'.$val->text.'</dd>';
    				}
    			echo '</dl>';
				} else {
    				echo 'つぶやきはありません。';
				}			
			?>
        </div>
    </body>
</html>