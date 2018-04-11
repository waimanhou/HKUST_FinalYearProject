<?php #pageAPI.php
function GET($g){
	return CoreFunc::GET($g);
}

$p = new Page();
if(GET('f')=='index'){ echo CoreFunc::loggedIn()?$p->home():$p->index(); }
else if(GET('f')=='home') echo $p->home(GET('t'));
else if(GET('f')=='event') echo $p->event(GET('t'),GET('m'),GET('y'));
else if(GET('f')=='calendar') echo $p->calendar(GET('c'),GET('m'),GET('y'));
else if(GET('f')=='wall') echo $p->wall();
else if(GET('f')=='post') echo $p->post();
else if(GET('f')=='friend') echo $p->friend();
else if(GET('f')=='checkin') echo $p->checkIn();
else if(GET('f')=='notification') echo $p->notification();
else if(GET('f')=='account') echo $p->account();
else if(GET('f')=='profile') echo $p->profile();
else if(GET('f')=='search') echo $p->search();
else if(GET('f')=='venue') echo $p->venue();
else if(GET('f')=='test') echo $p->test();
else echo 'Invalid action in pageAPI.php.';
?>