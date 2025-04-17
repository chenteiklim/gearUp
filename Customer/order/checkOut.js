document.getElementById("home").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "../homepage/mainpage.php";
}); 
function selectPayment(selectedId) {
    console.log("Button clicked:", selectedId);

    document.querySelectorAll('.paymentButton').forEach(button => {
        button.classList.remove('selected');
    });

    const selectedButton = document.getElementById(selectedId);
    if (selectedButton) {
        console.log("Found element:", selectedButton); // Debugging log
        selectedButton.classList.add('selected'); // Apply the class
    } else {
        console.log("Error: Element not found -", selectedId);
    }

    // Update hidden input with selected payment method
    document.getElementById('selectedPayment').value = selectedId;
}