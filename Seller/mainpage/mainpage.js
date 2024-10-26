
var sell= document.getElementById("sell");
sell.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../CRUDProduct/createProduct.php";
});

var edit= document.getElementById("edit");

edit.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../CRUDProduct/edit/editProduct.php";
});


var deletes= document.getElementById("delete");

deletes.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../CRUDProduct/deleteProduct.php";
});

var sales= document.getElementById("sales");
sales.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../sales.php";
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
