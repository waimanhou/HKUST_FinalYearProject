<?php
# Name: header.php
# Desc: This page is used to setup the header

$i=CoreFunc::isLoggedIn();
//$o=CoreFunc::isOwner();
if($i){
	$b='/Fyp User Files/demo/profile/banner.png';
	$p='/Fyp User Files/demo/profile/photo.png';
}else $b='images/banner.png';
?>

<div id="banner" style="background:url('<?php echo $b ?>');">
<?php if($i){ ?>
	<div id="image" class="unSel"><img src="<?php echo $p ?>" /></div>
<? } ?>
</div>

<?php if($i){ ?>
	<div id="panel" class="unSel">
		<div id="panel_top">
			<div id="name">Man Ip</div>
			<div id="controls">
				<input id="btnLogout" class="inputBtn" type="button" value="Logout" onclick="btnLogout_click();" />
			</div>
		</div>
		<div id="panel_middle"><!-- Management Controls -->
			<div id="about"></div>
			<div id="act">
				<div id="act_left" class="clickable" onclick="act_goL();">
					<div id="act_arrowL"></div>
				</div>
				<div id="act_center">
					<div id="act_container">
						<div id="act_friends" class="block clickable"><div>Friends</div></div>
						<div id="act_likes" class="block clickable"><div>Likes</div></div>
						<div id="act_events" class="block clickable"><div>Events</div></div>
						<div id="act_Notifactions" class="block clickable"><div>Notes</div></div>
						<div id="act_forFutureUse" class="block clickable"><div>…</div></div>
					</div>
				</div>
				<div id="act_right" class="clickable shadow" onclick="act_goR();">
					<div id="act_arrowR"></div>
				</div>
			</div>
		</div>
		<!--<div id="panel_bottom"></div>-->
	</div>
<?php } ?>