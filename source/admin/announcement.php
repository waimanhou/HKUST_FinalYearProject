<?php
require_once('adminFunc.php');

session_start();
if(!isset($_SESSION['adminUsername'])||empty($_SESSION['adminUsername']))
	header( 'Location: index.php' ) ;
	
function getYearRange($intRange){
	$y=date('Y');
	return array('min'=>$y,'max'=>$y+$intRange);
}

$months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
$d='<option value="-">Date</option>';
$m='<option value="-">Month</option>';
$y='<option value="-">Year</option>';
$hr='<option value="-">Hour</option>';
$min='<option value="-">Minute</option>';
$yRange=getYearRange(5);
for($i=$yRange['min'];$i<=$yRange['max'];$i++) $y.='<option value="'.$i.'">'.$i.'</option>';
for($i=0;$i<12;$i++) $m.='<option value="'.($i+1).'">'.$months[$i].'</option>';
for($i=1;$i<32;$i++) $d.='<option value="'.$i.'">'.$i.'</option>';
for($i=0;$i<24;$i++){
	if($i<10)
		$time='0'.$i;
	else
		$time=$i;
	$hr.='<option value="'.$time.'">'.$time.'</option>';
}
for($i=0;$i<60;$i++){
	if($i<10)
		$time='0'.$i;
	else
		$time=$i;
	$min.='<option value="'.$time.'">'.$time.'</option>';
}
?>
<html>
<head>
<script type="text/javascript" src="../ext/js/plugin/jQuery.js"></script>
<script type="text/javascript">
	function CalDays(intYear,intMonth){
		switch(parseInt(intMonth)){
			case 1:case 3: case 5: case 7: case 8: case 10: case 12: return 31;
			case 4:case 6: case 9: case 11: return 30;
			case 2:return (intYear=='-'||isLeapYear(intYear))?29:28;
		}
	}

	function isLeapYear(intYear){
		var intYear=parseInt(intYear);
		return (intYear%4==0&&intYear%100!=0)||(intYear%400==0)?true:false;
	}
	
	function birthday_change(){
		var options='<option value="-">Date</option>';
		var lastDate=31;
		var y=$('#selYear').val();
		var m=$('#selMonth').val();
		var d=$('#selDate').val();
		if(m!='-')lastDate=CalDays(y,m);
		for(var i=1;i<=lastDate;i++) options+='<option value="'+i+'">'+i+'</option>';
		$('#selDate').html(options);
	}
	
	function addAnnouncement(){		
		a=$("input:[name=effectTime]:radio:checked").val();
		if(a=='now'){
			$.post("admin.php?f=addAnnouncement",{ 
					"content":escape($('#content').val()),
					"ms":'now'},
					function(data){
						result=data;
						if(result=='fail')
							$("#errMsg").html('*Error.');
						else
							$('#main').html(result);
					},'html'
				);
		}else{
			if(($('#content').val()!='')&&($('#selHour').val()!='-')&&($('#selMin').val()!='-')&&
				($('#selDate').val()!='-')&&($('#selMonth').val()!='-')&&($('#selYear').val()!='-')){
				var date=new Date($('#selYear').val(),$('#selMonth').val(),$('#selDate').val(),$('#selHour').val(),$('#selMin').val(),0,0);
				//alert(date.getTime()/1000);
				effectTime=date.getTime()/1000;
				$.post("admin.php?f=addAnnouncement",{ 
					"content":escape($('#content').val()),
					"ms":date.getTime()/1000},
					function(data){
						result=data;
						
						if(result=='fail')
							$("#errMsg").html('*Error.');
						else
							$('#main').html(result);
					},'html'
				);
			}else{
				$('#errMsg').html('*Please fill in all information.');
			}
		}
	}
	
	function convertDateTime(tid,dateTime){
		var myDate = new Date(dateTime);
		myDate.setTime(myDate.getTime() - myDate.getTimezoneOffset() * 60 * 1000);
		$('#'+tid).html(myDate.toUTCString());
	}
</script>
</head>
<body id="main">
<a href="home.php">HOME</a><br/>
Add Announcement<br/>
<b>Content</b><br/>
<textarea id="content" style="width:570px; height:60px;"></textarea><br/>
<input type="radio" id="effectTime" name="effectTime" value = "-"><b>Effect Date and Time</b>
<select id="selYear" class=".inputSel" onchange="birthday_change();"><?php echo $y; ?></select>
<select id="selMonth" class=".inputSel" onchange="birthday_change();"><?php echo $m; ?></select>
<select id="selDate" class=".inputSel"><?php echo $d; ?></select>
<select id="selHour" class=".inputSel"><?php echo $hr; ?></select> :
<select id="selMin" class=".inputSel"><?php echo $min; ?></select>
<br/>
<input type="radio" id="effectTime" name="effectTime" value = "now"><b>Now</b><br/>
<input type="button" value="Submit" onclick="addAnnouncement();"><br/>
<span id="errMsg" style="font-weight:bold;color:#FF0000;"></span>

<br/>
Announcement<br/>
<table>
<tr><th>announceId</th><th>adminId</th><th>email</th><th>content</th><th>createDate</th><th>effectDate</th><th>deleteDate</th><th>Delete</th><th>Edit</th></tr>
<?php
	//[real_connection]
	$curl=curlGet('/announcement/getAllAnnouncement');
	if($curl!='0'){
		$result='{"curl":['.$curl.']}';
		$obj = json_decode($result,true);
		$i=1;
		foreach($obj['curl'] as $key=>$val){ 
			
				echo '<tr>';
			echo '<td>'.$val['announceId'].'</td>';
			echo '<td>'.$val['announceBy']['adminId'].'</td>';
			echo '<td>'.$val['announceBy']['email'].'</td>';
			echo '<td>'.$val['content'].'</td>';
			echo '<td id="createDate'.$i.'">'.$val['createDate']['$date'].'</td>';
			echo '<td id="effectDate'.$i.'">'.$val['effectDate']['$date'].'</td>';
			if(array_key_exists('deleted',$val)){
				echo '<td id="deleteDate'.$i.'">'.$val['deleted']['deleteDate']['$date'].'</td>';//deleted by
			}else{
				echo '<td></td>';
			}
			
			if(array_key_exists('deleted',$val)){
				echo '<td style="color:red;">Deleted</td>';
			}else{
				echo '<td><a href="admin.php?f=deleteAnnocunce&announceId='.$val['announceId'].'">Delete</a></td>';
			}
			echo '<td><a href="#">Edit</a></td>';
			echo '</tr>';
			$i++;
		}	
	}

	
?>
</table>
</body>
</html>

<script type="text/javascript">
	//convertDateTime('createDate1',"2013-02-06T06:46:11.019Z");
	<?php
		$i=1;
		foreach($obj['curl'] as $key=>$val){ 
			echo 'convertDateTime("createDate'.$i.'","'.$val['createDate']['$date'].'");';
			echo 'convertDateTime("effectDate'.$i.'","'.$val['effectDate']['$date'].'");';
			if(array_key_exists('deleted',$val)){
				echo 'convertDateTime("deleteDate'.$i.'","'.$val['deleted']['deleteDate']['$date'].'");';
			}
			$i++;
		}
	?>
</script>
