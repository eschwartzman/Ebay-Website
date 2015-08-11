
<!DOCTYPE html>
<html>
<head>
	<title>Ad</title>
	<link rel="stylesheet" href="Final_css.css">

</head>

<body>
	<ul class="navbar">
		<li><a href="Final_my_home.php">User Page</a>
		<li><a href="Final_main.php">Home Page</a>
		<li><a href="Final_all_users.php">User Feedback</a>
		<li><a href="Final_logout.php">Log Out</a> 
	</ul>
	

<?php
//uploadimage.php

$ads_id = $_POST['uploadimage'];

echo "ads_id is $ads_id";
if(isset($_POST['submit1'])){
    $mysqli = new mysqli('localhost', 'wustl_inst', 'wustl_pass', 'Ebay');
    //var_dump($mysqli);
    //mysql_connect('localhost' , 'root' , 'mousic');
//mysql_select_db('Ebay');
	
    
    $imageName = $mysqli->real_escape_string($_FILES["image"]["name"]);
    
    $imageData = $mysqli->real_escape_string(file_get_contents($_FILES["image"]["tmp_name"]));
    //echo $imageData;
    $imageType = $mysqli->real_escape_string($_FILES["image"]["type"]);
	//var_dump($_FILES["image"]);
    
    
    if(substr($imageType,0,5) == "image"){
	//echo "working";
	$cur_id = $_SESSION['userAccount'];
	//echo $cur_id;
	$rs = $mysqli->query("INSERT INTO Ebay.ads_image (ads_id,name,image) VALUES('".$ads_id."','".$imageName."','".$imageData."');");
	//var_dump($rs);
		
	//echo "image uploaded!";
	
    }else{
	echo $imageType;
	echo "only image is allowed.";
    }
}
header('Location: Final_main.php');

?>

</body>
</html>