<?php
# Name:	coreFunc.php
# Desc:	A static class, it provides all of the common functions.
#		This class is the core class, it MUST be included before use these function.

class CoreFunc{
	
	public static function loggedIn(){
		return CoreFunc::session_valuable('username')?true:false;
	}
	
	public static function clientInfo(){
		if(isset($_SERVER["REMOTE_ADDR"])) $i['ip'] = $_SERVER["REMOTE_ADDR"]; 
		else if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) $i['ip'] = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
		else if(isset($_SERVER["HTTP_CLIENT_IP"])) $i['ip'] = $_SERVER["HTTP_CLIENT_IP"];
		else $i['ip']='Unknown';
		$i['browser']=$_SERVER['HTTP_USER_AGENT'];
		return $i;
	}
	
	public static function getYearRange($intRange){
		$y=date('Y');
		return array('min'=>$y-$intRange,'max'=>$y);
	}
	
	public static function genErrorMsg($strTitle,$strMsg,$strCode){
		if(strlen($strTitle)>0&&strlen($strMsg)>0&&strlen($strCode)>0){
			return "
			<error>
				<title>$strTitle</title>
				<message>$strMsg</message>
				<code>$strCode</code>
			</error>
			";
		}
	}
	
	public static function valuable($mixed=null){
		return isset($mixed)&&!empty($mixed)&&$mixed!=null&&$mixed!='';
	}
	
	public static function isValuable($mixed=null){
		return isset($mixed)&&!empty($mixed)&&$mixed!=null&&$mixed!='';
	}
	
	public static function session_valuable($sid){
		return isset($_SESSION[$sid])&&!empty($_SESSION[$sid])&&$_SESSION[$sid]!=null&&$_SESSION[$sid]!='';
	}
	
	public static function GET($g){
		return isset($_GET[$g])?$_GET[$g]:'';
	}
	
	// A function to access the web services 
	public static function curlGet($json_url){
		$username = 'your_username';  // authentication
		$password = 'your_password';  // authentication
		 
		// jSON String for request
		$json_string = '[your json string here]';
		 
		// Initializing curl
		// $curlConnectionUrl is defined in /system/config/settings
		$ch = curl_init(curlConnectionUrl.$json_url);
		 
		// Configuring curl options
		$options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERPWD => $username . ":" . $password,   // authentication
		CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
		CURLOPT_POSTFIELDS => $json_string,
		CURLOPT_HTTPGET => true
		);
		 
		// Setting curl options
		curl_setopt_array( $ch, $options );
		
		$result=curl_exec($ch); // Getting result string
		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if($http_status==200)
			return $result;
		else
			return '';
	}
	
	public static function isMobileBrowser() {
		//if(extension_loaded('zlib')){ob_start('ob_gzhandler');}
		//header("Content-type: text/css");
		$UA = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match("/mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo|iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|ipod|iphone|android|opera mini|blackberry|palm os|palm|hiptop|avantgo|fennec|plucker|xiino|blazer|elaine|iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile/i", $UA) == 1) {
			return true;
		} else {//include($_SERVER['DOCUMENT_ROOT']."/wp-content/themes/default/style.css");
			//echo 'desktop';
			return false;
		}
		//if(extension_loaded('zlib')){ob_end_flush();}
	}
	
	public static function getPageFolderName(){
		return ($_SESSION['device']=='desktop')?'pages':'mpages';
	}
}
?>