<?php	
# Name: index.php (www)
# Desc: The main layout of entire website
require_once('requires.php');

if(CoreFunc::isMobileBrowser())
	$_SESSION['device']='mobile';
else
	$_SESSION['device']='desktop';

if(CoreFunc::isMobileBrowser()){
	$t=User::curr();
	if(isset($t))
		include_once('ajax/mpages/home.php');
	else
		include_once('ajax/mpages/index.php');
}else{
?>

<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="resolution" content="1024x768" />
		<meta name="Authors" content="Jeff Lo, Terence Chan, Tom Tsui, Raymond Hou" />
		<meta name="description" content="This is a Geo-Social Network for HKUST." />
		<title><?php echo SITE_NAME; ?></title>
		<link rel="stylesheet" href="ext/css/css.php" />
	</head>
	<body id="aboutBody">
		<div id="globalMask" class="mask"></div>
		<div id="main">
			<a href="index.php"><div id="webBanner"></div></a><br/>
			<p class="subtitle" style="font-size:18px;">About</p>
			<p style="padding-left:25px;padding-top:15px;width:800px;font-size:15px;line-height:30px;">
Geo-social Network focuses on providing location-based service to users, for example, check-in and finding nearby places for the current location. The project aims to develop a Geo-social Network as mobile and web application for HKUST students that would allow them to check-in and find their friends nearby, and to communicate on the personal status through postings. The web application acts as a thin client such that no additional software is needed to be installed for providing service and functionality.
			</p>
		</div>
		<footer>
			<div>
				<span id="logo"></span>
				<ul class="isMenu">
					<li><a href="about.php">About</a></li>
					<li><a href="credit.php">Credit</a></li>
				</ul>
			</div>
		</footer>
		<div id="remarks">
			<div class="lhs64">
				<div><?php echo COPY_RIGHT ?></div>
				<div><?php echo WEB_INFO; ?></div>
			</div>
			<div class="rhs64">
				<a href="http://127.0.0.1/fyp/" target="_blank"><div id="cert1" class="cert" title="<?php echo SITE_NAME ?>"></div></a>
				<a href="http://www.cse.ust.hk/" target="_blank"><div id="cert2" class="cert" title="Department of Computer Science and Engineering"></div></a>
				<a href="http://www.ust.hk/" target="_blank"><div id="cert3" class="cert" title="The Hong Kong University of Science and Technology"></div></a>
			</div>
		</div>
	</body>
</html>
<?php
}
?>