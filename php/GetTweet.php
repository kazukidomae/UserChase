<?php
	class GetTweet{
		
		// Twitter認証
		private function twittertCertification(){
    		require "./php/twitteroauth.php";
			require "./key/Key.php";
			$twitterKey = new Key();
			// 認証
			return new TwitterOAuth($twitterKey->getTwitterApiKey(),$twitterKey->getTwitterApiSecret(),$twitterKey->getTwitterAccessToken(),$twitterKey->getTwitterAccessTokenSecret());
  		}
		
		// 緯度経度指定
		public function geocodeDesignation(){
			$twObj = $this->twittertCertification();
			// TimeLine取得
			$req = $twObj->OAuthRequest('https://api.twitter.com/1.1/search/tweets.json','GET',
				array(
					//'lang' => 'ja',
					//'q' => '%23羽田空港'
					// 羽田空港
					'geocode'=> '35.5493,139.7798,1km'
				));
			return json_decode($req);
		}
		
		// ScreenName指定
		public function screenNameDesignation(){
			$twObj = $this->twittertCertification();
			// TimeLine取得
			$req = $twObj->OAuthRequest('https://api.twitter.com/1.1/statuses/user_timeline.json','GET',
					array(
						'count'=>'10',
						'screen_name' => 'd_zack3',
					));
			return json_decode($req);
		}
		// 緯度経度逆検索
		public function reverseDesignation(){
			$twObj = $this->twittertCertification();
			// TimeLine取得
			$req = $twObj->OAuthRequest('https://api.twitter.com/1.1/geo/reverse_geocode.json','GET',
					array(
						'lat'=>'35.5493',
						'long' => '139.7798',
						'accuracy' => '5ft'
					));
			return json_decode($req);
		}
		
	}
	
?>