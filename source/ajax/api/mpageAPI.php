<?php #pageAPI.php
function GET($g){
	return CoreFunc::GET($g);
}

$p = new mPage();
if(GET('f')=='index'){ echo CoreFunc::loggedIn()?$p->home():$p->index(); }

else if(GET('f')=='event') echo $p->event(GET('t'),GET('m'),GET('y'));
else if(GET('f')=='calendar') echo $p->calendar(GET('c'),GET('m'),GET('y'));

else if(GET('f')=='post') echo $p->post();


else if(GET('f')=='notification') echo $p->notification();



else if(GET('f')=='test') echo $p->test();
else if(GET('f')=='login') echo $p->login();
else if(GET('f')=='home') echo $p->home();
else if(GET('f')=='checkin') echo $p->checkIn();
else if(GET('f')=='account') echo $p->account();
else if(GET('f')=='profile') echo $p->profile();
else if(GET('f')=='friend') echo $p->friend();
else if(GET('f')=='wall') echo $p->wall();
else echo 'Invalid action in pageAPI.php.';
?>