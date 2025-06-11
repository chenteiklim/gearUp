

window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    const Param = urlParams.get('success');

    if (Param === '5') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Register successfully!';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      setTimeout(function() {
        messageContainer.style.display = "none";
      }, 3000);
    }
  };

    document.getElementById("register").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "../signUp/register.php";
  }); 
    document.getElementById("login").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "../login/login.php";
  }); 