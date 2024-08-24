
// Retrieve the value of the 'success' query parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    const Param = urlParams.get('success');

    if (Param === '5') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Invalid email or password!';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      setTimeout(function() {
        messageContainer.style.display = "none";
      }, 3000);
    }
  };