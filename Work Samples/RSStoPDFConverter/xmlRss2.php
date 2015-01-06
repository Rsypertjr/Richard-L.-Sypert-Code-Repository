<!-- Recieves URL from GUI file rssToHtml.html and parses the RSS file which the 
URL represents and then uses HTML2PDF API to create PDF output which is sent back
from server to the client -->
<?php
 //echo $_POST[rssURL];

require('htmltopdf\html2fpdf.php');  
require('htmltopdf\fpdf.php');
 

if(isset($_POST[rssURL]))
{
 
	//$q = "http://news.google.com/news?pz=1&cf=all&ned=us&hl=en&output=rss";	
	$q = $_POST[rssURL];
	$xml = $q;
	
	$xmlDoc = new DOMDocument();
    $xmlDoc->load(trim($xml));
 
	$channel=$xmlDoc->getElementsByTagName('channel')->item(0);	
	
    //$pdf=new FPDF();
	$pdf=new HTML2FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	
	
	$name = "rssPDF.pdf";
	$x=40;
	$y=10;
	$fileHead = "<html><body>";
	$fileFoot = "</body></head>";
	$test = "This is a test string";

	$channelTitle= $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
	//echo $channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
	//echo $channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
	$channelImage = $channel->getElementsByTagName('image')->item(0)->childNodes->item(1)->nodeValue;
	
	$pdf->Cell($x,$y,$channelTitle);
	
	$pdf->Image($channelImage,10,$pdf->GetY()+10);
	
	
    $items = $channel->getElementsByTagName('item');
    $fileBody = "";
   foreach($items as $item)
	{
	   //echo $item_title=$item->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
	   //echo $item_link=$item->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
	   
	   $itemDesc =$item->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
		   
	   //echo htmlspecialchars($itemDesc);
	  
	   $pattern = '/img+?[\.|\w|\d|\s|=|\/|"]*jpg+?/';
	   preg_match($pattern, $itemDesc, $matches, PREG_OFFSET_CAPTURE);
	   
	   $itemImage = $matches[0][0];
	  
	   $srch = 'img src="';
	   $srch2 = 'src="';
	   $repl = 'src="http:';
	   $repl2 = 'http:';
	   $count = 1;
	   if($itemImage != null)
		{
		   $itemImage3 = str_replace($srch,$repl2,$itemImage,$count);
		   $pdf->WriteHTML('<style>div{float:right;top:0;width:50px;}</style><div>'.$itemDesc.'</div>');
		   $pdf->Image($itemImage3,2,$pdf->GetY()-52);
		   $pdf->SetX(0);
		   $pdf->SetY($pdf->GetY()+52);
	   }
	  
    }
	echo "<a id='plink' href='pdfDownload.php'>Download PDF file of RSS Feed.</a>";
	$pdf->Output($name);
}


?>

