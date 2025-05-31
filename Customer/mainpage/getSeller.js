document.getElementById("chatIcon").addEventListener("click", function () {
    const sellerList = document.getElementById("sellerList");
    const chatPopup = document.getElementById("chatPopup");

    // Show seller list
    sellerList.style.display = "block";

    // Hide chat popup
    chatPopup.style.display = "none";
});

document.getElementById("closeSellerList").addEventListener("click", function () {
    document.getElementById("sellerList").style.display = "none";
});

document.addEventListener("DOMContentLoaded", function () {
    const sellersContainer = document.getElementById("sellersContainer");
    const chatPopup = document.getElementById("chatPopup");
    const chatSellerName = document.getElementById("chatSellerName");

    // Fetch and display sellers
    fetch("getSeller.php")
        .then(response => response.json())
        .then(sellers => {
            sellers.forEach(seller => {
                const sellerDiv = document.createElement("div");
                sellerDiv.classList.add("seller-item");
                sellerDiv.innerHTML = seller.usernames;

                sellerDiv.addEventListener("click", function () {
                    const selectedSellerName = seller.usernames;
                    chatSellerName.innerText = selectedSellerName;

                    localStorage.setItem("selectedSellerName", selectedSellerName);
                    chatPopup.style.display = "block";
                    // Hide the seller list after selection
                    sellerList.style.display = "none";

                    // Now `loadMessages` is defined globally
                    if (window.loadMessages) {
                        loadMessages(selectedSellerName);
                    } else {
                        console.error("loadMessages is not defined");
                    }
                });

                sellersContainer.appendChild(sellerDiv);
            });
        })
        .catch(error => console.error("Error fetching sellers:", error));
});
