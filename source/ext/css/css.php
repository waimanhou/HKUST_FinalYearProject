<?php
# Name:	css.php
# Desc:	Combine and set values to variables in css files,
# 		All the css style MUST output through this css.php filter.

require_once('../../requires.php');
header('Content-type: text/css; charset: UTF-8');

$css=array('main');	//css files list
$style='';
foreach($css as $c)	//combine css file contents
	$style.=file_get_contents($c.'.css')."\n";
for($i=0; $i<max(count($m_colors),count($f_colors)); $i++){	//replace css variables
	$style=isset($m_colors[$i]) ? str_replace("{M_COLOR$i}", $m_colors[$i], $style) : $style;
	$style=isset($f_colors[$i]) ? str_replace("{F_COLOR$i}", $f_colors[$i], $style) : $style;
}
echo $style;
?>