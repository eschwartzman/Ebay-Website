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
session_start();
require 'dbase.php';
$_id =$_GET['ad_id'];

$stmt = $mysqli->prepare("SELECT * FROM ads WHERE id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<table>
<tr>
<th>Title:</th>
<th>Snippet</th>
<th>Price:</th>
<th>Seller:</th>
<th>Category:</th>
<th>Reserve:</th>
</tr>";
while($row = $result->fetch_assoc()){
	$_SESSION['Description']=$row['snippet'];
	$seller = ($row['seller']);
	echo "<tr>";
	echo "<td>" . htmlentities($row['title']) . "</td>";
	echo "<td>" . htmlentities($row['snippet']) . "</td>";
	echo "<td>" . htmlentities($row['price']) . "</td>";
	echo "<td>" . "<a href=\"./Final_link_seller.php?seller=$seller\">$seller\n\n</a>". "</td>"; 
	echo "<td>" . htmlentities($row['catagory']) . "</td>";
	echo "<td>" . htmlentities($row['reserve']) . "</td>";
	echo "</tr>";

}
$stmt->close();
echo "</table>";    
?>


<?php
//$name1 = $_SESSION['userAccount'];
if(isset($_GET['ad_id'])){
	$_id1 =$_GET['ad_id'];
}
//echo "id1 = $_id1";
	$mysqli = new mysqli('localhost','wustl_inst','wustl_pass','Ebay');
	$rs = $mysqli->query("SELECT * FROM Ebay.ads_image WHERE ads_id='".$_id1."';");
	$rs_img = $rs->fetch_object();
	if(isset($rs_img)){
	echo '<img src="data:image/JPEG;base64,'.base64_encode($rs_img->image).'" >';
}
?>


<?php
$usern=$_SESSION['userAccount'];

$_idps =$_GET['ad_id'];

$stmt = $mysqli->prepare("SELECT `mon` FROM users WHERE username=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $usern);
	$stmt->execute();
	$stmt->bind_result($usern2);
	$stmt->fetch();
	$stmt->close();


	$stmt = $mysqli->prepare("SELECT `price` FROM ads WHERE id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $_idps);
	$stmt->execute();
	$stmt->bind_result($price2);
	$stmt->fetch();
	$stmt->close();


if (isset($_POST['submit_buy_now']) && ($usern2 > $price2) ){

	$_ids =$_GET['ad_id'];
	//select specific ad
	$stmt = $mysqli->prepare("SELECT  `title`, `seller` FROM ads WHERE id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $_ids);
	$stmt->execute();
	$stmt->bind_result($title2, $seller2);
	$stmt->fetch();
	$stmt->close();
	$stmt = $mysqli->prepare("DELETE FROM ads where id = ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $_ids);
	$stmt->execute();
	$stmt->close();
	$name = $_SESSION['userAccount'];
	$stmt = $mysqli->prepare("INSERT into cart(seller,title, buyer) values (?,?,?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('sss', $seller2, $title2, $name);
	$stmt->execute();
	$stmt->close();

	$new_mon = ($usern2 - $price2);

	$stmt = $mysqli->prepare("UPDATE users set mon=? where username=?;");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('is', $new_mon, $usern);
	$stmt->execute();
	$stmt->close();

	echo "This item has been removed from main listing and put in your cart!";

}else if(isset($_POST['submit_buy_now']) && ($usern2 < $price2) ){
	echo "Not enough money in your account!" ;


}
?>

<?php
//only seller can upload image for product.
$_tem = $_GET['ad_id'];

$stmt = $mysqli->prepare("SELECT `seller` FROM ads WHERE id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $_tem);
	$stmt->execute();
	$stmt->bind_result($seller2);
	$stmt->fetch();
	$stmt->close();
if($seller2 == $_SESSION['userAccount']){
?>

<form action='./Final_upload_image.php' method='post' enctype='multipart/form-data'>
    <input type='file' name='image'><input type='submit' name='submit1' value='Upload'>
    <input type='hidden' name='uploadimage' value="<?php echo $_tem;?>"/>
</form>

<?php
}
?>

<form method="post">
	Buy It Now!:<br>
	<input type="submit" name="submit_buy_now" value="Buy It">
</form>
<form method="post">
	<p id ="123">Set The Auction Timer:</p>
	<input type="number" name="amount" id ="amount">
	<input type="submit" name="submit3" value="Set" id="sub"><br>
	<input type="number" name="amount_bid" max ="<?php echo $usern2;?>" id ="amount_bid">
	<input type="submit" name="submit7" value="Bid" id="bidding"><br>
	<input type="submit" name="submit8" value="See Bid History!" id="bidding_num"><br>
	<input type="submit" name="submit4" value="Get Time Remaining!" id="sub"><br><br>
	<div id="time"></div>
</form>

<?php
$_idz =$_GET['ad_id'];
$stmt = $mysqli->prepare("SELECT * FROM ads WHERE id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $_idz);
$stmt->execute();
$result = $stmt->get_result();
$name = $_SESSION['userAccount'];
while($row = $result->fetch_assoc()){
	if($row['seller'] !== $name){
		?>
		<script> document.getElementById("123").style.visibility="hidden";
		document.getElementById("amount").style.visibility="hidden";
		document.getElementById("sub").style.visibility="hidden";  
		document.getElementById("bidding").style.visibility="visible";  
		document.getElementById("amount_bid").style.visibility="visible";  
		</script> 
		<?php
	}else{
		?>
		<script>   
		document.getElementById("bidding").style.visibility="hidden";  
		document.getElementById("amount_bid").style.visibility="hidden";  
		</script> 
		<?php
	}

}
$stmt->close();
?>

<?php
if(isset($_POST['amount'])){
	$z=($_POST['amount']) * 60;

	$_idm =$_GET['ad_id'];
	require 'dbase.php';
	if(isset($_POST['submit3'])){
		$stmt = $mysqli->prepare("INSERT into countdown (duration, ad_id) values (?,?)"); 
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('ii', $z, $_idm);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

	}
}

if(isset($_POST['submit4'])){
	$stmt = $mysqli->prepare("SELECT TIME_TO_SEC(timediff(now(),time_start)), duration FROM  `countdown` WHERE TIME_TO_SEC(timediff(now(),time_start))<=`duration` and ad_id =?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i', $_idm);
	$stmt->execute();
	$stmt->bind_result($_still_run, $dur);
	$stmt->fetch();
	$result = $stmt->get_result();
	$stmt->close();
	if($dur){
		echo $_still_run . " " . " seconds out of" . " " . $dur . " " . "have passed"; 
	}else{
		echo "This is not a live auction as of right now" ;
	}
}


?>

<?php
$stmt = $mysqli->prepare("SELECT TIME_TO_SEC(timediff(now(),time_start)), duration FROM  `countdown` WHERE TIME_TO_SEC(timediff(now(),time_start))<=`duration` and ad_id =?");

$_idmd =$_GET['ad_id'];
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i', $_idmd);
$stmt->execute();
$stmt->bind_result($_still_run, $dur);
$stmt->fetch();
$result = $stmt->get_result();
$stmt->close();
if($dur && isset($_POST['submit7']) && isset($_POST['amount_bid'] ) ){
	$zl=($_POST['amount_bid']);
	$_idmk =$_GET['ad_id'];
	$name = $_SESSION['userAccount'];
	require 'dbase.php';
	$stmt = $mysqli->prepare("INSERT into bids (bidder, ad_id, bid) values (?,?,?)"); 
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('sii', $name, $_idmk, $zl);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$stmt = $mysqli->prepare("SELECT * FROM bids where ad_id=?"); 
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;}
		$stmt->bind_param('i', $_idmk);
		$stmt->execute();
		$result = $stmt->get_result();
		echo "Bid History", 
		"<table>
		<tr>
		<th>Bidder:</th>
		<th>Bid</th>
		</tr>";
		while($row = $result->fetch_assoc()){
			echo "<tr>";
			echo "<td>" . htmlentities($row['bidder']) . "</td>";
			echo "<td>" . htmlentities($row['bid']) . "</td>"; 
			echo "</tr>";

		}
		$stmt->close();
		echo "</table>";

	}
	if($dur && isset($_POST['submit8']) )  {
		$_idmk =$_GET['ad_id'];
		$stmt = $mysqli->prepare("SELECT * FROM bids where ad_id=?"); 
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;}
			$stmt->bind_param('i', $_idmk);
			$stmt->execute();
			$result = $stmt->get_result();
			echo "Bid History", 
			"<table>
			<tr>
			<th>Bidder:</th>
			<th>Bid</th>
			</tr>";
			while($row = $result->fetch_assoc()){
				echo "<tr>";
				echo "<td>" . htmlentities($row['bidder']) . "</td>";
				echo "<td>" . htmlentities($row['bid']) . "</td>"; 
				echo "</tr>";

			}
			$stmt->close();
			echo "</table>";

		}



		$_idmd =$_GET['ad_id'];
		$stmt = $mysqli->prepare("SELECT bidder, (bid)  FROM `bids` WHERE ad_id =? and bid = (SELECT MAX(bid) FROM bids)");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $_idmd);
		$stmt->execute();
		$stmt->bind_result($bd, $mon);
		$stmt->fetch();
		$result = $stmt->get_result();
		$stmt->close();

		$_idmd =$_GET['ad_id'];
		$stmt = $mysqli->prepare("SELECT reserve  FROM `ads` WHERE id =?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $_idmd);
		$stmt->execute();
		$stmt->bind_result($reserve);
		$stmt->fetch();
		$result = $stmt->get_result();
		$stmt->close();

if(($dur === $_still_run) && ($mon > 0) && ($mon < $reserve)){
	$_idmk =$_GET['ad_id'];
	$stmt = $mysqli->prepare("DELETE FROM bids where ad_id = ?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('s', $_idmk);
			$stmt->execute();
			$stmt->close();


}


		if(($dur === $_still_run) && ($mon > 0) && ($mon > $reserve)){
			$_idsp =$_GET['ad_id'];
	//select specific ad
			$stmt = $mysqli->prepare("SELECT  `title`, `seller` FROM ads WHERE id=?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('s', $_idsp);
			$stmt->execute();
			$stmt->bind_result($title2, $seller2);
			$stmt->fetch();
			$stmt->close();
			$stmt = $mysqli->prepare("DELETE FROM ads where id = ?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('s', $_idsp);
			$stmt->execute();
			$stmt->close();
			$name = $_SESSION['userAccount'];
			$stmt = $mysqli->prepare("INSERT into cart(seller,title, buyer) values (?,?,?)");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('sss', $seller2, $title2, $bd);
			$stmt->execute();
			$stmt->close();

			$_idmk =$_GET['ad_id'];
	$stmt = $mysqli->prepare("DELETE FROM bids where ad_id = ?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('s', $_idmk);
			$stmt->execute();
			$stmt->close();


			$usern=$_SESSION['userAccount'];

			$new_mon = ($usern2 - $mon);

			$stmt = $mysqli->prepare("UPDATE users set mon=? where username=?;");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('is', $new_mon, $bd);
	$stmt->execute();
	$stmt->close();
		}
		?>

	</body>
	</html>