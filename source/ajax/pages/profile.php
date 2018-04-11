<?php
# todo: check or resize the photo after uploaded

# Name:	profile.php
# Desc:	This is the profile page layout.

$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
$d='<option value="-">Date</option>';
$m='<option value="-">Month</option>';
$y='<option value="-">Year</option>';
$yRange=CoreFunc::getYearRange(100);
for($i=$yRange['min'];$i<=$yRange['max'];$i++) $y.='<option value="'.$i.'">'.$i.'</option>';
for($i=0;$i<12;$i++) $m.='<option value="'.($i+1).'">'.$months[$i].'</option>';
for($i=1;$i<32;$i++) $d.='<option value="'.$i.'">'.$i.'</option>';


$t=User::curr();
$profileUid=$_GET['uid'];

//[real_connection]
$result='{"curl":['.CoreFunc::curlGet('/user/getUserById/'.$profileUid).']}';
//$result='{"curl":['.CoreFunc::curlGet('/user.json').']}';
$obj = json_decode($result,true);

foreach($obj['curl'] as $key=>$val){ 
	echo '<br/>';
	echo '<p class="subtitle">'.(($profileUid==$t->id())?$t->fullname():$val['firstName'].' '.$val['lastName'])." 's Profile</p>";

	//echo $result.'<br/><br/>';
	echo '<br/>';
	echo '<div class="buttons">';
	
	//echo '<li>'.$val['content'].'</li>';
	echo	'<table><tr><td valign="top" align="center">';
	echo    '<img id="profilePic" src="'.profilePicUrl.$val['profilePic'].'" width="200" height="200"><br/><br/>';
	if($profileUid==$t->id())
		echo	'<input type="file" id="file" name="file"/><a href="#" class="regular" onclick="ajaxFileUpload();">Upload</a>';
	echo	'</td><td width="530"><table style="padding:0px 0px 0px 30px;">';
	//About
	echo 	'<tr><td width="150"><b>About</b></td><td style="float:right;">';
	if($profileUid==$t->id())
		echo	'<a href="#" class="regular" onclick="editAbout();"><span id="btnEditAbout">Edit</span></a>';
	
	echo '</td></tr><tr><td colspan="2"><p id="about">'.((array_key_exists('aboutMe',$val))?$val['aboutMe']:'').'</p></td></tr>';
		
	echo 	'<tr><td><br/></td><td><br/></td></tr>';
	//Basic Info
	echo 	'<tr><td><b>Basic Info</b></td><td style="float:right;">';
	if($profileUid==$t->id())
		echo	'<a href="#" class="regular" onclick="editBasic();"><span id="btnEditBasic">Edit</span></a>';
	echo	'</td></tr><tr><td>Title</td><td width="50%"><span id="title">'.$val['title'].'</span></td></tr>';
	echo	'<tr><td>Name</td><td><span id="firstName">'.$val['firstName'].'</span> <span id="lastName">'.$val['lastName'].'</span></td></tr>';
	$gender=$val['gender'];
	if($val['gender']=='M')
		$gender='Male';
	else if($val['gender']=='F')
		$gender='Female';
	echo 	'<tr><td>Gender</td><td><span id="gender">'.$gender.'</span></td></tr>';
	echo 	'<tr><td>Birthday</td><td><span id="dateOfBirth">'.$val['dateOfBirth'].'</span>
			<select id="selRegBirthDate" class=".inputSel">'.$d.'</select>
			<select id="selRegBirthMonth" class=".inputSel" onchange="birthday_change();">'.$m.'</select>
			<select id="selRegBirthYear" class=".inputSel" onchange="birthday_change();">'.$y.'</select>
			</td></tr>';
	echo 	'<tr><td>Address</td><td><p id="home_address">'.(array_key_exists('home_address',$val)?$val['home_address'][0]:'').'</p></td></tr>';
	//echo 	'<tr><td>Date of Registration</td><td>'.$val['registerDate']['$date'].'</td></tr>';
	echo 	'<tr><td><br/></td><td><br/></td></tr>';
	//Contact Info
	echo 	'<tr><td><b>Contact Info</b></td><td style="float:right;">';
	if($profileUid==$t->id())
		echo	'<a href="#" class="regular" onclick="editContact();"><span id="btnEditContact">Edit</span></a>';
	echo	'</td></tr><tr><td>Email</td><td><span id="email">'.$val['contact']['emailAddress'].'</span></td></tr>';
	echo	'<tr><td>Email Backup</td><td><span id="emailBackup">'.((array_key_exists('emailBackup',$val['contact']))?$val['contact']['emailBackup']:'').'</span></td></tr>';
	echo	'<tr><td>Home Phone</td><td><span id="homePhone">'.((array_key_exists('homePhone',$val['contact']))?$val['contact']['homePhone']:'').'</span></td></tr>';
	echo	'<tr><td>Office Phone</td><td><span id="officePhone">'.((array_key_exists('officePhone',$val['contact']))?$val['contact']['officePhone']:'').'</span></td></tr>';
	echo	'<tr><td>Mobile Phone</td><td><span id="mobilePhone">'.((array_key_exists('mobilePhone',$val['contact']))?$val['contact']['mobilePhone']:'').'</span></td></tr>';
	echo	'</table></td></tr></table>';
}
echo '</div>';
?>
<div id="errMsg"></div>

<script type="text/javascript">
	$("#selRegBirthDate").hide();
	$("#selRegBirthMonth").hide();
	$("#selRegBirthYear").hide();
	var i=1;
	function ajaxFileUpload(){
		$.ajaxFileUpload({ 
			//url:'uploadProfilePic.php',
			url:'ajax.php?act=u&f=profilePic',
			secureuri:false, 
			fileElementId:'file', 
			dataType: 'text', 
			success: function (data){ 
				//alert(data.file_infor); 
				//alert(data);
				if(data=='Invalid file'){
					$("#errMsg").html('<strong style="color:#FF0000;">*'+data+'</strong>');
				}else{
					$("#profilePic").attr("src",'images/user/profilePic/'+data+'?version='+i);
					$("#errMsg").html('<strong style="color:#4CC417;">*Saved.</strong>');
					i++;
				}
				//alert($("#profilePic").attr("src"));
			}} 
		) 
		//alert('asdasa');
		return false; 
	}

	function update(updatedInfo){//?fieldname=value1&fieldname=value2&.....
		//alert(updatedInfo);
		var result;	
		$.post('ajax.php?act=u&f=change_my_info',{ 'info':'?'+updatedInfo },
			function(data){
				result=data;
				$("#errMsg").html(result);
			},'html'
		);
	}
	function editAbout(){
		if($('#btnEditAbout').html()=='Edit'){
			$('#about').html('<input type="text" id="txtAbout" class="searchBox" style="width:250px;" value="'+unescape($('#about').html())+'">');
			$('#btnEditAbout').html('Save');
		}else{
			$('#about').html($('#txtAbout').val());
			$('#btnEditAbout').html('Edit');
			update('aboutMe='+escape($('#about').html()));
		}
	}
	
	function editBasic(){
		if($('#btnEditBasic').html()=='Edit'){
			$('#home_address').html('<input type="text" id="txtHome_address" class="searchBox" value="'+$('#home_address').html()+'">');
			
			//title
			title=$('#title').html();
			$('#title').html(
				'<select id="selTitle"><option value="-">--</option><option value="Miss">Miss</option><option value="Ms.">Ms.</option>'
					+'<option value="Mrs.">Mrs.</option><option value="Mr.">Mr.</option><option value="Dr.">Dr.</option>'
					+'<option value="Prof.">Prof.</option></select>'
			)
			$("#title option[value='"+title+"']").attr("selected",true);
			
			//name
			$('#firstName').html('First Name:<input type="text" id="txtFirstName" class="searchBox" value="'+$('#firstName').html()+'"><br/>');
			$('#lastName').html('Last Name:<input type="text" id="txtLastName" class="searchBox" value="'+$('#lastName').html()+'">');
			
			//gender
			gender=$('#gender').html();
			if(gender=='Male')
				gender='M';
			else if(gender=='Female')
				gender='F';
			$('#gender').html(
				'<select id="selSex"><option value="-">--</option><option value="M">Male</option><option value="F">Female</option></select>'
			)
			$("#gender option[value='"+gender+"']").attr("selected",true);
			
			//birthday
			birthday=($('#dateOfBirth').html()).split("-");
			date=parseInt(birthday[0]);
			month=parseInt(birthday[1]);
			year=parseInt(birthday[2]);
			$("#selRegBirthDate option[value='"+date+"']").attr("selected",true);
			$("#selRegBirthMonth option[value='"+month+"']").attr("selected",true);
			$("#selRegBirthYear option[value='"+year+"']").attr("selected",true);
			$("#selRegBirthDate").show();
			$("#selRegBirthMonth").show();
			$("#selRegBirthYear").show();
			$("#dateOfBirth").hide();
			$('#btnEditBasic').html('Save');
		}else{
			var objRegExp  = /[a-zA-Z][a-zA-Z ]*/;
			if(($('#txtFirstName').val()=='')||($('#txtLastName').val()=='')){
				$("#errMsg").html('<strong style="color:#FF0000;">*Please fill in your first and last name.</strong>');
			}else if((!objRegExp.test($('#txtFirstName').val()))||(!objRegExp.test($('#txtLastName').val()))){
				$("#errMsg").html('<strong style="color:#FF0000;">*Wrong first and last name.</strong>');
			}else{
				update(
					'title='+$('#selTitle').val()
					+'&firstName='+escape($('#txtFirstName').val())
					+'&lastName='+escape($('#txtLastName').val())
					+'&gender='+$("#selSex").val()
					+'&dateOfBirth='+$("#selRegBirthDate").val()+'-'+$("#selRegBirthMonth").val()+'-'+$("#selRegBirthYear").val()
					+'&line1='+escape($('#txtHome_address').val())
				);
				
				$('#fullnameL').html($('#txtFirstName').val()+' '+$('#txtLastName').val());
				
				$('#home_address').html($('#txtHome_address').val());
				$('#title').html($("#selTitle").val());
				$('#firstName').html($('#txtFirstName').val());
				$('#lastName').html($('#txtLastName').val());
				$('#gender').html($("#selSex").find("option:selected").text());//need to save $("#selSex").val() in the database
				$('#dateOfBirth').html($("#selRegBirthDate").val()+'-'+$("#selRegBirthMonth").val()+'-'+$("#selRegBirthYear").val());
				$("#selRegBirthDate").hide();
				$("#selRegBirthMonth").hide();
				$("#selRegBirthYear").hide();
				$("#dateOfBirth").show();
		
				$('#btnEditBasic').html('Edit');
			}
		}
	}
	
	function editContact(){
		if($('#btnEditContact').html()=='Edit'){
			//$('#email').html('<input type="text" id="txtEmail" class="searchBox" value="'+$('#email').html()+'">');
			$('#emailBackup').html('<input type="text" id="txtEmailBackup" class="searchBox" value="'+$('#emailBackup').html()+'">');
			$('#homePhone').html('<input type="text" id="txtHomePhone" class="searchBox" value="'+$('#homePhone').html()+'">');
			$('#officePhone').html('<input type="text" id="txtOfficePhone" class="searchBox" value="'+$('#officePhone').html()+'">');
			$('#mobilePhone').html('<input type="text" id="txtMobilePhone" class="searchBox" value="'+$('#mobilePhone').html()+'">');
			$('#btnEditContact').html('Save');
		}else{
			update(
				//'email='+$('#txtEmail').val()
				'emailBackup='+$('#txtEmailBackup').val()
				+'&homePhone='+$('#txtHomePhone').val()
				+'&officePhone'+$('#txtOfficePhone').val()
				+'&mobilePhone'+$('#txtMobilePhone').val()
			);
			
			//$('#email').html($('#txtEmail').val());
			$('#emailBackup').html($('#txtEmailBackup').val());
			$('#homePhone').html($('#txtHomePhone').val());
			$('#officePhone').html($('#txtOfficePhone').val());
			$('#mobilePhone').html($('#txtMobilePhone').val());
			$('#btnEditContact').html('Edit');
		}
	}
</script>