
// Retrieve the value of the 'success' query parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    const Param = urlParams.get('success');

  
    
    if (Param === '1') {
      const emailDiv = document.createElement('div');
      emailDiv.classList.add('errorMessage'); 
      emailDiv.innerText = 'New password need same with confirm password';
      const errorContainer = document.getElementById('errorContainer');
      errorContainer.appendChild(emailDiv);
    
      // Hide the message after 5 seconds
      setTimeout(() => {
          emailDiv.remove();
          const url = new URL(window.location);
          url.searchParams.delete('success');
          window.history.replaceState({}, document.title, url);    }, 10000);
    }
  
    
  
    
  if (Param === '2') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Password must at least 10 character long';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

  
  if (Param === '3') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Need at least four special character';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

    
  if (Param === '4') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Need at least one Upper Case';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }
    
  if (Param === '5') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Need at least one lower case';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

  if (Param === '6') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Must contain number';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

  if (Param === '7') {
    const emailDiv = document.createElement('div');
    emailDiv.classList.add('errorMessage'); 
    emailDiv.innerText = 'Cannot contain 4 continuous sequence of character or 4 same character in a row';
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.appendChild(emailDiv);
  
    // Hide the message after 5 seconds
    setTimeout(() => {
        emailDiv.remove();
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url);    }, 10000);
  }

}
 
  const togglePasswordBtn = document.getElementById('show');
  const passwordInput = document.getElementById('password');

  togglePasswordBtn.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent form submission
      if (togglePasswordBtn.textContent === 'Show') {
          passwordInput.type = 'text';
          togglePasswordBtn.textContent = 'Hide';
      } else {
          passwordInput.type = 'password';
          togglePasswordBtn.textContent = 'Show';
      }
  });

  const togglePasswordBtn2 = document.getElementById('show2');
  const passwordInput2 = document.getElementById('password2');

  togglePasswordBtn2.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent form submission
      if (togglePasswordBtn2.textContent === 'Show') {
          passwordInput2.type = 'text';
          togglePasswordBtn2.textContent = 'Hide';
      } else {
          passwordInput2.type = 'password';
          togglePasswordBtn2.textContent = 'Show';
      }
  });
