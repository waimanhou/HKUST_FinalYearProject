<?php
require_once('adminFunc.php');
?>
<html>
<head>
<script type="text/javascript" src="../ext/js/plugin/jQuery.js"></script>
<script>
	function lockUnlockAccount(uid){
		$.post('admin.php?f=lockUnlockAccount',{ 'uid':uid },
			function(data){
				if(data=='1')
					alert('Success.');
				searchUser();
			},'text'
		);
	}

	function searchUser(){
		if($('#searchInfo').val().length<2)
			return;
		var result='';	
		$.post('admin.php?f=searchUser',{ 'info':escape($('#searchInfo').val()) },
			function(json){
				//alert(json);
				if(json==null){
					result='';
					$('#searchResult').html(result);
				}else{
					result+='<table>';
					result+='<tr>';
					result+='<th></th><th>Username</th><th width="200px;">Name</th><th width="200px;">Email</th><th>Lock</th>';
					for (var i = 0; i < json.curl.length; i++) { 
						result+='<tr>';
						result+='<td>';
						result+='<div class="user_sImg">';
						result+='<a href="#" onclick="browseFrd(\''+json.curl[i].userId+'\',\''+json.curl[i].firstName+" "+json.curl[i].lastName+'\');">';
						result+='<img id="profilePic" style="vertical-align:middle;" src="../images/user/profilePic/'+json.curl[i].profilePic+'" width="50" height="50">';
						result+='</a></div>';
						result+='</td>';
						result+='<td>'+json.curl[i].username+'</td>';
						result+='<td>'+json.curl[i].firstName+" "+json.curl[i].lastName+'</td>';
						result+='<td>'+json.curl[i].contact.emailAddress+'</td>';
						if(json.curl[i].locked!=null)
							result+='<td><input type="checkbox" id="'+json.curl[i].userId+'" checked onchange="lockUnlockAccount(\''+json.curl[i].userId+'\');"></td>';
						else
							result+='<td><input type="checkbox" id="'+json.curl[i].userId+'" onchange="lockUnlockAccount(\''+json.curl[i].userId+'\');"></td>';
						result+='</tr>';
					}
					result+='</table><br/>';
				}
				$('#searchResult').html(result);
			},'json'
		);
	}
</script>
</head>
<body>
<a href="home.php">HOME</a><br/>
<input type="text" id="searchInfo" class="searchBox" onkeydown="searchUser();" onkeydown="if (event.keyCode == 13) { searchUser(); return false; }">
<a href="#" class="regular" onclick="searchUser();">Search</a>
<div id="searchResult"></div>
</body>
</html>