function registerAjax(event) {
    event.preventDefault();
    const newuser = document.getElementById("newuser").value; // Get the username from the form
    const newpass = document.getElementById("newpass").value; // Get the password from the form

    // Make a URL-encoded string for passing POST data:
    const data = { 'newuser': newuser, 'newpass': newpass };

    fetch("register_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.success ? "You've been registered!" : `You were not registered ${data.message}`)
            if (!data.success){
                alert(data.message);
            }
            else{
                console.log(data.message);

                alert("You have been successfully registered! Please log in now!");

            }
        })
        .catch(err => console.error(err));
}

document.getElementById("register_btn").addEventListener("click", registerAjax, false); // Bind the AJAX call to button click
document.getElementById("registerbutton").addEventListener("click", function() {
    document.getElementById("login").style.display = "none";
    document.getElementById("register").style.display = "block";
    document.getElementById("message").style.display = "none";
    
});
