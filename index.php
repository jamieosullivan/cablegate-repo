<?php // Initial PHP stuff here

/* Not gonna work, probably other php headers sent before this gets processed.
if ($_REQUEST['cableid'] != 'z') {
	$cableidx = $_REQUEST['cableid'];
	$str = 'Location: http://eire.dyndns-home.com/gate/cablegs.php?cableid=' + $cableidx;
	header($str);
}
 */

?>
<html>
<head>
<title> Cablegate cable viewer </title>

<!-- Stylesheet -->
<link rel="stylesheet" type="text/css" href="styl1.css"/>
</head>

<body>
<!-- <div id="doc_welcome"> -->
<div id="doc_welcome">


<div id="container">

<div id="opaquetext">

<?php

include "header.php";

function getcable($cabid){
	
	include "header.php";
	// Connect to the database
	$con = mysql_connect($dbhost, $dbuser, $dbpw) or die (" Couldn't
		connect to the database." . mysql_error());

	mysql_select_db($dbname);

	// Query to get the text corresponding to the cable id
	$query = "select cab_text from cables where cab_id='" . $cabid .  "'"; 
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$cabtext = $row[0];

	// Create the table and print the text
	echo "<textarea class='cable' spellcheck='false' readonly='readonly'>"; 
	echo $cabtext;
	echo "</textarea>";

	/* Don't see a need for the hidden variable in this case
	echo "<form id='hform'>";
	echo "<input type='hidden' name='hvar' value='" . $cabid . "'>";
	echo "</form>";
	 */

	mysql_close($con);
}

// Check if a non-dummy value for the cable ID has been passed (i.e. if the
// page is loaded from a link in a wall post referring to a particular cable.

$reqarr = explode("?", $_REQUEST['cableid']);
echo $reqarr[0];
echo "<br>";
echo $reqarr[1];
//if ($_REQUEST['cableid'] != 'z') {
if ($reqarr[0] != 'z') {
//	$cableidx = $_REQUEST['cableid'];
	$cableidx = $reqarr[0];
	// Call "getcable" passing the ID and display a link to the front page 
	// p
	echo "<h1>Welcome!</h1>";
	echo "<p>The full text of " . $cableidx . " is shown below.</p>";
	getcable($cableidx);
	echo "<br><br>";
	echo "<a href='" . $my_canvas_page . "'>Click here to go to the main page</a>";	

} else {
	echo "<br>
<table id='quote_table'>
<td>
<i>\"Information is the currency of democracy.\"</i> -  Thomas Jefferson. 
</td>
</table>

<h1>Welcome!</h1> 
<p>
This app will allow you to read the cablegate material released in September 2011, consisting of 251,288 diplomatic cables. If you're not familiar with the news regarding <b>Wikileaks</b>
 and <b>Bradley Manning</b>, I suggest you look it up, as you should be aware of the origin 
of the material before deciding whether you want to proceed.
</p>
<p>
Is is our position that, regardless of one's stance on the leaking of government documents, once leaked, they
become part of the public domain and historical record. It is furthermore our contention that citizens have the 
right, and indeed the responsibility, to bring their full powers of scrutiny to bear on the actions of the governments that
claim to act on their behalf. 
</p>
<br>
<b>
<a href='cableg.php'>Click here to view the cables and share your thoughts</a>
</b> ";
}


?>


</div>

<div id="translucentbkg">

</div>


</div>



</body>
</html>
