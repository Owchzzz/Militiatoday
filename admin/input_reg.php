<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

$db = mysql_connect("localhost","militiat_chadmin","Temp0114!") or
die ("Unable to connect to Database Server.");
mysql_select_db ("militiat_brandcoder", $db) or die ("Could not select database.");;

$cid=$_POST['cid'];
$email=$_POST['email'];
$status=$_POST['status'];
$pwd=md5($_POST['pwd']);
$descrip=$_POST['descrip'];
$url=$_POST['url'];

mysql_query("INSERT INTO channels(channelID, email, status, password, description,pathurl)
values('$cid','$email','$status','$pwd','$descrip','$url')");


$fld = '../u/'.$cid.'/';

function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true)
{
  if ($zip = zip_open($src_file))
  {
    if ($zip)
    {
      $splitter = ($create_zip_name_dir === true) ? "." : "/";
      if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
     
      // Create the directories to the destination dir if they don't already exist
      create_dirs($dest_dir);
      echo "$dest_dir created!";
      // For every file in the zip-packet
      while ($zip_entry = zip_read($zip))
      {
        // Now we're going to create the directories in the destination directories
       
        // If the file is not in the root dir
        $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
        if ($pos_last_slash !== false)
        {
          // Create the directory where the zip-entry should be saved (with a "/" at the end)
          create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
        }

        // Open the entry
        if (zip_entry_open($zip,$zip_entry,"r"))
        {
         
          // The name of the file to save on the disk
          $file_name = $dest_dir.zip_entry_name($zip_entry);
         
          // Check if the files should be overwritten or not
          if ($overwrite === true || $overwrite === false && !is_file($file_name))
          {
            // Get the content of the zip entry
            $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            file_put_contents($file_name, $fstream );
            // Set the rights
		$db_file = "'../u/$cid/youtv.xml';";
		 chown($db_file, "apahce");
                 chgrp($db_file, "apache"); 
	        chmod($db_file, 0755);
           // echo "save: ".$db_file."<br />";
          }
         
          // Close the entry
          zip_entry_close($zip_entry);
        }      
      }
      // Close the zip-file
      zip_close($zip);
    }
  }
  else
  {
    return false;
  }
 
  return true;
}

/**
 * This function creates recursive directories if it doesn't already exist
 *
 * @param String  The path that should be created
 * 
 * @return  void
 */
function create_dirs($path)
{
  if (!is_dir($path))
  {
    $directory_path = "";
    $directories = explode("/",$path);
    array_pop($directories);
   
    foreach($directories as $directory)
    {
      $directory_path .= $directory."/";
      if (!is_dir($directory_path))
      {
        mkdir($directory_path);
//        chmod($directory_path, 0777);
      }
    }
  }
}


unzip("studio.zip", "$fld", true, false);


// $serverurl = "http://brandcoder.com"; //always the same
// $username = "ext_mycoolchann"; //this is the channelID
//wait on this
$myFile = $fld."config.php";
$fh = fopen($myFile, 'w');
$stringData = "<?php \$adminu = '".$cid."'; \$adminp = '".$_POST['pwd']."'; ?>";
fwrite($fh, $stringData); 

////mail///////
$from_address = "webtv@militiatoday.com";
$cc="jsinger.militia@techriver.net";
$bcc=$cc;
$to=$email;
$headers = "From: webtv@militatoday.com\r\n";

$headers .= "CC:".$cc." \r\n";



$headers .= "BCC:".$bcc."\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= 'Content-Type: text/html; charset=UTF-8'."\r\n";
$headers .='X-Mailer: PHP/' . phpversion();
$subject = '=?UTF-8?B?'.base64_encode("Your Militia Today Channel ID").'?=';
 


$body = "Your Militia Today Channel has been created! \r Channel ID: ".$cid.". \n Password: ".$_POST['pwd']."  \r  NOTE: Please save this data for future reference! Use this Channel ID and password to Login and begin managing your channels. \r\n If you have any questions, please feel free to contact us at webtv@militiatoday.com. \r\n Thanks!";
 
 if (mail($to, $subject, $body, $headers,"-f ".$from_address)) {
   echo("<p>Message successfully sent!</p>");
  } else {
   echo("<p>Message delivery failed...</p>");
  }


?>

