<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
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
                  viewCart();
                  ?>
                  <?php
                  function viewCart(){
                   ?>
                   <?php
                   require 'dbase.php';
                   $name = $_SESSION['userAccount'];
                   $stmt = $mysqli->prepare("SELECT * FROM cart WHERE buyer=?");
                   if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('s', $name);
                $stmt->execute();
                $result = $stmt->get_result();

                echo  "$name","'s Cart",
                "<table>
                <tr>
                <th>Title:</th>
                <th>Seller</th>
                </tr>";

                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>" . htmlentities($row['title']) . "</td>";
                    echo "<td>" . htmlentities($row['seller']) . "</td>";
                    echo "</tr>";
                }
                $stmt->close();
                echo "</table>";
            }
            ?>
        </body>
        </html>