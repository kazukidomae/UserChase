<?php
  class GeoCode{
        function getAddress($address){
            $res = array();
            $req = 'http://maps.google.com/maps/api/geocode/xml';
            $req .= '?address='.urlencode($address);
            $req .= '&sensor=false';
            $xml = simplexml_load_file($req) or die('XML parsing error');
            if ($xml->status == 'OK') {
                $location = $xml->result->geometry->location;
				
                $res['lat'] = (string)$location->lat[0];
                $res['long'] = (string)$location->lng[0];
            }
            
            return $res;
		}
	}
?>