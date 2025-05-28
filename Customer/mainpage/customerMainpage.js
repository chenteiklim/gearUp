window.onload = function() {

    var urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const message2 = urlParams.get('message2');
    const message3 = urlParams.get('message3');
    const message4 = urlParams.get('message4');
  
  
    if (message) {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = decodeURIComponent(message); // Decode the URL-encoded message
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        
        // Clear the message from the URL
        const url = new URL(window.location);
        url.searchParams.delete('message');
        window.history.replaceState({}, document.title, url);
      }, 3000);
    }
  
    if (message2) {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = decodeURIComponent(message2); // Decode the URL-encoded message
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        
        // Clear the message from the URL
        const url = new URL(window.location);
        url.searchParams.delete('message2');
        window.history.replaceState({}, document.title, url);
      }, 3000);
    }
  
  
  
    if (message3) {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = decodeURIComponent(message3); // Decode the URL-encoded message
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        
        // Clear the message from the URL
        const url = new URL(window.location);
        url.searchParams.delete('message3');
        window.history.replaceState({}, document.title, url);
      }, 10000);
    }
    
    if (message4) {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = decodeURIComponent(message4); // Decode the URL-encoded message
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        
        // Clear the message from the URL
        const url = new URL(window.location);
        url.searchParams.delete('message4');
        window.history.replaceState({}, document.title, url);
      }, 10000);
    }
  }
 document.addEventListener("DOMContentLoaded", function () {
    const chatPopup = document.getElementById("chatPopup");
    const closeChat = document.getElementById("closeChat");
    
    closeChat.addEventListener("click", function () {
        chatPopup.style.display = "none"; // Hide chat window
    });
});
  