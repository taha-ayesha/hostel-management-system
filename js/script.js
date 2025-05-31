document.querySelector("form").addEventListener("submit", function(event) {
    var email = document.querySelector("input[name='email']").value;
    if (!email.includes("@")) {
        alert("Please enter a valid email address.");
        event.preventDefault();
    }
});
