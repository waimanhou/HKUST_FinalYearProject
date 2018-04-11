<?php include_once('ajax/mpages/header.php');?>
<div data-role="page" id="page1">
	<div data-theme="a" data-role="header">
		<a data-role="button" onclick="loadPage('home');" class="ui-btn-left" rel="external">Home</a>
		<a data-role="button" onclick="btnLogout();" class="ui-btn-right" rel="external">Logout</a>
		<h3>
			<?php echo $t->fullname(); ?>
		</h3>
	</div>
	<div data-role="content" align="center">
		
		<form>
		<div class="buttons">
		<br/>
		
		<table width="300px" height="100%" align="center">
			<tr><td>
				<p class="subtitle">Edit Account</p>
				<b>Change Password</b><br/>
				Current Password<input id="oldPwd" type="password" name="fname" class="searchBox">
				New Password<input id="newPwd" type="password" name="fname" class="searchBox">
				Confirm Password<input id="confirmPwd" type="password" name="fname" class="searchBox">
				<span id="errMsg"></span>
				
			</td></tr>
		</table><br/>
		<a href="#" class="regular" onclick="changePassword();">Save</a>
		</div>
		</form>
	</div>
<?php include_once('ajax/mpages/footer.php');?>
<script type="text/javascript">
	function changePassword(){
		if($('#confirmPwd').val()==$('#newPwd').val()){
			var result;	
			$.post('ajax.php?act=u&f=change_my_pwd',{ 'oldPwd':hex_md5($('#oldPwd').val()), 'newPwd':hex_md5($('#newPwd').val())},
				function(data){
					if(data==false)
						$("#errMsg").html('<b style="color:#FF0000;">*Current password is wrong.</b>');
					else
						$("#errMsg").html('<b style="color:#4CC417;">*Saved.</b>');
				},'text'
			);
		}else{
			$("#errMsg").html('<b style="color:#FF0000;">*New password does not match Confirm Password.</b>');
		}
	}
</script>