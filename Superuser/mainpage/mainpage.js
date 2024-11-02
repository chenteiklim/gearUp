
const userButton=document.getElementById('view');

    // Add an event listener to the button
    userButton.addEventListener('click', function() {
      // Code to navigate to another page goes here
      // For example, you can use the window.location.href property to redirect to a new URL
      window.location.href = '../user/user.php';
    });

    const sellerButton=document.getElementById('approve');

    // Add an event listener to the button
    sellerButton.addEventListener('click', function() {
      // Code to navigate to another page goes here
      // For example, you can use the window.location.href property to redirect to a new URL
      window.location.href = '../seller/seller.html';
    });
  