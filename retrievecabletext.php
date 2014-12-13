<?php

include "header.php";

// Receives the string containing the chosen cable origin and date and 
// queries the database for the corresponding cable id. We then query the
// summary table for the classification and summary and send the HTML text

// 
// Get value of the origin
$orig = $_GET["orig"];
$cdate = $_GET["cdate"];

// Connect to the database
$con = mysql_connect($dbhost, $dbuser, $dbpw) or die (" Couldn't
	connect to the database." . mysql_error());

mysql_select_db($dbname);

$query = "select cab_id from cable_dates where cab_origin='" . $orig
	. "' and cab_date='" . $cdate . "'";

$result = mysql_query($query) or die(mysql_error());

$row = mysql_fetch_array($result);
$cabid = $row[0];

$query = "select cab_class, cab_summary from cable_summary where cab_id='" 
	. $cabid . "'";

$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
$classification = $row[0];
$summary = $row[1];

$query = "select cab_text from cables where cab_id='" . $cabid .  "'"; 
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
$cabtext = $row[0];

echo "<textarea class='cable' spellcheck='false' readonly='readonly'>"; 
echo $cabtext;
echo "</textarea>";

echo "<form id='hform'>";
echo "<input type='hidden' name='hvar' value='" . $cabid . "'>";
echo "</form>";

mysql_close($con);
?>

