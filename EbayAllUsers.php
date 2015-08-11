<!DOCTYPE html>
<html>
<head>
	<title>Feedback</title>
	<link rel="stylesheet" href="Final_css.css">
	<h1>All Users</h1>
</head>

<body>
	<ul class="navbar">
		<li><a href="Final_my_home.php">User Page</a>
			<li><a href="Final_main.php">Home Page</a>
				<li><a href="Final_logout.php">Log Out</a> 
				</ul> 

				<form method="post">

					User to Rate<input name='rater' type='text' /> Rating <input name='rating' type='number' max ="5" min = "0"/> Submit Rating <input name="submit2" type="submit"><br>
					User to Find Out Rating For<input name='rater1' type='text' />View Rating For User <input name="submit1" type="submit"><br><br>
				</form>

				<?php
				require 'dbase.php';
				if(isset($_POST['submit2'])){
					$stmt = $mysqli->prepare("INSERT into rate (rating, seller) values (?, ?)"); 
					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;
					}
					$rating = $_POST['rating'];
					$seller = $_POST['rater'];
					$stmt->bind_param('is', $rating, $seller);
					$stmt->execute();
					$result = $stmt->get_result();

					$stmt->close();
				}
				if(isset($_POST['submit1'])){
					$seller = $_POST['rater1'];
					$stmt = $mysqli->prepare("SELECT AVG(rating) FROM rate WHERE seller=?"); 
					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;
					}
					$stmt->bind_param('s', $seller);
					$stmt->execute();
					$stmt->bind_result($_avg);
					$stmt->fetch();
					$result = $stmt->get_result();
					echo "<table>
					<tr>
					<th>" . $seller .  " " . $_avg . "</th>
					</tr>";
					$stmt->close();
				}
				?>
				<?php
				session_start();
				?>

				<?php 
 	//query to find number of users in database
				require 'dbase.php';

				$stmt = $mysqli->prepare("select username from users");
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_assoc()){
					$ids[] = $row['username'];
				}
				if(!empty($ids)){
					for($i = 0; $i < sizeof($ids); $i++)
					{
						showReviews($ids[$i]);
						reviewOthers($ids[$i]);
					}
				}
				else{
					echo "try adding some news in the 'User Page' tab";
				}
				$stmt->close();

				?>  	

				<?php 
				function showReviews($seller){
					require 'dbase.php';


					$stmt = $mysqli->prepare("SELECT * FROM reviews WHERE seller=?"); 

					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;}
						$stmt->bind_param('s', $seller);
						$stmt->execute();
						$result = $stmt->get_result();
						echo "<table>
						<tr>
						<th>" . "<a href=\"./Final_link_seller.php?seller=$seller\">$seller\n\n</a>". "</th>
						</tr>";
						while($row = $result->fetch_assoc()){
							echo "<tr>";
							echo "<td>" . htmlentities($row['poster']) . " said: " .  htmlentities($row['review']) ."</td>";
							echo "</tr>";
						}

						$stmt->close();

						echo "</table>";
					}

					?>

					<?php 
					function reviewOthers($seller1){
						?>
						<form method="post">
							<textarea type="text" name="Review"  style ="background-color:  CornflowerBlue " rows="2" cols="50"> </textarea>
							<input type="submit" name="<?php echo $seller1; ?>" value="Review">
							<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
						</form>

						<?php

						require 'dbase.php';
						if(isset($_POST[$seller1])){
 	//check for CSRF
							if($_SESSION['token'] !== $_POST['token']){
								echo "error";
								die("Request forgery detected");
							}
							$review = $mysqli->real_escape_string($_POST['Review']);
    	//adds reviews to database	
							$userName= $_SESSION['userAccount'];
							$stmt = $mysqli->prepare("INSERT into reviews (poster, review, seller) values (?, ?, ?)");
							if(!$stmt){
								printf("Query Prep Failed: %s\n", $mysqli->error);
								exit;}

								$stmt->bind_param('sss', $userName, $review, $seller1);
								$stmt->execute();
								$stmt->close();
								header( "Location: Final_all_users.php" );
							}	 
							?>
							<?php
						}?>

					</body>
					</html>