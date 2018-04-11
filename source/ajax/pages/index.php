<?php 
# Name: index.php
# Desc: this index is the layout of the index.php

$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
$d='<option value="-">Date</option>';
$m='<option value="-">Month</option>';
$y='<option value="-">Year</option>';
$yRange=CoreFunc::getYearRange(100);
for($i=$yRange['min'];$i<=$yRange['max'];$i++) $y.='<option value="'.$i.'">'.$i.'</option>';
for($i=0;$i<12;$i++) $m.='<option value="'.($i+1).'">'.$months[$i].'</option>';
for($i=1;$i<32;$i++) $d.='<option value="'.$i.'">'.$i.'</option>';

//[real_connection]
$result='{"curl":['.CoreFunc::curlGet('/announcement/getActiveAnnouncement?asc=true').']}';

//echo $result;
//$result='{"curl":['.CoreFunc::curlGet('/test.json').']}';
$obj = json_decode($result,true);
?>

<script type="text/javascript">
function openMsg(msgdiv){
	document.getElementById(msgdiv).style.display='block'; 
	document.getElementById('fade').style.display='block';
}
function closeMsg(msgdiv){
	document.getElementById(msgdiv).style.display='none';
	document.getElementById('fade').style.display='none';
}
function getActiveAnnouncement(){
	$.post('ajax.php?act=u&f=getActiveAnnouncement',{},
		function(json){
			var result='';
			if(json!=null){
				for(var i=0;i<json.curl.length;i++){
					result+='<tr><td valign="top" width="250px;">'
					result+='<li><a href="#" onclick="msgBox_show(\'Announcement\',\''+json.curl[i].content+'\',\'info\',\'message\');">'+json.curl[i].content+'</a></li>';
					result+='</td>';
					result+='<td valign="top">'
					
					var myDate = new Date(json.curl[i].createDate.$date);
					myDate.setTime(myDate.getTime() - myDate.getTimezoneOffset() * 60 * 1000);
					result+='<li><p id="postDate">'+myDate.toUTCString()+'</p></li>';
					result+='</td></tr>';
				}
				//convertDateTime('postDate');
			}else{
				result='No announcement.';
			}
			$('#announcement').html(result);
		},'json'
	);
}
getActiveAnnouncement();
</script>

<div id="index">
	<div id="webBanner"></div>
	<!-- Left hand side -->
	<div id="intro" class="lhs64">
		<table>
			<tr>
				<td class="tblCaption" colspan="2">We made communications and sharing easy.<br />So it can make you better at everything.</td>
			</tr>
			<tr>
				<td class="tblTitle">Announcements</td>
				<td class="tblTitle"></td>
			</tr>
			<tr>
			</tr>
		</table>
		<div id="announcement"></div>
	</div>
	<!-- Right hand side -->
	<div class="rhs64">
		<div id="actSelect" class="unSel clickable" onclick="signUpForm_change(this);">Sign Up</div>
		<div id="actForm">
			<!-- Sign Up Form -->
			<div id="signUpForm" class="insetBox">
				<table>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('selRegTitle');">Title</td>
						<td class="field">
							<select id="selRegTitle" class=".inputSel">
								<option value="-">--</option>
  								<option value="Miss">Miss</option>
  								<option value="Ms.">Ms.</option>
								<option value="Mrs.">Mrs.</option>
								<option value="Mr.">Mr.</option>
								<option value="Dr.">Dr.</option>
								<option value="Prof.">Prof.</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('txtRegFname');">First Name</p>
						<td class="field"><input id="txtRegFname" class="inputBox" type="text" maxlength="20" style="width:250px;" title="Please enter your ID or Email" /></td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('txtRegLname');">Last Name</p>
						<td class="field"><input id="txtRegLname" class="inputBox" type="text" maxlength="20" style="width:250px;" title="Please enter your ID or Email" /></td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('txtRegEmail');">Email</p>
						<td class="field"><input id="txtRegEmail" class="inputBox" type="text" maxlength="50" style="width:250px;" title="Please enter your ID or Email" /></td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('txtRegUsername');">Usernname</p>
						<td class="field"><input id="txtRegUsername" class="inputBox" type="text" maxlength="20" style="width:250px;" title="Please enter your ID or Email" /></td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('txtRegPWD');">Password</p>
						<td class="field"><input id="txtRegPWD" class="inputBox" type="password" maxlength="50" style="width:250px;" title="Please enter your Password" /></td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('txtRegPWDConfirm');">Confirm Password</p>
						<td class="field"><input id="txtRegPWDConfirm" class="inputBox" type="password" maxlength="50" style="width:250px;" title="Please enter your Password" /></td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('selRegSex');">Gender</td>
						<td class="field">
							<select id="selRegSex" class=".inputSel">
								<option value="-">--</option>
  								<option value="M">Male</option>
  								<option value="F">Female</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('selRegBirthYear');">Birthday</td>
						<td class="field">
							<select id="selRegBirthDate" class=".inputSel"><?php echo $d; ?></select>
							<select id="selRegBirthMonth" class=".inputSel" onchange="birthday_change();"><?php echo $m; ?></select>
							<select id="selRegBirthYear" class=".inputSel" onchange="birthday_change();"><?php echo $y; ?></select>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td><p id="errMsg" style="color:#FF0000;"></p></td>
						<td style="text-align:right;"><input id="btnSignUp" class="inputBtn" type="button" value="Sign Up" onclick="btnSignUp_click();" /></td>
					</tr>
				</table>
			</div>
			<!-- Login Form -->
			<div id="loginForm">
				<table>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('txtLoginID');">Username</p>
						<td class="field"><input id="txtLoginID" class="inputBox" type="text" maxlength="20" title="Please enter your Username" /></td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="focusTxt('txtLoginPWD');">Password</p>
						<td class="field"><input id="txtLoginPWD" class="inputBox" type="password" maxlength="50" title="Please enter your Password" onkeydown="if(event.keyCode==13){btnLogin_click();return false;}"/></td>
					</tr>
					<tr>
						<td class="title clickable unSel" onclick="txtKeepLogin_change();">Keep me logged in</td>
						<td class="field"><input id="txtKeepLogin" class="clickable unSel" type="text" value="Yes" readonly onclick="txtKeepLogin_change();"/></td>
					</tr>
				</table>
				<table>
					<tr>
						<td><p id="errMsg2" style="color:#FF0000;"></p></td>
						<td></td>
					<tr/>
					<tr>
						<td style="text-align:right;">
							<input id="btnLogin" class="inputBtn" type="button" value="Forgot Password" onclick="btnForgotPwd_click();" />
						</td>
						<td style="text-align:right;">
							<input id="btnLogin" class="inputBtn" type="button" value="Login" onclick="btnLogin_click();" />
						</td>
					</tr>
				</table>
			</div>
		</div>	
	</div>
</div>
