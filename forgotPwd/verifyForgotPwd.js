// Retrieve the value of the 'success' query parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    const Param = urlParams.get('success');
    if (Param === '1') {
        var messageContainer = document.getElementById("messageContainer");
        messageContainer.textContent = 'No account found with those email address';
        messageContainer.style.display = "block";
        messageContainer.classList.add("message-container");
        
        setTimeout(function() {
          messageContainer.style.display = "none";
          messageContainer.classList.remove("message-container");
      }, 10000); // 10,000 milliseconds = 10 seconds
      }

    if (Param === '2') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Email address must different with Backup email address';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
    }, 10000); // 10,000 milliseconds = 10 seconds
    }
  
  };