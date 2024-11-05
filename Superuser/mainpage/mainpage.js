  
let timeoutDuration = 10 * 1000; // 15 minutes in milliseconds
let timeout; // Variable to hold the timeout reference

function logout() {
  // You can use navigator.sendBeacon if you want to do it reliably on unload
  navigator.sendBeacon('../login/logout.php');
  
  // Optionally redirect to the login page
  window.location.href = '../login/login.html';
}

// Function to reset the timeout
function resetTimeout() {
  clearTimeout(timeout); // Clear the existing timeout
  timeout = setTimeout(logout, timeoutDuration); // Set a new timeout
}

// Set event listeners for user activity
window.addEventListener('mousemove', resetTimeout);
window.addEventListener('keypress', resetTimeout);
window.addEventListener('click', resetTimeout);
window.addEventListener('scroll', resetTimeout);

// Initialize the timeout when the page loads
resetTimeout();


document.getElementById('customer').addEventListener('click', function() {
  window.location.href = 'user/viewC.php';
});

document.getElementById('seller').addEventListener('click', function() {
  window.location.href = 'seller/seller.php';
});


document.getElementById('product').addEventListener('click', function() {
  window.location.href = 'product/product.php';
});
  