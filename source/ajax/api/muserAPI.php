<?php
$p = new mPage();

if($_GET['f']=='login'){
	$result=User::login($_POST['id'],$_POST['pwd']);
	if($result=='3')
		echo $p->home();
	else
		echo $result;
}else if($_GET['f']=='logout'){
	$t=User::curr();
	if(isset($t)&&CoreFunc::loggedIn())
		echo $t->logout()?$p->index():'';
	else
		echo $p->index();
}

?>