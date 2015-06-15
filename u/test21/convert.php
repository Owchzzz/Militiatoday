<?php

if (isset($_REQUEST['src'])) {
$src = $_REQUEST['src'];
//Read XML file tv.xml
$input = file_get_contents($src.".xml");
}
else { 
die("Unable to get Source File!");
}



$output = fopen("../epg/xml/channels.xml", "w") or die("Unable to open file!");

$txt = '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
fwrite($output, $txt);



$matches = array();

if (preg_match_all('#(<broadcasting title=".*?">[\\s\\S]*?</broadcasting>)#', $input, $matches)) {	
	$count = max(array_map('count', $matches));
	$kk = 1;
	echo "Started. Now running.....";
	for($i = 0; $i < $count; $i++){
		
		$matches2 = array();
		$broadcastTag = $matches[1][$i];
		$location_name = "";
		$start = "";
		$end = "";
		$video_title = "";
		$video_subtitle = "";
		$video_image = "";
		$youtube = "";
		$description = "";
		
		if (preg_match('#<broadcasting title="(.*?)">#', $broadcastTag, $matches2)) {
			$location_name = $matches2[1];
			//echo "ok:".$location_name."<br>";
		}
		if (preg_match('#<start>(.*?)</start>#', $broadcastTag, $matches2)) {			
			$start = $matches2[1];			
		}
		if($i == 0){
			$txt = '<timetable start="'.$start.'" end="23:59" interval="12" title="">'."\n";
			fwrite($output, $txt);
		
		}
		$txt = '<location name="'.$location_name.'" subtext="">'."\n";
		fwrite($output, $txt);
		
		$matches3 = array();
		if (preg_match_all('#<video>([\\s\\S]*?)</video>#', $broadcastTag, $matches3)) {
			$count2 = max(array_map('count', $matches3));			
			for($k = 0; $k < $count2; $k++){
				$matches4 = array();
				$videoTag = $matches3[1][$k];
				if (preg_match('#<ID>(.*?)</ID>#', $videoTag, $matches4)) {
					//var_dump($matches);
					$video_id = $matches4[1];
					$youtube = "https://www.youtube.com/watch?v=".$video_id;
					$youtube_input = file_get_contents($youtube);
					if (preg_match('#<meta name="description" content="([^"]*?)">#', $youtube_input, $matches4)) {
						//var_dump($matches);
						$description = $matches4[1];
						//echo "ok1:".$description."<br>";
					}
					if (preg_match('#<title>(.*?)</title>#', $youtube_input, $matches4)) {
						//var_dump($matches);
						$video_title = $matches4[1];
						$video_title = substr($video_title, 0, 30).'…';

						//echo "ok2:".$video_title."<br>";
					}
					$kk++;
				}
				
				if (preg_match('#<length>(.*?)</length>#', $videoTag, $matches4)) {
					//var_dump($matches);
					$video_length = $matches4[1]; // In second
					
					
				}
				
				$end = getEndTime($start, $video_length);
				$txt = '<event start="'.$start.'" end="'.$end.'">'."\n";
				$start = $end;
				fwrite($output, $txt);
				
				$txt = '	<title><![CDATA['.$video_title.']]></title>'."\n";
				fwrite($output, $txt);
				
				$txt = '	<subtitle>'.$video_subtitle.'</subtitle>'."\n";
				fwrite($output, $txt);
				
				$txt = '	<image>'.$video_image.'</image>'."\n";
				fwrite($output, $txt);
				
				$txt = '	<youtube>'.$youtube.'</youtube>'."\n";
				fwrite($output, $txt);
				
				$txt = '	<description><![CDATA['.$description.' <a id="music" href="http://militiatoday.com/tv/play.php?channelid='.$location_name.'" class="watch_now" target="tl">Watch Channel</a> ]]></description>'."\n";
				fwrite($output, $txt);
				
				$txt = '</event>'."\n";
				fwrite($output, $txt);
								
			}
		}
		
		$txt = '</location>'."\n";
		fwrite($output, $txt);			
		
	}
	$txt = '</timetable>'."\n";
	fwrite($output, $txt);
	echo "Done!";

}

fclose($output);



function getEndTime($startTime, $num_second){	
	$endTime = strtotime($startTime) + $num_second;
	$endTime = date('h:i', $endTime);
	return $endTime;


}
?>
