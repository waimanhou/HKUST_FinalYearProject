<?php include_once('ajax/mpages/header.php');?>
<div data-role="page" id="page1">
	<div data-theme="a" data-role="header">
		<a data-role="button" onclick="loadPage('home');" class="ui-btn-left" rel="external">Home</a>
		<a data-role="button" onclick="btnLogout();" class="ui-btn-right" rel="external">Logout</a>
		<h3>
			<?php echo $t->fullname(); ?>
		</h3>
	</div>
	<div data-role="content">
		<table width="400px" height="100%" align="center">
			<tr><td>
				<?php
					if((isset($_GET['t']))&&($_GET['t']=='news')){
						echo 'News Feed';
					}else
						echo 'Wall';
				?>
				
				<div id="chkInHistDiv">
					<p id="subtitle" class="subtitle">Recent Check-In</p>
					<table align="center">
						<tr valign="middle" align="center">
							<td>
								<div id="frdChkBack" class="frdChkHist" onclick="getBackFriendsCheckin();">
									Back
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<p id="checkInHistory"></p>
							</td>
						</tr>
						<tr valign="middle" align="center">
							<td valign="middle">
								<div id="frdChkNext" class="frdChkHist" onclick="getNextFriendsCheckin();">
									Next
								</div>
							</td>
						</tr>
					</table>
					
					
				</div>
			</td></tr>
		</table><br/>
	</div>
<?php include_once('ajax/mpages/footer.php');?>
<script>
	var frdChkSkip=0;
	function getFriendsCheckin(){
		$.post("ajax.php?act=u&f=getFriendsCheckin",{'skip':frdChkSkip},
			function(json){
				var result='';
				if(json!=null){
					for(var i=0;i<json.curl.length;i++){
						result+='<p id="postDate" style="text-decoration:underline;">'+json.curl[i].checkinDate.$date+'</p>'+
							'<a href="#" onclick="browseFrd(\''+json.curl[i].user.userId+'\',\''+json.curl[i].user.name+'\');">'+
							'<img id="profilePic" style="vertical-align:middle;" src="<?php echo profilePicUrl;?>'+json.curl[i].user.profilePic+'" width="25" height="25">'+
							json.curl[i].user.name+'</a> @ '+json.curl[i].venueName+'<br/>';
					}
					if(frdChkSkip==0)
						$('#frdChkBack').hide();
					else{
						$('#frdChkBack').show();
						$('#frdChkNext').show();
					}
					//alert(frdChkSkip+','+json.curl.length);
					if(json.curl.length<5)
						$('#frdChkNext').hide();
					else
						$('#frdChkNext').show();
					$('#checkInHistory').html(result);
					convertDateTime('postDate');
				}
			},'json'
		);
	}
	function getBackFriendsCheckin(){
		frdChkSkip-=5
		getFriendsCheckin();
	}
	function getNextFriendsCheckin(){
		frdChkSkip+=5;
		getFriendsCheckin();
	}
	getFriendsCheckin();
</script>