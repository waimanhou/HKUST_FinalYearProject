<?php
# Name:	accout.php
# Desc:	This is the notification page layout.
$t=User::curr();

?>
<br/>
<p class="subtitle">Edit Account</p>
<br/>
<form>
<div class="buttons">
<br/>
<b>Change Password</b>
<table>
	<tr><td width="200">Current Password</td><td><input id="oldPwd" type="password" name="fname" class="searchBox"></td></tr>
	<tr><td>New Password</td><td><input id="newPwd" type="password" name="fname" class="searchBox"></td></tr>
	<tr><td>Confirm Password</td><td><input id="confirmPwd" type="password" name="fname" class="searchBox"></td></tr>
	<tr><td><span id="errMsg"></span></td><td><a href="#" class="regular" style="float:right;" onclick="changePassword();">Save</a></td></tr>
</table>
</div>
</form>

<script type="text/javascript">
	function changePassword(){
		if(($('#newPwd').val().length<8)||($('#newPwd').val().length>20)){
			$("#errMsg").html('<b style="color:#FF0000;">*Password must have 8-20 characters.</b>');
		}else if($('#confirmPwd').val()==$('#newPwd').val()){
			var result;	
			$.post('ajax.php?act=u&f=change_my_pwd',{ 'oldPwd':hex_md5($('#oldPwd').val()), 'newPwd':hex_md5($('#newPwd').val())},
				function(data){
					if(data==false)
						$("#errMsg").html('<b style="color:#FF0000;">*Current password is wrong.</b>');
					else{
						$("#errMsg").html('<b style="color:#4CC417;">*Saved.</b>');
						$('#newPwd').val('');
						$('#oldPwd').val('');
						$('#confirmPwd').val('');
					}
				},'text'
			);
		}else{
			$("#errMsg").html('<b style="color:#FF0000;">*New password does not match Confirm Password.</b>');
		}
	}
</script>