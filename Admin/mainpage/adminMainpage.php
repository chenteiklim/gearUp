<?php
session_start();
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1>";
    echo "<p>Please login to access this page.</p>";
    exit;
}
$username=$_SESSION['adminUsername'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Dashboard</title>

<style>
  /* Reset & base */
  * {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: Arial, sans-serif;
  }

  /* Main content */
  .main {
    margin-left: 250px; /* beside sidebar */
    padding: 20px;
  }
  
    
</style>

</head>
<body>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';?>
<div class="main">
  <div id="messageContainer"></div>

  <h1>Welcome to Admin Dashboard</h1>
  <p>Select an option from the sidebar to get started.</p>
</div>

</body>
</html>
<script>
  
window.onload = function() {
  var urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get('message');
  const message2 = urlParams.get('message2');

   if (message) {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = decodeURIComponent(message); // Decode the URL-encoded message
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    
    setTimeout(function() {
      messageContainer.style.display = "none";
      messageContainer.classList.remove("message-container");
      
      // Clear the message from the URL
      const url = new URL(window.location);
      url.searchParams.delete('message');
      window.history.replaceState({}, document.title, url);
    }, 3000);
  }

  if (message2) {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = decodeURIComponent(message2); // Decode the URL-encoded message
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    
    setTimeout(function() {
      messageContainer.style.display = "none";
      messageContainer.classList.remove("message-container");
      
      // Clear the message from the URL
      const url = new URL(window.location);
      url.searchParams.delete('message2');
      window.history.replaceState({}, document.title, url);
    }, 3000);
  }
}
</script>