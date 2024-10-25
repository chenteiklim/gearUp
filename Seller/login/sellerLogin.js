
// Retrieve the value of the 'success' query parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    const Param = urlParams.get('success');

    if (Param === '1') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Email is not being verified';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 5000);
    }

    if (Param === '2') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Backup email is not being verified';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      setTimeout(function() {
        
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 5000);
    }

    
    if (Param === '3') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Invalid username or password';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    
      }, 5000);
    }
    
    if (Param === '4') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'No user found with this username';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    
      }, 5000);
    }
    if (Param === '5') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Password Changed Successfully';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    
      }, 5000);
    }
  };

  



const forgotBtn=document.getElementById('forgotBtn');

 
 
  const togglePasswordBtn = document.getElementById('show');
  const passwordInput = document.getElementById('password');

  togglePasswordBtn.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent form submission
      if (togglePasswordBtn.textContent === 'Show') {
          passwordInput.type = 'text';
          togglePasswordBtn.textContent = 'Hide';
      } else {
          passwordInput.type = 'password';
          togglePasswordBtn.textContent = 'Show';
      }
  });

  document.getElementById("register").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "../signUp/register.html";
  });
  document.getElementById("home").addEventListener("click", function() {
  // Replace 'login.html' with the URL of your login page
  window.location.href = "../homepage/homepage.php";
  });