<?php	
# Name: index.php (www)
# Desc: The main layout of entire website
require_once('requires.php');

if(CoreFunc::isMobileBrowser())
	$_SESSION['device']='mobile';
else
	$_SESSION['device']='desktop';

if((CoreFunc::isMobileBrowser())||(isset($_GET['mode'])&&$_GET['mode']=='mobile')){
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
		<script type="text/javascript" src="ext/js/plugin/jQuery.js"></script>
		<script type="text/javascript" src="ext/js/lib/msgBox.js"></script>
		<script type="text/javascript" src="ext/js/ajax.js"></script>
		<script type="text/javascript" src="ext/js/func.js"></script>
		<script type="text/javascript" src="ext/js/md5.js"></script>
		<script type="text/javascript" src="ext/js/ajaxfileupload.js"></script>
		<script	src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false"></script>
	</head>
	<body>
		<div id="msgBox">
			<div id="msgBox_top">
				<div id="msgBox_title"></div>
				<div id="msgBox_btnClose" class="clickable unSel" onclick="glob_msgBox.Hide();">X</div>
			</div>
			<div id="msgBox_main">
				<div id="msgBox_icon"></div>
				<div id="msgBox_content"></div>
				<input id="msgBox_txtInput" class="inputBox" type="text" />
			</div>
			<div id="msgBox_controls">
				<div id="msgBox_btnConfirm" class="inputBtn clickable unSel" onclick="glob_msgBox.btnConfirm_click();">Confirm</div>
				<div id="msgBox_btnCancel" class="inputBtn clickable unSel" onclick="glob_msgBox.btnCancel_click();">Cancel</div>
				<div id="msgBox_btnOK" class="inputBtn clickable unSel" onclick="glob_msgBox.btnOK_click();">OK</div>
			</div>
		</div>
		<div id="globalMask" class="mask"></div>
		<div id="main"><!--ajax file content will be placed into this area.--></div>
		<footer>
			<div>
				<span id="logo"></span>
				<ul class="isMenu">
					<li><a style="color:#a0aab3;text-decoration:none;" href="about.php">About</a></li>
					<li><a style="color:#a0aab3;text-decoration:none;" href="credit.php">Credit</a></li>
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

<script>
	<?php
		//need to access home page first as the header is included inside home.php
		if(isset($_GET['p'])){
			if($_GET['p']=='home'){
				echo 'ajax_home();';
			}else if($_GET['p']=='event'){
				echo 'ajax_home(function(){ajax_event();});';
			}else if($_GET['p']=='news'){
				echo 'ajax_home(function(){ajax_news();});';
			}else if($_GET['p']=='wall'){
				if(isset($_GET['id']))
					echo 'ajax_home(function(){ajax_wall('.$_GET['id'].');});';
				else{
					$t=User::curr();
					if(isset($t))
						echo 'ajax_home(function(){ajax_wall('.$t->id().');});';
					else
						echo 'init();';
				}
			}else if($_GET['p']=='friend'){
				if(isset($_GET['id']))
					echo 'ajax_home(function(){ajax_friend('.$_GET['id'].');});';
				else{
					$t=User::curr();
					if(isset($t))
						echo 'ajax_home(function(){ajax_friend('.$t->id().');});';
					else
						echo 'init();';
				}
			}else if($_GET['p']=='checkin'){
				echo 'ajax_home(function(){ajax_checkIn();});';
			}else if($_GET['p']=='notification'){
				echo 'ajax_home(function(){ajax_notification();});';
			}else if($_GET['p']=='account'){
				echo 'ajax_home(function(){ajax_account();});';
			}else if($_GET['p']=='profile'){
				if(isset($_GET['id']))
					echo 'ajax_home(function(){ajax_profile('.$_GET['id'].');});';
				else{
					$t=User::curr();
					if(isset($t))
						echo 'ajax_home(function(){ajax_profile('.$t->id().');});';
					else
						echo 'init();';
				}
			}else if($_GET['p']=='search'){
				if(isset($_GET['searchInfo'])){
					echo 'ajax_home(function(){ajax_search(\''.$_GET['searchInfo'].'\');});';
				}else
					echo 'init();';
			}else if($_GET['p']=='venue'){
				echo 'ajax_home(function(){ajax_venue();});';
			}
		}else{
			echo 'init();';
		}
	?>	
</script>
<?php
}
?>