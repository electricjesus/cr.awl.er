<?php
include_once "db.php";

//$id = $_GET['id'];
$q = "SELECT * FROM `productindex` WHERE `downloaded`=0 LIMIT 1";

//$rs = mysql_query($q, $_connection);
//$r = mysql_fetch_assoc($rs);

$_piid = $r['id'];
$_baseurl = $r['from'];
//$url = $_baseurl . $r['itemurl'];
$url = "http://www.asia-dress.com/en_shop_gjxa2/products.aspx?sku=1280507&shbid=21750";
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
$grepped = str_replace("</table>", "\n</table>", $grepped);
$fp = fopen('temp2', 'w');
fwrite($fp, $grepped);
fclose($fp);
$grepped =  `cat temp2 | grep -ie "unnamed9.*label"`;
//$grepped .= `cat temp | grep -i \"img1\"`; //"
$grepped .= `cat temp | grep -i \"HyperLink1\"`; //"
$grepped = str_replace("\r\n", "", $grepped);
$grepped = str_replace("\t", "", $grepped);
//$grepped = str_replace("<span", "\n<span", $grepped);
//$grepped = str_replace("</span>", "</span>\n", $grepped);

echo $grepped;

preg_match_all("/<span id=\"(.*)\">(.*)<\/span>|<img src=\"(.*)\" id=\"(.*)\" \/>/", $grepped, $arr, PREG_PATTERN_ORDER);

//print_r($arr);

$_name = $arr[2][0];
$_id = $arr[2][1];
$_numviews = $arr[2][2];
$_weight = $arr[2][3];
$_retail = $arr[2][4];
$_memprice = trim(str_replace("USD","",$arr[2][5]));
$_description = $arr[2][6];
if(empty($arr[3][8])) {
$_image = $_baseurl . $arr[3][8];
//$_imagedata = base64_encode(file_get_contents($_image));
}
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

//echo $q;	
$q = "UPDATE `productindex` SET `downloaded`=2 WHERE `id` = {$_piid} LIMIT 1";
//$rs = mysql_query($q, $_connection);
/*
?>
<div>
	<p>Name: <?= $_ ?></p>
	<p>id: <?= $_ ?></p>
	<p>numviews: <?= $_ ?></p>
	<p>weight: <?= $_ ?></p>
	<p>retail: <?= $_ ?></p>
	<p>memprice: <?= $_ ?></p>
	<p>description: <?= $_ ?></p>
	<p>image: <?= $_ ?></p>
</div>
*/