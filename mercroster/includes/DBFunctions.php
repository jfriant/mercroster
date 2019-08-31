<?php

class DBFunctions
{
  /**
   * This Funtion is used to make SELECT qeuries and then return result array
   * @param <array> $queryArray
   * @return mysqli_result
   */
  function queryselect($queryArray)
  {
    require("htdocs/dbsetup.php");
    $result = mysqli_query($conn, $queryArray);
    mysqli_close($conn);
    if(!$result )
    {
      die('SQL ERROR '. $queryArray);
    }
    return $result;
  }

  /**
   * This function is used to make INSERT and UPDATE queries.
   * @param <mysqli query> $queryArray
   */
  function queryarray($queryArray)
  {
    require("htdocs/dbsetup.php");
    for($i=0; $i<sizeof($queryArray);$i++)
    {
      $result=mysqli_query($conn, $queryArray[$i]);
      if(!$result)
      {
          echo "ERROR!  Failed to perform SQL query: ";
          echo 'query :' . $i . ' : ' . $queryArray[$i] . '';
          die(mysqli_error($conn));
      }
    }
    mysqli_close($conn);
  }

    /**
     * This function is used to return array of arrays containing quered table data
     * @param <mysqli query> $result
     * @return array
     */
  function resulttoarray($result)
  {
    $counter=0;
    $returnArray = array();
    while($array = mysqli_fetch_array($result, MYSQLI_BOTH))
    {
      $returnArray[$counter]=$array;
      $counter++;
    }
    return $returnArray;
  }

  /**
   * This function is used to return array containing quered table data
   * @param <mysqli query> $result
   * @return array
   */
  function resulttoarraysingle($result)
  {
    $counter=0;
    while($array = mysqli_fetch_array($result, MYSQLI_BOTH))
    {
      $returnArray[$counter]=$array[0];
      $counter++;
    }
    return $returnArray;
  }
}
?>