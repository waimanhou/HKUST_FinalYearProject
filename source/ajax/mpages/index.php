<?php include_once('ajax/mpages/header.php');?>
<div data-role="page" id="page1">
	<div data-theme="a" data-role="header">
		<a data-role="button" href="#" onclick="loadPage('login');" class="ui-btn-right">Login</a>
		<!--a data-role="button" href="ajax/mpages/register.php" class="ui-btn-left">Sign Up</a>-->
		<h3>
			<?php echo SITE_NAME; ?>
		</h3>
	</div>
	<div data-role="content">
		<div style="text-align:center;">
            <img style="width: 100%;" src="images/banner.png">
        </div><br/>
		<b>Announcements</b><br/>
		<?php
			$result=CoreFunc::curlGet('/announcement/getActiveAnnouncement?asc=true');
			//echo $result;
			if($result!='0'){
				$result='{"curl":['.$result.']}';
				$obj = json_decode($result,true);
				echo '<div data-role="collapsible-set" class="ui-collapsible-set">';
				foreach($obj['curl'] as $key=>$val){ 
					echo '<div data-role="collapsible" data-state="collapsed" data-collapsed="true">';
					echo '<h6><span style="font-size:10px;">'.$val['content'].
						'</span><span class="ui-li-aside" style="font-size:8px;" id="postDate">'.
						$val['createDate']['$date'].'</span></h6>';
					echo '<p style="font-size:10px;">'.$val['content'].'</p>';
					echo '</div>';
				}
				echo '</div>';
			}else{
				echo 'No announcement.';
			}
		?>
	</div>

<?php include_once('ajax/mpages/footer.php');?>