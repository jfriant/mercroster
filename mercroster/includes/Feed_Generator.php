<?php

class Feed_Generator
{
  private $dbf;

  function __construct($dbfunctions)
  {
    $this->dbf=$dbfunctions;
  }

  function parsefeed()
  {
    require("BBFunctions.php");
    $bbf=new BBFunctions;

    $headerResult = $this->dbf->queryselect("SELECT name, motto, description, image, services, contact, main FROM command WHERE id='1';");
    $logResult = $this->dbf->queryselect("SELECT r.id, r.logtype, r.topic, l.type, r.start, r.text FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id ORDER BY r.start DESC, r.opdate DESC, r.id ASC LIMIT 0, 10;");

    while($array=mysql_fetch_array($headerResult, MYSQL_ASSOC))
    {
      $details = '<?xml version="1.0" encoding="ISO-8859-1" ?>
	                <rss version="2.0"> 
	                    <channel> 
	                        <title>'. $array['name'] .'. '. $array['motto'] .'</title> 
	                        <link>http://koti.kapsi.fi/~darkkis/bt/gkk/index.php</link> 
	                        <description>'. $bbf->removeTags($array['description']) .'</description> 
	                        <language>english</language>'; 
    }

    while($array=mysql_fetch_array($logResult, MYSQL_ASSOC))
    {
      $items .= '<item>
	                <title>'. $array["topic"] .'</title> 
	                <link>http://koti.kapsi.fi/~darkkis/bt/gkk/index.php?action=news&amp;log='. $array["id"] .'&amp;first=0</link> 
	                <description>'. $bbf->removeTags($array["text"]) .'</description> 
	            </item>'; 
    }
    $items .= '</channel>
	                </rss>'; 

    $feedString="$details " . " $items";

    $myFile="feeds/feed.xml";
    $fh=fopen($myFile, 'w') or die("can't open file");
    fwrite($fh, $feedString);
    fclose($fh);
  }
}
?>