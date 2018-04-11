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
			<p class="subtitle" style="font-size:18px;">Credit</p>
			<p id="credit">
				<p id="credit_title">Project Supervisor: Dimitris Papadias</p>
				<table style="padding-left:25px;line-height:20px;">
					<tr>
						<td>
							<img id="profilePic" src="images/Dimitris Papadias.jpg" width="89px" height="121px">
						</td>
						<td width="500px" valign="top" style="padding-top:10px;">
							I am a Professor at the Department of Computer Science and Engineering, Hong Kong University of Science and Technology (HKUST).
							<br/>
							Home Page: <a href="http://www.cse.ust.hk/~dimitris/">http://www.cse.ust.hk/~dimitris/</a>
						</td>
					</tr>
				</table>
				<p id="credit_title">Project Coordinator: Nikos Armenatzoglou, Nick</p>
				<table style="padding-left:25px;line-height:20px;">
					<tr>
						<td>
							<img id="profilePic" src="images/Nikos Armenatzoglou.jpg" width="89px" height="121px">
						</td>
						<td width="500px" valign="top" style="padding-top:10px;">
							Currently, I am in Hong Kong, pursuing a Ph.D. at the Department of Computer Science and Engineering, Hong Kong University of Science and Technology, under the supervision of Prof. Dimitris Papadias.
							<br/>
							Home Page: <a href="http://www.cse.ust.hk/~nikos/">http://www.cse.ust.hk/~nikos/</a>
						</td>
					</tr>
				</table>
				<p id="credit_title">Project Member: Hou Wai Man, Raymond</p>
				<table style="padding-left:25px;line-height:20px;">
					<tr>
						<td>
							<img id="profilePic" src="images/Hou Wai Man.jpg" width="88px" height="139px">
						</td>
						<td width="500px" valign="top" style="padding-top:10px;">
							Responsibility: User Interface, Mobile and web Application design & implementation
							<br/><br/>
							About: I am studying BEng Computer Science at HKUST (2011-2013). Programming is my favor especially in game development.
							<br/>
							Email:<a href="mailto:waiman01010@hotmail.com">waiman01010@hotmail.com</a>
						</td>
					</tr>
				</table>
				<p id="credit_title">Project Member: Lo Wai Tong, Jeff</p>
				<table style="padding-left:25px;line-height:20px;">
					<tr>
						<td>
							<img id="profilePic" src="images/Lo Wai Tong.jpg" width="89px" height="121px">
						</td>
						<td width="500px" valign="top" style="padding-top:10px;">
							Responsibility: Web service implementation
							<br/><br/>
							About: I am studying Computer Science at the Department of Computer Science and Engineering, Hong Kong University of Science and Technology (HKUST).
							<br/>
							Email:<a href="mailto:wtloaa@gmail.com">wtloaa@gmail.com</a>
						</td>
					</tr>
				</table>
				<p id="credit_title">Project Member: Tsui Kam Ming, Tom</p>
				<table style="padding-left:25px;line-height:20px;">
					<tr>
						<td>
							<img id="profilePic" src="images/Tsui Kam Ming.jpg" width="89px" height="108">
						</td>
						<td width="500px" valign="top" style="padding-top:10px;">
							Responsibility: Database design, API implementation
							<br/><br/>
							About: Currently, I am pursuing a Bachelor Degree of Engineering in Computer Science in Hong Kong University of Science and Technology. I completed my Higher Diploma in Software Engineering in Hong Kong Institute of Vocational Education.
							<br/>
							Email:<a href="mailto:tomttkm@gmail.com">tomttkm@gmail.com</a>
						</td>
					</tr>
				</table>
				<p id="credit_title">Project Member: Chan Pak Lai, Terence</p>
				<table style="padding-left:25px;line-height:20px;">
					<tr>
						<td>
							<img id="profilePic" src="images/Chan Pak Lai.jpg" width="89px" height="134px">
						</td>
						<td width="500px" valign="top" style="padding-top:10px;">
							Responsibility: Web Application design (preliminary)
						</td>
					</tr>
				</table>
			<p>
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