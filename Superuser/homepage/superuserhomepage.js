
console.log('hello world')

window.onload = function() {
  var urlParams = new URLSearchParams(window.location.search);
  const Param = urlParams.get('success');

  if (Param === '2') {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = 'Login successfully!';
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    setTimeout(() => {
      messageContainer.remove();
      const url = new URL(window.location);
      url.searchParams.delete('success');
      window.history.replaceState({}, document.title, url);    }, 3000);
  } 
}; 
    const loginButton = document.getElementById('login');
    
    // Add an event listener to the button
    loginButton.addEventListener('click', function() {
      // Code to navigate to another page goes here
      // For example, you can use the window.location.href property to redirect to a new URL
      window.location.href = '../login/login.html';
    });
    