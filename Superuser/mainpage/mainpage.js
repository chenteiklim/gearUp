  
let timeoutDuration = 2 * 60 * 1000; 
let timeout; // Variable to hold the timeout reference
function logout() {
  navigator.sendBeacon('../login/logout.php');
   window.location.href = '../login/login.html';
}

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


document.getElementById('session').addEventListener('click', function() {
  window.location.href = 'superuser/superuser.php';
});

document.getElementById('customer').addEventListener('click', function() {
  window.location.href = 'user/viewC.php';
});

document.getElementById('seller').addEventListener('click', function() {
  window.location.href = 'seller/seller.php';
});


document.getElementById('product').addEventListener('click', function() {
  window.location.href = 'product/product.php';
});
  