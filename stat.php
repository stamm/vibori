<?php

$m = new Mongo();
$db = $m->logs->vibori;

$step = 1;
for ($i=0; $i <= 100; $i += $step)
{
	$count = $db->count(array('count' => array(
		'$gt' => $i,
		'$lte' => $i + $step,
	)));

echo sprintf("%01.1f", $i) . ' : ' . $count . PHP_EOL;
}
#echo $i + 0.5;