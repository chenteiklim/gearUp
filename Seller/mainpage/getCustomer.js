document.getElementById("chatIcon").addEventListener("click", function () {
    const customerList = document.getElementById("customerList");
    const chatPopup = document.getElementById("chatPopup");

    // Show customer list
    customerList.style.display = "block";

    // Hide chat popup
    chatPopup.style.display = "none";
});

document.getElementById("closeCustomerList").addEventListener("click", function () {
    document.getElementById("customerList").style.display = "none";
});

document.addEventListener("DOMContentLoaded", function () {
    const customersContainer = document.getElementById("customersContainer");
    const chatPopup = document.getElementById("chatPopup");
    const chatCustomerName = document.getElementById("chatCustomerName");

    // Fetch and display customers
    fetch("getCustomer.php")
        .then(response => response.json())
        .then(customers => {
            customers.forEach(customer => {
                const customerDiv = document.createElement("div");
                customerDiv.classList.add("customer-item");
                customerDiv.innerHTML = customer.usernames;
                customerDiv.dataset.customerId = customer.customer_id;

                customerDiv.addEventListener("click", function () {
                    const selectedCustomerName = customer.usernames;
                    chatCustomerName.innerText = selectedCustomerName;

                    localStorage.setItem("selectedCustomerName", selectedCustomerName);
                    chatPopup.style.display = "block";
                    // Hide the customer list after selection
                    customerList.style.display = "none";

                    // Now `loadMessages` is defined globally
                    if (window.loadMessages) {
                        loadMessages(selectedCustomerName);
                    } else {
                        console.error("loadMessages is not defined");
                    }
                });

                customersContainer.appendChild(customerDiv);
            });
        })
        .catch(error => console.error("Error fetching customers:", error));
});


