
var sell= document.getElementById("sell");
sell.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../createProduct/createProduct.php";
});

var edit= document.getElementById("edit");

edit.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "editProduct.php";
});

var sales= document.getElementById("sales");
sales.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "sales.php";
});


var sales= document.getElementById("dailySales");
sales.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "dailySales.php";
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
        }, 2000);
    }
}
