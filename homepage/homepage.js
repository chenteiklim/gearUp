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

    const registerButton = document.getElementById('register');

    // Add an event listener to the button
    registerButton.addEventListener('click', function() {
      // Code to navigate to another page goes here
      // For example, you can use the window.location.href property to redirect to a new URL
      window.location.href = 'register.html';
    });
    
    
    const loginButton = document.getElementById('login');
    
    // Add an event listener to the button
    loginButton.addEventListener('click', function() {
      // Code to navigate to another page goes here
      // For example, you can use the window.location.href property to redirect to a new URL
      window.location.href = 'login.html';
    });
    
    const sellerButton = document.getElementById('seller');
    
    // Add an event listener to the button
    sellerButton.addEventListener('click', function() {
      // Code to navigate to another page goes here
      // For example, you can use the window.location.href property to redirect to a new URL
      window.location.href = 'sellerLogin.html';
    });
    
    