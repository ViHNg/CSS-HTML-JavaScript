
function loginAjax(event) {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const data = { 'username': username, 'password': password };

    fetch("login_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            
            if (!data.success) {
                alert(data.message);
            } else {
                localStorage.setItem('token',data.token);

                alert("You have been successfully logged in!");
                updateDisplayBasedOnLoginStatus(username); // display nav for logged in user
                updateCalendar(); 
            }
        })
        .catch(err => console.error(err));
}


document.getElementById("login_btn").addEventListener("click", loginAjax, false); // Bind the AJAX call to button click

document.getElementById("loginbutton").addEventListener("click", function() {
    console.log("login button clicked");
    document.getElementById("login").style.display = "block";
    document.getElementById("register").style.display = "none";
    document.getElementById("message").style.display = "none";
});

document.getElementById("logoutbutton").addEventListener("click", function() {
    // Reset the buttons
    // document.getElementById("authSectionGuest").style.display = "block"; // Show login/register buttons
    // document.getElementById("authSectionUser").style.display = "none";  // Hide logout/userID buttons
    updateDisplayBasedOnLoginStatus(null);
});
