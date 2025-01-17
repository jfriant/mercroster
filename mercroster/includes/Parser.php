<?php
include_once "includes/StringFunctions.php";
class Parser
{
  /**
   * Funtion used to strip all kind of nasty thing out of _POST data
   * @param <string> $data
   * @return <string>
   */
  protected function strip($data)
  {
    require("htdocs/dbsetup.php");
    $data=stripslashes($data);
    $data=html_escape($data);
    $data=strip_tags($data);
    return $data;
  }
  
  /**
   * Function used to check whatever given argument in positive integer
   * @param $data
   * @return boolean
   */
  protected function checkposint($data)
  {
    if(ctype_digit($data) && $data>0)
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }
}
?>