// Retrieve the value of the 'success' query parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

if (Param === '1') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Email already exists. Please choose a different email.';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);

    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
    }, 10000);

} if (Param === '2') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Backup Email already exists. Please choose a different Backup email.';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);

    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
    }, 10000);
} 

else if (Param === '3') {
    const confirmDiv = document.createElement('div');
    confirmDiv.classList.add('errorMessage'); 
    confirmDiv.innerText = 'Password does not match with Confirm password.';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(confirmDiv);

    // Hide the message after 5 seconds
    setTimeout(() => {
        confirmDiv.remove();
    }, 10000);
} 

else if (Param === '4') {
    const confirmDiv = document.createElement('div');
    confirmDiv.classList.add('errorMessage'); 
    confirmDiv.innerText = 'Email is badly formatted';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(confirmDiv);

    // Hide the message after 5 seconds
    setTimeout(() => {
        confirmDiv.remove();
    }, 10000);
}

else if (Param === '5') {
    const confirmDiv = document.createElement('div');
    confirmDiv.classList.add('errorMessage'); 
    confirmDiv.innerText = 'Backup Email is badly formatted';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(confirmDiv);

    // Hide the message after 5 seconds
    setTimeout(() => {
        confirmDiv.remove();
    }, 10000);
}
  
  
document.addEventListener('DOMContentLoaded', function () {
  const togglePasswordBtn = document.getElementById('show');
  const passwordInput = document.getElementById('password');

  if (togglePasswordBtn && passwordInput) {
    togglePasswordBtn.addEventListener('click', function (event) {
      event.preventDefault();
      if (togglePasswordBtn.textContent === 'Show') {
        passwordInput.type = 'text';
        togglePasswordBtn.textContent = 'Hide';
      } else {
        passwordInput.type = 'password';
        togglePasswordBtn.textContent = 'Show';
      }
    });
  }

  const togglePasswordBtn2 = document.getElementById('show2');
  const passwordInput2 = document.getElementById('password2');

  if (togglePasswordBtn2 && passwordInput2) {
    togglePasswordBtn2.addEventListener('click', function (event) {
      event.preventDefault();
      if (togglePasswordBtn2.textContent === 'Show') {
        passwordInput2.type = 'text';
        togglePasswordBtn2.textContent = 'Hide';
      } else {
        passwordInput2.type = 'password';
        togglePasswordBtn2.textContent = 'Show';
      }
    });
  }
});

document.getElementById("login").addEventListener("click", function() {
  // Replace 'login.html' with the URL of your login page
  window.location.href = "../userLogin/login.html";
});
document.getElementById("home").addEventListener("click", function() {
// Replace 'login.html' with the URL of your login page
window.location.href = "../homepage/homepage.php";
});