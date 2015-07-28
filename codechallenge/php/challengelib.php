<?php

	$REQ = $_REQUEST;

	if(isset($REQ['libcheck']) && !empty($REQ['libcheck'])){
		define('LIBRARY_CHECK', true);
	}
	if(!defined('LIBRARY_CHECK')){
		die ('<div style="width:100%;height:100%;text-align:center;">
				<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:100px;">
					Sorry, this isn\'t a real page, so I have nothing to show you :-(
				</div>
				<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:30px;margin-bottom:30px;">Wait, here\'s a funny cat!</div>
				<div style="background-repeat:no-repeat;margin-left:auto;margin-right:auto;width:500px;height:280px;background:url(../images/cat.gif)">
				</div>
			</div>');
	}

	define('INCLUDE_CHECK',true);
	require_once 'connect.php';
	require 'geocode.php';
	require 'socrata.php';

	date_default_timezone_set('America/New_York');

	$webaddress = "http://twandrews.com/codechallenge";

	if(isset($REQ['method']) && !empty($REQ['method']))
	{
		$method = $REQ['method'];
		$method = urldecode($method);
		$method = $mysqli->real_escape_string($method);

		switch($method)
		{
			case 'getItemInfo':		getItemInfo($REQ, $mysqli);
				break;
			case 'saveItemInfo':	saveItemInfo($REQ, $mysqli);
				break;
			case 'removeItem':		removeItem($REQ, $mysqli);
				break;
			default: noFunction($REQ['method']);
				break;
		}
		mysqli_close($mysqli);
	}


	function noFunction($m)
	{
		$func = $m;
		$result = array(
				"status"	=> "failure",
				"message"	=> "User attempted to call function: " . $func . " which does not exist",
				"content"	=> "You seem to have encountered an error - Contact the web admin if this keeps happening!"
		);
		echo json_encode($result);
	}


	function removeItem($P, $m)
	{
		$P = escapeArray($P, $m);

		$status  = "";
		$message = "";
		$content = array();

		try {
			$m->select_db("andrewsdb");
			$item = $m->prepare("DELETE FROM projectpages WHERE ID = ?;");
			$item->bind_param("i", $P['itemid']);
			$item->execute();

			if($item->errno != 0)
			{
				$status = "failure";
				$message = "Error attempting to remove item:<br>" . $item->error . "<br>Error code: " . $item->errno;
			}
			else
			{
				$content['pageid']	 = $P['itemid'];
				$status = "success";
			}
			$item->close();
		}
		catch(Exception $e){
			$status  = "ERROR: " . $e->getMessage();
			$message = "ERROR: " . $e->getMessage();
		}

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function getItemInfo($P, $m)
	{
		$P = escapeArray($P, $m);

		$status  = "";
		$message = "";
		$content = array();

		try {
			$m->select_db("andrewsdb");
			$item = $m->prepare("SELECT * FROM projectpages WHERE ID = ? LIMIT 1;");
			$item->bind_param("i", $P['itemid']);
			$item->execute();

			if($item->errno != 0)
			{
				$status = "failure";
				$message = "Error attempting to retrieve item info:<br>" . $item->error . "<br>Error code: " . $item->errno;
			}
			else
			{
				$result = $item->get_result();
				$rfa	= $result->fetch_assoc();
				$content['pageid']	 = $rfa['ID'];
				$content['pagename'] = $rfa['PAGENAME'];
				$content['pagelink'] = $rfa['PAGELINK'];
				$content['pagetype'] = $rfa['PAGETYPE'];
				$content['pagedesc'] = $rfa['PAGEDESC'];
				$content['pagestat'] = $rfa['PAGESTAT'];
				$status = "success";
			}
			$item->close();
		}
		catch(Exception $e){
			$status  = "ERROR: " . $e->getMessage();
			$message = "ERROR: " . $e->getMessage();
		}

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function saveItemInfo($P, $m)
	{
		global $webaddress;

		$status = "";
		$message = "";
		$content = array();
		$n_or_e = "new";
		$prevtyp = $P['ptype'];
		$prevstt = $P['pstat'];

		try {
			$m->select_db("andrewsdb");

			$itmid = $P['itemid'];
			$check = $m->prepare("SELECT * FROM projectpages WHERE PAGENAME = ? AND ID != ? LIMIT 1;");
			$check->bind_param("si", $P['pname'], $itmid);
			$check->execute();
			$reslt = $check->get_result();
			$check->close();

			if($reslt->num_rows > 0)
			{
				$status  = "failure";
				$message = "<br>That item name is already being used - please use a different name";
			}
			else
			{
				$pstat = $P['ptype'] == "tool" ? "" : $P['pstat'];
				if($itmid == 0)
				{
					//new item
					$insert = $m->prepare("INSERT INTO projectpages(PAGENAME, PAGELINK, PAGETYPE, PAGESTAT, PAGEDESC)
											VALUES (?, ?, ?, ?, ?)");
					$insert->bind_param("sssss",
										$P['pname'],
										$P['plink'],
										$P['ptype'],
										$pstat,
										$P['pdesc']);
					$insert->execute();
					if($insert->errno != 0)
					{
						$status = "failure";
						$message = "Error attempting to add item:<br>" . $insert->error . "<br>Error code: " . $insert->errno;
					}
					else
					{
						$itmid   = $insert->insert_id;
						$status  = "success";
						$message = "Item added successfully!";
					}
					$insert->close();
				}
				else
				{
					//update existing item
					$n_or_e = "existing";

					$oldtype = $m->prepare("SELECT PAGETYPE, PAGESTAT FROM projectpages WHERE ID = ? LIMIT 1;");
					$oldtype->bind_param("i", $itmid);
					$oldtype->execute();
					$result = $oldtype->get_result();
					$rfa	= $result->fetch_assoc();
					if($rfa['PAGETYPE'] != $prevtyp)
					{
						$prevtyp = $rfa['PAGETYPE'];
					}
					if($rfa['PAGESTAT'] != $pstat)
					{
						$prevstt = $rfa['PAGESTAT'];
					}
					$oldtype->close();

					$update = $m->prepare("UPDATE projectpages SET PAGENAME = ?, PAGELINK = ?, PAGETYPE = ?, PAGESTAT = ?, PAGEDESC = ?
											WHERE ID = ?");
					$update->bind_param("sssssi",
										$P['pname'],
										$P['plink'],
										$P['ptype'],
										$pstat,
										$P['pdesc'],
										$itmid);
					$update->execute();

					if($update->errno != 0)
					{
						$status  = "failure";
						$message = "Error attempting to update item:<br>" . $update->error . "<br>Error code: " . $update->errno;
					}
					else
					{
						$status  = "success";
						$message = "Item updated successfully!";
					}
					$update->close();
				}
				$content['pageid'] = $itmid;
				$content['pname']  = $P['pname'];
				$content['plink']  = $P['plink'];
				$content['ptype']  = $P['ptype'];
				$content['pstat']  = $P['pstat'];
				$content['pdesc']  = $P['pdesc'];
				$content['prvtp']  = $prevtyp;
				$content['prvst']  = $prevstt;
				$content['n_or_e'] = $n_or_e;
			}
		}
		catch(Exception $e)
		{
			$status  = "ERROR: " . $e->getMessage();
			$message = "ERROR: " . $e->getMessage();
		}

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function escapeArray($req, $mysqli)
	{
		//recursive function called on the REQ object sent back by an AJAX call
		//it accounts for nested arrays/hashes (these were being nulled out previously)
		foreach($req as $key => $val)
		{
			if(gettype($val) == "array") {
				escapeArray($val);
			}
			else {
				$val = urldecode($val);
				$val = $mysqli->real_escape_string($val);
				$req[$key] = $val;
			}
		}
		return $req;
	}

?>
