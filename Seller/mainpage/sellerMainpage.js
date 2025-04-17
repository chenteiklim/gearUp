document.addEventListener("DOMContentLoaded", function () {
    const chatPopup = document.getElementById("chatPopup");
    const closeChat = document.getElementById("closeChat");
    
    closeChat.addEventListener("click", function () {
        chatPopup.style.display = "none"; // Hide chat window
    });
});

