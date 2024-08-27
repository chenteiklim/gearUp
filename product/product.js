console.log('hello world')
// Ensure you correctly reference your input and buttons
const quantity_input = document.getElementById('quantity_input'); // Update this to your actual input ID
const incrementButton = document.getElementById('increment'); // Update this to your actual increment button ID
const decrementButton = document.getElementById('decrement'); // Update this to your actual decrement button ID

// Increment button functionality
incrementButton.addEventListener('click', function(event) {
    event.preventDefault();
    let currentValue = parseInt(quantity_input.value, 10); // Ensure base 10 parsing
    if (!isNaN(currentValue)) {
        quantity_input.value = currentValue + 1;
    } else {
        console.error('Invalid current value for increment:', quantity_input.value);
    }
});

// Decrement button functionality
decrementButton.addEventListener('click', function(event) {
    event.preventDefault();
    let currentValue = parseInt(quantity_input.value, 10); // Ensure base 10 parsing
    if (!isNaN(currentValue) && currentValue > 1) {
        quantity_input.value = currentValue - 1;
        console.log(currentValue)
    } else {
        console.error('Invalid current value or value too low for decrement:', quantity_input.value);
    }
});

  document.getElementById("cart").addEventListener("click", function() {
    window.location.href = "cart.php";
  });

  

  document.getElementById('back').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = 'mainpage.php';
  })

  window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    var message = urlParams.get('message');
    var message3 = urlParams.get('message3');
    
    if (message) {
        var messageContainer = document.getElementById("messageContainer");
        messageContainer.textContent = message;
        messageContainer.style.display = "block";
        messageContainer.classList.add("message-container");
        setTimeout(function() {
            messageContainer.style.display = "none";
        }, 3000);
    }
     
    if (message3) {
        var messageContainer3 = document.getElementById("messageContainer3");
        messageContainer3.textContent = message;
        messageContainer3.style.display = "block";
        messageContainer3.classList.add("message-container");
        setTimeout(function() {
            messageContainer3.style.display = "none";
        }, 3000);
    }
    
    if (urlParams.get("redirect") === "true") {
        var messageContainer2 = document.getElementById("messageContainer2");
        messageContainer2.textContent = "Your cart is empty";
        messageContainer2.style.display = "block";
        messageContainer2.classList.add("message-container");
        setTimeout(function() {
            messageContainer2.style.display = "none";
        }, 3000);
    }
};
  
  
