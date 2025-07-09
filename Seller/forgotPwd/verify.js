// Retrieve the value of the 'success' query parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const Param = urlParams.get('success');

if (Param === '1') {
  const confirmDiv = document.createElement('div');
  confirmDiv.classList.add('errorMessage'); 
  confirmDiv.innerText = 'Invalid Verification Code';
  const errorContainer = document.getElementById('errorContainer');
  errorContainer.appendChild(confirmDiv);

  // Hide the message after 5 seconds
  setTimeout(() => {
    confirmDiv.remove();
    const url = new URL(window.location);
    url.searchParams.delete('success');
    window.history.replaceState({}, document.title, url);    }, 10000);
}

  document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[type="text"]');

    inputs.forEach((input, index) => {
      input.addEventListener('input', function () {
        const value = input.value;
        if (value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
      });

      input.addEventListener('keydown', function (event) {
        // Allow Backspace to move to the previous input if current is empty
        if (event.key === 'Backspace' && input.value === '' && index > 0) {
          inputs[index - 1].focus();
        }

        // Arrow key navigation
        if (event.key === "ArrowRight" && index < inputs.length - 1) {
          event.preventDefault();
          inputs[index + 1].focus();
        } else if (event.key === "ArrowLeft" && index > 0) {
          event.preventDefault();
          inputs[index - 1].focus();
        }
      });
    });
  });
