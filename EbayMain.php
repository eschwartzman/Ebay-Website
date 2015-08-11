<!DOCTYPE html>
<html>
<head>
	<title>Listings</title>
	<link rel="stylesheet" href="Final_css.css">
	<h1>Listings</h1>
</head>

<body>
	<ul class="navbar">
		<li><a href="Final_my_home.php">User Page</a>
			<li><a href="Final_all_users.php">User Feedback</a> 
				<li><a href="Final_logout.php">Log Out</a> 
				</ul> 

				<form method="post">
					Filter by Catagory:<br>
					<input type="text" name="catagory" ><br>
					<input type="submit" name="submit" value="Filter"><br>
					<input type="submit" name="submit1" value="Reset"><br><br>
				</form>




				<?php
				if(isset($_POST['submit'])){

					showAds2();
				}else{
					require 'dbase.php';
					$stmt = $mysqli->prepare("select id from ads");
					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;
					}
					$stmt->execute();
					$result = $stmt->get_result();
					while($row = $result->fetch_assoc()){
						$ids[] = $row['id'];
					}
					if(!empty($ids)){
						for($i = 0; $i < sizeof($ids); $i++)
						{
							showAds($ids[$i]);
						}
					}
					else{
						echo "try adding some news in the 'User Page' tab";
					}
					$stmt->close();


				}
				?>

				<?php

				if(isset($_POST['submit1'])){
					require 'dbase.php';
					$stmt = $mysqli->prepare("SELECT * FROM ads where id=?"); 

					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;}
						$stmt->bind_param('i', $id);
						$stmt->execute();
						$result = $stmt->get_result();
						while($row = $result->fetch_assoc()){
							$story = ($row['title']);
							echo "<tr>";
							echo "<td>" . "<a href=\"./Final_link_ad.php?ad_id=$id\">$story\n\n</a>". "</td>";
							echo "<td>" . htmlentities($row['price']) . "</td>";
							echo "<td>" . htmlentities($row['seller']) . "</td>";
							echo "<td>" . htmlentities($row['catagory']) . "</td>";
							echo "</tr>";

						}
						$stmt->close();
						echo "</table>";
					}

					?>




					<?php
					function showAds($id){
						require 'dbase.php';
						$stmt = $mysqli->prepare("SELECT * FROM ads where id=?"); 
						if(!$stmt){
							printf("Query Prep Failed: %s\n", $mysqli->error);
							exit;}
							$stmt->bind_param('i', $id);
							$stmt->execute();
							$result = $stmt->get_result();

							echo "<table>
							<tr>
							<th>Title:</th>
							<th>Price:</th>
							<th>Seller:</th>
							<th>Category:</th>
							<th>Reserve:</th>
							</tr>";
							while($row = $result->fetch_assoc()){
								$story = ($row['title']);
								echo "<tr>";
								echo "<td>" . "<a href=\"./Final_link_ad.php?ad_id=$id\">$story\n\n</a>". "</td>";
								echo "<td>" . htmlentities($row['price']) . "</td>";
								echo "<td>" . htmlentities($row['seller']) . "</td>";
								echo "<td>" . htmlentities($row['catagory']) . "</td>";
								echo "<td>" . htmlentities($row['reserve']) . "</td>";
								echo "</tr>";

							}
							$stmt->close();
							echo "</table>";
						}
						?>

						<?php
						function showAds2(){
							require 'dbase.php';
							$cat =$_POST['catagory'];
							$stmt = $mysqli->prepare("SELECT * FROM ads where catagory=?"); 
							if(!$stmt){
								printf("Query Prep Failed: %s\n", $mysqli->error);
								exit;
							}
							$stmt->bind_param('s', $cat);
							$stmt->execute();
							$result = $stmt->get_result();

							echo "<table>
							<tr>
							<th>Title:</th>
							<th>Price:</th>
							<th>Seller:</th>
							<th>Category:</th>
							</tr>";
							while($row = $result->fetch_assoc()){
								$story = ($row['title']);
								$id = $row['id'];
								echo "<tr>";
								echo "<td>" . "<a href=\"./Final_link_ad.php?ad_id=$id\">$story\n\n</a>". "</td>";
								echo "<td>" . htmlentities($row['price']) . "</td>";
								echo "<td>" . htmlentities($row['seller']) . "</td>";
								echo "<td>" . htmlentities($row['catagory']) . "</td>";
								echo "</tr>";

							}
							$stmt->close();
							echo "</table>";
	
						}
						?>

					</body>
					</html>