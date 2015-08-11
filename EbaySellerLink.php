<!DOCTYPE html>
<html>
<head>
	<title>Seller Info</title>
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
					showMyAds();
					?>

					<?php 
					function showMyAds(){
						session_start();
						require 'dbase.php';

						$name = $_SESSION['userAccount'];
						$sell = $_GET['seller'];

	//show posted ads
						$stmt = $mysqli->prepare("SELECT * FROM ads WHERE seller=?");
						if(!$stmt){
							printf("Query Prep Failed: %s\n", $mysqli->error);
							exit;
						}
						$stmt->bind_param('s', $sell);
						$stmt->execute();
						$result = $stmt->get_result();

						echo "<table>
						<tr>
						<th>Title:</th>
						<th>Snippet</th>
						<th>Price:</th>
						<th>Seller:</th>
						<th>Category:</th>
						</tr>";
						while($row = $result->fetch_assoc()){
							$_SESSION['Description']=$row['snippet'];
							echo "<tr>";
							echo "<td>" . htmlentities($row['title']) . "</td>";
							echo "<td>" . htmlentities($row['snippet']) . "</td>";
							echo "<td>" . htmlentities($row['price']) . "</td>";
							echo "<td>" . htmlentities($row['seller']) . "</td>";
							echo "<td>" . htmlentities($row['catagory']) . "</td>";
							echo "</tr>";

						}
						$stmt->close();
						echo "</table>";
						$stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?"); 
						if(!$stmt){
							printf("Query Prep Failed: %s\n", $mysqli->error);
							exit;}
							$stmt->bind_param('s',$sell);
							$stmt->execute();
							$result = $stmt->get_result();
							echo "<table>
							<tr>
							<th>About:</th>
							</tr>";
							while($row = $result->fetch_assoc()){
								echo "<tr>";
								echo "<td>" . htmlentities($row['about']) . "</td>";
								echo "</tr>";


							}
							$stmt->close();
							echo "</table>";   
						}

						?>
					</body>
					</html>