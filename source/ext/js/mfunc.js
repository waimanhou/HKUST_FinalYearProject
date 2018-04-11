/*
	Name: mfunc.js
	Desc: This is a javascript function page for mobile device.
*/
	function loadPage(page,param){
		if(page=='home'){
			if($('#name').val()!=''){
				$('#hmMain').load('ajax.php?act=mp&f=home&uid='+$('#uid').val()+'&frdname='+encodeURIComponent($('#name').val()));
			}else{
				if(param==null)
					$('#hmMain').load('ajax.php?act=mp&f='+page);
				else
					$('#hmMain').load('ajax.php?act=mp&f='+page+'&'+param);
			}
		}else{
			if(param==null)
				$('#hmMain').load('ajax.php?act=mp&f='+page);
			else
				$('#hmMain').load('ajax.php?act=mp&f='+page+'&'+param);
		}
	}
	
	function birthday_change(){
		//TO-DO
		//Dont know why can't update $('#selRegBirthDate').html()
		var options='<option value="-">Date</option>';
		var lastDate=31;
		var y=$('#selRegBirthYear').val();
		var m=$('#selRegBirthMonth').val();
		var d=$('#selRegBirthDate').val();
		if(m!='-')lastDate=CalDays(y,m);
		for(var i=1;i<=lastDate;i++) options+='<option value="'+i+'">'+i+'</option>';
		$('#selRegBirthDate').html(options);
	}
	
	function browseFrd(fid,frdname){
		if(fid!=$('#loginUid').val()){
			$('#uid').val(fid);
			$('#name').val(frdname);
			loadPage('home','uid='+fid+'&frdname='+encodeURIComponent(frdname));
		}else{
			$('#uid').val($('#loginUid').val());
			loadPage('home');
			$('#name').val('');
		}
	}

//login
	function btnLogin(){
		var id=$('#loginName').val();
		var pwd=$('#password').val();
		if(id.length>0 && pwd.length>0){ 
			pwd=hex_md5(pwd);
			$.post('ajax.php?act=mu&f=login',{'id':id,'pwd':pwd},
				function(data){
					result=data;
					if(result=='4')
						alert('*Account is locked.');
					else if((result=='1')||(result=='2'))
						alert('*Wrong username or password.');		
					else
						$('#hmMain').html(data);
				},'html'
			);
		}else{
			alert('Please enter the username & password.');
		}
	}
	
	function btnLogout(){
		$.post('ajax.php?act=mu&f=logout',{},
			function(data){
				//alert(data);
				$('#hmMain').html(data);
			},'html'
		);
	}
	
	function unFriend(fid,fname){
		opt=confirm('Do you want to unfriend '+fname+' ?');
		if(opt==true){
			$.post('ajax.php?act=u&f=unFriend',{'fid':fid},
				function(data){
					if(data=='1')
						loadPage('friend','uid='+$('#loginUid').val());
				},'html'
			);
		}
	}
	
	