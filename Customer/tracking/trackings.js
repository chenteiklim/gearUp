document.addEventListener('DOMContentLoaded', function() {
    const refundButtons = document.querySelectorAll('.refund-btn');
    console.log(refundButtons);  // Add this to check if buttons are being selected

    refundButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = button.getAttribute('data-order-id');
            requestRefund(orderId);
        });
    });
});

function requestRefund(orderId) {
    if (confirm(`Are you sure you want to request a refund for Order #${orderId}?`)) {
        window.location.href = "refundRequestForm.php?orders_id=" + orderId;
    }
}