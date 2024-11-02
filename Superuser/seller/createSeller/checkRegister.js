const home=document.getElementById('home');
home.addEventListener('click', function (event) {
  event.preventDefault()
    window.location.href="../homepage/homepage.php"
  })
  const registerButton = document.getElementById('register');

  // Add an event listener to the button
  registerButton.addEventListener('click', function() {
    // Code to navigate to another page goes here
    // For example, you can use the window.location.href property to redirect to a new URL
    window.location.href = '../signUp/register.html';
  });
  
  
  const loginButton = document.getElementById('login');
  
  // Add an event listener to the button
  loginButton.addEventListener('click', function() {
    // Code to navigate to another page goes here
    // For example, you can use the window.location.href property to redirect to a new URL
    window.location.href = '../userLogin/login.html';
  });
  
  document.addEventListener('keydown', function(event) {
    const inputs = document.querySelectorAll('input[type="text"]');
    const current = document.activeElement;
    const currentIndex = Array.from(inputs).indexOf(current);

    // Check if the current element is one of the inputs and move focus accordingly
    if (currentIndex !== -1) {
        // Move focus to the next input on right arrow ("ArrowRight")
        if (event.key === "ArrowRight" && currentIndex < inputs.length - 1) {
            event.preventDefault(); // Prevent default behavior of arrow key
            inputs[currentIndex + 1].focus();
        }
        // Move focus to the previous input on left arrow ("ArrowLeft")
        else if (event.key === "ArrowLeft" && currentIndex > 0) {
            event.preventDefault(); // Prevent default behavior of arrow key
            inputs[currentIndex - 1].focus();
        }
    }
});