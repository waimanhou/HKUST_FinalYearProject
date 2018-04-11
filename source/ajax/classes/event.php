<?php
class Event{
	public function drawCalendar($intMonth=null,$intYear=null){
		$days=array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

		$intMonth=CoreFunc::valuable($intMonth)&&$intMonth>0&&$intMonth<13?$intMonth:date('m');
		$intYear=CoreFunc::valuable($intYear)&&strlen($intYear)==4?$intYear:date('Y');
		$monthStr=date('F',mktime(0,0,0,$intMonth,1,$intYear));
		$start_day=date('w',mktime(0,0,0,$intMonth,1,$intYear));
		$total_days=date('t',mktime(0,0,0,$intMonth,1,$intYear));
		$today=$intMonth==date('m')&&$intYear==date('Y')?date('j'):-1;
		$lstMonth='';
		$lstYear='';
		
		//Month, Year in <table>
		$month_html='
		<tr>
			<input type="hidden" id="hidden_intMonth" value="'.$intMonth.'"/>
			<input type="hidden" id="hidden_intYear" value="'.$intYear.'"/>
			<td class="btnPMonth inputBtn" onclick="eventGoTo_click('.($intMonth-1>0?$intMonth-1:12).','.($intMonth-1>0?$intYear:$intYear-1).')">&lt;</td>
			<td class="onMonth" colspan="5">'.$monthStr.' '.$intYear.'</td>
			<td class="btnNMonth inputBtn" onclick="eventGoTo_click('.($intMonth+1>12?1:$intMonth+1).','.($intMonth+1>12?$intYear+1:$intYear).')">&gt;</td>
		</tr>
		';
		
		//Header of Days in <table>
		$headers_html='<tr class="dayHeader">';
		for($i=0;$i<count($days);$i++) $headers_html.='<td>'.$days[$i].'</td>';
		$headers_html.='</tr>';
		
		//Days field in <table>
		$days_html='';	//html of day fields
		$days_count=0;	//count num of days printed to calcuate the row
		$days_html.='<tr>';
		for($i=0;$i<$start_day;$i++){	//pervious month days
			$days_html.='<td class="a_day outBound '.($days_count==0?'st':'').'" onclick="eventGoTo_click('.($intMonth-1>0?$intMonth-1:12).','.($intMonth-1>0?$intYear:$intYear-1).');"><div></div></td>';
			$days_count++;
		}
		for($i=1;$i<=$total_days;$i++){	//current month days
			$days_html.='<td id="'.$i.'" class="'.($i==$today?'a_day today':'a_day').' '.($days_count%7==0||$days_count==0?'st':'').'"><div><p class="date"><a href="#" onclick="showCreateEventForm('.$intYear.','.$intMonth.','.$i.');">'.$i.'</a><span id="d'.$i.'"></span></p></div></td>';
			$days_count++;
			$days_html.=$days_count%7==0?'</tr><tr>':'';
		}
		while($days_count%7!=0){	//next month days
			$days_html.='<td class="a_day outBound" onclick="eventGoTo_click('.($intMonth+1<13?$intMonth+1:1).','.($intMonth+1<13?$intYear:$intYear+1).');"><div></div></td>';
			$days_count++;
		}
		$days_html.='</tr>';
		
		//Controls in <table>
		for($i=1;$i<13;$i++){ 
			$monthStr=date("F",mktime(0,0,0,$i,1,2000));
			$lstMonth.='<option value="'.$i.'" '.($i==$intMonth?'selected':'').'>'.$monthStr.'</option>';
		}
		for($i=date('Y')-5;$i<date('Y')+6;$i++) $lstYear.='<option '.($i==$intYear?'selected':'').'>'.$i.'</option>';
		
		$ctrl_html='
		<tr>
			<td colspan="6" style="text-align:left">
				<select id="calender_lstMonth">'.$lstMonth.'</select>
				<select id="calender_lstYear">'.$lstYear.'</select>
				<input class="inputBtn" type="button" value="Go" onclick="eventGoTo_click($(\'#calender_lstMonth\').val(),$(\'#calender_lstYear\').val());"/>
			</td>
			<td><div class="inputBtn unSel" onclick="eventGoTo_click();">Today</div></td>
		</tr>
		';
	
		return '<input type="hidden" id="hidden_month" value="'.$intMonth.'"/><table id="calendar" cellspacing="0">'.$month_html.$headers_html.$days_html.$ctrl_html.'</table>';
	}
	
	public function drawList(){	//only show coming up events
		//tmp for demo
		$html='
		<li id="evt0" class="clickable" onclick="event_showInfo(this.id);">
			<p class="title">This is the event title.</p>
			<p class="desc" style="float:right;">10 Dec 2012</p>
			<input id="evt0_hdnInfo" type="hidden" value="This is the event title.|This is the description of the eventThis is the description of the eventThis is the description of the eventThis is the description of the eventThis is the description of the eventThis is the description of the event|10 Dec 2012|10 Dec 2012|1 Dec 2012|3 Dec 2012|50" />
			<input id="evt0_comments" type="hidden" value="..." />
		</li>
		<li id="evt1" class="clickable" onclick="event_showInfo(this.id);">
			<p class="title">Good Luck.</p>
			<p class="desc" style="float:right;">12 Dec 2012</p>
			<input id="evt1_hdnInfo" type="hidden" value="Good Luck.|This is the description of the event|12 Dec 2012|14 Dec 2012|1 Dec 2012|3 Dec 2012|50" />
			<input id="evt1_comments" type="hidden" value="..." />
		</li>
		<li id="evt2" class="clickable" onclick="event_showInfo(this.id);">
			<p class="title">Comp3711 is dead.</p>
			<p class="desc" style="float:right;">28 Dec 2012</p>
			<input id="evt2_hdnInfo" type="hidden" value="Comp3711 is dead.|This is the description of the event|8 Dec 2012|8 Dec 2012|1 Dec 2012|3 Dec 2012|50" />
			<input id="evt2_comments" type="hidden" value="..." />
		</li>
		<li id="evt3" class="clickable" onclick="event_showInfo(this.id);">
			<p class="title">BB kill.</p>
			<p class="desc" style="float:right;">10 Jan 2013</p>
			<input id="evt3_hdnInfo" type="hidden" value="BB kill.|Barbecue@UST|10 Jan 2013|10 Jan 2013|7:00PM|9:00PM|50" />
			<input id="evt3_comments" type="hidden" value="..." />
		</li>
		';
		
		return '<p class="topic">Coming Up Events</p><ul id="evt_list">'.$html.'</ul>';
	}
}
?>