var tracking = document.getElementById("tracking");

tracking.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../tracking/tracking.php";
});


var refund = document.getElementById("refund");

refund.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "receipt.php";
});



window.onload = function() {
  var urlParams = new URLSearchParams(window.location.search);
  var message = urlParams.get('message');

  if (message) {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = message;
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    setTimeout(function() {
      messageContainer.style.display = "none";
    }, 3000);
  }
};