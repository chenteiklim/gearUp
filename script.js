// JavaScript for changing order tracking elements to completed state after a delay
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        document.getElementById("order1").classList.add("completed");
    }, 10000);

    setTimeout(function() {
        document.getElementById("order2").classList.add("completed");
    }, 20000);

    setTimeout(function() {
        document.getElementById("order3").classList.add("completed");
    }, 30000);
});