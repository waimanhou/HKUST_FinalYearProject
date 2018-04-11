<?php
	
	
	//echo CoreFunc::curlGet('/test.json');

	// jSON URL which should be requested
		$json_url = 'http://localhost/fyp/test.json';
		//$json_url = 'http://192.168.1.59:8080/GSN_WS/venue/getVenueByName/25';
		//$json_url = 'http://192.168.1.59:8080/GSN_WS/announcement/insert/90000001/contentAbc/'.time();
		//$json_url = 'http://192.168.1.59:8080/GSN_WS/announcement/getActiveAnnouncement/true';
		//$json_url = 'http://192.168.1.59:8080/GSN_WS/checkin/insert/10000001/3';
		//$json_url = 'http://192.168.1.59:8080/GSN_WS/user/10000001/getCheckin/0/10/true';
		
		$username = 'your_username';  // authentication
		$password = 'your_password';  // authentication
		 
		// jSON String for request
		$json_string = '[your json string here]';
		 
		// Initializing curl
		$ch = curl_init($json_url);
		 
		// Configuring curl options
		$options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERPWD => $username . ":" . $password,   // authentication
		CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
		CURLOPT_POSTFIELDS => $json_string,
		CURLOPT_HTTPGET => true
		);
		 
		// Setting curl options
		curl_setopt_array( $ch, $options );
		 
		// Getting results
		$result =  '{"dummy":['.curl_exec($ch).']}'; // Getting jSON result string
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if($http_status==200)
			echo $result;
		
		curl_close($ch);
		//echo '<br/><br/>';
		//echo time();
		
		//$obj = json_decode($result,true);
		/*
		foreach($obj['users'] as $key=>$val){ 
			echo 'username='.$val['username'].',content='.$val['content'].'<br/>';
		}
		*/
		/*
		foreach($obj['dummy'] as $key=>$val){ 
			echo 'adminId='.$val['announceBy']['adminId'].',';
			echo 'email='.$val['announceBy']['email'].',';
			echo 'content='.$val['content'].',';
			echo 'createDate='.$val['createDate']['$date'].',';
			
			//$timestamp=strtotime($val['createDate']['$date']);
			//echo date('l dS \o\f F Y h:i:s A', $timestamp).',';
			
			echo 'effectDate='.$val['effectDate']['$date'].',';
			echo 'announceId='.$val['announceId'].'<br/>';
		}
		*/
		//print_r($obj[0]->{'username'});
?>

<p id="demo"></p>

<script>
var d = new Date();
var x = document.getElementById("demo");
x.innerHTML=d.getTimezoneOffset();
</script>


<form action="uploadTest.php" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>

<form enctype="multipart/form-data" action="uploadTest.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
Choose a file to upload: <input name="uploadedfile" type="file" /><br />
<input type="submit" value="Upload File" />
</form>
