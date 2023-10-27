function logoutAjax(event) {
    fetch("logout_ajax.php", {
        method: 'POST',
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.success ? "You've been logged out!" : `You were not logged out`);
        if (data.success) {
            document.getElementById("authSectionGuest").style.display = "block"; // Show the guest section
            document.getElementById("authSectionUser").style.display = "none"; // Hide the user section
            document.getElementById("event-change").style.display = "none"; // Hide the event form

            updateCalendar(); // update display after logging out
        }
    })
    .catch(err => console.error(err));
}

document.getElementById("logoutbutton").addEventListener("click", logoutAjax, false);