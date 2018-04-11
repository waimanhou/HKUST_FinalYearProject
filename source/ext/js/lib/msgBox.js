function MsgBox(strTitle,strMsg,strIcon,strType){
	this.title=strTitle;
	this.msg=strMsg;
	this.icon=strIcon;
	this.response='';
	this.type=strType=='prompt'?'prompt':strType=='confirm'?'confirm':'message';
	
	this.Title=function(){ return this.title; }
	this.Msg=function(){ return this.msg; }
	this.Icon=function(){ return this.icon; }
	this.Response=function(){ return this.response; }
	this.Type=function(){ return this.type; }
	
	/*Public functions*/
	this.Show=function(){
		this.response='';
		$('#msgBox_title').html(this.title);
		$('#msgBox_icon').css('background-position-x',this.icon_position_x()+'px');
		$('#msgBox_content').html(this.msg);
		$('#msgBox_txtInput').css('display',this.type=='prompt'?'block':'none').val('');
		$('#msgBox_btnConfirm').css('display',this.type=='confrim'?'block':'none');
		$('#msgBox_btnCancel').css('display',this.type=='confirm'?'block':'none');
		$('#msgBox_btnOK').css('display',this.type!='confirm'?'block':'none');
		if(this.type=='confirm'){
			$('#msgBox_btnOK').css('display','block');
		}
		
		globalMask_show();
		$('#msgBox').css({
			'top':($('#msgBox').height()<$(window).height()?$(window).scrollTop()+150:$(window).scrollTop())+'px',
			'left':($(window).width()-$('#msgBox').width())/2+'px',
    		'display':'block'
  		});
  		
  		$('#msgBox_txtInput').focus();
  	}
  	
  	this.Hide=function(){
  		globalMask_hide();
		$('#msgBox').css('display','none');
		this.btnCustom=null;
  	}
  	
  	this.btnConfirm_click=function(){
		alert('adsasd');
  	}
  	
  	this.btnCancel_click=function(){
		this.Hide();
  	}
  	
  	this.btnOK_click=function(){
  		if(this.type=='prompt') 
			this.response=$('#msgBox_txtInput').val();
  		if(this.btnCustom!=null)
			this.btnCustom();
		else
			this.Hide();	
  	}
	
	this.btnCustom;
  	
  	/*Private functions*/
  	this.icon_position_x=function(){
  		switch(this.icon){ 
  			case 'info':return 0;
  			case 'question':return -60;
  			case 'attention':return -120;
  			case 'error':return -180;
  			case 'warning':return -240;
  			case 'prevent':return -300;
  			default: return -360;	//no icon
  		}
  	}
}