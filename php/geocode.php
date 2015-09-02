<?php

	class Geocode
	{
		private $apikey;
		private $apiurl;
		private $geodat;
		private $status;
		private $rescnt;
		private $lookup = array(
							"suburb"	=> "neighborhood",
							"localname" => "colloquial_area",
							"city" 		=> "locality",
							"state"		=> "administrative_area_level_1",
							"county"	=> "administrative_area_level_2",
							"district"	=> "administrative_area_level_3",
							"aal4"		=> "administrative_area_level_4",
							"aal5"		=> "administrative_area_level_5",
							"country"	=> "country",
							"zipcode"	=> "postal_code"
						);
/*
    street_address => indicates a precise street address.

    route => indicates a named route (such as "US 101").

	intersection => indicates a major intersection, usually of two major roads.

	political => indicates a political entity. Usually, this type indicates a polygon of some civil administration.

	country => indicates the national political entity, and is typically the highest order type returned by the Geocoder.

	administrative_area_level_1 => indicates a first-order civil entity below the country level. Within the United States, these administrative levels are states. Not all nations exhibit these administrative levels.

	administrative_area_level_2 => indicates a second-order civil entity below the country level. Within the United States, these administrative levels are counties. Not all nations exhibit these administrative levels.

	administrative_area_level_3 => indicates a third-order civil entity below the country level. This type indicates a minor civil division. Not all nations exhibit these administrative levels.

	administrative_area_level_4 => indicates a fourth-order civil entity below the country level. This type indicates a minor civil division. Not all nations exhibit these administrative levels.

	administrative_area_level_5 => indicates a fifth-order civil entity below the country level. This type indicates a minor civil division. Not all nations exhibit these administrative levels.

	colloquial_area => indicates a commonly-used alternative name for the entity.

	locality => indicates an incorporated city or town political entity.

	ward => indicates a specific type of Japanese locality, to facilitate distinction between multiple locality components within a Japanese address.

	sublocality => indicates a first-order civil entity below a locality. For some locations may receive one of the additional types: sublocality_level_1 to sublocality_level_5. Each sublocality level is a civil entity. Larger numbers indicate a smaller geographic area.

	neighborhood => indicates a named neighborhood

	premise => indicates a named location, usually a building or collection of buildings with a common name

	subpremise => indicates a first-order entity below a named location, usually a singular building within a collection of buildings with a common name

	postal_code => indicates a postal code as used to address postal mail within the country.

	natural_feature => indicates a prominent natural feature.

	airport => indicates an airport.

	park => indicates a named park.

	point_of_interest => indicates a named point of interest. Typically, these "POI"s are prominent local entities that don't easily fit in another category, such as "Empire State Building" or "Statue of Liberty."

*/

		/**
		* Create a new instance
		*
		* @param Boolean $loadfromdb
		* @param String $apikey
		* @param String $apiurl
		*/
		function __construct($loadfromdb, $apikey = "", $apiurl = "")
		{
			if($loadfromdb)
			{
				try {
					$apinfo = mysql_fetch_assoc(mysql_query("SELECT APIKEY, URL FROM apikeys WHERE SERVICE='google';"));
					$apikey = $apinfo['APIKEY'];
					$apiurl = $apinfo['URL'];
				}
				catch(Exception $e) {}

			}
			$this->apikey = $apikey;
			$this->apiurl = $apiurl;
		}

		public function getApiKey()
		{
			return $this->apikey;
		}

		public function setApiKey($key)
		{
			$r = true;
			try {
				$this->apikey = $key;
			}
			catch(Exception $e) {
				$r = false;
			}
			return $r;
		}

		public function getApiUrl()
		{
			return $this->apiurl;
		}

		public function setApiUrl($url)
		{
			$r = true;
			try {
				$this->apiurl = $url;
			}
			catch(Exception $e) {
				$r = false;
			}
			return $r;
		}

		public function getLatitude($type = "string")
		{
			return $this->getLatLongInformation("lat", $type);
		}

		public function getLongitude($type = "string")
		{
			return $this->getLatLongInformation("lng", $type);
		}

		public function getLatLong($type = "1DArray")
		{
			return $this->getLatLongInformation("latlng", $type);
		}

		public function getFormattedAddress($type = "string")
		{
			return $this->getPlaceInformation("formatted_address", $type);
		}

		public function getPlaceID($type = "string")
		{
			return $this->getPlaceInformation("place_id", $type);
		}

		public function getSuburb($type = "string")
		{
			return $this->getAddressComponents("suburb", $type);
		}

		public function getCity($type = "string")
		{
			return $this->getAddressComponents("city", $type);
		}

		public function getState($type = "string")
		{
			return $this->getAddressComponents("state", $type);
		}

		public function getCountry($type = "string")
		{
			return $this->getAddressComponents("country", $type);
		}

		public function getZipCode($type = "string")
		{
			return $this->getAddressComponents("zipcode", $type);
		}

		public function getResultCount()
		{
			return $this->rescnt;
		}

		public function getStatus()
		{
			return $this->status;
		}

		public function getGeoData()
		{
			return $this->geodat;
		}

		public function loadGeoData($srch)
		{
			try {
				$url = $this->getApiUrl();
				$key = $this->getApiKey();
				if($srch == "")
				{
					throw new Exception("Invalid search string");
				}

				if($url == "" || $key == "")
				{
					throw new Exception("Invalid parameters set for Geocode object - please check value of Key and Url");
				}

				$search   = urlencode($srch);
				$requrl   = $url . "address=" . $search . "&key=" . $key;
				$content  = file_get_contents($requrl);
				$rescon   = json_decode($content, true);

				if(!isset($rescon['status']) || $rescon['status'] != "OK")
				{
					throw new Exception("Bad result from geocode server - please check parameters and try again");
				}

				$this->rescnt = count($rescon['results']);
				$this->geodat = $rescon['results'];
				$this->status = $rescon['status'];
			}
			catch(Exception $e){
				$this->status = "ERROR: " . $e->getMessage();
				$this->rescnt = -1;
				$this->geodat = array("ERROR" => $e->getMessage());
			}
		}

		private function getLatLongInformation($k, $type)
		{
			$rval = array();
			$rslt = $this->getGeoData();
			$alen = count($rslt);
			try {
				if($k == "latlng"){
					if($type == "array" || $type == "a"){
						for($i = 0; $i < $alen; $i++)
						{
							array_push($rval, array($rslt[$i]['geometry']['location']['lat'], $rslt[$i]['geometry']['location']['lng']));
						}
					}
					else
					{
						array_push($rval, array($rslt[0]['geometry']['location']['lat'], $rslt[0]['geometry']['location']['lng']));
					}
				}
				else
				{
					if($type == "array" || $type == "a"){
						for($i = 0; $i < $alen; $i++)
						{
							array_push($rval, $rslt[$i]['geometry']['location'][$k]);
						}
					}
					else
					{
						$rval = $rslt[0]['geometry']['location'][$k];
					}
				}

			} catch(Exception $e) {}
			return $rval;
		}

		private function getPlaceInformation($k, $type)
		{
			$rval = array();
			$kval = $k;
			$rslt = $this->getGeoData();
			$alen = count($rslt);
			try {
				if($type == "array" || $type == "a"){
					for($i = 0; $i < $alen; $i++)
					{
						array_push($rval, $rslt[$i][$kval]);
					}
				}
				else
				{
					$rval = $rslt[0][$kval];
				}
			} catch(Exception $e) { $rval = $this->status; }
			return $rval;
		}

		private function getAddressComponents($k, $type)
		{
			$rval = array();
			$kval = $this->lookup[$k];
			$rslt = $this->getGeoData();
			$alen = count($rslt);
			try {
				if($type == "array" || $type == "a"){
					for($i = 0; $i < $alen; $i++)
					{
						$addcomps = $rslt[$i]['address_components'];
						$flag = 0;
						for($j = 0; $j < count($addcomps); $j++)
						{
							if($addcomps[$j]['types'][0] == $kval)
							{
								array_push($rval, array("long_name" => $addcomps[$j]['long_name'], "short_name" => $addcomps[$j]['short_name']));
								$flag = 1;
								break;
							}
						}
						if($flag == 0){
							array_push($rval, array("long_name" => "NO_DATA_FOR_KEY " . $kval, "short_name" => "NO_DATA"));
						}
					}
				}
				else
				{
					$addcomps = $rslt[0]['address_components'];
					$flag = 0;
					for($j = 0; $j < count($addcomps); $j++)
					{
						if($addcomps[$j]['types'][0] == $kval)
						{
							$rval = ($kval == "locality") ? $addcomps[$j]['long_name'] : $addcomps[$j]['short_name'];
							$flag = 1;
							break;
						}
					}
					if($flag == 0){
						$rval = "NO_DATA";
					}
				}
			} catch(Exception $e) { $rval = $this->status; }
			return $rval;
		}

	}

?>