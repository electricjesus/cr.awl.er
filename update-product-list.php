<?php

include_once "db.php";

$base_url = "http://www.asia-dress.com/en_shop_gjxa2/";
$page = 1;
$url = $base_url . "Search1.aspx?page={$page}&shbid=21750";
//get page
//$e = `curl -s "{$url}" > search-page`;
$max = `cat search-page | grep -i "070614jt-y2"`;
$max = str_replace("\r\n", "", $max);$max = str_replace("\t", "", $max);$max = str_replace("<a", "\n<a", $max);$max = str_replace("</a>", "</a>\n", $max);
preg_match_all("/page=(.*)&shbid=21750><img src=\'images\/070614jt-y2.jpg\'/", $max, $arr, PREG_PATTERN_ORDER);
$max = $arr[1][0];
for($x = 1; $x <= $max; $x++) {	
	$page = $x;
	$url = $base_url . "Search1.aspx?page={$page}&shbid=21750";	
	echo $url;
	$e = `curl -s "{$url}" > temp-search`;
	$grepped = `cat temp-search | grep -i "a href=\"prod"`;
	$grepped = str_replace("\r\n", "", $grepped);
	$grepped = str_replace("\t", "", $grepped);
	$grepped = str_replace("<a", "\n<a", $grepped);
	$grepped = str_replace("</a>", "</a>\n", $grepped);
	//echo $grepped;
	preg_match_all("/<a href=\"(.*)\" target=\"(.*)\">(.*)<\/a>/", $grepped, $arr, PREG_PATTERN_ORDER);
	//print_r($arr);

	for($i = 1; $i < count($arr[0]); $i+=2) {
		$_url = $arr[1][$i];$_name = $arr[3][$i];
		$sku_id = preg_match_all('/products.aspx\?sku=(.*?)\&shbid=.*/', $_url, $arr2, PREG_PATTERN_ORDER);
		$_id = $arr2[1][0];		
		echo ">> : " . $_id . " | " . $_url . " | " . $_name . "<br />";
		
		$q = "SELECT * FROM `productindex` WHERE `itemnumber`={$_id} LIMIT 1";
		$r = mysql_query($q, $_connection) or die(mysql_error());		
		if(mysql_num_rows($r) > 0) {
			$rs = mysql_fetch_assoc($r);
			if($_name != $rs['itemname']) {
				// already in DB but name has changed.. do update
				$q = "UPDATE FROM `productindex` SET `itemname` = '{$_name}' WHERE `id`={$rs['id']}";
				$r = mysql_query($q, $_connection) or die(mysql_error());
				echo "UPDATED : " . $_id . " | " . $_url . " | " . $_name . "<br />";
			}
		} else {
				echo "add";
				//this is a new item
				$q = "INSERT INTO `productindex` (`itemnumber`,`itemname`,`itemurl`,`downloaded`,`from`) VALUES({$_id},'{$_name}','{$_url}',0,'{$base_url}');";
				$r = mysql_query($q, $_connection) or die(mysql_error());
				echo "NEW : " . $_id . " | " . $_url . " | " . $_name . "<br />";
		}
	}
}
?>



