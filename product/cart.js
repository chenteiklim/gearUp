var logOutButton = document.getElementById("logOut");

logOutButton.addEventListener("click", function(event) {
  // Perform the navigation action here
  event.preventDefault()
  window.location.href = "../userLogin/login.html";
});
var homeButton = document.getElementById("home");

homeButton.addEventListener("click", function(event) {
  // Perform the navigation action here
  event.preventDefault()
  window.location.href = "../homepage/mainpage.php";
});

var checkOutBtn = document.getElementById("checkOutBtn");

checkOutBtn.addEventListener("click", function(event) {
  // Perform the navigation action here
  event.preventDefault()
  window.location.href = "../order/checkOut.php";
});
