

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

    