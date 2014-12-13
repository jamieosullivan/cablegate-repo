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

echo "<table id='summ_table' width='680'>";
//echo "<table id='summ_table' width='650'>";
echo "<tr>";
echo "<td width='300'>Cable ID: " . $cabid . "</td>";
echo "<td width='430'>Status: " . $classification . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='2' width='680'>Cable Summary: " . $summary . "</td>";
echo "</tr>";
echo "</table>";
/*
echo "Cable ID: " . $cabid . ". Status: " . $classification . ".<br>";
echo "Summary: " . $summary;
 */

mysql_close($con);
?>

