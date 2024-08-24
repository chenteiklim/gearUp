console.log("hello world");

const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

function clearURLParams() {
    const newURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({path: newURL}, '', newURL);
}

if (Param === '1') { 
  const nameDiv = document.createElement('div');
  nameDiv.classList.add('nameMessage'); 
  nameDiv.innerText = 'Username already exists. Please choose a different username.';
  const nameContainer = document.getElementById('nameContainer');
  if (nameContainer) {
    nameContainer.appendChild(nameDiv);
  }
  clearURLParams();
} else if (Param === '2') {
  const emailDiv = document.createElement('div');
  emailDiv.classList.add('emailMessage'); 
  emailDiv.innerText = 'Email already exists. Please choose a different email.';
  const emailContainer = document.getElementById('emailContainer');
  if (emailContainer) {
    emailContainer.appendChild(emailDiv);
  }
  clearURLParams();
} else if (Param === '3') {
  const passwordDiv = document.createElement('div');
  passwordDiv.classList.add('passwordMessage');
  passwordDiv.innerText = 'Registration failed. Please choose a different password.';
  const passwordContainer = document.getElementById('passwordContainer');
  if (passwordContainer) {
    passwordContainer.appendChild(passwordDiv);
  }
  clearURLParams();
} else if (Param === '4') {
  const confirmDiv = document.createElement('div');
  confirmDiv.classList.add('confirmMessage');
  confirmDiv.innerText = 'Password do not match with Confirm password.';
  const confirmContainer = document.getElementById('confirmContainer');
  if (confirmContainer) {
    confirmContainer.appendChild(confirmDiv);
  }
  clearURLParams();
} else if (Param === '6') { 
  const passwordDiv2 = document.createElement('div');
  passwordDiv2.classList.add('successMessage');
  passwordDiv2.innerText = 'Password do not match!';
  const successMessage2 = document.getElementById('successMessage2');
  if (successMessage2) {
    successMessage2.appendChild(passwordDiv2);
  }
  clearURLParams();
} else if (Param === '7') { 
  const successDiv3 = document.createElement('div');
  successDiv3.classList.add('successMessage');
  successDiv3.innerText = 'Password change successfully!';
  const successMessage3 = document.getElementById('successMessage3');
  if (successMessage3) {
    successMessage3.appendChild(successDiv3);
  }
  clearURLParams();
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