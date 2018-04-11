<?php   #settings.php
//Website Title
define("SITE_NAME",'GeoSN');

//<Footer> - Copy Right
define("COPY_RIGHT",'Copyright &copy; <span datadetectors="off">2012-2013<span> Jeff Lo, Terence Chan, Tom Tsui and Raymond Hou All rights reserved.');

//<Footer> - Website Info
define("WEB_INFO",'Resolution: 1024x768 / HTML5 & CSS3');

define("USER_FILES_DIR",$_SERVER['DOCUMENT_ROOT'].'/Fyp user files/');

//define("curlConnectionUrl",'http://223.16.249.178:8080/GSN_WS');

define("root","/fyp/");
define("profilePicUrl",root.'images/user/profilePic/');
define("uploadProfilePicUrl",'images/user/profilePic/');
define("curlConnectionUrl",'http://localhost:8080/GSN_WS');


# bg, mask, shadow, deep, ligth
$m_colors=array('#FFF','#CCC','#777','#F5F5F5','#eee','#EEF','#D7FFCC');

# deep, median, light, shadow_deep, shadow_light
$f_colors=array('#000', '#a0aab3', '#FFF','#777','#FFF');	//reference:[Font Color Set Desc]


//$m_colors=array('#FFF','#FFF','#ed2d2d','#ed2d2d','#ed2d2d','#fd8686','#FFF','#fd8686','#777','#FFF');	//reference:[Main Color Set Desc]
//$f_colors=array('#000','#FFF','#777');	//reference:[Font Color Set Desc]

?>