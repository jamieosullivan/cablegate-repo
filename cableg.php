<?php // Initial PHP stuff here

include "header.php"; 

//-------------------------------------------------------------------------------------------
// Authenticate with Facebook  - old stuff
//-------------------------------------------------------------------------------------------
/*
// Get code from previous call, if made
$code = $_REQUEST['code'];

// Make oauth call if $code not obtained yet
if(empty($code)) {
	$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_canvas_page) . "&scope=publish_stream";
//       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&scope=publish_stream";
       
       echo("<script> top.location.href='" . $dialog_url . "'</script>");
}

// Authorize app, get access token
$token_url = "https://graph.facebook.com/oauth/access_token?"
	. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_canvas_page)
//	. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
	. "&client_secret=" . $app_secret . "&code=" . $code;
//echo "Code = " . $code;
$response = file_get_contents($token_url);
//echo "Response = " . $response . "\n";
//echo "Access token = " . 	$response->access_token . "\n";
$params = null;

parse_str($response, $params);
//echo $params['access_token'];


// Use curl to make the call for the wall post   
$graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
//echo "Graph url: " . $graph_url;
//var_dump(json_decode(file_get_contents($graph_url)));
$user = json_decode(file_get_contents($graph_url));
//echo $user->first_name;
//echo("Hello " . $user->first_name);
/*curl -F 'curl -F 'access_token=$params['access_token']' \
 -F 'message=Test message, don't mind...' \ 
 -F 'picture=http://www.iloveclipart.com/images/tiger-clip-art.jpg' \
 -F 'name=Article Title' \
 -F 'caption=Tiger' \
 -F 'privacy={"value": "ALL_FRIENDS"}' \
 https://graph.facebook.com/me/feed' */
 
 /*
$c_postfield = "access_token=" . $params['access_token'] . "&message=$fort_text";
$ch = curl_init("https://graph.facebook.com/me/feed");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $c_postfield);
//curl_exec($ch);
curl_close($ch);
*/



//-------------------------------------------------------------------------------------------
// MySQL section
//-------------------------------------------------------------------------------------------

// Connect to MySQL
$connect = mysql_connect($dbhost, $dbuser, $dbpw) or die ("Problem connecting or authenticating to DB");

// Select the right database
mysql_select_db($dbname, $connect) or die ("Unable to select database: " . mysql_error());;

// Select the list of locations, created a separate table of these called
// "cable_origins"
$query = "select origin from cable_origins";
$result = mysql_query($query) or die (mysql_error());
while ($row =  mysql_fetch_assoc($result)) {
	foreach ($row as $val) {   // This could be redundant
		$origins[] = $val;
	}
}
sort($origins);    // move somewhere else off this script



?>
<html>
<head>
<title>Cablegate cable viewer</title>

<!-- Javascript functions here -->
<!--<script type="text/javascript"> -->
<script Language="Javascript">

// Script to get an array of dates for the entered cable origin, via
// an AJAX request to retrievedates.php
function getDates(str_orig)
{
	// Store the origin as a global variable
	glob_origin = str_orig;

        if (window.XMLHttpRequest)

       {// code for IE7+, Firefox, Chrome, Opera, Safari

           xmlhttp=new XMLHttpRequest();

      }

        else

{// code for IE6, IE5

xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

}
	
	xmlhttp.onreadystatechange=function()
{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		// Will be printing the cable classification and summary before
		// letting the user retrieve the full cable
		document.getElementById("sel_date").innerHTML=xmlhttp.responseText;
	}
}
xmlhttp.open("GET","retrievedates.php?orig="+str_orig,true);
xmlhttp.send();

}

// Another AJAX request, this time to retrievecabid.php
function getCabId(arg_date)
{
	// Store date globally
	glob_date = arg_date;

        if (window.XMLHttpRequest)

      {// code for IE7+, Firefox, Chrome, Opera, Safari

           xmlhttp=new XMLHttpRequest();

      }

        else

       {// code for IE6, IE5

       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

       }
	
	xmlhttp.onreadystatechange=function()
{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		// Need to figure out how to return "cab_id" from the php file
		document.getElementById("display_summary").innerHTML=xmlhttp.responseText;
	}
}
xmlhttp.open("GET","retrievecabid.php?orig="+glob_origin+"&cdate="+arg_date,true);
xmlhttp.send();

}
	 
// Last AJAX request, this time to retrievecabletext.php
//
function getCabText()
{
        if (window.XMLHttpRequest)

       {// code for IE7+, Firefox, Chrome, Opera, Safari

           xmlhttp=new XMLHttpRequest();

      }

        else

       {// code for IE6, IE5

       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

       }
	
	xmlhttp.onreadystatechange=function()
{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		document.getElementById("yourcable").innerHTML=xmlhttp.responseText;
	}
}
xmlhttp.open("GET","retrievecabletext.php?orig="+glob_origin+"&cdate="+glob_date,true);
xmlhttp.send();

}
 
function clearFirst(obj)
{
	if (obj.clearThis == undefined)
	{
		obj.value = '';
		obj.clearThis = true;
	}
}


function doPost()
{
    // Have the hidden variable for cable ID, retrieve it
    var oForm = document.getElementById("hform");
    var cabid = oForm.elements["hvar"].value; 
	post_text = document.getElementById("postarea").value;
	var ptext = cabid+"\n"+post_text;
	var descrip = "Full text of Wikileaks cable " + cabid + ".";
	//alert(descrip);
	plink = "http://apps.facebook.com/xxx/?ref=bookmarks" + "&cableid=" + cabid;
//	imglink = "http://xxx.dyndns-home.com/gate/img/magnify.jpg";
	imglink = "http://xxx.co.cc/gate/img/magnify.jpg";
//	postConfirmed(ptext);	
	
	/*
	FB.getLoginStatus(function(response) {
		alert(response.perms);
		if (response.session && response.perms.match(/\"publish_stream\"/)) {
			FB.api('/me/feed', 'post', { message: ptext}, function(response) {
				if (!response || response.error) {
					alert('Error occured');
				} else {
					alert('Post ID: ' + response.id);
				}
			});
} else { */
			// alert("Problem!");
			FB.login(function(response) {
			alert("here!");
				if (response.session) {
					alert("here!");
					if (response.perms) {
						FB.api('/me/feed', 'post', { message: ptext, link: plink, picture: imglink, description: descrip }, function(response) {
							if (!response || response.error) {
								alert('Error occured');
							} else {
								alert('Post ID: ' + response.id);
							}
						});
					} else { //session, but no perms
						alert("Session, but no perms");
					}
				} else { //No session, no perms
					alert("No session, no perms");
				}
			}, {perms:'publish_stream'});
/*		}
}); */
}

 

function postConfirmed(arg)
{
	if(arg){
		alert(arg);
	} else {
		alert("Post successful");
	}
}
	
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
			

</script>
<!-- Stylesheet -->
<link rel="stylesheet" type="text/css" href="styl1.css"/>
</head>


<body>

<!-- Facebook, Javascript SDK initialisation 
-->
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
	  appId = 1; // Global variable
	  FB.init({
				appId  : '1',
			  	status : true, // check login status
				cookie : true, // enable cookies to allow the server to access the session
			  	xfbml  : false // parse XFBML
			  });
};

  (function() {
	      var e = document.createElement('script');
		      e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		      e.async = true;
			  document.getElementById('fb-root').appendChild(e);
			}());
</script>


<div id="doc_welcome">

<!-- EDEDB3 8ED6EA D881ED F0DDD5
<div id="doc" style="height:600px;width:760px;font:16px/26px Georgia, Garamond, Serif;overflow:scroll;">
-->

<br>

<form>
<table width="650" style="margin-top:10px">
<tr>
<td width="250">
<!-- Select list for locations -->
<select name="sel_origin" onchange="getDates(this.value)">
<?php
echo "<option value=''>Select a location</option>";
foreach ($origins as $val) {
	echo "<option>" . $val . "</option>";
} 
?>
</select>
</td>

<td width="150">
<!-- Select list for dates -->
<select id="sel_date" onchange="getCabId(this.value)" onfocus="getCabId(this.value)"> 
<!-- <select id="sel_date" onchange="getCabId()">  -->
<option value="">Date..</option> 
</select>
</td>

<td width="200">
<button type="button" onclick="getCabText()">Get cable...</button>
</td>
</tr>
</table>

</form>

<!-- Here's where the preview of the cable classification and summary go.
     Initialise with empty fields -->
<div id="display_summary">
<table id="summ_table" width="680">
<tr>
<td width="300">Cable ID:</td>
<td width="430">Status:</td>
</tr>
<tr>
<td colspan="2" width="680">Cable Summary:</td>
</tr>
</table>
</div>

<br>
<div id="yourcable">
<!--
<textarea rows="16" cols="85" spellcheck="false" readonly="readonly"> 
-->
<textarea class="cable" spellcheck="false" readonly="readonly"> 
STEPS:

1) Select a location 

2) Select a date 

3) Click the "Get Cable" button.

4) Write comments below and post to your wall, or select another cable.

</textarea>
<br><br>
</div>

<!--
<textarea id="postarea"rows="7" cols="85" onclick="clearFirst(this);" spellcheck="false">
-->
<form name="myform">
<textarea id="postarea" class="post" onclick="clearFirst(this);" onKeyDown="limitText(this.form.postarea,this.form.countdown,380);" onKeyUp="limitText(this.form.postarea,this.form.countdown,380);" spellcheck="false">
Type your comments here. They will be posted on your wall with a link to the above cable. You can also paste in parts of the cable here.
</textarea>
<div align="right"><font size="1" style="margin-left:auto;margin-right:50px;color:#ced3e3;">(Maximum characters: 380). 
You have <input readonly type="text" name="countdown" style="font-size:1em;" size="3" value="380"> characters left.</font></div>
</form>
<button type="button" style="margin-left:50px;" onclick="doPost()">Post to wall</button>

</div>
</body>
</html>
