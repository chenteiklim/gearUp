<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE role = 'Customer' "; // Adjust the SQL as per your table structure
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Table</title>
    <style>
        table {
        width: 100%; /* Full width of the container */
        border-collapse: collapse; /* Merge borders */
        max-width: 1500px; /* Set max width for the table */
        margin: 0 auto; /* Center the table */
        table-layout: fixed; /* Fix the table layout to respect widths */
        background-color:white;
        }
        h1{
            margin-left:20px;
        }

        th, td {
            padding: 10px; /* Add padding for cells */
            text-align: center; /* Align text to the left */
            border: 1px solid #ccc; /* Border around cells */
            width:100px;
            word-wrap: break-word; /* Allow wrapping of long words */
            white-space: normal; /* Enable normal wrapping */
        }

        th {
            background-color: #f2f2f2; /* Background color for headers */
        }
        td{
            width: 100px;
        }

       
      html, body {
        margin: 0;
        padding: 0;
        width: 100%; /* Ensure full width */
        height: 100%; /* Ensure full height */
        background-color:bisque;
    }
    
    #navContainer {
        display: flex;
        background-color: black;
        width: 100%; /* Adjust width as needed */
        height: 80px; /* Adjust height as needed */
        
        /* Ensure it remains visible within the container */
      
      }
    .button {
        background-color: black;
        color: white;
        cursor: pointer;
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 12px;
        }
        button:hover{
          transform: scale(0.9);
          background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
        }
  
        #home{
            margin-left: 10px;
        }
    #login{
        margin-left: 800px;
    }
    #logout{
      height: 80px;    
      margin-left: 800px;
    }
    #logoImg{
        margin-top: 25px;
        width: 35px;
        height: 35px;
        border-radius: 5px;
        margin-left: 100px;
    }
    
  
   
    </style>
</head>

<body>
<div id="navContainer"> 
        <img id="logoImg" src="../../../assets/logo.jpg" alt="" srcset="">
        <button class="button" id="home">Computer Shop</button>
       
</div>

    <h1>Customer List</h1>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Address</th>
                <th>Role</th>
                <th>Change Role</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>"; // Start a new table row for each user
                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>"; // Use htmlspecialchars to prevent XSS
                    echo "<td>" . htmlspecialchars($row['usernames']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                    echo "<td><form method='POST' action='changeUser.php'>"; // Change 'delete_user.php' to your delete action file
                    echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row['user_id']) . "' />"; // Include user ID
                    echo "<input type='hidden' name='action' value='change' />"; // Specify the action
                    echo "<button class='button' type='submit' onclick='return confirm(\"Are you sure you want to change this customer to seller?\")'>Change</button>"; // DELETE button
                    echo "</form></td>";
                    echo "<td><form method='POST' action='changeUser.php'>"; // Change 'delete_user.php' to your delete action file     
                    echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row['user_id']) . "' />"; // Include user ID
                    echo "<input type='hidden' name='action' value='delete' />"; // Specify the action
                    echo "<button class='button' type='submit' onclick='return confirm(\"Are you sure you want to delete this customer?\")'>DELETE</button>"; // DELETE button
                    echo "</form></td>";                    echo "</tr>"; // End the table row
                }
            } else {
                echo "<tr><td colspan='10'>No users found</td></tr>"; // Ensure colspan matches the number of columns
            }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>

<script>
var homeButton = document.getElementById("home");    
document.getElementById("home").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "../../mainpage/mainpage.php";
}); 

</script>