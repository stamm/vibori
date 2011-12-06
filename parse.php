<?php

$m = new Mongo();
$db = $m->logs->vibori;



function getLinks($url, $array = array())
{
	$html = file_get_contents($url);
	$html = iconv('cp1251', 'utf8', $html);
#	echo $html;
// <a style="TEXT-DECORATION: none" href="http://www.vybory.izbirkom.ru/region/region/izbirkom?action=show&amp;tvd=1001000882951&amp;vrn=1001000882950&amp;region=0&amp;global=1&amp;sub_region=0&amp;prver=0&amp;pronetvd=null&vibid=1001000883237&type=225">Томская область</a>
	preg_match_all('~<a style="TEXT-DECORATION: none" href="(http://www\.vybory\.izbirkom\.ru/region/region/izbirkom\?action=show[^"]+)">(.*?)</a>~is', $html, $p, PREG_SET_ORDER);
	if (count($p))
	{
		foreach($p as $link)
		{
			getLinks($link[1], array_merge($array, array($link[2])));
		}
	}
	else {
		preg_match_all('~<td style="color:black">[^<]*</td>\s*<td style="color:black">([^<]+)</td>.*?<td style="color:black" align="right">(\d+\.\d+)%~is', $html, $p, PREG_SET_ORDER);
#		preg_match_all('~УИК №\d+~is', $html, $p, PREG_SET_ORDER);
		if (count($p))
		{
			foreach ($p as $data)
			{
				$dump = $GLOBALS['db']->insert(array(
					'uik' => $data[1],
					'count' => floatval($data[2]),
					'path' => $array,
				));
			}
		}
		else
		{
			echo $url . PHP_EOL;
		}
	}
}

getLinks('http://www.vybory.izbirkom.ru/region/region/izbirkom?action=show&root=1&tvd=1001000882951&vrn=1001000882950&region=0&global=1&sub_region=0&prver=0&pronetvd=null&vibid=1001000882951&type=225');