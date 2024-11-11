


// Retrieve the value of the 'success' query parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    const Param = urlParams.get('success');

    
    
    if (Param === '1') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Invalid email or password';
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
    if (Param === '2') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'No user found with these email and backupEmail';
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
