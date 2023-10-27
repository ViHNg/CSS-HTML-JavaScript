document.addEventListener("DOMContentLoaded", function() {
    window.onload = function() {
        checkUserStatus();
    };
});

let userIsLoggedIn = false;  // Global flag for user status

function checkUserStatus() {
    fetch('user_status.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.loggedIn) {
                console.log('entering logged in branch');
                console.log(`User ${data.username} is STILL logged in`);
                userIsLoggedIn = true;
                updateDisplayBasedOnLoginStatus(data.username);
            } else {
                console.log('entering logged out branch');
                console.log('User is not logged in');
                userIsLoggedIn = false;
                updateDisplayBasedOnLoginStatus();
            }
            updateCalendar();
        })
        .catch(error => {
            console.error('There was an error checking user status:', error);
        });
}
