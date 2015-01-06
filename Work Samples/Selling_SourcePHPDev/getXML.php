<!-- This file is called by the createFiles.php file which is the GUI for the program.  This file does the XSLT transformation -->
<!-- for the file set input on the GUI.  This file stores the file names in the namestore.txt file -->

<?php

$xml = $_GET["xmlFile"];
$xsl = $_GET["xslFile"];
$trans = $_GET["doTrans"];
$nstore = "namestore.txt";
//echo "xml: ".$xml."<br/>";
//echo "xsl: ".$xsl."<br/>";
//echo "trans: ".$trans."<br/>";



if($xml)
{
    unlink($nstore);
	$nstoreh = fopen($nstore, 'a') or die("can't open file");
	$filestr = file_get_contents($nstore);
	$filestr .= $xml."-";
	file_put_contents($nstore, $filestr);
	if($lines = file($xml))
	{
		foreach($lines as $line)
		{
		
		echo htmlspecialchars($line)."<br/>";
		}
	}
}

if($xsl)
{
	
	$filestr = file_get_contents($nstore);
	$filestr .= $xsl;
	file_put_contents($nstore, $filestr);
	if($lines = file($xsl))
	{
		foreach($lines as $line)
		{
		echo htmlspecialchars($line)."<br/>";
		}
	}
} 

if($trans)
{
   	$filestr = file_get_contents($nstore);
	$pieces = explode("-", $filestr);
	
	$xml = new DOMDocument();
	$xml->load($pieces[0]);
	

	$xsl = new DOMDocument;
	$xsl->load($pieces[1]);
	 
	$proc = new XSLTProcessor();
	$proc->importStyleSheet($xsl);

	echo htmlspecialchars($proc->transformToXML($xml));
}

 
?>