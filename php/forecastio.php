<?php

	class ForecastIO
	{
		private $apikey;
		private $apiurl;
		private $fordat;
		private $status;
		private $concnt;
		private $units;
		private $numreq;
		private $language;
		private $validunits = array('auto', 'us', 'si', 'ca', 'uk');
		private $directions = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE",
               	   					"S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW");

		/**
		* Create a new instance
		*
		* @param Boolean $loadfromdb
		* @param String $apikey
		* @param String $units
		* @param String $language
		*/
		function __construct($loadfromdb, $apikey = "", $apiurl = "", $units = "ca", $language = "en")
		{
			if($loadfromdb)
			{
				try {
					$apinfo = mysql_fetch_assoc(mysql_query("SELECT APIKEY, URL FROM weatherapikeys WHERE SERVICE='forecast';"));
					$apikey = $apinfo['APIKEY'];
					$apiurl = $apinfo['URL'];
				}
				catch(Exception $e) {}

			}
			$this->apikey = $apikey;
			$this->apiurl = $apiurl;
			$this->units = $units;
			$this->language = $language;
			$this->numreq = 0;
		}

		/**
		* @return string
		*/
		public function getUnits()
		{
			return $this->units;
		}

		/**
		* @param string $units
		*/
		public function setUnits($units)
		{
			$this->units = $units;
		}

		/**
		* @return string
		*/
		public function getLanguage()
		{
			return $this->language;
		}

		/**
		* @param string $language
		*/
		public function setLanguage($language)
		{
			$this->language = $language;
		}

		public function getNumReqs()
		{
			return $this->numreq;
		}

		public function setNumReqs($n = 0)
		{
			$this->numreq = $n;
		}

		public function loadForecastData($latitude, $longitude, $timestamp = false, $exclusions = "minutely")
		{
			try {
				if (!in_array($this->units, $this->validunits))
				{
					throw new Exception("Invalid units given: " . $this->units);
				}

				$url = $this->getApiUrl();
				$key = $this->getApiKey();
				if(!in_array($this->units, $this->validunits))
				{
					throw new Exception("Invalid unit parameter");
				}

				if($url == "" || $key == "")
				{
					throw new Exception("Invalid parameters set for Forecast object - please check value of Key and Url");
				}

				//setup the standard url
				$requrl  = $this->apiurl . $this->apikey . "/" . $latitude . "," . $longitude;
				//append the timestamp if available
				$requrl .= $timestamp ? "," . $timestamp : "";
				//set the units and the language
				$requrl .= "?units=" . $this->units . "&lang=" . $this->language;
				//append the exclusions if applicable
				$requrl .= $exclusions ? "&exclude=" . $exclusions : "";

				$content = file_get_contents($requrl);
				$rsltcon  = json_decode($content, true);

				$reqstat = explode(" ", $http_response_header[0]);
				$numreqs = explode(" ", $http_response_header[8]);

				if(!isset($reqstat[1]) || $reqstat[1] != "200")
				{
					throw new Exception("Bad result from geocode server - please check parameters and try again");
				}

				$this->setNumReqs($numreqs[1]);
				$this->fordat = $rsltcon;
				$this->status = $reqstat[1];
			}
			catch(Exception $e){
				$this->status = "ERROR: " . $e->getMessage();
				$this->concnt = -1;
				$this->fordat = array("ERROR" => $e->getMessage());
			}
		}

		/**
		* Wrapper function to return specified portion of forecast array object
		*
		* @return \getForecastData|array
		*/
		private function getForecastData($k)
		{
			if(!isset($this->fordat['ERROR'])) {
				return $this->fordat[$k];
			}
			else {
				return $this->fordat;
			}
		}

		/**
		* Will return the current conditions
		*
		* @return \getCurrentForecast|array
		*/
		public function getCurrentForecast()
		{
			return $this->getForecastData("currently");
		}

		/**
		* Will return conditions on hourly basis for today
		*
		* @return \getHourlyForecast|array
		*/
		public function getHourlyForecast()
		{
				return $this->getForecastData("hourly");
		}

		/**
		* Will return daily conditions for next seven days
		*
		* @return \getDailyForecast|array
		*/
		public function getDailyForecast()
		{
			return $this->getForecastData("daily");
		}

		/**
		* Will return a hash object of the location timezone as text
		* and the offset in hours from GMT
		*
		* @return \getTimezone|array
		*/
		public function getTimezone()
		{
			$txt = $this->getForecastData("timezone");
			$off = $this->getForecastData("offset");
			return array("timezone" => $txt, "offset" => $off);
		}

		public function getStatus()
		{
			return $this->status;
		}

		private function getApiKey()
		{
			return $this->apikey;
		}

		private function getApiUrl()
		{
			return $this->apiurl;
		}

	}

?>