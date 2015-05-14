<?php

	class Geocode
	{
		private $apikey;
		private $apiurl;
		private $geodat;
		private $status;
		private $rescnt;
		private $lookup = array(
							"city" 		=> "locality",
							"state"		=> "administrative_area_level_1",
							"county"	=> "administrative_area_level_2",
							"country"	=> "country",
							"zipcode"	=> "postal_code"
						);

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
					$apinfo = mysql_fetch_assoc(mysql_query("SELECT APIKEY, URL FROM weatherapikeys WHERE SERVICE='google';"));
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
							$rval = $addcomps[$j]['short_name'];
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