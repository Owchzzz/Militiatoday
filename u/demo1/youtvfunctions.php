<?php session_start();
//error_reporting(E_ALL);
//----------------------------------------------------------------
// There are 3 main classes:
//
// ----> broadcasting_interface
//			{XML (broadcastings) I/O handling}
//
// ----> admin_panel
//			{admin panel management}
//
// ----> toolbox
//			{useful support snippets and html markups}
//
// Minor classes are just data containers for YT API feedback
//
// The $_SESSION['cache'] helps getting rid of 
// YT quota limits during frequent admin operations
// storing all temporary batch results.
//----------------------------------------------------------------

class broadcasting_interface {
	
	// XML file path
	const XML_PATH = 'youtv.xml';
	
	// Class constructor
	// NOTE: $broadcasting_name must be unique.
	function __construct ($broadcasting_name = null){
		$this->broadcasting_xml = simplexml_load_file(self::XML_PATH);
		if (empty($broadcasting_name)){
			$initial = $this->broadcasting_xml->xpath('//broadcasting');
			if($initial[0]){
				$initial= trim($initial[0]->attributes());
			} else {
				$initial = "No Broadcasts!";
			}
			$this->broadcasting = $initial;
		}
		else {
			$this->broadcasting = $broadcasting_name;
		}
	}

	// Simple access to all broadcastings data into XML
	// Returns an ARRAY to caller.
	private function get_broadcastings_data () {
		return $this->broadcasting_xml;
	}
	
	// Broadcasting list
	// Returns an ARRAY to caller, with broadcasting titles.
	public function broadcastings_list() {
		$data = $this->get_broadcastings_data();
		foreach ($data->broadcasting as $v) {
			$broadcasting_title[] = trim($v->attributes());
		}
		return $broadcasting_title;
	}

	// This function build a container class filled with
	// all broadcasting infos.
	// It returns that class to calles.
	public function broadcasting_data($broadcasting = null ){
		if (empty($broadcasting)) {
			$broadcasting = $this->broadcasting;
		}
		$data = $this->get_broadcastings_data();
		$broadcasting_data = new broadcasting;
		$broadcasting_data->title = $broadcasting;
		$path = $data->xpath('//broadcasting[@title = "' . $broadcasting . '"]/videos/video');
		foreach ($path as $v) {
			$videos[] = array("id" => trim($v->ID), "duration" => trim($v->length));
		}
		$broadcasting_data->videos = $videos;
		$path = $data->xpath('//broadcasting[@title = "' . $broadcasting . '"]/start');
		$broadcasting_data->starting_time = trim($path[0]);
		$path = $data->xpath('//broadcasting[@title = "' . $broadcasting . '"]/days');
		$broadcasting_data->days = trim($path[0]);
		$path = $data->xpath('//broadcasting[@title = "' . $broadcasting . '"]/loop');
		$broadcasting_data->loop = trim($path[0]);
		$path = $data->xpath('//broadcasting[@title = "' . $broadcasting . '"]/controls');
		$broadcasting_data->controls = trim($path[0]);
		$path = $data->xpath('//broadcasting[@title = "' . $broadcasting . '"]/advice');
		$broadcasting_data->advice = trim($path[0]);
		$path = $data->xpath('//broadcasting[@title = "' . $broadcasting . '"]/height');
		$broadcasting_data->player_height = trim($path[0]);
		$path = $data->xpath('//broadcasting[@title = "' . $broadcasting . '"]/width');
		$broadcasting_data->player_width = trim($path[0]);
		//@ to avoid warnings for shiny new broadcastings
		$broadcasting_data->duration = @array_reduce($videos, create_function('$total, $next', '$total += $next["duration"]; return $total;'));
		return $broadcasting_data;
	}
	
	// Save (update) all broadcasting settings/infos
	public function save_broadcasting_settings ($array) {
		$broadcasting = $this->broadcasting;
		$data = $this->get_broadcastings_data();
		$dom = $this->writeXML($data, "load");
		$xpath = new DOMXpath($dom);
		$path = $xpath->query('//broadcasting[@title="'.$broadcasting.'"]')->item(0);
		$path->getElementsByTagName('controls')->item(0)->nodeValue = $array['controls'];
		$path->getElementsByTagName('loop')->item(0)->nodeValue = $array['loop'];
		$path->getElementsByTagName('advice')->item(0)->nodeValue = $array['advice'];
		$path->getElementsByTagName('days')->item(0)->nodeValue = $array['days'];
		$path->getElementsByTagName('start')->item(0)->nodeValue = $array['start'];
		$path->getElementsByTagName('width')->item(0)->nodeValue = $array['width'];
		$path->getElementsByTagName('height')->item(0)->nodeValue = $array['height'];
		$path->getElementsByTagName('shift')->item(0)->nodeValue = $array['shift'];
		$path->setAttribute('title', $array['title']);
		$this->writeXML(null, "write", $dom);
	}
	
	// The core function: it returns ARRAY to caller with
	// timestamp of the right video ID based on a given time
	// (relative to broadcasting starting time) in seconds, and 
	// the video ID. If the given $time is greater than total 
	// broadcasting length, returns FALSE.
	public function getPointers($time){
		$xmlfile = $this->get_broadcastings_data();
		$broadcasting = $this->broadcasting;
		if($xmlfile->xpath('//broadcasting[@title="'.$broadcasting.'"]')){
			$xml = $xmlfile->xpath('//broadcasting[@title="'.$broadcasting.'"]');
			$xml = $xml[0];
			if (!$xml->videos->video) return false;
			foreach ($xml->videos->video as $v) {
				if ($time >= $v->length){
					$time = $time - $v->length;
					$ID = "not this";
				}
				else {
					$ID = $v->ID;
					break;
				}
			}
			if ($ID == "not this"){
				return false;
			} else {
				return array('ID'=>$ID,'timestamp'=>$time);
			}
		} else {
			return false;
		}
	}
	
	
	// Add a new broadcasting, with some default properties.
	// It checks if the broadcasting title exists already.
	public function add_broadcasting ($title, $time, $days = null) {
		$data = $this->get_broadcastings_data();
		foreach ($data->xpath('//broadcasting') as $v) {
			if($v==$title){
				$duplicate = 1;
				break;
			} else {
				$duplicate = 0;
			}
		}
		if ($duplicate == 0) {
			$dom = $this->writeXML($data, "load");
			$xpath = new DOMXpath($dom);
			$newNode = $dom->createElement('broadcasting');
			$root = $xpath->query('/broadcastings')->item(0);
			$newNode->setAttribute('title', $title);
			$newNode->appendChild($dom->createElement('days', $days));
			$newNode->appendChild($dom->createElement('start', $time));
			$newNode->appendChild($dom->createElement('width', "640"));
			$newNode->appendChild($dom->createElement('height', "390"));
			$newNode->appendChild($dom->createElement('loop', "no"));
			$newNode->appendChild($dom->createElement('controls', "no"));
			$newNode->appendChild($dom->createElement('advice', "Nothing to broadcast right now"));
			$newNode->appendChild($dom->createElement('shift', "0"));
			$newNode->appendChild($dom->createElement('videos'));
			$root->appendChild($newNode);
			$this->writeXML(null, "write", $dom);
			return true;
		} else {
			return false;
		}
	}
	
	// Delete a broadcasting
	public function delete_broadcasting ($broadcasting) {
		$data = $this->get_broadcastings_data();
		$dom = $this->writeXML($data, "load");
		$xpath = new DOMXpath($dom);
		$root = $xpath->query('//broadcasting[@title="'.$broadcasting.'"]')->item(0);
		$root->parentNode->removeChild($root);
		$this->writeXML(null, "write", $dom);
		return true;
	}

	// Manage a DOM XML. If $action is not specified,
	// it creates a DOM from simple xml object, and write it.
	// If $action=load, it only creates a DOM object,
	// if $action=write, it only writes the passed DOM.
	private function writeXML ($xml, $action=null, $dom=null) {
		if($action!=="write"){
			$dom = new DOMDocument('1.0');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;	
			$dom->loadXML($xml->asXML());
		}
		if($action!=="load"){
			$dom->save(self::XML_PATH);
		}
		if ($action!=="write") {
			return $dom;
		}
	}

	// Add videos to a broadcasting using YT batch results data
	// $reference is the insertion point.
	private function add_videos_from_batch_results ($results, $reference) {
		$broadcasting = $this->broadcasting;
		$broadcasting_ext_data = $this->broadcasting_data($broadcasting);
		$data = $this->get_broadcastings_data();
		$dom = $this->writeXML($data,"load");
		$xpath = new DOMXpath($dom);
		if ($results == false) return false;
		$total_duration = $broadcasting_ext_data->duration;
		$max_in = true;
		foreach ($results as $v) {
			// We get errors mostly for YT quota exceeding
			// or private/deleted videos, in this case nothing will
			// be written to XML file
			if (get_class($v)=="YT_error") {
				continue;
			}
			// let's create a new node filled up with video info
			$video_duration = trim($v->duration['value']);
			// that loop checks if the broadcasting lenght is < 24h
			if ($total_duration + $video_duration < 86400){
				$total_duration += $video_duration;
			} else {
				$max_in = false;
				continue;
			}
			$video_id = trim($v->id);
			$new_node = $dom->createElement('video');
			$new_id = $dom->createElement('ID', $video_id);
			$new_duration = $dom->createElement('length', $video_duration);
			$new_node->appendChild($new_id);
			$new_node->appendChild($new_duration);
			// conditional positioning
			if (empty($reference)) {
				$insert_point = $xpath->query('//broadcasting[@title="'.$broadcasting.'"]/videos')->item(0);
				$insert_point->appendChild($new_node);
			} else {
				$insert_point = $xpath->query('//broadcasting[@title="'.$broadcasting.'"]/videos/video['.$reference.']')->item(0);
				$insert_point->parentNode->insertBefore($new_node, $insert_point);
				$reference++;
			}
		}
		$this->writeXML(null,"write",$dom);
		return $max_in;
	}

	// It prepare videos from a playlist URL and passes them
	// to previous function
	public function add_videos_from_playlist_url ($playlist_url, $reference = null) {
		$batch_request = new video_feeds;
		$batch_results = $batch_request->query_by_playlist_url($playlist_url);
		if($this->add_videos_from_batch_results($batch_results, $reference)) return true;
	}
	
	// It prepare videos from video URL and passes them
	// to previous function
	public function add_videos_from_urls ($video_urls, $reference = null) {
		$batch_request = new video_feeds;
		$batch_results = $batch_request->query_by_video_url($video_urls);
		if($this->add_videos_from_batch_results($batch_results, $reference)) return true;
	}

	// Function to move an XML node (video entry) up or down
	public function move_video ($entity, $up_or_down) {
		$broadcasting = $this->broadcasting;
		$data = $this->get_broadcastings_data();
		$dom = $this->writeXML($data, "load");
		$xpath = new DOMXpath($dom);
		$moving_node = $xpath->query('//broadcasting[@title="'.$broadcasting.'"]/videos/video['.$entity.']')->item(0);
		$cloned_node = $moving_node->cloneNode(true);
		// checks if moving node is moving down && it's not the last
		if ($up_or_down=="down" && !empty($moving_node->nextSibling)) {
			$down = $moving_node->nextSibling;
			$moving_node->parentNode->insertBefore($cloned_node, $down->nextSibling);
			$moving_node->parentNode->removeChild($moving_node);
		}
		// checks if moving node is moving up && it's not the first
		elseif ($up_or_down=="up" && !empty($moving_node->previousSibling)) {
			$moving_node->parentNode->insertBefore($cloned_node, $moving_node->previousSibling);
			$moving_node->parentNode->removeChild($moving_node);
		}
		// so first does not move up, and last does not move down
		else {return false;}
		$this->writeXML(null, "write",$dom);
		return true;
	}

	// Changes a video entry
	public function change_video ($entity, $new_url) {
		$entity++;
		$broadcasting = $this->broadcasting;
		$data = $this->get_broadcastings_data();
		$dom = $this->writeXML($data, "load");
		$xpath = new DOMXpath($dom);
		$batch_request = new video_feeds;
		if (!$batch_results = $batch_request->query_by_video_url($new_url)) return false;
		$video_duration = trim(reset($batch_results)->duration['value']);
		$video_id = trim(reset($batch_results)->id);
		$node = $xpath->query('//broadcasting[@title="'.$broadcasting.'"]/videos/video['.$entity.']')->item(0);
		$new_node = $dom->createElement('video');
		$new_id = $dom->createElement('ID', $video_id);
		$new_duration = $dom->createElement('length', $video_duration);
		$new_node->appendChild($new_id);
		$new_node->appendChild($new_duration);
		$node->parentNode->insertBefore($new_node, $node);
		$node->parentNode->removeChild($node);
		$this->writeXML(null, "write",$dom);
		return true;
	}
	

	// Remove a video entry
	public function remove_video ($entity) {
		$broadcasting = $this->broadcasting;
		$data = $this->get_broadcastings_data();
		$dom = $this->writeXML($data,"load");
		$xpath = new DOMXpath($dom);
		$video = $xpath->query('//broadcasting[@title="'.$broadcasting.'"]/videos/video['.$entity.']')->item(0);
		$video->parentNode->removeChild($video);
		$this->writeXML(null, "write", $dom);
		return true;
	}
}



class admin_panel {

	// Print the broadcasting list
	public function print_broadcasting_list () {
		$broadcasting_class = new broadcasting_interface($broadcasting);
		$broadcastings = $broadcasting_class->broadcastings_list();
		echo "<p class=\"title\">Channels<p>";
		foreach ($broadcastings as $v) {
			echo "<p>" . $v . ": <a href=\"#\" onclick=\"javascript:preview('" . $v . "');\" class=\"clear bcselector\" id=\"" . $v . "\">curate</a> 
<a href=\"write.php?vid=".$v."\" id=\"standard\" class=\"clear pale\" target=\"conversion\">GO LIVE!</a> </span></p>";
		}
		return true;
	}

	// Print all broadcasting listing
	public function print_broadcasting($broadcasting = null) {
		$toolbox = new toolbox;
		$broadcasting_class = new broadcasting_interface($broadcasting);
		$broadcasting_data = $broadcasting_class->broadcasting_data();
		$title = $broadcasting_data->title;
		$loop = $broadcasting_data->loop;
		if ($loop == "no"){
			$starting_time = $broadcasting_data->starting_time;
		} else {
			$starting_time = "looping!";
		}
		$toolbox->html_broadcasting_title_admin_panel($title, $starting_time);
		if ($loop=="no"){
			$toolbox->print_bar(495, $broadcasting_data);
			$video_starting_times = @$toolbox->videos_starting_time($broadcasting_data);
			$i=0;
		}
		// let's build the ID array
		foreach ($broadcasting_data->videos as $v) {
			$video_ids[] = $v['id'];
		}
		// Check for previous cached results and prepare to bach only new ids
		// or old ids that get back some errors
		foreach ($video_ids as $v){
			if (!$_SESSION['cache'][$v] || get_class($_SESSION['cache'][$v]) == "YT_error"){
				$video_ids_tocall[] = $v;
			}
		}
		// let's call the batch
		$batch_request = new video_feeds;
		$batch_results = $batch_request->query_by_videoid($video_ids_tocall);
		// put results in cache
		if(is_array($batch_results)) {
			$_SESSION['cache'] = array_merge((array)$_SESSION['cache'], $batch_results);
		}
		// let's read results and build output
		foreach ($video_ids as $v){
			$v = trim($v);
			$duration = $toolbox->inMinutes($_SESSION['cache'][$v]->duration['value']);
			$thumb = $_SESSION['cache'][$v]->thumbnails[1]['url'];
			$thumb_w = $_SESSION['cache'][$v]->thumbnails[1]['width'];
			$thumb_h = $_SESSION['cache'][$v]->thumbnails[1]['height'];
			// trims the title, keeping first 30 charachters
			// (admin panel visualization should be essential and organized)
			$title = $toolbox->trimString($_SESSION['cache'][$v]->title, 30);
			if ($loop=="no") {
				$startsAt = $video_starting_times[$i];
			} else {
				$startsAt = "looping!";
			}
			$i == 0 ? $j = "0" : $j = $i;
			$toolbox->html_add_video_entry($j);
			$toolbox->html_video_entry_small($thumb, $thumb_w, $thumb_h, $title, $j, $v, $duration, $startsAt);
			$toolbox->html_video_actions_buttons($j);
			$i++;
		}
		$toolbox->html_add_video_entry();
	}
}


// This is the Play class, it contains extension needed by the webpage that holds the webtv.
class play extends broadcasting_interface {

	// Function to get the time (server time!) in seconds, relative to midnight, or 00:00. 
	// If used with $mode == "unix", it returns the Unix Epoch.
	// If used with $mode == "week", it returns also a numeric identifier for the day of the week.
	// $shift is used to set a different timezone
	private function getTime($mode=null, $shift){
		$toolbox = new toolbox;
		if ($mode == "unix"){
			$time = time();
		} 
		elseif ($mode == "week") {
			$now = $toolbox->inSeconds(date("H:i:s"))+3600*$shift;
			$today = date("w");
			$time = array('time' => $now, 'today' => $today);
		}
		else {
			$time = $toolbox->inSeconds(date("H:i:s"))+3600*$shift;
		}
		return $time; // This could be an array!
	}

	// Infinite Loop mode, Unix Epoch is the broadcasting starting time for that mode.
	// (a custom starting point for a loop is essentially useless)
	private function loopInfinite(){
		$time = $this->getTime(unix,0);
		$broadcasting = $this->broadcasting_data($this->broadcasting);
		$totalLength = $broadcasting->duration ;
		if ($totalLength > 0) {
			while ( $time >= $totalLength) {
				$time = $time - $totalLength;
			}
			$pointer = $this->getPointers($time);
		} else {
			$pointer = "nothing to broadcast";
		}
		return $pointer;
	}

	// Repeat daily mode, it repeats the broadcasting every day at the same time
	private function repeatDaily($shift){
		$toolbox = new toolbox;
		$time = $this->getTime(null,$shift);
		$broadcasting = $this->broadcasting_data($this->broadcasting);
		$start = $broadcasting->starting_time;
		$start = $toolbox->inSeconds($start.":00");
		$end = $start + $broadcasting->duration;
		// it checks if the broadcasting will stop before midnight..
		if ($end < 86400){
			// it checks if "right now" we're in between of $start and $end.
			if ($time >= $start && $time <= $end){
				$time = $time - $start;
				$pointer = $this->getPointers($time);
			}
			else {
				// looking for that value could be used to drive a specific
				// behaviour if we are outside broadcasting time.
				$pointer = "outside";
			}
		}
		// ..and if the broadcasting does NOT stop before midnight..
		else {
			if ($time >= $start){
				$time = $time - $start;
				$pointer = $this->getPointers($time);
			}
			else {
				$beforemidnight = 86400 - $start;
				$aftermidnight = $end - $beforemidnight;
				if ($time < $aftermidnight){
					$time = $time + $beforemidnight;
					$pointer = $this->getPointers($time);
				}
				else {
					$pointer = "outside";
				}
			}
		}
		return $pointer; //this is an array OR a string (value = "outside" by default)
	}

	// Repeat weekly mode, it repeats the broadcasting every week, at the same day and time
	private function repeatWeekly($shift=0){
		$weekTime = $this->getTime("week",$shift); // This is an array!
		// $time = $weekTime['time']; //not used yet
		$today = $weekTime['today'];
		$xmlfile = simplexml_load_file(self::XML_PATH);
		$bc = $this->broadcasting;
		$xml = $xmlfile->xpath('//broadcasting[@title="'.$bc.'"]');
		$xml = $xml[0];
		$days = explode(",", $xml->days);
		$check = "no";
		foreach ($days as $v) {
			if ($v == $today) {
				$check = "yes";
				break;
			}
		}
		if ($check == "yes"){
			$pointer = $this->repeatDaily($shift);
		}
		else {
			$pointer = "outside";
		}
		return $pointer; //this is an array OR a string (value = "outside" by default)
		// if returned $pointer == FALSE, something went wrong, somewhere..
	}

	// This function, called within a webpage, creates the webtv!
	// Some YouTube API parameters are hardcoded here, please 
	// modify in order to fit you needs, but carefully.
	public function playNow() {
		$toolbox = new toolbox;
		$xmlfile = simplexml_load_file(self::XML_PATH);
		$bc = $this->broadcasting;
		$path = $xmlfile->xpath('//broadcasting[@title="'.$bc.'"]');
		$path = $path[0];
		$width = $path->width;
		$height = $path->height;
		$loop = $path->loop;
		$controls = $path->controls;
		$days = $path->days;
		$advice = $path->advice;
		$shift = trim($path->shift);
		if ($loop=="no"){
			$pointers = $this->repeatWeekly($shift);
			$repeat = 0;
		} else {
			$pointers = $this->loopInfinite();
			$repeat = 1;
		}
		if ($controls == "no"){
			$showcontrols = 0;
		} else {
			$showcontrols = 2;
		}
		if (is_array($pointers)){
			$ID = $pointers['ID'];
			$timestamp = $pointers['timestamp'];
			foreach ($path->videos->video as $v){
				$videolist[] = $v->ID;
			}
			// let's prepare the string for playlist
			foreach($videolist as $key => $value){
				if (trim($value) !== trim($ID)){
					$videolist_to_prepend[] = $videolist[$key];
					unset($videolist[$key]);
				} else {
				$videolist_to_prepend[] = $videolist[$key];
				unset($videolist[$key]);
				break;
				};
			}
			if ($loop=="yes") {
				$videolist = array_merge($videolist, $videolist_to_prepend);
			}
			$playlist = implode(',', $videolist);
			$toolbox->html_yt_iframe($ID, $timestamp, $playlist, $width, $height, $repeat, $showcontrols);
  		} else {
			$toolbox->html_out_broadcasting_advice($advice);
  		}
  		return true;
	}

	// This function, called within a webpage, creates the webtv!
	// Some YouTube API parameters are hardcoded here, please 
	// modify in order to fit you needs, but carefully.
	public function NowPlaying() {
		$toolbox = new toolbox;
		$xmlfile = simplexml_load_file(self::XML_PATH);
		$bc = $this->broadcasting;
		$path = $xmlfile->xpath('//broadcasting[@title="'.$bc.'"]');
		$path = $path[0];
		$width = $path->width;
		$height = $path->height;
		$loop = $path->loop;
		$controls = $path->controls;
		$days = $path->days;
		$advice = $path->advice;
		$shift = trim($path->shift);
		if ($loop=="no"){
			$pointers = $this->repeatWeekly($shift);
			$repeat = 0;
		} else {
			$pointers = $this->loopInfinite();
			$repeat = 1;
		}
		if ($controls == "no"){
			$showcontrols = 0;
		} else {
			$showcontrols = 2;
		}
		if (is_array($pointers)){
			$ID = $pointers['ID'];
			$timestamp = $pointers['timestamp'];
			foreach ($path->videos->video as $v){
				$videolist[] = $v->ID;
			}
			// let's prepare the string for playlist
			foreach($videolist as $key => $value){
				if (trim($value) !== trim($ID)){
					$videolist_to_prepend[] = $videolist[$key];
					unset($videolist[$key]);
				} else {
				$videolist_to_prepend[] = $videolist[$key];
				unset($videolist[$key]);
				break;
				};
			}
			if ($loop=="yes") {
				$videolist = array_merge($videolist, $videolist_to_prepend);
			}
			$playlist = implode(',', $videolist);
			$toolbox->html_np_frame($ID);
  		} else {
			$toolbox->html_out_broadcasting_advice($advice);
  		}
  		return true;
	}
	// It prints broadcasting listing
	public function playInfo(){
		$xmlfile = simplexml_load_file(self::XML_PATH);
		$bc = $this->broadcasting;
		$path = $xmlfile->xpath('//broadcasting[@title="'.$bc.'"]');
		$path = $path[0];
		$shift = trim($path->shift);
		$pointer = $this->repeatWeekly($shift);
		$broadcasting_data = $this->broadcasting_data();
		$toolbox = new toolbox;
		$video_starting_times = @$toolbox->videos_starting_time($broadcasting_data);
		if ($broadcasting_data->videos){
			foreach ($broadcasting_data->videos as $v) {
				$video_ids[] = $v['id'];
			}
			// check for previous cached results and prepare to bach only new ids
			foreach ($video_ids as $v){
				if (!$_SESSION['cache'][$v]){
					$video_ids_tocall[] = $v;
				}
			}
		} else {
			$video_ids = null;
		}
		$batch_request = new video_feeds;
		$batch_results = $batch_request->query_by_videoid($video_ids_tocall);
		// put results in cache
		if(is_array($batch_results)) {
			$_SESSION['cache'] = array_merge((array)$_SESSION['cache'], $batch_results);
		}
		echo "<div class=\"ytv_playinfo\">";
		if (is_array($pointer)){
			echo
			"<p class=\"ytv_now_playing\"></p>";
		} else {
			echo "<p class=\"ytv_not_playing\"><p>";
		}
		echo "<table class=\"ytv_broadcasting_list\">";
		$i = 0;
		if (!empty($video_ids)) {
			foreach ($video_ids as $v) {
				echo "<tr><td colspan='2' style='padding:10px;'>".$_SESSION['cache'][$v]->title." <br/>Time: ".$video_starting_times[$i]."</td></tr>";
				$i++;
			}
		}
		echo "</table></div>";
	}

	// It prints a list of video thumbnails with title,
	// starting time and description.
	public function playThumb() {
		$toolbox = new toolbox;
		$broadcasting_data = $this->broadcasting_data();
		$video_starting_times = @$toolbox->videos_starting_time($broadcasting_data);
		if ($broadcasting_data->videos){
			foreach ($broadcasting_data->videos as $v) {
				$video_ids[] = $v['id'];
			}
		} else {
			$video_ids = null;
		}
		if (!empty($video_ids)) {
			// check for previous cached results and prepare to bach only new ids
			foreach ($video_ids as $v){
				if (!$_SESSION['cache'][$v]){
					$video_ids_tocall[] = $v;
				}
			}
		}
		echo "<div class=\"ytv_thumbnail_column\">";
		$batch_request = new video_feeds;
		$batch_results = $batch_request->query_by_videoid($video_ids_tocall);
		// put results in cache
		if(is_array($batch_results)) {
			$_SESSION['cache'] = array_merge((array)$_SESSION['cache'], $batch_results);
		}
		$i=0;
		if (!empty($video_ids)) {
			foreach ($video_ids as $v) {
				$v = trim($v);
				$thumb = $_SESSION['cache'][$v]->thumbnails[4]['url'];
				$thumb_w = $_SESSION['cache'][$v]->thumbnails[4]['width'];
				$thumb_h = $_SESSION['cache'][$v]->thumbnails[4]['height'];
				$title = $_SESSION['cache'][$v]->title;
				$description = $toolbox->trimString($_SESSION['cache'][$v]->description,150);
				$starting_time = $video_starting_times[$i];
				$toolbox->html_thumbnail_description($thumb, $thumb_w, $thumb_h, $title, $starting_time, $description);
				$i++;
			}
		}
		echo "</div>";
	}
}

class video_feeds {
	const BATCH_REQUEST = 'http://gdata.youtube.com/feeds/api/videos/batch';
	const PLAYLIST_FEED = 'http://gdata.youtube.com/feeds/api/playlists/';
	const XMLNS_ATOM = 'http://www.w3.org/2005/Atom';
	const XMLNS_BATCH = 'http://schemas.google.com/gdata/batch';
	const XMLNS_MRSS = 'http://search.yahoo.com/mrss/';
	const XMLNS_YT = 'http://gdata.youtube.com/schemas/2007';
	const RESPONSE_CODE_OK = 200;
	
	// Uses any playlist URL to generate relative video IDs array
	// and return complete feed array to caller
	// NOTE: it's able to "break" the max 50 video per request, if list is > 50 videos
	// it sets a request every 50 videos
	public function query_by_playlist_url($playlist_url){
		$playlist = substr($this->_get_video_id_from_url($playlist_url, true),2);
		$start_index = 1;
		do {
			$feed_url = self::PLAYLIST_FEED . $playlist . '?v=2&max-results=50&start-index=' . $start_index . '&alt=json';
			$data = json_decode(file_get_contents($feed_url), true);
			$video = $data["feed"]["entry"];
			$video_number = count($video);
			for($i = 0; $i < $video_number; $i++){
				   $video_list[] .= $video[$i]['media$group']['yt$videoid']['$t'];
			}
			$start_index = $start_index + 50;
		} while ($video_number != 0);
		return $this->query_by_videoid($video_list);
	}

	// Uses a video URL array to generate relative video IDs array
	// and return complete feed array to caller
	public function query_by_video_url($video_urls = array()){
		if(!is_array($video_urls) && !empty($video_urls)) {
			$video_urls = (array)$video_urls;
		}
		$video_list = array();
		foreach($video_urls as $url) {
			$video_list[] = $this->_get_video_id_from_url($url);
		}
		return $this->query_by_videoid($video_list);
	}

	// Uses a video ID array to return feed array to caller
	// NOTE: since single batch requests are processed by YT up to
	// 50 videos, if the ID array is > 50 function breaks array
	// and does multiple batch requests
	public function query_by_videoid($video_list = array()) {
		if(!is_array($video_list) && !empty($video_list)) {
			$video_list = (array)$video_list;
		}
		if(count($video_list) < 1) 
			return false;
		$video_lists = array_chunk($video_list, 50);
		foreach ($video_lists as $video_ids) {
			$request = '<feed xmlns="' . self::XMLNS_ATOM . '" xmlns:media="' . self::XMLNS_MRSS . '"
					   xmlns:batch="' . self::XMLNS_BATCH . '" xmlns:yt="' . self::XMLNS_YT . '">
					   <batch:operation type="query"/>';
			foreach($video_ids as $video_id) {
				$request .= '<entry><id>http://gdata.youtube.com/feeds/api/videos/'. $video_id .'</id></entry>';
			}
			$request .= '</feed>';
			try {
				$that_response = $this->do_post_request($request);
				$response = array_merge((array)$response, $this->_parse_response($that_response));
			} catch (Exception $e) {
				$response = array($e->getMessage());
				return $response;
			}
		}
		return $response;
	}
	
	// Sends data to batch URL, by a simple HTTP POST request
	// Returns the complete feed array .
	private function do_post_request($data, $url = self::BATCH_REQUEST) {
		$parameters = array(
			'http' => array(
				'method' => 'POST',
				'content' => $data
			)
		);
		$stream = stream_context_create($parameters);
		$post = @fopen($url, 'rb', false, $stream);
		if (!$post) {
			throw new Exception("Problem with $url, $php_errormsg");
		}
		$response = @stream_get_contents($post);
		if ($response === false) {
			throw new Exception("Problem reading data from $url, $php_errormsg");
		}
		return $response;
	}

	// A video entry class / error class is constructed and returned filled with data
	private function _parse_response($response) {
		if(!class_exists('SimpleXMLElement')) return false;
		$xml = new SimpleXMLElement($response);
		$feed = $xml->children(self::XMLNS_ATOM);
		if(count($feed) < 1) return false;
		$entries = array();
		foreach($feed->entry as $entry) {
			$batch = $entry->children(self::XMLNS_BATCH);
			if(count($batch) < 1) continue;
			$batch_attributes = $batch->status->attributes();
			if($batch_attributes['code'] != self::RESPONSE_CODE_OK) {
				$yt_error = new YT_error();
				$yt_error->id = $this->_parse_entry_id((string)$entry->id);
				$yt_error->code = (string)$batch_attributes['code'];
				$yt_error->message = (string)$batch_attributes['reason'];
				$entries["$yt_error->id"] = $yt_error;
				continue;
			}
			$media = $entry->children(self::XMLNS_MRSS);
			if(count($media) < 1) continue;
			$yt_entry = new YT_entry();
			$yt_entry->id = $this->_parse_entry_id((string)$entry->id);
			$yt_entry->title = (string)$media->group->title;
			foreach($media->group->content as $content) {
				$attributes = $content->attributes();
				if($attributes['isDefault']) {
					$yt_entry->duration = array(
						'value' => (string)$attributes['duration'],
						'unit' => 'seconds'
					);
				}
			}
			$yt_entry->description = (string)$media->group->description;
			$player = $media->group->player->attributes();
			foreach($media->group->thumbnail as $thumbnail) {
				$attributes = $thumbnail->attributes();
				$entry_thumbnail = array(
					'url' => (string)$attributes['url'],
					'height' => (string)$attributes['height'],
					'width' => (string)$attributes['width']
				);
				$yt_entry->thumbnails[] = $entry_thumbnail;
			}
			$entry_thumbnail = array(
					'url' => "http://i.ytimg.com/vi/" . $this->_parse_entry_id((string)$entry->id) . "/mqdefault.jpg",
					'height' => '180',
					'width' => '320'
					);
			$entries["$yt_entry->id"] = $yt_entry;
			$yt_entry->thumbnails[] = $entry_thumbnail;
		}
		return (count($entries) < 1) ? false : $entries;
	}

	private function _parse_entry_id($id) {
		if(empty($id)) return false;
		return substr($id, strrpos($id, '/') + 1);
	}
	
	// Useful function to trim any YT ID from almost any video URL
	public function _get_video_id_from_url($url, $playlist = false) {
		if (empty($playlist)) {
			$type = "v";
		} else {
			$type = "list";
		}
		// check it: http://en.wikipedia.org/wiki/Regular_expression
		$pattern = '/^((http:\/\/)|(https:\/\/)|(http:\/\/www\.)|(https:\/\/www\.)|(www\.))youtube\.com\/(.+)(' . $type . '=.+)/';
		preg_match($pattern, $url, $matches);
		if($matches != null) {
			if(count($matches) >= 3) {
				$pieces = explode('&',$matches[count($matches)-1]);
				$id = false;
				for($i = 0; $i < count($pieces); $i++) {
					$piece = explode('=', $pieces[$i]);
					if($piece[0] == $type && $piece[1]) {
						$id = $piece[1];
						return $id;
					} else {
						return false;
					}
				}
			}
		}
		return false;
	}
}


// Toolbox Class for useful snippets and html markups
class toolbox {
	
	// MARKUP: admin panel video entry
	public function html_video_entry_small($thumbnail, $thumbnail_width, $thumbnail_height, $title, $unique_id, $video_id_for_link, $duration, $starts_at) {
		echo "<div class=\"videoentry clear\">
			 <img src=\"${thumbnail}\" class=\"thumbnail\" alt=\"${thumbnail}\" width=\"$thumbnail_width\"height=\"$thumbnail_height\"/>
			 <div class=\"videoinfos\">
			 <p class=\"videoname\">$title</p>
			 <p class=\"videoid\" id=\"v$unique_id\"><a href=\"http://www.youtube.com/watch?v=$video_id_for_link\" target=\"_blank\">youtube.com/watch?v=$video_id_for_link</a></p>
			 <p class=\"duration\">Duration: $duration (starts @$starts_at)</p>
			 </div>";
	}

	// MARKUP: broadcasting titling and buttons for admin panel
	public function html_broadcasting_title_admin_panel ($title, $starting_time=null) {
		echo "<p class=\"title\" id=\"bctitle\">$title</p>
			<button class=\"metrobtn bcsettings\" value=\"$title\"></button>
			<button class=\"metrobtn bcimport\" value=\"$title\"></button>
			<button class=\"metrobtn bcdelete\" value=\"$title\"></button>
			<p class=\"startingtime\">On Air @$starting_time / Server time: ".date("H:i")."</p>";
			
	}
	
	// MARKUP: little "+" buttons to add a new video in admin panel
	public function html_add_video_entry ($unique_id = null) {
		echo "<div class=\"videoadd clear\">
		<button class=\"metrobtn add btn_small\" value=\"v$unique_id\"></button></div>";
	}
	
	// MARKUP: video actions buttons to the right of any video entry
	// in admin panel
	public function html_video_actions_buttons ($unique_id) {
		echo "<div class=\"videoactions\">
		<button class=\"metrobtn videoremove\" value=\"v$unique_id\"></button>
		<button class=\"metrobtn videoup\" value=\"v$unique_id\"></button>
		<button class=\"metrobtn videoedit\" value=\"v$unique_id\"></button>
		<button class=\"metrobtn videodown\" value=\"v$unique_id\"></button>
		</div></div>";
	}
	
	// MARKUP: YT embedded IFRAME
	public function html_yt_iframe ($video_id, $timestamp, $playlist, $width, $height, $loop = 0, $showcontrols) {
		echo "
		<iframe id=\"ytplayer\" width=\"100%\" height=\"100%\"
  src=\"http://www.youtube.com/embed/${video_id}?autoplay=1&amp;showinfo=0&amp;rel=0&amp;iv_load_policy=3&amp;controls=$showcontrols&amp;modestbranding=1&amp;autohide=1&amp;start=$timestamp&amp;loop=$loop&amp;playlist=$playlist&amp;fs=1&amp;wmode=transparent\"></iframe>";	
	}


	// MARKUP: YT embedded IFRAME
	public function html_np_frame ($video_id) {
		echo "<img src='http://img.youtube.com/vi/${video_id}/0.jpg' width='250px' height='175'/></div>";
	}
	
	// MARKUP: advice - out of broadcasting
	public function html_out_broadcasting_advice ($advice) {
		echo "<div class=\"ytv_advice\"><p>$advice</p></div>";
	}
	
	// MARKUP: thumbnail and info column
	public function html_thumbnail_description ($thumb, $thumb_w, $thumb_h, $title, $starting_time, $description) {
		echo "<div class=\"ytv_video_thumbnail\" style='background:url(". $thumb . ");width:${thumb_w}px;height:${thumb_h}px;'><p class=\"ytv_thumbnail_title\">".$this->trimString($title, 100)."</p><p class=\"ytv_thumbnail_time\">". $starting_time ."</p><p class=\"ytv_thumbnail_description\">$description</div>";
	}
	
	// MARKUP: playlist import form, in admin panel
	public function html_import_playlist ($broadcasting, $text_value = null) {
		echo "
		<label>Append a Youtube playlist URL:</label>
		<table><tr><td>
		<input type=\"text\" name\"playlist\" id=\"playlist\" value=\"$text_value\"></input>
		</td></table>
		<button class=\"metrobtn btn_big bcadd\" value=\"$broadcasting\"></button>";
	}
	
	// MARKUP: broadcasting settings form in admin panel
	public function html_broadcasting_settings_form ($title, $hour, $minute, $days, $checked_loop, $width, $height, $advice, $tz, $checked_controls){
		echo "<form id=\"editbroadcasting\">
			<fieldset>
			<label>Title</label>
			<input type=\"text\" name=\"title\" maxlength=\"20\" value=\"$title\"></input>
			<label>Starting time (24h)</label>
			<span class=\"time\">
			<input class=\"input_small\" name=\"time1\" type=\"text\" maxlength=\"2\" value=\"$hour\"></input>
			<p>:</p><input class=\"input_small\" name =\"time2\" type=\"text\" maxlength=\"2\" value=\"$minute\"></input>
			</span>
			<label>Days</label>
			<div class=\"daysblock\">";
		$i = 1;
		$check = false;
		$labels = array('0' => 'S', '1' => 'M','2' => 'T','3' => 'W','4' => 'T','5' => 'F','6' => 'S');
		while ($i <= 6) {
			foreach ($days as $v) {
				if ($v == $i && (!empty($v) || $v=='0')) {
					$check = true;
					break;
				} else {
					$check = false;
				}
			}
			if ($check == true) {
				echo "<label for=\"ed$i\">${labels[$i]}</label>
					<input id=\"ed$i\" name=\"days[$i]\" type=\"checkbox\" checked=\"checked\"></input>";
			} else {
				echo "<label for=\"ed$i\">${labels[$i]}</label>
					<input id=\"ed$i\" name=\"days[$i]\" type=\"checkbox\"></input>";
			}
			if ($i == 0){
				break;
			}
			$i == 6 ? $i = 0 : $i++;
		}
		echo "<input type=\"hidden\" name=\"action\" value=\"save_bc_settings\"></input>
			 <input type=\"hidden\" name=\"broadcasting\" value=\"$title\"></input>
			 </div><label id=\"pre_loop\">Loop (override days)</label>
			 <div class=\"daysblock\">
			 <label id=\"loop\" for=\"loopbox\"></label>
			 <input id=\"loopbox\" name=\"loop\" type=\"checkbox\" $checked_loop=\"$checked_loop\"></input></div>
			 <label>Player size</label>
			 <span class=\"time\">
			 	<input class=\"input__small input_size\" name=\"width\" type=\"text\" maxlength=\"4\" value=\"$width\"></input>
			 	<p>X</p><input class=\"input_small input_size\" name =\"height\" type=\"text\" maxlength=\"4\" value=\"$height\">
			 </input></span>
			 <label id=\"pre_controls\">Show controls</label>
			 <div class=\"daysblock\">
			 <label for=\"controlsbox\" id=\"controls\"></label>
			 <input id=\"controlsbox\" name=\"controls\" type=\"checkbox\" $checked_controls=\"$checked_controls\"></input></div>
			 <label>Broadcasting off advice</label>
			 <textarea name=\"advice\">$advice</textarea>
			 <label>Shift from server Time Zone</label>
			 <select name=\"shift\">
				 <option value=\"1\" $tz[1]>+1</option>
				 <option value=\"2\" $tz[2]>+2</option>
				 <option value=\"3\" $tz[3]>+3</option>
				 <option value=\"4\" $tz[4]>+4</option>
				 <option value=\"5\" $tz[5]>+5</option>
				 <option value=\"6\" $tz[6]>+6</option>
				 <option value=\"7\" $tz[7]>+7</option>
				 <option value=\"8\" $tz[8]>+8</option>
				 <option value=\"9\" $tz[9]>+9</option>
				 <option value=\"10\" $tz[10]>+10</option>
				 <option value=\"11\" $tz[11]>+11</option>
				 <option value=\"12\" $tz[12]>+12</option>
				 <option value=\"0\" $tz[0]>0</option>
				 <option value=\"-1\"". $tz[-1].">-1</option>
				 <option value=\"-2\"". $tz[-2].">-2</option>
				 <option value=\"-3\"". $tz[-3].">-3</option>
				 <option value=\"-4\"". $tz[-4].">-4</option>
				 <option value=\"-5\"". $tz[-5].">-5</option>
				 <option value=\"-6\"". $tz[-6].">-6</option>
				 <option value=\"-7\"". $tz[-7].">-7</option>
				 <option value=\"-8\"". $tz[-8].">-8</option>
				 <option value=\"-9\"". $tz[-9].">-9</option>
				 <option value=\"-10\"". $tz[-10].">-10</option>
				 <option value=\"-11\"". $tz[-11].">-11</option>
				 <option value=\"-12\"". $tz[-12].">-12</option>
			 </select>
			 </fieldset></form><button class=\"metrobtn btn_big bcsavesettings\"></button>";
	}

	// It prints (echoes) a graphic bar that shows which part of the day(s) is covered by broadcasting.
	// Calling it with $pixels == "[value]" returns a <div> with width=[value]px.
	// Calling it without $mode return a <div> with width=100%;
	public function print_bar($pixels, $broadcasting_data) {
		$start = $broadcasting_data->starting_time;
		$start = $this->inSeconds($start.":00");
		$length = $broadcasting_data->duration;
		$left = ($start * $pixels / 86400);
		if (($start+$length)<=86400){
			//in pixels!
			$width = ($length * $pixels / 86400);
			// <div> classes are styled in youtv.css
			echo "
			<div class=\"bar\" style=\"width:${pixels}px;\">
			<div class=\"inside_up\">";
			for ($i = 1; $i <= 12; $i++){
			echo "<span>". $i*2 .":00</span>";
			}
			echo "</div>
			<div class=\"inside\" style=\"left:${left}px;width:${width}px;\"></div>
			</div>";
		} else {
			$width = ($start + $length - 86400) * $pixels / 86400;
			// <div> classes are styled in youtv.css
			echo "
			<div class=\"bar\" style=\"width:${pixels}px;\">
			<div class=\"inside_up\">";
			for ($i = 1; $i <= 12; $i++){
			echo "<span>". $i*2 .":00</span>";
			}
			echo "</div>
			<div class=\"inside\" style=\"width:${width}px;left:0px;\"></div>
			<div class=\"inside\" style=\"left:${left}px;right:0px;\"></div>
			</div>";
		}
	}
	
	// Gets starting time for each video in a broadcasting
	// Returns an ARRAY to caller
	public function videos_starting_time($broadcasting_data) {
		$start = $broadcasting_data->starting_time;
		$start = $this->inSeconds($start.":00");
		$videos = $broadcasting_data->videos;
		$fill = 0;
		foreach ($videos as $v) {
			$trimmed_time = $fill + $start;
			if ($trimmed_time > 86400) {
				$trimmed_time = $trimmed_time -86400;
			}
			$check_string = $this->inMinutes($trimmed_time);
			if (strlen($check_string) == 5) {
				$check_string = "00:".$check_string;
			}
			elseif (strlen($check_string) == 7) {
			$check_string = "0".$check_string;
			}
			elseif (strlen($check_string) == 4) {
						$check_string = "00:0".$check_string;
			}
			$trimmed_time = substr($check_string,0,-3);
			$starting_times[] = $trimmed_time;
			$fill = $fill + trim($v['duration']);
		}
		return $starting_times;
	}
	
	// Useful conversion function: seconds->i:s
	public function inMinutes($seconds){
		$part1 = floor($seconds / 60);
		$part2 = sprintf("%02u", ($seconds - $part1 * 60));
		if ($part1 < 60){
			$total = $part1.":".$part2;
		}
		else {
			$part3 = floor($part1 / 60);
			$part1 = sprintf("%02u", ($part1 - $part3 * 60));
			$total = $part3.":".$part1.":".$part2;
		}
		return $total;
	}
	
	// uUseful conversion function: i:s->seconds or H:i:s->seconds
	public function inSeconds($iiss) {
		$pieces = array_reverse(explode(":", $iiss));
		return $pieces[0] + $pieces[1] * 60 + $pieces[2] *3600;
	}
	
	// Useful function to trim a string by length, appending a defined pattern
	public function trimString($string, $length=25, $method='WORDS', $pattern=' ...') {
	    if (!is_numeric($length)) { 
	            $length = 25; 
	        } 
	    if (strlen($string) <= $length) { 
	            return rtrim($string) . $pattern; 
	        }
	    $truncate = substr($string, 0, $length);
	    if ($method != 'WORDS') { 
	            return rtrim($truncate) . $pattern; 
	        }
	    if ($truncate[$length-1] == ' ') { 
	            return rtrim($truncate) . $pattern; 
	        } 
	    $pos = strrpos($truncate, ' '); 
	    if (!$pos) { return $pattern; } 
	    return rtrim(substr($truncate, 0, $pos)) . $pattern; 
	}
}

// Broadcasting data class
class broadcasting {
	public $title;
	public $days;
	public $starting_time;
	public $loop;
	public $player_width;
	public $player_height;
	public $time_shift;
	public $advice;
	public $videos;
	public $duration;
	public $controls;
}

// Video entry data class
class YT_entry {
	public $id;
	public $description;
	public $thumbnails;
	public $title;
	public $duration;
}

// Error data class
class YT_error {
	public $code;
	public $message;
}


// Ajax responses (admin panel only)...

	if ($_POST['action']=="remove_video"){
		// get rid of the "v" before position index
		$entity = substr($_POST['entity'], 1);
		$entity++;
		$broad = new broadcasting_interface($_POST['broadcasting']);
		$broad->remove_video($entity);
		$admin = new admin_panel;
		@$admin->print_broadcasting($_POST['broadcasting']);;
		exit();
	}
	if ($_POST['action']=="change_video"){
		// get rid of the "v" before position index
		$entity = substr($_POST['entity'], 1);
		$broad = new broadcasting_interface($_POST['broadcasting']);
		@$broad->change_video($entity, $_POST['newurl']);
		$admin = new admin_panel;
		@$admin->print_broadcasting($_POST['broadcasting']);
		exit();
	}
	if ($_POST['action']=="add_video"){
		$target = $_POST['target'];
		echo "<p>Video URL:</p> 
			 <input type=\"text\" id=\"textadd${target}\" class=\"textinput\">
			 <button class=\"metrobtn save btn_small\" value=\"$target\"></button></input>";
		exit();
	}
	if ($_POST['action']=="add_video_save"){
		// get rid of the "v" before position index
		$entity = substr($_POST['entity'], 1);
		$entity++;
		$broad = new broadcasting_interface($_POST['broadcasting']);
		@$broad->add_videos_from_urls($_POST['newurl'], $entity);
		$admin = new admin_panel;
		@$admin->print_broadcasting($_POST['broadcasting']);
	}
	if ($_POST['action']=="edit_video_press"){
		$target = $_POST['target'];
		$value = $_POST['value'];
		echo "<p class=\"videourl\">Video URL:</p>
			 <input type=\"text\" value=\"$value\" id=\"text$target\">
			 <button class=\"metrobtn btn_big save\" value=\"$target\"></button>
			 </input>";
	}
	if ($_POST['action']=="moveup_video") {
		// get rid of the "v" before position index
		$entity = substr($_POST['entity'], 1);
		$entity++;
		$broad = new broadcasting_interface($_POST['broadcasting']);
		$broad->move_video($entity, "up");
		$admin = new admin_panel;
		@$admin->print_broadcasting($_POST['broadcasting']);
		exit();
	}
	if ($_POST['action']=="movedown_video") {
		// get rid of the "v" before position index
		$entity = substr($_POST['entity'], 1);
		$entity++;
		$broad = new broadcasting_interface($_POST['broadcasting']);
		$broad->move_video($entity, "down");
		$admin = new admin_panel;
		@$admin->print_broadcasting($_POST['broadcasting']);
		exit();
	}
	if ($_POST['action']=="load_bc_settings") {
		$toolbox = new toolbox;
		$broad = new broadcasting_interface;
		$broadcasting_data = $broad->broadcasting_data($_POST['broadcasting']);
		$starting_time = $broadcasting_data->starting_time;
		$title = $broadcasting_data->title;
		$days = $broadcasting_data->days;
		$width = $broadcasting_data->player_width;
		$height = $broadcasting_data->player_height;
		$advice = $broadcasting_data->advice;
		$shift = $broadcasting_data->time_shift;
		$keys = array(-12,-10,-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9,10,11,12);
		$tz = array_fill_keys($keys, "");
		$tz[trim($shift)]="selected=\"selected\"";
		$pieces = explode(":", $starting_time);
		$days = explode(",", $days);
		if ($broadcasting_data->loop == "yes") {
			$checked_loop = "checked";
		}
		if ($broadcasting_data->controls == "yes") {
			$checked_controls = "checked";
		}
		$toolbox->html_broadcasting_title_admin_panel($title, $starting_time);
		$toolbox->html_broadcasting_settings_form($title, $pieces[0], $pieces[1], $days, $checked_loop, $width, $height, $advice, $tz, $checked_controls);
		exit();
	}
	if ($_POST['action']=="save_bc_settings") {
		$admin = new admin_panel;
		$time1 = $_POST['time1'];
		$time2 = $_POST['time2'];
		if (empty($_POST['title']) || empty($time1) || $time1 > 23 || $time2 > 59 || !is_numeric($time1) || !is_numeric($time2) || !is_numeric($_POST['width']) || !is_numeric($_POST['height'])) {
			@$admin->print_broadcasting(null);
			exit();
		}
		$broad = new broadcasting_interface($_POST['broadcasting']);
		$array['title'] = $_POST['title'];
		if (empty($time2)) {
			$time2 = "00";
		}
		$array['start'] = $time1.":".$time2;
		$checked = $_POST['days'];
		if (!empty($checked)) {
			foreach ($checked as $k => $v) {
				$days .= $k.","; 
			}
			$array['days'] = $days;
		}
		$array['width'] = $_POST['width'];
		$array['height'] = $_POST['height'];
		$array['advice'] = $_POST['advice'];
		$array['shift'] = $_POST['shift'];
		if(isset($_POST['loop'])){
			$loop = "yes";
		} else {
			$loop = "no";
		}
		$array['loop'] = $loop;
		if(isset($_POST['controls'])){
			$controls = "yes";
		} else {
			$controls = "no";
		}
		$array['controls'] = $controls;
		$broad->save_broadcasting_settings($array);
		//@ to avoid warnings on shiny new broadcastings
		@$admin->print_broadcasting($_POST['title']);
	}
	if ($_POST['action']=="select_broadcasting") {
		$admin = new admin_panel();
		//@ to avoid warnings on shiny new broadcastings
		@$admin->print_broadcasting($_POST['broadcasting']);
	}
	if ($_POST['action']=="delete_broadcasting") {
		$broad = new broadcasting_interface($_POST['broadcasting']);
		$broad->delete_broadcasting($_POST['broadcasting']);
		$admin = new admin_panel;
		//@ to avoid warnings on shiny new broadcastings
		@$admin->print_broadcasting();
		exit();
	}
	if ($_POST['action']=="add_broadcasting") {
		$broad = new broadcasting_interface;
		$admin = new admin_panel;
		$time1 = $_POST['time1'];
		$time2 = $_POST['time2'];
		if (empty($_POST['title']) || empty($time1) || $time1 > 23 || $time2 > 59) {
			@$admin->print_broadcasting_list();
			exit();
		}
		$checked = $_POST['days'];
		if (!empty($_POST['days'])) {
			foreach ($checked as $k => $v) {
				$days .= $k.","; 
			}
		}
		if (empty($time2)) {
			$time2 = "00";
		}
		$time = $time1.":".$time2;
		$broad->add_broadcasting(trim($_POST['title']), $time, $days);
		//@ to avoid warnings on shiny new broadcastings
		@$admin->print_broadcasting_list();
		exit();
	}
	if ($_POST['action']=="import_playlist"){
		$toolbox = new toolbox;
		$broadcasting = $_POST['broadcasting'];
		$toolbox->html_broadcasting_title_admin_panel($broadcasting);
		$toolbox->html_import_playlist($broadcasting);
		exit();
	}
	if($_POST['action']=="import_playlist_save"){
		$toolbox = new toolbox;
		if (!empty($_POST['playlist'])) {
			$broadcasting = $_POST['broadcasting'];
			$broad = new broadcasting_interface($broadcasting);
			if(!$broad->add_videos_from_playlist_url($_POST['playlist'],false)){
				$toolbox->html_broadcasting_title_admin_panel($broadcasting);
				$toolbox->html_import_playlist($broadcasting, "Invalid Playlist URL!");
				exit();
			};
		}
		$admin = new admin_panel;
		@$admin->print_broadcasting($broadcasting);
		exit();
	}
?>
