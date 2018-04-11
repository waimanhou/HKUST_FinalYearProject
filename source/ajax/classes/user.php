<?php 
# Name:	user.php
# Desc:	A class, it provides all of the user functions.

# Class Description (Page)
#------------------------------------------------------
# +  __construct()
# +' login():string
# +' is_exists():bool
# +' curr_user():User
# +  logout():string

# get user's data : /user/getUserByUsername/{uname} 

class User{	
	private $id;				//string
	private $username;			//string
	private $fname;				//string
	private $lname;				//string
	private $salutation;		//string enum('Miss,Ms,Mrs,Mr,Dr,Prof')
	private $dept;				//string
	private $email;				//string
	private $tel;				//string, may be null if N/A
	private $mobile;			//string, may be null if N/A
	private $reg_date;			//date
	private $address;			//string
	private $profilePic;		//string
	private $aboutMe;
	private $title;
	private $gender;
	private $dateOfBirth;
	private $emailBackup;
	private $homePhone;
	private $officePhone;
	private $mobilePhone;
	private $wallId;
	
	
	//Non-Static functions-------------------------------------------------------------------------------------------
	function __construct($username){
		//[real_connection]
		$result='{"curl":['.CoreFunc::curlGet('/user/getUserByUsername/'.$username).']}';
		//$result='{"curl":['.CoreFunc::curlGet('/user.json').']}';
		$obj = json_decode($result,true);
		foreach($obj['curl'] as $key=>$val){ 
			$this->profilePic=$val['profilePic'];
			$this->id=$val['userId'];
			
			$this->title=$val['title'];
			$this->fname=$val['firstName'];
			$this->lname=$val['lastName'];
			$this->gender=$val['gender'];
			$this->dateOfBirth=$val['dateOfBirth'];
			$this->email=$val['contact']['emailAddress'];
			
			$this->wallId=$val['wallId'];

			if(array_key_exists('aboutMe',$val))
				$this->aboutme=$val['aboutMe'];
				
			if(array_key_exists('home_address',$val))
				$this->address=$val['home_address'][0];

			if(array_key_exists('emailBackup',$val['contact']))
				$this->email=$val['contact']['emailBackup'];
				
			if(array_key_exists('homePhone',$val['contact']))
				$this->email=$val['contact']['homePhone'];
			
			if(array_key_exists('officePhone',$val['contact']))
				$this->email=$val['contact']['officePhone'];
			
			if(array_key_exists('mobilePhone',$val['contact']))
				$this->email=$val['contact']['mobilePhone'];
				
		}
		
	}
	
	public function id(){
		return $this->id;
	}
	
	public function username(){
		return $_SESSION['username'];
	}
	
	public function fname(){
		return $this->fname;
	}
	
	public function lname(){
		return $this->lname;
	}
	
	public function fullname(){
		return $this->fname.' '.$this->lname;
	}
	
	public function name(){
		return $this->fname . $this->lname;
	}
	
	public function get_salutation(){
		return $this->salutation;
	}
	
	public function get_dept(){
		return $this->dept;
	}
	
	public function get_email(){
		return $this->email;
	}
	
	public function get_tel(){
		return ($this->tel==null)?'Nil':$this->tel;
	}
	
	public function get_mobile(){
		return ($this->mobile==null)?'Nil':$this->mobile;
	}
	
	public function get_reg_date(){
		return $this->reg_date;
	}
	
	public function get_address(){
		return $this->address;
	}
	
	public function get_profilePic(){
		return $this->profilePic;
	}
	
	public function get_wallId(){
		return $this->wallId;
	}
	
	public function getWallPost($uid,$skip){
		$result=CoreFunc::curlGet('/user/getWallPost/'.$uid.'?before=false&asc=false&skip='.$skip);
		return ($result=='0')?'':'{"curl":['.$result.']}';
	}
	
	public function getWallPostByWallId($wallId,$skip){
		$result=CoreFunc::curlGet('/post/getWallPost/'.$wallId.'?asc=false&skip='.$skip);
		return ($result=='0')?'':'{"curl":['.$result.']}';
	}
	
	public static function getPostById($pid){
		$result=CoreFunc::curlGet('/post/getPostById/'.$pid);
		return ($result=='0')?'':'{"curl":['.$result.']}';
	}
	
	public function getNewsFeedPost($uid,$skip){
		$result=CoreFunc::curlGet('/user/getNewsFeedPost/'.$uid.'?before=false&asc=false&skip='.$skip);
		return ($result=='0')?'':'{"curl":['.$result.']}';
	}
	
	public function insertOwnWall($content){
		$result=CoreFunc::curlGet('/post/insertOwnWall/'.$this->id.'/'.$content);
		return $result;
	}
	
	public function insertFrdWall($fId,$content){
		$result=CoreFunc::curlGet('/post/insertFrdWall/'.$this->id.'/'.$fId.'/'.$content);
		return $result;
	}
	
	public function insertEvtWall($eId,$authorComment){
		$result=CoreFunc::curlGet('/post/insertEvtWall/'.$this->id.'/'.$eId.'/'.$authorComment);
		return $result;
	}
	
	public function replyPost($pid,$content){
		$result=CoreFunc::curlGet('/post/addReply/'.$this->id.'/'.$pid.'/'.$content);
		return $result;
	}
	
	public function deletePost($pid){
		$result=CoreFunc::curlGet('/post/delete/'.$pid);
		return $result;
	}
	
	public function deleteReply($replyId){
		$result=CoreFunc::curlGet('/post/deleteReply/'.$replyId);
		return $result;
	}
	
	public function change_my_info($info){
		#/updateField/{uId}?fieldname=value1&fieldname=value2&.....
		$result=CoreFunc::curlGet('/user/updateField/'.$this->id.$info);
		if($result=='0') //Email address exists.
			return false;
		else{
			return true;
		}
	}

	public static function sendPasswordMail($newPassword, $email) {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64";
		$mail->Username = "fyp.gsn@gmail.com";
		$mail->Password = "Password.";
		$mail->From = "fyp.gsn@gmail.com";
		$mail->FromName = "Geo-Social Network";
		$mail->Subject = "Password Reset";
		$bd = "Dear User,<br /><br />  Your account password in Geo-Social Network is reset. Please log on the system with the following password and update the password in your personal profile.<br /><br />New Password: ".($newPassword)."<br /><br />Please do not reply to this email.<br /><br />Yours faithfully,<br />Geo-Social Network";
		$mail->Body = $bd;    
		$mail->IsHTML(true);

		$mail->AddAddress($email, $email);

		return ($mail->Send());
	}
	
	public static function resetPassword($uname,$email){
		//user/resetPassword/{uname}/{email}
		$result=CoreFunc::curlGet('/user/resetPassword/'.$uname.'/'.$email);
		// 0 = Username and Email address not match or Username not correct or Email address not correct
		// else return the new Password
		if($result!='0'){
			if (User::sendPasswordMail($result, $email))
				return 'success';
			else 
				return 'fail';
		}else
			return $result;
		
		//return $result;
	}
	
	
	
	public function change_my_pwd($oldPwd,$newPwd){
		//[real_connection]
		$result=CoreFunc::curlGet('/user/checkLogin/'.$_SESSION['username'].'/'.$oldPwd);
		//$result=CoreFunc::curlGet('/test.json');
		if($result!='3') //Wrong username or password.
			return false;
		else{//correst username and password
			//[real_connection]
			$result=CoreFunc::curlGet('/user/updateField/'.$this->id.'?pswd='.$newPwd);
			return $result;
		}
	}
	
	public function changeProfilePic($file){
		$allowedExts = array("jpg", "jpeg", "gif", "png");
		$extension = end(explode(".", $file["name"]));
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		if ((($file["type"] == "image/gif")
		|| ($file["type"] == "image/jpeg")
		|| ($file["type"] == "image/png")
		|| ($file["type"] == "image/pjpeg"))
		&& ($file["size"] < 20000000)
		&& in_array($extension, $allowedExts)){
			if ($file["error"] > 0){
				return "Return Code: " . $file["error"] . "<br>";
			}else{
				/*
				echo "Upload: " . $file["name"] . "<br>";
				echo "Type: " . $file["type"] . "<br>";
				echo "Size: " . ($file["size"] / 1024) . " kB<br>";
				echo "Temp file: " . $file["tmp_name"] . "<br>";
				*/
				move_uploaded_file($file["tmp_name"],
				uploadProfilePicUrl . $_SESSION['username'] . "." . $ext);
				//echo "Stored in: " . "upload/" . $file["name"];
				
				//[real_connection]
				$result=CoreFunc::curlGet('/user/updateField/'.$this->id.'?profilePic='.$_SESSION['username'] . "." . $ext);
				
				$this->profilePic=$_SESSION['username'] . "." . $ext;
				return $_SESSION['username'] . "." . $ext;
		   }
		}else{
			return "Invalid file";
		}
	}
	
	public function sendFriendRequest($fid){
		$result=CoreFunc::curlGet('/user/sendFriendRequest/'.$this->id.'/'.$fid);
		//0=fail 1=succeed 2=friend already 3=sent before
		return $result;
	}
	
	public function friendRequest($requestId,$decision){
		#FriendRequest: /friendRequest
		# 0 = Fail, 1 = Succeed
		#replyrequestWS: @Path("/replyRequest/{requestId}/{accept: boolean}"
		#/friendRequest/replyRequest/{requestId}/{accept}
		if($decision=='accept')
			$decision='true';
		else
			$decision='false';
		$result=CoreFunc::curlGet('/friendRequest/replyRequest/'.$requestId.'/'.$decision);
		return $result;
	}
	
	public function isFriendWith($uid){
		$result=CoreFunc::curlGet('/user/isFriendWith/'.$this->id.'/'.$uid);
		return $result;
	}
	
	public function getFriendRequest(){
		$result=CoreFunc::curlGet('/user/getRequest/'.$this->id.'/friend');
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public function unFriend($fid){
		$result=CoreFunc::curlGet('/user/unFriend/'.$this->id.'/'.$fid);
		return $result;
	}
	
	public function getNotification(){
		$result=CoreFunc::curlGet('/user/getNotification/'.$this->id.'?asc=false');
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public function resetNotification(){
		return CoreFunc::curlGet('/user/resetNotification/'.$this->id);
	}
	
	public function getVenueByLoc($longitude,$latitude){
		$result=CoreFunc::curlGet('/venue/getVenueByLoc/'.$longitude.','.$latitude);
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public static function getVenueByName($name){
		$result=CoreFunc::curlGet('/venue/getVenueByName/'.$name);
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public static function getVenueByNameExact($name){
		$result=CoreFunc::curlGet('/venue/getVenueByName/'.$name.'?exact=true');
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public static function getVenueById($vid){
		$result=CoreFunc::curlGet('/venue/getVenueById/'.$vid);
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public function getAllVenue(){
		$result='{"curl":['.CoreFunc::curlGet('/venue/getAllVenue').']}';	
		return $result;
	}
	
	public function checkIn($venueId,$longitude,$latitude){
		$result=CoreFunc::curlGet('/checkin/insert/'.$this->id.'/'.$venueId.'/'.$longitude.','.$latitude);
		return $result;
	}
	
	public function checkInByVid($venueId){
		$result=User::getVenueById($venueId);
		$obj = json_decode($result,true);
		$longitude='';
		$latitude='';
		foreach($obj['curl'] as $key=>$val){
			$longitude=$val['location'][0];
			$latitude=$val['location'][1];
		}
		return $this->checkIn($venueId,$longitude,$latitude);
		//return $latitude;
	}
	
	public function checkInHistory($id){
		$result=CoreFunc::curlGet('/checkin/getCheckinByUserId/'.$id.'?asc=false');
		return ($result=='0')?'':('{"curl":['.$result.']}'); 
	}
	
	public function getUserCheckinCnt($vid){
		$result=CoreFunc::curlGet('/venue/getUserCheckinCnt/'.$vid.'/'.$this->id);
		return $result;
	}
	
	public function getFriendsCheckin($skip){
		$result=CoreFunc::curlGet('/user/getFriendsCheckin/'.$this->id.'?limit=5&asc=false&skip='.$skip);
		return ($result=='0')?'':('{"curl":['.$result.']}'); 
	}
	
	public function getFrdCheckinAtVenue($vId){
		//getFrdCheckinAtVenueWS: @Path("/getFrdCheckinAtVenue/{vId}/{uId}"), Optional: skip: int, limit: int
		$result=CoreFunc::curlGet('/venue/getFrdCheckinAtVenue/'.$vId.'/'.$this->id.'?limit=5');
		return ($result=='0')?'':('{"curl":['.$result.']}'); 
	}
	
	public function getNearestFriends($longitude,$latitude){
		// /user/getNearestFriends/4/114.21591439999999,22.3180521/0
		$result=CoreFunc::curlGet('/user/getAllNearestFriends/'.$this->id.'/'.$longitude.','.$latitude);
		return ($result=='0')?'':('{"curl":['.$result.']}'); 
	}
	
	public function getFriendsInRange($longitude,$latitude){
		//getFriendsInRangeWS: @Path("/getFriendsInRange/{uId}/{loc: String}"), Optional: asc: boolean
		$result=CoreFunc::curlGet('/user/getFriendsInRange/'.$this->id.'/'.$longitude.','.$latitude.'?asc=false');
		return ($result=='')?'':('{"curl":['.$result.']}'); 
	}
	
	public function createEvent($title,$startDate,$endDate,$venue,$applyDate,$eventContent){
		//insertEventWS: @Path("/insert/{uId}/{title}/{startDate: long}/{endDate: long}/{venue}/{applyDate: long}/{eventContent}")
		$result=CoreFunc::curlGet('/event/insert/'.$this->id.'/'.$title.'/'.$startDate.'/'.$endDate.'/'.$venue.'/'.$applyDate.'/'.$eventContent);
		return $result;
	}
	
	public static function searchEvent($keyword){
		//searchEventWS: @Path("/searchEvent/{keyword}")
		$result=CoreFunc::curlGet('/event/searchEvent/'.$keyword);
		return ($result=='0')?'':('{"curl":['.$result.']}'); 
	}
	
	public function updateEvent($eId,$title,$startDate,$endDate,$venue,$applyDate,$eventContent){
		//updateEventWS: @Path("/update/{eId}/{title}/{startDate: long}/{endDate: long}/{venue}/{applyDate: long}/{eventContent}")
		$result=CoreFunc::curlGet('/event/update/'.$eId.'/'.$title.'/'.$startDate.'/'.$endDate.'/'.$venue.'/'.$applyDate.'/'.$eventContent);
		return $result;
	}
	
	public function deleteEvent($eId){
		$result=CoreFunc::curlGet('/event/delete/'.$eId);
		return $result;
	}
	
	public function getEvent($uid){
		$result=CoreFunc::curlGet('/user/getEvent/'.$uid.'?asc=false&limit=100');
		return ($result=='0')?'':('{"curl":['.$result.']}'); 
	}
	
	public function getEventById($evtId){
		$result=CoreFunc::curlGet('/event/getEventById/'.$evtId);
		return ($result=='0')?'':('{"curl":['.$result.']}'); 
	}
	
	public function sendEventRequest($eID,$fID){
		//sendEventRequestWS: @Path("/sendEventRequest/{uId}/{eID}/{fID}"), Optional: requestMessage
		$result=CoreFunc::curlGet('/user/sendEventRequest/'.$this->id().'/'.$eID.'/'.$fID);
		return $result;
	}
	
	public function getEventRequest(){
		$result=CoreFunc::curlGet('/user/getRequest/'.$this->id.'/event');
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public function getRequestByUserId($eId){
		$result=CoreFunc::curlGet('/event/getRequestByUserId/'.$eId.'/'.$this->id());
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public function replyEventRequest($requestId,$decision){
		if($decision=='accept')
			$decision='true';
		else
			$decision='false';
		$result=CoreFunc::curlGet('/eventRequest/replyRequest/'.$requestId.'/'.$decision);
		return $result;
	}
	
	public function logout(){ 
		unset($_SESSION['username']); 
		return true;
	}
		
	//Static functions-----------------------------------------------------------------------------------------------
	public static function login($strID, $strPWD){
		//[real_connection]
		$result=CoreFunc::curlGet('/user/checkLogin/'.$strID.'/'.$strPWD);
		//$result=CoreFunc::curlGet('/test.json');
		if($result=='3')
			$_SESSION['username']=$strID;
		return $result;
	}
	
	public static function register($info){
		//[real_connection]
		$result=CoreFunc::curlGet('/user/insert'.$info);
		if($result=='0')	
			return 'Fail';
		else if($result=='1')
			return 'Username exists.';
		else if($result=='2')
			return 'Email address exists.';
		else
			return 'Succeed';
	}
	
	public static function exists($strID){
		if(CoreFunc::isValuable($strID)){
			return true;
		}return false;
	}
	
	public static function disable($strID){
		if(CoreFunc::isValuable($strID)){
			return true;
		}return false;
	}
	
	public static function enable($strID){
		if(CoreFunc::isValuable($strID)){
			return true;
		}return false;
	}
	
	public static function change_info(){
		
	}
	public static function searchUser($info){
		$result=CoreFunc::curlGet('/user/searchUser/'.$info);
		if($result!='0'){
			return '{"curl":['.$result.']}';
		}else
			return '';
	}
	
	public static function get_salutation_list(){
		
	}
	
	public static function get_user_info($strID){
		if(CoreFunc::isValuable($strID)){
			return true;
		}return false;
	}
	
	public static function reset_user_pwd($strID, $strPWD){
		
	}

	public static function count_user(){ //For staff only.
		
	}
	
	public static function get_fullNameById($uid){//find fullname by uid
		return CoreFunc::curlGet('/user/getFullName/'.$uid);
	}
	
	public static function get_userById($uid){
		$result=CoreFunc::curlGet('/user/getUserById/'.$uid);
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public static function curr(){
		//if(isset($_SESSION['username'])&&!empty($_SESSION['username'])) $u=new User($_SESSION['user_id']);
		if(isset($_SESSION['username'])&&!empty($_SESSION['username'])) $u=new User($_SESSION['username']);
		if(@is_object($u)) return $u;
		return null;
	}
	
	public static function getActiveAnnouncement(){
		$result=CoreFunc::curlGet('/announcement/getActiveAnnouncement?asc=true');
		return ($result=='0')?'':('{"curl":['.$result.']}');
	}
	
	public static function getTimeLimit(){
		return CoreFunc::curlGet('/parameter/getTimeLimit');
	}
	
	public static function getRadiusInMeter(){
		return floor(CoreFunc::curlGet('/parameter/getRadius')*111.2*1000);
	}
}
?>