<!DOCTYPE html>
<html>
<head>
	<title>User Page</title>
	<link rel="stylesheet" href="Final_css.css">
</head>

<body>
	<!-- Side menu -->
	<ul class="navbar">
		<li><a href="Final_main.php">Home Page</a>
			<li><a href="Final_all_users.php">User Feedback</a> 
				<li><a href="Final_logout.php">Log Out</a> 
				</ul> 


				<h1> Hello <?php session_start(); 
				echo $_SESSION['userAccount']; ?> </h1>
				<form method="post">
					<input type="submit" name="editProfile" value="Edit Profile"> <br>
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" /><br>
					<input type="submit" name="vcart" value="View Cart"> <br><br>
				</form>
			</form>

			<?php
			if(isset($_POST['editProfile'])){
 	//check for CSRF
				if($_SESSION['token'] !== $_POST['token']){
					echo "error";
					die("Request forgery detected");
				}
				header( "Location: Final_about_me.php" );
			}
			?> 

			<?php
			if(isset($_POST['vcart'])){
 	//check for CSRF
				if($_SESSION['token'] !== $_POST['token']){
					echo "error";
					die("Request forgery detected");
				}
				header( "Location: Final_cart.php" );
			}
			?> 
			<?php
			showMyAds();
			postAd();
			showProfile();
			?>

			<?php 
			function showMyAds(){
				require 'dbase.php';
				$name = $_SESSION['userAccount'];
	//show posted ads
				$stmt = $mysqli->prepare("SELECT * FROM ads WHERE seller=?");
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				$stmt->bind_param('s', $name);
				$stmt->execute();
				$result = $stmt->get_result();

				echo "<table>
				<tr>
				<th>Title:</th>
				<th>Snippet</th>
				<th>Price:</th>
				<th>Seller:</th>
				<th>Category:</th>
				<th>Reserve Price:</th>
				</tr>";

				while($row = $result->fetch_assoc()){
					$_SESSION['Description']=$row['snippet'];
					echo "<tr>";
					echo "<td>" . htmlentities($row['title']) . "</td>";
					echo "<td>" . htmlentities($row['snippet']) . "</td>";
					echo "<td>" . htmlentities($row['price']) . "</td>";
					echo "<td>" . htmlentities($row['seller']) . "</td>";
					echo "<td>" . htmlentities($row['catagory']) . "</td>";
					echo "<td>" . htmlentities($row['reserve']) . "</td>";
					echo "<td>";
					deleteAd($row['id']);
					editAd($row['id']);
					echo "</td>";
					echo "</tr>";

				}
				$stmt->close();
				echo "</table>";  
			}

			?>
			<?php 
			function deleteAd($button_id){
				?>
				<form method="post">
					<input type="submit" name="<?php echo $button_id; ?>" value="Remove">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
				</form>

				<?php

				require 'dbase.php';
				if(isset($_POST[$button_id])){
 	//check for CSRF
					if($_SESSION['token'] !== $_POST['token']){
						echo "error";
						die("Request forgery detected");
					}
					$stmt = $mysqli->prepare("delete from ads where id = ?");
					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;
					}
					$stmt->bind_param('i', $button_id);
					$stmt->execute();
					$stmt->close();
					header( "Location: Final_my_home.php" );

				}
			}	 
			?>

			<?php
			function editAd($id){
				$butt_on=$id*3;
				$butt_on2=$id*4;
				?>
				<form method="post">
					<input type="submit" name="<?php echo $butt_on; ?>" value="Edit Comment">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
				</form>

				<?php
				require 'dbase.php';
				if(isset($_POST[$butt_on])){
					?>
					<form method="post">
						<textarea name="titleE" placeholder="title" rows="2" cols="40"></textarea> 
						<textarea name="priceE" placeholder="price" rows="2" cols="40"></textarea> 
						<textarea name="catagoryE" placeholder="catagory" rows="2" cols="40"></textarea> 
						<textarea name="snippetE" placeholder="snippet" rows="2" cols="40"></textarea> 
						<textarea name="reserveE" placeholder="reserve" rows="2" cols="40"></textarea> 
						<input type="submit" name="<?php echo $butt_on2; ?>" value="Submit Edit">
						<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					</form>

					<?php
				}
				if(isset($_POST[$butt_on2])){
	//check for CSRF
					if($_SESSION['token'] !== $_POST['token']){
						echo "error";
						die("Request forgery detected");
					}

					$edit1 = $mysqli->real_escape_string($_POST['titleE']);
					$edit2 = $mysqli->real_escape_string($_POST['priceE']);
					$edit3 = $mysqli->real_escape_string($_POST['catagoryE']);
					$edit4 = $mysqli->real_escape_string($_POST['snippetE']);
					$edit5 = $mysqli->real_escape_string($_POST['reserveE']);
					$stmt = $mysqli->prepare("update ads set title=?, price=?, catagory=?, snippet=?, reserve=? where id=$id");
					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;
					}
					$stmt->bind_param('sissi', $edit1, $edit2, $edit3, $edit4, $edit5);
					$stmt->execute();
					$stmt->close();
					header( "Location: Final_my_home.php" );
				}
			}
			?>

			<?php
			function postAd(){
				?>
				<form method="post">
					<p>Post Ad:</p>
					Ad Title: <input type="text" name="postTitle"><br>
					Ad Price: <input type="text" name="postPrice"><br>
					Ad Catagory: <input type="text" name="postCatagory"><br>
					Ad Snippet: <input type="text" name="postSnippet"><br>
					Ad Reserve: <input type="text" name="postReserve"><br>
					<input type="submit" name="post_story" value="Post Ad"> <br><br>
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
				</form>

				<?php
				require 'dbase.php';

				if(isset($_POST['post_story'])){
 	//check for CSRF
					if($_SESSION['token'] !== $_POST['token']){
						echo "error";
						die("Request forgery detected");
					}

					$snippet = $mysqli->real_escape_string($_POST['postSnippet']); 
					$title = $mysqli->real_escape_string($_POST['postTitle']); 
					$name = $_SESSION['userAccount'];
					$price = $mysqli->real_escape_string($_POST['postPrice']);
					$catagory = $mysqli->real_escape_string($_POST['postCatagory']);

					$reserve = $mysqli->real_escape_string($_POST['postReserve']);

					$stmt = $mysqli->prepare("insert into ads(seller,title, price, catagory, snippet, reserve) values (?,?,?,?,?,?)");

					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;
					}

					$stmt->bind_param('ssissi', $name, $title, $price, $catagory, $snippet, $reserve);
					$stmt->execute();
					$stmt->close();
					header( "Location: Final_my_home.php" );
				}
				?>
				<?php
			}
			?>

			<?php 
			function showProfile(){
				require 'dbase.php';

	//will show  user's info 
				$stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?"); 

				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;}
					$stmt->bind_param('s',$_SESSION['userAccount']);
					$stmt->execute();
					$result = $stmt->get_result();

					echo "<table>
					<tr>
					<th>About:</th>
					<th>Money In Account:</th>
					</tr>";
					while($row = $result->fetch_assoc()){
						echo "<tr>";
						echo "<td>" . htmlentities($row['about']) . "</td>";
						echo "<td>" . htmlentities($row['mon']) . "</td>";
						echo "</tr>";


					}
					$stmt->close();
					echo "</table>";
				}
				?>

			</body>
			</html>