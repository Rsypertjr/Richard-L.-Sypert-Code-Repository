<!--This file needs the getXML.php and namestore.txt file to run.  The namestore.txt file is an empty text file to begin with -->
<!-- The getXML.php file does the XSLT transformation while this file is the GUI where the XML and XSL file names are input  -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>


<head>
<style type="text/css">

button {position:relative;width:200px;margin:15px;padding:10px;color:red}
input{width:250px;padding:5px;text-align:center}
div {width:600px;border-style:none;border-width:2px)

</style>



<script type="text/javascript">
// JAVASCRIPT FUNCTIONS ---------------------------------------------------------------------------
var xmlFil = "";
var xslFil = "";
var doc = "";
function getXMLHttp()
{
  var xmlHttp;

  try
  {
    //Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    //Internet Explorer
    try
    {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e)
    {
      try
      {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e)
      {
        alert("Your browser does not support AJAX!")
        return false;
      }
    }
  }
  return xmlHttp;
}

function MakeRequest2(file,gfile,rec)
{


  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      document.getElementById(rec).innerHTML = xmlHttp.responseText;
	  
    }

  }
  
  xmlHttp.open("GET","getXML.php?"+gfile+"="+file , true);
  xmlHttp.send(null);

}



function MakeRequest(file,rec)
{


  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      document.getElementById(rec).innerHTML = xmlHttp.responseText;
    }

  }
  xmlHttp.open("GET",file, true);
  xmlHttp.send();
}


function HandleResponse(response,rec)
{
/*
  if(rec=="exc")
     {
		 //alert(response);
		 var filenm = response.split("-");
		 alert(filenm[0]);
		 var xmlRef = new ActiveXObject("Microsoft.XMLDOM");  // Load DOM for xml File
		 xmlRef.async="false";
		 var ck = xmlRef.load(filenm[0]);
		 //alert(ck);

	     var xslRef=new ActiveXObject("Microsoft.XMLDOM");  // Load DOm for xsl File
	     xslRef.async="false";
    	 var ck = xslRef.load(filenm[1]);
		 //alert(ck);
		 
		 var result = xmlRef.transformNode(xslRef);
		 //alert("THIS IS THE TRANSFORMED XML FILE:  " + "'\n'" + "'\n'" + result);
		 response = result;
		 document.getElementById(rec).innerText = response;
	 }
	else
	{  */
      document.getElementById(rec).innerHTML = response;
//	}
   
   
}





function displayXMLString()
{

  var filei1 = document.getElementById("xml").value;
  

  MakeRequest2(filei1,"xmlFile","exa");
  
}

function displayXSLString()
{

  
  var filei2 = document.getElementById("xsl").value;

 
  MakeRequest2(filei2,"xslFile","exb");
  
}



function clearXMLInput()
{
document.getElementById("xml").value = "";
}

function clearXSLInput()
{
document.getElementById("xsl").value = "";
}

function displayResult()
{

var filei3 = "namestore.txt";
MakeRequest2(filei3,"doTrans","exc");
}


getFileString()
{
alert(doc)
alert("press to continue");
//alert(doc);

/* var xmlRef = new ActiveXObject("Microsoft.XMLDOM");  // Load DOM for xml File
   xmlRef.async="false";
   var ck = xmlRef.load(xmlFil);
   alert(ck);

   var xslRef=new ActiveXObject("Microsoft.XMLDOM");  // Load DOm for xsl File
   xslRef.async="false";
   var ck = xslRef.load(xslFil);
   alert(ck);

    alert("getshere");
   var result = xmlRef.transformNode(xslRef);
   HandleResponse(result,"exa"); */


}


// END OF JAVASCRIPT FUNCTIONS --------------------------------------------------------------------------------
</script>
</head>
<body>
<?php
$xmlFile = "SelSource1xa.xml";
$xslFile = "SelSource1s.xml";
$xslString = "";
$xmlString = "";



//end of Functions







?>
<div id="page" style="position:absolute">
<div style="left:25px;height:50px"><h2 id="hdr">Input/View Files and Perform XSLT Transform</h2></div>

<form id="fileinp">
<div id="xmldiv">
<input type="text" id="xml" name="xmlFile" value="Please Input the XML File Name" onclick="clearXMLInput()"/><input type="text" id="xsl" name="xslFile" value="Please Input the XSL File Name" onclick="clearXSLInput()"/>
<input type="hidden" id="sv" name="sv" style="display:none" value=""/><br/>

<button type="button"  id ="xmlbut" style="left:15px;top:25px" onclick="displayXMLString();">Click Me to Show XML</button><button type="button"  id ="xmlbut" style="left:40px;top:25px" onclick="displayXSLString();">Click Me to Show XSL</button><br/><br/>
</div>

<div id="trans">
<button type="button"  id="dbut" style="left:135px;top:5px" onclick="displayResult();">Click Me For Transformed XML</button>
</div>
</form>

<div id="ex" style="position:fixed;width:1500px">
<div id="exa" style="position:absolute;left:5px;width:380px;overflow-x:scroll;overflow-y:scroll"></div>
<div id="exb" style="position:absolute;left:410;width:380px;overflow-x:scroll;overflow-y:scroll"></div>
<div id="exc" style="position:absolute;left:820;width:380px;overflow-x:scroll;overflow-y:scroll"></div>
</div>


</body>
</html>
