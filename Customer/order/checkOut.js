
function selectPayment(selectedId) {
    console.log("Button clicked:", selectedId);

    document.querySelectorAll('.paymentButton').forEach(button => {
        button.classList.remove('selected');
    });

    const selectedButton = document.getElementById(selectedId);
    if (selectedButton) {
        console.log("Found element:", selectedButton); // Debugging log
        selectedButton.classList.add('selected'); // Apply the class
    } else {
        console.log("Error: Element not found -", selectedId);
    }

    // Update hidden input with selected payment method
    document.getElementById('selectedPayment').value = selectedId;
}


window.onload = function() {
  var urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get('message');

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

  const message2 = urlParams.get('message');

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
}