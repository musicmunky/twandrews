<?php 

	$topad = "";

	$req1 = new HttpRequest("http://ads.sonobi.com/ttj?id=1667503&z=" . mt_rand(1000000, 9999999), HttpRequest::METH_GET);
	try {
		$req1->send();
		$topad = $req1->getResponseBody();
		$t = $req1->getResponseData();
		$rc = $req1->getResponseCode();

		$h = $t['headers'];
		$b = $t['body'];

		echo "c is: " . $rc;

		foreach($h as $key => $val)
		{
			echo "HEADER KEY IS: " . $key . "<br />HEADER VALUE IS: " . $val . "<br />";
		}

		foreach($b as $key => $val)
		{
			echo "BODY KEY IS: " . $key . "<br />BODY VALUE IS: " . $val . "<br />";
		}

		echo "<br />DATA IS: " . $t . "<br />";
		if ($rc != 400 && $rc != 500 && $rc != 404) {
			$topad = $req1->getResponseBody();
			echo "bar";
		}
	} catch (HttpException $ex) {
		$topad = $ex;
		echo "stuff";
	}
	echo $topad;

	//phpinfo();
?>