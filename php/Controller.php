<?php
	$Conditions = null;
	
	// アドレス検索
	$Conditions = $_POST['searchConditions'];
	session_start();
	if(strpos($Conditions[0],'address') !== false){
		require "GeoCode.php";
		$code = new GeoCode();
		$result = $code->getaddress($Conditions[1]);
		$_SESSION['lat'] = $result['lat'];
		$_SESSION['long'] = $result['long'];
		$_SESSION['search'] = 'address';
		
	}
	
	// ユーザー検索
	else if(strpos($Conditions[0],'screenName') !== false){
		$_SESSION['screenname'] = $Conditions[1];
		$_SESSION['search'] = 'screenname';
	}
	
	header('Location: http://localhost/UserChase');
	exit();
?>