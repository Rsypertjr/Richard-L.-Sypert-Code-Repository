
<html>
<head>
<title>Input Form for Minimotif Search</title>
<script type="text/javascript">
function valInput()
{
var inform = document.forms["filein"];
var fnm = inform.elements["fname"].value;
var rcvid = "wait";

if(!fnm.match(/(\w|\d|(\Q-_\E))*\.txt/))
  {
  var inform = document.forms["filein"];
  inform.elements["fname"].value = "Please input a FASTA type file";
  }
else
   {
	   MakeRequest(fnm,rcvid);
   }
}



function getXMLHttp()
{
  var xmlHttp

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

function MakeRequest(snd,recid)
{
  
  
  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      HandleResponse(xmlHttp.responseText,recid);
    }
  
  }		
  xmlHttp.open("GET","fastaToMySQL.php?file="+snd, true);
  xmlHttp.send(null);
}
 
function MakeRequest2(snd,recid)
{
  
  
  var xmlHttp = getXMLHttp();
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      HandleResponse(xmlHttp.responseText,recid);
    }
  
  }		
  xmlHttp.open("GET","fastaToMySQL.php?motif="+snd, true);
  xmlHttp.send(null);
}

function HandleResponse(response,recid)
{
   document.getElementById(recid).innerHTML = response;
}


function clearInput()
{
  var inform = document.forms["filein"];
  inform.elements["fname"].value = "";
  document.getElementById("wait").innerHTML = "";
}

function doSearch()
{
var am1 = document.getElementById("am1").value;
var am2 = document.getElementById("am2").value;
var qu = document.getElementById("qu").value;
var recid = "wait";
//if(am1 && am2 && qu)
  //{
    var fnm = am1 +  am2 + qu;

    MakeRequest2(fnm,recid);
    
 // }
}

</script>

</head>

<body onload="clearInput()">
<h1>Input Form for Minimotif Search</h1>

<div id="inputdiv" style="position:fixed; width:125%;heigth:80%" >  




<div id="fileinput" style="position:relative; border-style:solid;border-width:1px;width:50%;padding:5px;background-color:rgb(220,220,220)">
<form id="filein">
<p>Input the file name below that you desire to search for
minimotif sequences.  The file must be in FASTA format (.txt extension)</p>
File Name: <input type="text" onclick="clearInput()" name="fname" value="type file name" /><button type="button" onclick="valInput()">Verify File and Start Database Build</button>
</form></div>



<div id="motifsel" style="position:relative; width:50%; border-style:solid; border-width:1px;padding:5px;background-color:rgb(240,240,240)">
<form id="aminocodes">
<p>Select below the first and last letter codes for minimotif sequence you
want to search.  (e.g. P and G for Px.....xG where x is any other amino acid code).</p>

<select id="am1" name="am1"  >
  <option value="G">G - Glycine</option>
  <option value="A">A - Alanine</option>
  <option value="V">V - Valine</option>
  <option value="L">L - Leucine</option>
  <option value="I">I - Isoleucine</option>
  <option value="M">M - Methionine</option>
  <option value="F">F - Phenylalanine</option>
  <option value="W">W - Tryptophan</option>
  <option value="P">P - Proline</option>
  <option value="S">S - Serine</option>
  <option value="T">T - Threonine</option>
  <option value="C">C - Cysteine</option>
  <option value="Y">Y - Tyrosine</option>
  <option value="N">N - Asparagine</option>
  <option value="Q">Q - Glutaminie</option>
  <option value="D">D - Aspartic Acid</option>
  <option value="E">E - Glutamic Acid</option>
  <option value="K">K - Lysine</option>
  <option value="R">R - Arginine</option>
  <option value="H">H - Histidine</option>
</select><span style="margin-left:5px">X....X</span>
<select id="am2" name="am2">
  <option value="G">G - Glycine</option>
  <option value="A">A - Alanine</option>
  <option value="V">V - Valine</option>
  <option value="L">L - Leucine</option>
  <option value="I">I - Isoleucine</option>
  <option value="M">M - Methionine</option>
  <option value="F">F - Phenylalanine</option>
  <option value="W">W - Tryptophan</option>
  <option value="P">P - Proline</option>
  <option value="S">S - Serine</option>
  <option value="T">T - Threonine</option>
  <option value="C">C - Cysteine</option>
  <option value="Y">Y - Tyrosine</option>
  <option value="N">N - Asparagine</option>
  <option value="Q">Q - Glutaminie</option>
  <option value="D">D - Aspartic Acid</option>
  <option value="E">E - Glutamic Acid</option>
  <option value="K">K - Lysine</option>
  <option value="R">R - Arginine</option>
  <option value="H">H - Histidine</option>
</select><br/><br/>
<div>
<select id="qu" name="qu"  >
  <option value="X" selected="selected">Select which Search that you want to do.</option>
  <option value="0">Get All Accession Numbers for chosen miniMotif</option>
  <option value="1">Get All Motif Instances in All Proteins</option>
  <option value="2">Get Start Positions of selected Motif in All Proteins</option>
  <option value="3">Get Number of Motifs for Each Protein</option>
  <option value="4">Get All Species Names in which selected Motif occurs</option>
  <option value="5">Get Average Length of All Proteins for the selected Motif</option>
</select>
</div>  
</br><input type="button"  style="float:left" onclick="doSearch()" value="Submit Search"/></br></br>

</div>

</form>
<div id="wait" overflow:auto; style="position:relative; border-style:solid;border-width:1px;width:50%;height:360px;padding:5px;background-color:rgb(350,275,190);overflow-x:scroll;overflow-y:scroll"></div>
</div>

</body>
</html>

