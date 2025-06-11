
// Retrieve the value of the 'success' query parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    const Param = urlParams.get('success');

    if (Param === '1') {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = 'Invalid Verification Code';
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
      messageContainer.textContent = 'User not found';
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
      messageContainer.textContent = 'Email verification code is expired';
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 5000);
    }
}

  
  document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[type="text"]');

    inputs.forEach((input, index) => {
        input.addEventListener('input', function () {
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', function (event) {
            // Move left on ArrowLeft or Backspace when input is empty
            if ((event.key === 'ArrowLeft' || (event.key === 'Backspace' && !this.value)) && index > 0) {
                inputs[index - 1].focus();
                event.preventDefault();
            }
            // Move right on ArrowRight
            else if (event.key === 'ArrowRight' && index < inputs.length - 1) {
                inputs[index + 1].focus();
                event.preventDefault();
            }
        });
    });
});