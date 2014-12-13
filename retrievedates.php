<?php

include "header.php"; 

// Receives the string containing the chosen cable origin and queries the
// database for a list of the cable dates for that origin. The HTML for the
// select drop-box is sent back to the main script
// 
// Get value of the origin
$orig = $_GET["orig"];

// Connect to the database
$con = mysql_connect($dbhost, $dbuser, $dbpw) or die (" Couldn't
	connect to the database." . mysql_error());

mysql_select_db($dbname);

$query = "select cab_date from cable_dates where cab_origin='" . $orig
	. "' order by cab_date desc";

$result = mysql_query($query) or die(mysql_error());

// Not sure what is meant by "innerHTML", just create "inner" HTML code for 
// the select list in that case?? Seems to work fine.
while ($row = mysql_fetch_array($result))
{
	echo "<option>" . $row[0] . "</option>";
}

mysql_close($con);
?>

