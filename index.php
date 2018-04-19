<?php
	// エラー出力無し
	ini_set('display_errors', 0);
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>UserChase</title>
        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <link rel="stylesheet" type="text/css" href="css/top.css">
        <link rel="stylesheet" type="text/css" href="css/map.css">       
    </head>
    
    <header>
        <img src="img/log.png" alt="Geotter">
        <!-- address入力 -->
            <div class = "input_address_area">
                <form action="php/Controller.php" method="post">
                    <input type="hidden" name = "searchConditions[0]" value = "address">
                    <input type="text" name = "searchConditions[1]">
                    <button type="submit">Serach</button>
                </form>
            </div>
    </header>
    
    <body>
        <div class="wrapper">
            <?php
				session_start();  
                if(is_null($_SESSION['lat']) || is_null($_SESSION['long'])){
					echo 'SESSION無し';
                    $_SESSION['lat'] = '35.5493';
                    $_SESSION['long'] = '139.7798';
					$_SESSION['search'] = 'address';
                }
			?>
            <script>
                window.sessionStorage.setItem(['mlat'],['<?php echo $_SESSION['lat']?>']);
                window.sessionStorage.setItem(['mlong'],['<?php echo $_SESSION['long']?>']);
            </script>
                        
            <!-- GoogleMap -->
            <div id="map"></div>
            
            <!-- Twittter -->
            <div class="time_line">
            <?php
				require "php/GetTweet.php";
				$getTweet = new GetTweet();
				$markerList = array();
				
				
				if (strpos($_SESSION['search'],'address')!== false){
					// 緯度経度指定指定Tweet
					$tweets = $getTweet->geocodeDesignation($_SESSION['lat'], $_SESSION['long']);
					$tweets = $tweets->statuses;
				}
				else if(strpos($_SESSION['search'],'screenname')!== false){
					// screenname指定Tweet
					$tweets = $getTweet->screenNameDesignation($_SESSION['screenname']);
				}
				if (isset($tweets) && empty($tweets->errors)) {
    				$count = 0; 
    				foreach ($tweets as $val) {
                        echo '<div>';
						try{
							if(!is_null($val->geo->coordinates[0]) && !is_null($val->geo->coordinates[1])){
								
								// ユーザー情報
								echo '<p>'. $val->user->name.'</p>';
								echo '<img src="'.$val->user->profile_image_url.'"/>';
								echo '<p>'.date('Y-m-d H:i:s', strtotime($val->created_at)).'</p>';
								echo '<p>'.$val->text.'</p>';
								
								// 座標情報
								echo '<p>'.$val->geo->coordinates[0].'</p>';
								echo '<p>'.$val->geo->coordinates[1].'</p>';
								
								// ユーザー中心の座標(ユーザー検索)
								if($count==0 &&(strpos($_SESSION['search'],'screenname')!== false)) {
									$_SESSION['lat'] = $val->geo->coordinates[0];
                    				$_SESSION['long'] = $val->geo->coordinates[1];
									?>
									<script>
                						window.sessionStorage.setItem(['mlat'],['<?php echo $_SESSION['lat']?>']);
                						window.sessionStorage.setItem(['mlong'],['<?php echo $_SESSION['long']?>']);
            						</script>
                                    <?php
								}
                                
								// Marker表示情報
								$markerList += array($count=>array("screenName"=>$val->user->screen_name,"day"=>date('Y-m-d H:i:s', strtotime($val->created_at)),"icon"=>$val->user->profile_image_url,"lat"=>$val->geo->coordinates[0],"lng"=>$val->geo->coordinates[1]));	
								++$count;	
							}
						}
						catch(Exception $e){
							echo '<p>つぶやきが取得できませんでした</p>';
						}
                        echo '</div>';
    				}
				} else {
    				echo 'つぶやきはありません。';
				}
				
				// replace + encode
				$markerJson = preg_replace("{\\\/}", "/",json_encode($markerList));				
			?>
            </div>
            <script type="text/javascript">
				// Marker情報をmap.jsへ
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
        </div>
    </body>
</html>