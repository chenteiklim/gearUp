<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



mysqli_select_db($conn, $dbname); 

session_start();

// Check if the session variables are set
// If email or backupEmail is not set, display an error message and exit
if (!isset($_SESSION['emailAdmin'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;  // Stop further execution of the script
}
$email=$_SESSION['emailAdmin'];
$selectNameQuery = "SELECT * FROM superuser WHERE email = '$email'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
    $usernames = $row['username'];

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="logo.jpg" type="image/jpg">
   <style>
    body {
 background-color: bisque;
}
html, body {
  margin: 0;
  padding: 0;
  width: 100%; /* Ensure full width */
  height: 100%; /* Ensure full height */
}

#navContainer {
  display: flex;
  background-color: black;
  width: 100%; /* Adjust width as needed */
  height: 80px; /* Adjust height as needed */
  
  /* Ensure it remains visible within the container */

}
#name{
  margin-left: 900px;
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
  #home{
      margin-left: 10px;
  }
#login{
  margin-left: 900px;
}
#logoImg{
  margin-top: 25px;
  width: 35px;
  height: 35px;
  border-radius: 5px;
  margin-left: 100px;
}

  button:hover{
      transform: scale(0.9);
      background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
    }


#title{
        font-size: 24px;
        margin-left: 130px;
        color: black;
    }

input[type=text], input[type=password] ,input[type=email] {
width: 100%;
padding: 12px 20px;
margin: 8px 0;
display: inline-block;
border: 1px solid #ccc;
box-sizing: border-box;
}


.container {
background-color: white;
width:420px;
margin-top:20px;
margin-left:500px;
padding: 16px;
}
p{
width: 400px;
}


span.psw {
float: right;
padding-top: 16px;
}

.img{
margin-left:400px;
width:50px;
height:50px;
}


#Login{
 margin-left: 200px;
}

form{
 width: 100px;
}


 #confirm{
   margin-top:20px;
 }
   </style>
</head>
<body>
  

<div id="navContainer"> 
    <img id="logoImg" src="../../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Pit Stop</button>

    <button class="button" id="name"><?php echo $usernames ?></button>
  
</div>
    
    <form action="addSuperuser.php" method="post">
      <div class="container">
        <p id="title">
          Add Superuser
        </p>
        <input type="text" placeholder="Enter Username" name="username" required>
        <div id="nameContainer"></div>
  
        <input type="text" placeholder="Enter Address. Eg: 1250, Jalan6, Kampung Berapit, 14000 BM." name="address" required>
      
        <input type="email" placeholder="Enter Email" name="email" required>
        <div id="emailContainer"></div>
  
        <input type="password" id="password" placeholder="Enter Password" name="passwords" required>
        <button id="show">Show</button>
 
        <div id="passwordContainer"></div>
  
        <input type="password" id="password2" placeholder="Enter Password Again" name="confirm_password" required>
        <button id="show2">Show</button>
          <div id="errorContainer"></div>
  
  
        <input id="register" class="button" type="submit" name="submit" value="Register">
      </div>

    </form>
    

  
    </body>
    <script>
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');


if (Param === '1') {
  const confirmDiv = document.createElement('div');
  confirmDiv.classList.add('errorMessage'); 
  confirmDiv.innerText = 'Username exist';
  const errorContainer = document.getElementById('errorContainer');
  errorContainer.appendChild(confirmDiv);

  // Hide the message after 5 seconds
  setTimeout(() => {
    confirmDiv.remove();
    const url = new URL(window.location);
    url.searchParams.delete('success');
    window.history.replaceState({}, document.title, url);    }, 10000);
} 


else if (Param === '2') {
  const confirmDiv = document.createElement('div');
  confirmDiv.classList.add('errorMessage'); 
  confirmDiv.innerText = 'Username must be 5-30 characters long and can contain letters, numbers, and _ symbols.';
  const errorContainer = document.getElementById('errorContainer');
  errorContainer.appendChild(confirmDiv);

  // Hide the message after 5 seconds
  setTimeout(() => {
    confirmDiv.remove();
    const url = new URL(window.location);
    url.searchParams.delete('success');
    window.history.replaceState({}, document.title, url);    }, 10000);
} 

else if (Param === '3') {
  const confirmDiv = document.createElement('div');
  confirmDiv.classList.add('errorMessage'); 
  confirmDiv.innerText = 'Superuser maximum limit is two';
  const errorContainer = document.getElementById('errorContainer');
  errorContainer.appendChild(confirmDiv);

  // Hide the message after 5 seconds
  setTimeout(() => {
    confirmDiv.remove();
    const url = new URL(window.location);
    url.searchParams.delete('success');
    window.history.replaceState({}, document.title, url);    }, 10000);
} 



if (Param === '4') {
  const confirmDiv = document.createElement('div');
  confirmDiv.classList.add('errorMessage'); 
  confirmDiv.innerText = 'Password does not match with Confirm password.';
  const errorContainer = document.getElementById('errorContainer');
  errorContainer.appendChild(confirmDiv);

  // Hide the message after 5 seconds
  setTimeout(() => {
    confirmDiv.remove();
    const url = new URL(window.location);
    url.searchParams.delete('success');
    window.history.replaceState({}, document.title, url);    }, 10000);
} 

else if (Param === '5') {
    const confirmDiv = document.createElement('div');
    confirmDiv.classList.add('errorMessage'); 
    confirmDiv.innerText = 'Email is badly formatted';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(confirmDiv);

    // Hide the message after 5 seconds
    setTimeout(() => {
      confirmDiv.remove();
      const url = new URL(window.location);
      url.searchParams.delete('success');
      window.history.replaceState({}, document.title, url);    }, 10000);
}

else if (Param === '6') {
    const confirmDiv = document.createElement('div');
    confirmDiv.classList.add('errorMessage'); 
    confirmDiv.innerText = 'Backup Email is badly formatted';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(confirmDiv);

    // Hide the message after 5 seconds
    setTimeout(() => {
      confirmDiv.remove();
      const url = new URL(window.location);
      url.searchParams.delete('success');
      window.history.replaceState({}, document.title, url);    }, 10000);
}


  if (Param === '7') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Password must at least 10 character long';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

  
  if (Param === '8') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Need at least four special character';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

    
  if (Param === '9') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Need at least one Upper Case';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }
    
  if (Param === '10') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Need at least one lower case';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

  if (Param === '11') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Must contain number';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

  if (Param === '12') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Cannot contain 4 continuous sequence of character or 4 same character in a row';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }
  


  
  const togglePasswordBtn = document.getElementById('show');
  const passwordInput = document.getElementById('password');

  if (togglePasswordBtn && passwordInput) {
    console.log('hello world')
    togglePasswordBtn.addEventListener('click', function (event) {
      event.preventDefault();
      if (togglePasswordBtn.textContent === 'Show') {
        passwordInput.type = 'text';
        togglePasswordBtn.textContent = 'Hide';
      } else {
        passwordInput.type = 'password';
        togglePasswordBtn.textContent = 'Show';
      }
    });
  }

  const togglePasswordBtn2 = document.getElementById('show2');
  const passwordInput2 = document.getElementById('password2');

  if (togglePasswordBtn2 && passwordInput2) {
    togglePasswordBtn2.addEventListener('click', function (event) {
      event.preventDefault();
      if (togglePasswordBtn2.textContent === 'Show') {
        passwordInput2.type = 'text';
        togglePasswordBtn2.textContent = 'Hide';
      } else {
        passwordInput2.type = 'password';
        togglePasswordBtn2.textContent = 'Show';
      }
    });
  }


document.getElementById("home").addEventListener("click", function() {
// Replace 'login.html' with the URL of your login page
window.location.href = "../../mainpage/mainpage.php";
});
    </script>
      </html>
   