<?php
include_once "db.php";

//$id = $_GET['id'];
$q = "SELECT * FROM `productindex` WHERE `downloaded`=0 LIMIT 1";

//$rs = mysql_query($q, $_connection);
//$r = mysql_fetch_assoc($rs);

$_piid = $r['id'];
//$_baseurl = $r['from'];
$_baseurl = "http://www.asia-dress.com/en_shop_gjxa2/";
//$url = $_baseurl . $r['itemurl'];
$url = "http://www.asia-dress.com/en_shop_gjxa2/products.aspx?sku=1280507&shbid=21750";
//$url = "http://www.asia-dress.com/en_shop_gjxa2/products.aspx?sku=1280498&shbid=21750";
$q = "UPDATE `productindex` SET `downloaded`=1 WHERE `id` = {$_piid} LIMIT 1";
//$rs = mysql_query($q, $_connection);

//get page
//$e = `curl -s "{$url}" > temp`;

//$grepped = `cat temp | grep -i \"lab`; //"
//$grepped .= "\n";

$grepped = `cat temp`;
$grepped = str_replace("\r\n", "", $grepped);
$grepped = str_replace("\t", "", $grepped);
$grepped = str_replace("class=\"unnamed9\"", "\nclass=\"unnamed9\"", $grepped);
$grepped = str_replace("Label7", "Label7\n", $grepped);
$fp = fopen('temp2', 'w');
fwrite($fp, $grepped);
fclose($fp);
$grepped =  `cat temp2 | grep -ie "unnamed9.*label"`;
//$grepped .= `cat temp | grep -i \"img1\"`; //"
$grepped .= `cat temp | grep -i \"HyperLink1\"`; //"
$grepped = str_replace("<tr", "\n<tr", $grepped);
$grepped = str_replace("</tr>", "</tr>\n", $grepped);
$grepped = str_replace("\r\n", "", $grepped);
$grepped = str_replace("\t", "", $grepped);

echo $grepped;
// NEW PREG! /<tr><td.*>(.*)<\/td>.*<span id=".*">(.*)<\/span>.*<\/tr>/
// OLD PREG. <span id=\"(.*)\">(.*)<\/span>

preg_match_all("/<tr><td.*>(.*)\:<\/td>.*<span id=\".*\">(.*)<\/span>.*<\/tr>|.*photo.aspx\?photo=(.*)'\).*|<tr><td.*><span id=\"Lab.*\">(.*)<\/span>.*<\/tr>/", $grepped, $arr, PREG_PATTERN_ORDER);

$photo_url = "photo.aspx?photo=";

// TODO keymap




print_r($arr);

$_name = $arr[2][array_search('Item Name', $arr[1])];

$_id = $arr[2][array_search('Item Number', $arr[1])];
$_numviews = $arr[2][array_search('Number Of Viewing', $arr[1])];
$_weight = $arr[2][array_search('Weight', $arr[1])];
$_retail = $arr[2][array_search('market price', $arr[1])];
$_memprice = trim(str_replace("USD","",$arr[2][array_search('Member Price', $arr[1])]));
$_description = $arr[4][7];
//$_image = $_baseurl . $arr[3][8];
$_image = $_baseurl . $photo_url . $arr[3][8];
$_imagedata = base64_encode(file_get_contents($_image));
//$tempfile = md5($_image);
//$e = `wget {$_image} -O {$tempfile}`;
//$_imagedata = base64_encode(file_get_contents($tempfile));

$q = "
	INSERT INTO `products` 
			(`productindexid`,`itemnumber`,`itemname`,`itemmemprice`,`itemdescription`,`itemweight`,`itempopularity`,`itemsrcurl`,`itemimagesrc`,`itemimageblob`) 
	VALUES(
			{$_piid},{$_id},'{$_name}',{$_memprice},'{$_description}',{$_weight},{$_numviews},'{$url}','{$_image}','{$_imagedata}'
	);";


//$rs = mysql_query($q, $_connection) or die(mysql_error());

echo $q;	
$q = "UPDATE `productindex` SET `downloaded`=2 WHERE `id` = {$_piid} LIMIT 1";
//$rs = mysql_query($q, $_connection);

?>
<div>
	<p>Name: <?= $_name ?></p>
	<p>id: <?= $_id ?></p>
	<p>numviews: <?= $_numviews ?></p>
	<p>weight: <?= $_weight ?></p>
	<p>retail: <?= $_retail ?></p>
	<p>memprice: <?= $_memprice ?></p>
	<p>description: <?= $_description ?></p>
	<p>image: <?= $_image ?></p>
</div>
