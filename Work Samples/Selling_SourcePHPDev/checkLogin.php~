<?php
function connectToMySQL($server,$user,$pssword,&$message)
{
   $isConnected ="Connection Made";
   if($conn = mysql_connect($server,$user,$pssword))
     {
       $message = $isConnected;
       return $conn;
     }

   else
    {
    $message ='Could not connect: ' . mysql_error();
    return NULL;
    }
}


function executeSQL($sql,$conn,&$message)
{
//execute SQL
$querySucceeded = "Query succeeded";

if($result = mysql_query($sql,$conn))
   {
   $message = $querySucceeded;
   return $result;
   }
else 
   {
   $message= "<br/>Query error: ". mysql_error();
   return NULL;
   }
}


$usrnm = $_GET["username"];
$passwrd = $_GET["password"];

if($usrnm && $passwrd)
{
	  $conn=connectToMySQL("localhost","richard","syp3rt",$mess);  //connect to MySQL
	  mysql_select_db("example");
	 
	  $sql = "SELECT username, password FROM login WHERE username = ".$usrnm." AND "."password = ".$passwrd;
	  $esql = executeSQL($sql,$conn,$mess);
	  //echo $mess;
	  if(!esql)
     	  {
		    $mess .= "--Login IS NOT Valid!!";
			die($mess);
    	  }
	  else
		  {
		  echo "Login is Valid!";
		  }
  
}
?>
