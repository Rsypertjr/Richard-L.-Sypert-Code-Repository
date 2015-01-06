<?php


//$seq = array();
//$retArr= array();
//$retPos= 0;



function assocArray($m,&$resarr, $c/*for comments*/,$ss/*strip spaces*/,$prev)  
{
        global $turns;
        $index=0;
       	foreach ($m as $key=>$value)
		  {
			  if(!is_array($value))
                           {
			     if($c) echo $key.'=>'.$value.'<br />';
                             $resarr[$turns-$prev][$key]=$value;

                             if($ss&&$key=0) 
                                $value = str_replace(" ", "", $value);
                            
                             if($index=1) 
                                $index=0;
                             else
                                $index++;
                                  
                           }
			  else
			   {
                            if(is_array($value))
                              {
			       if($c) echo "Value #: ".$turns.'<br />';
                               assocArray($value,$resarr,$c,$tr,$prev);
                               $turns++;
                              }
                            else 
                              break;
                             
			   }
		 }
        
        return $turns;
}


function findMotifs($conn,$seq,$l1,$l2,$patt,$acc,$sName,$sLength)
{

$soSeq = strlen((string)$seq);
$seqStr = array();
$seqStr = str_split($seq);


//echo $conn."<br/>".$seq."<br/>".$l1."<br/>".$l2."<br/>".$patt."<br/>".$acc."<br/>".$sName."<br/>".$sLength;
for($i1=0;$i1<$soSeq;$i1++)
   {
    if($seqStr[$i1]==$l1)
       {
       for($i2=$i1+1;$i2<$soSeq;$i2++)
           if($seqStr[$i2]==$l2)
               {
                $sl=($i2-$i1+1);
                $ss= substr($seq,$i1,$sl);
                $st= ($i1+1);
                $end=($i2+1);
                //echo "Start Position: ".$st."  End Position: ".$end."<br/>";
                //$retPos[]=$i1;
                $sql = "INSERT INTO miniMotif (motifPattern,actualMotif,accessionNumber,speciesName,proteinLength,motifLength,startPosition,endPosition) values('$patt','$ss','$acc','$sName','$sLength','$sl','$st','$end')";
                //execute SQL
                $result = executeSQL($sql,$conn,$mess);
               // if(!$result)
                //     die($mess);
		
               }
       }
   }

}

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

function proteinDatabaseCreate($sql,$conn,&$message)
{
  $databaseCreated ="Database created";
  if ($result=mysql_query($sql,$conn))
  {
	  $message = $databaseCreated;
	  return $result;
  }
  else
  {
	  $message = "<br/>Error creating database: " . mysql_error();
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

function buildProteinDb($filestr)  // function for building Protein Database
{
$fileString=$filestr;
$conn = 0;
$gi_number= array(array());
$accession_num= array(array());
$spcName= array(array());
$locus= array(array());
$seqnc= array(array());
$miniM= array();
$seqLength=array();
$arrOfPatts=array();
$num_Names = 0;
$num_Locus = 0;
$num_Species = 0;
$num_Acc_Nums = 0;
$num_Seqs = 0;


$pattern = '/gi\|[0-9]+\|ref/';


echo "Unless you see any errors, the tables are ready!";
preg_match_all($pattern, $fileString, $matches, PREG_OFFSET_CAPTURE);
$trns = assocArray($matches,$gi_number,0,0,0);
$num_Species = $trns;
//echo "Number of Species: ".$num_Species.'<br/>';

$pattern = '/ref\|[A-Za-z0-9(\.)(_)]+\|/';
preg_match_all($pattern, $fileString, $matches2, PREG_OFFSET_CAPTURE);
$trns2=assocArray($matches2,$accession_num,0,0,$trns);
$num_Acc_Nums = $trns2-$trns;
//echo "Number of Accessions: ".$num_Acc_Nums.'<br/>';


$pattern = '/\|\s+[A-Za-z]+[\w|\d|\s|\-|:|;|\(|\)|\,|\/|]+\[/';
preg_match_all($pattern, $fileString, $matches3, PREG_OFFSET_CAPTURE);
$trns3=assocArray($matches3,$locus,0,0,$trns2);
$num_Locus = $trns3-$trns2;
//echo "Number of Names: ".$num_Locus.'<br/>';

$pattern = '/[A-Z][A-Z]{50}[\w|\s]+[A-Z]/';
preg_match_all($pattern, $fileString, $matches4, PREG_OFFSET_CAPTURE);
$trns4=assocArray($matches4,$seqnc,0,0,$trns3);
$num_Seqs = $trns4-$trns3;
//echo "Number of Sequences: ".$num_Seqs.'<br/>';


$pattern = '/\[[\w|\s|\(|\)|\-|\.]*\]/';
preg_match_all($pattern, $fileString, $matches5, PREG_OFFSET_CAPTURE);
$trns5=assocArray($matches5,$spcName,0,0,$trns4);
$num_Names=$trns5-$trns4;
//echo "Number of Species Names: ".$num_Names.'<br/>';


//Create Protein Database

$conn=connectToMySQL("localhost","richard","syp3rt",$mess);  //connect to MySQL

if(!$conn)
  die($mess);



$dbName="proteins";                          //create Protein Database
$createDb = "CREATE DATABASE ";
$sql= $createDb.$dbName;

$result = proteinDatabaseCreate($sql,$conn,$mess);

if(!$result) 
  {
  if(stripos($mess,"database exists"))
        mysql_select_db($dbName);
  else
        die($mess);
  }
else
       mysql_select_db($dbName);


$sql = "CREATE TABLE protein (id int not null primary key auto_increment, locus varchar(75),speciesNumber varchar(75),speciesName varchar(75), accessionNumber varchar(75), proteinSequence varchar(2000), proteinLength int)";

//execute SQL
$esql = executeSQL($sql,$conn,$mess);
if(!$esql)
    //if(!stripos($mess,"already exists"))
      die($mess);
else
{
             
               
		for($i=0;$i<$num_Seqs;$i++)  // clean up strings and fill protein database
		{
		   $locus[$i][0]= substr($locus[$i][0],1,strlen($locus[$i][0])-2);  // locus strings
		   $lc = $locus[$i][0];
		   //echo '<br/>'.$lc;

		   $gi_number[$i][0]= substr($gi_number[$i][0],3,strlen($gi_number[$i][0])-7);  //  gi-number or species-number
		   $gi = $gi_number[$i][0];
		   //echo '<br/>'.$gi;

		   $accession_num[$i][0]= substr($accession_num[$i][0],4,strlen($accession_num[$i][0])-5);  //  accession numbers
		   $acc = $accession_num[$i][0];
		   //echo '<br/>'.$acc;

		   $seqnc[$i][0]= substr($seqnc[$i][0],4,strlen($seqnc[$i][0]));  //  sequences
		   $sq =  $seqnc[$i][0];
		   //echo '<br/>'.$sq;

		   $spcNm = $spcName[$i][0];      // species Names

		   $seqLength[$i] = strlen($sq);
		   //echo '<br/> Length of String:  '.$locus_length[$i];

		   // build sql insert statement'

		//execute SQL
			   
			   $sql = "INSERT INTO protein values('$i', '$lc', '$gi','$spcNm', '$acc', '$sq','$seqLength[$i]' )";
			   $esql = executeSQL($sql,$conn,$mess);
                           
		} 
  
       
}

buildMinimotifDatabase($conn);

}  // End of buildProteinDb function

function buildMinimotifDatabase($conn)
{
       
        $num_rows=0;
	$amino_code = array();
	$amino_code[]= 'G';
	$amino_code[]='P';
	$amino_code[]='A';
	$amino_code[]='V';
	$amino_code[]='L';
	$amino_code[]='I';
	$amino_code[]='M';
	$amino_code[]='C';
	$amino_code[]='P';
	$amino_code[]='Y';
	$amino_code[]='W';
	$amino_code[]='H';
	$amino_code[]='K';
	$amino_code[]='R';
	$amino_code[]='Q';
	$amino_code[]='N';
	$amino_code[]='E';
	$amino_code[]='D';
	$amino_code[]='S';
	$amino_code[]='T';

// Create mini-Motif database
   
   
   $sql = "CREATE TABLE miniMotif (id int not null primary key auto_increment,motifPattern varchar(75),actualMotif varchar(1500),accessionNumber varchar(75),speciesName varchar(75), proteinLength int, motifLength int, startPosition int, endPosition int )";

    // execute SQL
    $esql = executeSQL($sql,$conn,$mess);
    if(!$esql)
       //if(!stripos($mess,"already exists"))
          die($mess);
    else
      {
       // Building MiniMotifs
             
             $sql = "SELECT * FROM protein";

	     $result = mysql_query($sql, $conn);  // check for already existing miniMotif table
             //$num_rows = mysql_num_rows($result);
	     while($newArray = mysql_fetch_array($result)) // One Protein per Loop
		      {
			  $seq = $newArray[proteinSequence];
			  $acc = $newArray[accessionNumber];
			  $spName = $newArray[speciesName];
			  $sqName = $newArray[seqName];
			  $sqLength = $newArray[proteinLength];

			  //echo "The seq is: ".$seq;
			  $so = sizeof($amino_code);
                          for($k=0;$k<$so;$k++)
			     {
			      for($j=0;$j<$so;$j++)
				  {
				  $pattrn = $amino_code[$k]."XX".$amino_code[$j];
				  //echo "miniMotif Pattern: ".$pattrn."<br/>";
				  
				  findMotifs($conn,$seq,$amino_code[$k],$amino_code[$j],$pattrn,$acc,$spName,$sqLength);
				  }
			       
			     }  // end for
			
		     }//End While
   

     }//end else
        
  
}  // end of buildMinimotifDatabase


function query3($conn,&$cntarr,$mopat)
{
// Begin calculation of count of motifs for all accession numbers, subst. for query 3 below query 0 is the first
  $sql = 'SELECT DISTINCT accessionNumber FROM miniMotif WHERE motifPattern = "'.$mopat.'" ORDER BY accessionNumber';
  $esql = executeSQL($sql,$conn,$mess);
  $sarr = array();
  //$cntarr = array();
  //echo $mopat;
  if(!esql)
    die($mess);
  else
    {
     while($newArray = mysql_fetch_array($esql))
       { 
       $sarr[]=$newArray[accessionNumber];
      
        }
    }
    //echo "acc nos: ".sizeof($sarr);   
     //print_r($sarr);

 
  for($w=0;$w<sizeof($sarr);$w++)
   {
    //echo $sarr[$w];
     $sql = 'SELECT COUNT(p.actualMotif) FROM (SELECT actualMotif, accessionNumber, motifPattern FROM miniMotif WHERE motifPattern = "'.$mopat.'" AND accessionNumber = "'.$sarr[$w].'")p';
     //echo $sql.'<br/>';
     $esql = executeSQL($sql,$conn,$mess);
	  if(!esql)
	    die($mess);
	  else
             {
              
              while($newArray = mysql_fetch_array($esql))
	       { 
               
	       //print_r($newArray);
               $cntarr[] = $newArray[0];
		}
             
             }
 
   }

    //print_r($cntarr);  //End of motif count for all accession numbers

 
}



if(isset($_GET["file"]) && !isset($_GET["motif"]))
  {
        $file = $_GET["file"];
        //$file = "FASTA-TEST.txt";
	$fileString = file_get_contents($file);
        buildProteinDb($fileString);   // call function to Build Protein Database
        
        
 
  }
if(isset($_GET["motif"]))
  {
  
  $mo = array();
  $mo = str_split($_GET["motif"],1);
  $moPatt = $mo[0]."XX".$mo[1];
  //echo "gets amino #1: ".$mo[0]."<br/>";
  //echo "gets amino #2: ".$mo[1]."<br/>";
  //echo "gets question: ".$mo[2]."<br/>";
  //echo "motif pattern: ".$moPatt."<br/>";

  $conn=connectToMySQL("localhost","richard","syp3rt",$mess);  //connect to MySQL
  
  mysql_select_db("proteins");

 

  $qstr = array();
  $qstr[] = 'SELECT DISTINCT accessionNumber, motifPattern FROM miniMotif WHERE motifPattern = "'.$moPatt.'" ORDER BY accessionNumber';
  $qstr[] = 'SELECT actualMotif, motifPattern, accessionNumber, motifLength FROM miniMotif WHERE motifPattern = "'.$moPatt.'" ORDER BY accessionNumber, motifLength';
  $qstr[] = 'SELECT DISTINCT startPosition, motifPattern, accessionNumber FROM miniMotif WHERE motifPattern = "'.$moPatt.'" ORDER BY accessionNumber, startPosition';
  $qstr[] = 'SELECT  DISTINCT accessionNumber, motifPattern FROM miniMotif WHERE motifPattern = "'.$moPatt.'" ORDER BY accessionNumber';
  $qstr[] = 'SELECT DISTINCT speciesName, motifPattern FROM miniMotif WHERE motifPattern = "'.$moPatt.'" ORDER BY speciesName';
  $qstr[] = 'SELECT AVG(DISTINCT p.proteinLength), motifPattern FROM (SELECT DISTINCT actualMotif,motifPattern, accessionNumber, proteinLength  FROM miniMotif WHERE motifPattern = "'.$moPatt.'")p';
  //echo "check query: ".$qstr[5];
  $qu = array(array('accessionNumber','motifPattern','',''),
               array('actualMotif','motifPattern','accessionNumber','motifLength'),
               array('startPosition','motifPattern','accessionNumber',''),
               array('accessionNumber','motifPattern','',''),
               array('speciesName','motifPattern','',''),
               array('AVG(DISTINCT p.proteinLength)','motifPattern','',''));

  $qu1 = array(array('Accession Number','Motif Pattern','',''),
               array('Actual Motif','Motif Pattern','Accession Number','Motif Length'),
               array('Start Position','Motif Pattern','Accession Number',''),
               array('Accession Number','Number of '.$moPatt.' motifs<br/>Per Protein (accession Number)','',''),
               array('Species Name','Motif Pattern','',''),
               array('AVG Protein Length','Motif Patterns','',''));


   



  

  


 
  
  
  $in = $mo[2];   // question number 
  $sql= $qstr[$in];
  //echo $qu[$in][1];
  $esql = executeSQL($sql,$conn,$mess);
  //echo $mess;

  if(!esql)
    die($mess);
  else
    {

   $tp = 0;
   if($in == 1)
     $tp = 4;
   else if ($in ==2 || $in == 0 )
     $tp = 3;
  //else if ($in == 5)
   //  $tp = 1;
   else
     $tp = 2;


  if($in==3) 
     {
      $cntarry = array();
      query3($conn,$cntarry,$moPatt);
     }
     //echo "check: ".$tp;
     echo "<table border ='1'>";
    
	   echo"<tr>";
	     for($x=0;$x<$tp;$x++)
	       echo "<th>".$qu1[$in][$x]."</th>";
	   echo "</tr>";
     $count  = 0;
     while($newArray = mysql_fetch_array($esql))
       { 
         //print_r($newArray);
         echo "<tr>";
         for($z=0;$z<$tp;$z++)
           {
              if($in == 3 && $z==1)  // substitute for query3 for motif counts per protein
                echo "<td>".$cntarry[$count]."</td>";
              else
                echo "<td>".$newArray[$qu[$in][$z]]."</td>";
           }
          echo "</tr>";
       $count++;   
       }
      echo "</table>";
      

    } 

   //print $tbstr;  


  }


?>

