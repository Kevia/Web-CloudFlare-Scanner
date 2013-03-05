<html>
<head>
<meta http-equiv="Content-Language" content="fr">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>CloudFlare Subdomain Scanner</title>
<meta content="CloudFlare SubDomain Scanner, Shahril" name="description">
<style>
@import url(http://fonts.googleapis.com/css?family=Fredoka+One);@import url(http://fonts.googleapis.com/css?family=Alike);body {background: #000000 repeat-x;font: 75%/170% Arial, Helvetica, sans-serif;padding: 0px;margin: 0px;color: #C4C4C4;}a:visited {COLOR: #0066cc;text-decoration none;cursor:pointer;}a:link {COLOR: #ffcb07;cursor:pointer;}input{vertical-align: middle;color: #000;cursor:pointer;padding:4px 7px;font-weight:bold;background rgba(0, 0, 0, .75);border:1px solid #afbccb;border-radius:5px;box-shadow:0 1px 2px rgba(175,188,203,0.6), inset 0 10px 15px rgba(255,255,255,0.5), inset 1px 1px rgba(255,255,255,0.5), inset -1px -1px rgba(255,255,255,0.5);text-shadow:0 1px rgba(255,255,255,0.5);}input:hover, input:focus{background-color: #ffcb07;border-color:rgba(0,0,0,.25);color:#000}textarea{font-family: 'Alike', serif;font-weight:200;padding:5px;box-shadow: rgba(255, 255, 255, .75) 0px 0px 9px 1px;background-color:rgba(0, 0, 0, .25);        color:#ffcb07;border-radius:5px;height: 168px; width: 887px;}.link{font-size:12px;}.head{color: #FFF;font-family: 'Fredoka One', cursive;font-size: 30px;font-weight:400;}.link {font-size:}.foot{font-family: 'Fredoka One', cursive;padding: 2px;border-top: 1px solid #EBEBEB;background-color: #FFF;bottom:0;position:fixed;width:100%;height: 20px;font-size: 15px;}.number{ font-size:15px; color:#fff; }
</style>
</head>
<center>
<table border='1' bordercolor='#FFCC00' width='400' cellpadding='0' cellspacing='0'>
<form method='POST' name='BOX'>
<tr>
<td><b> Site : </b></td><td><input type='TEXT' name='site' size=25 value='' /></td><br>
</tr>
<tr>
<td><b> Wordlist : </b></td><td><input type='TEXT' name='wordlist' size=45 value='http://pastebin.com/raw.php?i=fkZdjLkZ' /></td><br>
</tr>
<tr><td colspan=2 ><center><input type='SUBMIT' name='submit' value='Submit' /></center></td></tr>
</form>
</table>
<?php

if(!isset($_POST['site'])) { $credit = credit();die("<br><br>$credit"); }

if(!isset($_POST['wordlist']) || empty($_POST['wordlist'])) {
	$subdomain = array('direct', 'mail', 'direct-connect', 'cpanel', 'ftp');
	} else {
	$subdomain = getwordlist($_POST['wordlist']);
}
 
$urlsite = CleanAndClear($_POST['site']);

if(CheckCloudFlareDNS($urlsite) && isset($_POST['site'])) {
	echo "<br>Scanning <b>[".count($subdomain)."]</b> Subdomain<br>Result : <br>";
	$array = array();
	foreach($subdomain as $scanarray) {
		$subdomain = $scanarray.".".$urlsite;
		$ip = gethostbyname($subdomain);
		if(check($subdomain)) {
			if(!CheckCloudFlareDNS($ip)) {
				array_push($array, $subdomain);
				echo "<br>".$subdomain." = <font color='#D91F1F'>".$ip."</font>";
			}
		}
	}
	if(count($array) < 1)
	{
		echo "<br><br><font color='#D91F1F'>cant find anything shit :( </font><br>";
	} else {
		echo "<br><br><font color='#D91F1F'>done finding ".count($array)." possible ip :) </font><br>";
	}
	credit();
} else {
	echo "<center>'<font color='#D91F1F'>".$_POST['site']."</font>' is not protect by <font color='#D91F1F'>CloudFlare</font><br>".credit()."</center>";
}

function credit(){
echo "</center>
<br><p align=\"center\">
Code By : <b> Shahril</b> | Thank To : <b>Afnum Vvip (gayies)</b><br>
Made in Malaysia | 2012  :)
</p>
</body>
</html>";
}


function CleanAndClear($site) {
	$output = $site;
	$remove = array("http://", "/", "www");
	foreach($remove as $remove1) {
		if (strpos($output, $remove1) !== FALSE) {
			$output = (str_replace($remove1, "", $output));
		}
	}
	return $output;
}
 
function check($url){
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url );
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,CURLOPT_VERBOSE,false);
	curl_setopt($ch,CURLOPT_TIMEOUT, 1);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch,CURLOPT_SSLVERSION,3);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
	$page=curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($httpcode>=200 && $httpcode<400) return true;
	else return false;
}
 
function CheckCloudFlareDNS($site) {
	$dns = dns_get_record($site, DNS_NS);
	$dns = $dns[0]['target'];
	if (strpos($dns, "cloudflare")) {
		return TRUE;
	}
}

function getdata($url){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec ($ch);
	curl_close ($ch);
	return $data;
}

function getwordlist($link) {
	$getwordlist = getdata($link);
	$getwordlist1 = str_replace("\r", "", $getwordlist);
	$subdomain = explode("\n", $getwordlist1);
	return $subdomain ;
}

?>