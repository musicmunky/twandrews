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

		/**
		* Create a new instance of the Geocode object
		*
		* @param MySQLi $mysqli
		*/
		function __construct($mysqli)
		{
			try {
				$apiqry = $mysqli->prepare("SELECT APIKEY, URL FROM weatherapikeys WHERE SERVICE='google';");
				$apiqry->execute();

				if($apiqry->errno != 0)
				{
					throw new Exception("Error attempting to API info:<br>" . $apiqry->error . "<br>Error code: " . $apiqry->errno);
				}
				else
				{
					$result = $apiqry->get_result();
					$apinfo	= $result->fetch_assoc();
					$this->setApiKey($apinfo['APIKEY']);
					$this->setApiUrl($apinfo['URL']);
				}
				$apiqry->close();
			}
			catch(Exception $e) {
				return "ERROR: " . $e->getMessage();
			}
		}


		/**
		* Private function to return the API key
		*/
		private function getApiKey()
		{
			return $this->apikey;
		}


		/**
		* Private function to set the API key for the Geocode object
		*
		* @param API Key $key
		*/
		private function setApiKey($key)
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


		/**
		* Private function to return the API url
		*/
		private function getApiUrl()
		{
			return $this->apiurl;
		}


		/**
		* Private function to set the API url for the Geocode object
		*
		* @param API Key $url
		*/
		private function setApiUrl($url)
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


		/**
		* Function to return the latitude information from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getLatitude($type = "string")
		{
			return $this->getLatLongInformation("lat", $type);
		}


		/**
		* Function to return the longitude information from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getLongitude($type = "string")
		{
			return $this->getLatLongInformation("lng", $type);
		}


		/**
		* Function to return the latitude and longitude information from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getLatLong($type = "1DArray")
		{
			return $this->getLatLongInformation("latlng", $type);
		}


		/**
		* Function to return the formatted address from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getFormattedAddress($type = "string")
		{
			return $this->getPlaceInformation("formatted_address", $type);
		}



		/**
		* Function to return the PlaceID from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getPlaceID($type = "string")
		{
			return $this->getPlaceInformation("place_id", $type);
		}


		/**
		* Function to return the Suburb information from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getSuburb($type = "string")
		{
			return $this->getAddressComponents("suburb", $type);
		}


		/**
		* Function to return the City information from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getCity($type = "string")
		{
			return $this->getAddressComponents("city", $type);
		}


		/**
		* Function to return the State information from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getState($type = "string")
		{
			return $this->getAddressComponents("state", $type);
		}


		/**
		* Function to return the Country information from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getCountry($type = "string")
		{
			return $this->getAddressComponents("country", $type);
		}


		/**
		* Function to return the Zipcode information from the Geocode object
		*
		* @param optional Type String $type
		*/
		public function getZipCode($type = "string")
		{
			return $this->getAddressComponents("zipcode", $type);
		}


		/**
		* Function to return the number of results returned by the Geocode object
		*/
		public function getResultCount()
		{
			return $this->rescnt;
		}


		/**
		* Function to get the status the Geocode object
		*/
		public function getStatus()
		{
			return $this->status;
		}


		/**
		* Function to return all of the information included in the Geocode object
		*/
		public function getGeoData()
		{
			return $this->geodat;
		}


		/**
		* Function to return the latitude information from the Geocode object
		*
		* @param Search String $srch
		*/
		public function loadGeoData($srch)
		{
			try
			{
				//make sure we have the api url and key...
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

				//format the url for the request
				$search   = urlencode($srch);
				$requrl   = $url . "address=" . $search . "&key=" . $key;

				//send the request and format the results
				$content  = file_get_contents($requrl);
				$rescon   = json_decode($content, true);

				//make sure request completed successfully
				if(!isset($rescon['status']) || $rescon['status'] != "OK")
				{
					throw new Exception("Bad result from geocode server - please check parameters and try again");
				}

				$this->rescnt = count($rescon['results']);
				$this->geodat = $rescon['results'];
				$this->status = $rescon['status'];
			}
			catch(Exception $e)
			{
				$this->status = "ERROR: " . $e->getMessage();
				$this->rescnt = -1;
				$this->geodat = array("ERROR" => $e->getMessage());
			}
		}


		/**
		* Private function to handle requests for the latitude and longitude information from the Geocode object
		*
		* @param Request Type $k
		* @param Requested Return Type $type
		*/
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


		/**
		* Private function to handle requests for the Place information from the Geocode object
		*
		* @param Request Type $k
		* @param Requested Return Type $type
		*/
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


		/**
		* Private function to handle requests for the Address Components from the Geocode object
		*
		* @param Request Type $k
		* @param Requested Return Type $type
		*/
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