
window.onload = function() {
var urlParams = new URLSearchParams(window.location.search);
const message2 = urlParams.get('message2');

if (message2) {
  var messageContainer = document.getElementById("messageContainer");
  messageContainer.textContent = decodeURIComponent(message2); // Decode the URL-encoded message
  messageContainer.style.display = "block";
  messageContainer.classList.add("message-container");
  
  setTimeout(function() {
    messageContainer.style.display = "none";
    messageContainer.classList.remove("message-container");
    
    const url = new URL(window.location);
    url.searchParams.delete('message2');
    window.history.replaceState({}, document.title, url);
  }, 10000);
}
}

function confirmUpload(form) {
    return confirm("Are you sure you want to upload this proof?");
}