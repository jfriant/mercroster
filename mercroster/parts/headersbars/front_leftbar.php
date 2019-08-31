<?php
include_once "includes/StringFunctions.php";
if(!defined('kgE3c68Fg2bnM'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

if(isset($_SESSION['SESS_TYPE']))
{
  require("htdocs/dbsetup.php");
  $readpermission=stripslashes($_SESSION['SESS_TYPE']);
  $readpermission=html_escape($readpermission);
  $readpermission=strip_tags($readpermission);
}
else
{
  $readpermission=6;
}

if (isset($_GET['page'])) { $pageID = $_GET['page']; } else { $pageID = ""; }
$pageID = stripslashes($pageID);
$pageID = html_escape($pageID);

$pagesResult=$dbf->queryselect("SELECT id, name FROM pages ORDER BY prefpos ASC;");

$pageResult=$dbf->queryselect("SELECT * FROM pages WHERE id='{$pageID}';");
$pageArray=mysqli_fetch_array($pageResult, MYSQLI_ASSOC);

$currentDate = "";
$contractResult=$dbf->queryselect("SELECT employer, target, start, end FROM contracts WHERE start<='{$currentDate}' AND end>='{$currentDate}' ORDER BY start ASC;");
$logResult=$dbf->queryselect("SELECT r.id, r.logtype, r.topic, l.type, r.start FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id WHERE l.readpermission>={$readpermission} ORDER BY r.start DESC, r.opdate DESC, r.id ASC LIMIT 0, 15;");

$headerResult=$dbf->queryselect("SELECT name, motto, image FROM command WHERE id='1';");
$headerArray=mysqli_fetch_array($headerResult, MYSQLI_ASSOC);

$currentLocation="";
$currentEmployer="";
while($array=mysqli_fetch_array($contractResult, MYSQLI_ASSOC))
{
  $currentLocation=$currentLocation." ".$array['target'];
  $currentEmployer=$currentEmployer." ".$array['employer'];
}
if($currentLocation=="")
{
  $currentLocation="Hiring Halls";
}
if($currentEmployer=="")
{
  $currentEmployer="None";
}

if(isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!=''))
{
  $logidArray=array();
  array_push($logidArray, "0");
  $lastCommentArray=array();
  array_push($lastCommentArray, "0");
  $visitedLogsResult=$dbf->queryselect("SELECT logid, lastcomment FROM logsvisited WHERE member='{$data}' ORDER BY logid ASC;");
  while($array=mysqli_fetch_array($visitedLogsResult, MYSQLI_ASSOC))
  {
    array_push($logidArray, "{$array['logid']}");
    array_push($lastCommentArray,  "{$array['lastcomment']}");
  }
}

echo "<div id='leftbar'>\n";

echo "<div class='sidetableheader'>\n";
echo "{$headerArray['name']}\n";
echo "</div>\n";
echo "<div class='sidetablebody'>\n";
echo "<img class='unitlogo' src=images/commandimages/{$headerArray['image']}>\n";
echo "<br><i>{$headerArray['motto']}</i>\n";
echo "</div>\n";

echo "<div class='sidetableheader'>\n";
echo "Front Navigation\n";
echo "</div>\n";
echo "<div class='sidetablebody'>\n";

echo "<ul>\n";
echo "<li class='oldtopic'><a class='newstable' href='index.php?action=main'>Home</a></li>\n";
while($array=mysqli_fetch_array($pagesResult, MYSQLI_ASSOC)) {
	echo "<li class='oldtopic'><a class='newstable' href='index.php?action=pages&page={$array['id']}'>{$array['name']}</a></li>\n";
}
echo "</ul>\n";

echo "</div>\n";

if($action!="pages" || $pageArray['game']==1) {
	echo "<div class='sidetableheader'>\n";
	echo "Game data\n";
	echo "</div>\n";
	echo "<div class='sidetablebody'>\n";
	echo "<b>Date:</b><br />\n";
	echo "{$currentGameDate}<br />\n";
	echo "<b>Located on:</b><br />\n";
	echo "{$currentLocation}<br />\n";
	echo "<b>Employed by:</b><br />\n";
	echo "{$currentEmployer}<br />\n";
	echo "</div>\n";
}

if(($action!="pages" && $action!="main") || $pageArray['news']==1) {
	echo "<div class='sidetableheader'>\n";
	echo "Latest News\n";
	echo "</div>\n";
	echo "<div class='sidetablebody'>\n";
	echo "<ul>\n";
	while($array = mysqli_fetch_array($logResult, MYSQLI_ASSOC))
	{
	  if((isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!='')) && array_search($array['id'], $logidArray)==false)
	  {
	    echo "<li class='newtopic'><small>{$array['type']}:</small><br />\n";
	    echo "<a class='newstable' href='index.php?action=news&amp;log=" . $array['id'] . "&amp;first=0'>" . $array['topic'] . "</a></li>\n";
	  }
	  else
	  {
	    echo "<li class='oldtopic'><small>{$array['type']}:</small><br />\n";
	    echo "<a class='newstable' href='index.php?action=news&amp;log=" . $array['id'] . "&amp;first=0'>" . $array['topic'] . "</a></li>\n";
	  }
	}
	echo "</ul>\n";
	echo "</div>\n";
}

if(isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!=''))
{
  //$users=$userfuntions->checkUsers($dbf);
  if($guests>0)
  {
    $guestString=($guests==1) ? "{$guests} Guest" : "{$guests} Guests";
  } else {
      $guestString = "";
  }

  echo "<div class='sidetableheader'>\n";
  echo "Users Online\n";
  echo "</div>\n";
  echo "<div class='sidetablebody'>\n";
  echo "{$guestString}";
  $i=sizeof($users);
  if($i>0)
  {
    $userString=($i==1) ? "{$i} User\n" : "{$i} Users\n";
    if($guests>0)
    {
      echo ", ";
    }
    echo "{$userString}<br />";

    foreach ($users as $username)
    {
      echo "$username <br />\n";
    }
  }
  echo "</div>\n";
}
echo "</div>\n";
?>