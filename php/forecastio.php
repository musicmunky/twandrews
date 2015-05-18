<?php

	class ForecastIO
	{
		private $apikey;
		private $apiurl;
		private $fordat;
		private $status;
		private $concnt;
		private $units;
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
		function __construct($loadfromdb, $apikey = "", $apiurl = "", $units = 'ca', $language = 'en')
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

				$this->concnt = $numreqs[1];
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
				return $this->getForecastData('hourly');
		}

		/**
		* Will return daily conditions for next seven days
		*
		* @return \getDailyForecast|array
		*/
		public function getDailyForecast()
		{
			return $this->getForecastData('daily');
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


	/**
	* Wrapper for get data by getters
	*/
	class ForecastIOConditions
	{
		private $raw_data;
		function __construct($raw_data)
		{
			$this->raw_data = $raw_data;
		}

		/**
		* Will return the temperature
		*
		* @return String
		*/
		function getTemperature()
		{
			return $this->raw_data->temperature;
		}

		/**
		* get the min temperature
		*
		* only available for week forecast
		*
		* @return type
		*/
		function getMinTemperature()
		{
			return $this->raw_data->temperatureMin;
		}

		/**
		* get max temperature
		*
		* only available for week forecast
		*
		* @return type
		*/
		function getMaxTemperature()
		{
			return $this->raw_data->temperatureMax;
		}

		/**
		* get apparent temperature (heat index/wind chill)
		*
		* only available for current conditions
		*
		* @return type
		*/
		function getApparentTemperature()
		{
			return $this->raw_data->apparentTemperature;
		}

		/**
		* Get the summary of the conditions
		*
		* @return String
		*/
		function getSummary()
		{
			return $this->raw_data->summary;
		}

		/**
		* Get the icon of the conditions
		*
		* @return String
		*/
		function getIcon()
		{
			return $this->raw_data->icon;
		}

		/**
		* Get the time, when $format not set timestamp else formatted time
		*
		* @param String $format
		* @return String
		*/
		function getTime($format = null)
		{
			if (!isset($format)) {
				return $this->raw_data->time;
			} else {
				return date($format, $this->raw_data->time);
			}
		}

		/**
		* Get the pressure
		*
		* @return String
		*/
		function getPressure()
		{
			return $this->raw_data->pressure;
		}

		/**
		* Get the dew point
		*
		* Available in the current conditions
		*
		* @return String
		*/
		function getDewPoint()
		{
			return $this->raw_data->dewPoint;
		}

		/**
		* get humidity
		*
		* @return String
		*/
		function getHumidity()
		{
			return $this->raw_data->humidity;
		}

		/**
		* Get the wind speed
		*
		* @return String
		*/
		function getWindSpeed()
		{
			return $this->raw_data->windSpeed;
		}

		/**
		* Get wind direction
		*
		* @return type
		*/
		function getWindBearing()
		{
			return $this->raw_data->windBearing;
		}

		/**
		* get precipitation type
		*
		* @return type
		*/
		function getPrecipitationType()
		{
			return $this->raw_data->precipType;
		}

		/**
		* get the probability 0..1 of precipitation type
		*
		* @return type
		*/
		function getPrecipitationProbability()
		{
			return $this->raw_data->precipProbability;
		}

		/**
		* Get the cloud cover
		*
		* @return type
		*/
		function getCloudCover()
		{
			return $this->raw_data->cloudCover;
		}

		/**
		* get sunrise time
		*
		* only available for week forecast
		*
		* @param String $format String to format date pph date
		*
		* @return type
		*/
		function getSunrise($format = null)
		{
			if (!isset($format)) {
				return $this->raw_data->sunriseTime;
			} else {
				return date($format, $this->raw_data->sunriseTime);
			}
		}

		/**
		* get sunset time
		*
		* only available for week forecast
		*
		* @param String $format String to format date pph date
		*
		* @return type
		*/
		function getSunset($format = null)
		{
			if (!isset($format)) {
				return $this->raw_data->sunsetTime;
			} else {
				return date($format, $this->raw_data->sunsetTime);
			}
		}
	}

?>