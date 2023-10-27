function addEvent(event) {
    const title = document.getElementById("title").value; 
    const date = document.getElementById("date").value; 

    time = document.getElementById("time").value;
    if(time == ""){ // setting default value
        time = "00:00";
    }

    // Make a URL-encoded string for passing POST data:
    const data = { 'title': title, 'date': date, 'time': time};

    fetch("addEvent-ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.success ? "You've added an event!" : `Your event was not added ${data.message}`)
            if (!data.success){
                alert(data.message);
                updateCalendar();
                
            }
            else{
                // Retrieve token from local storage and verify
                let token = localStorage.getItem('token');
                if(token == data.token){
                    alert("You have added an event!");
                    updateCalendar();
                }
                else{
                    alert("Request Forgery Detected");
                    exit;
                }
            
            }})
            .catch(err => console.error(err));
    

}

document.getElementById("AddEventButton").addEventListener("click", addEvent, false);



function deleteEvent(event) {
    let eventID;
    if (event && event.target && event.target.dataset.eventId) {
        eventID = event.target.dataset.eventId;
    } else {
        eventID = document.getElementById("eventID").value;
    }
    
    const data = {'eventID': eventID };

    fetch("deleteEvent-ajax.php", {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.message);
            
        }
        else{
            let token = localStorage.getItem('token');
                
                if(token == data.token){
                    alert("You have deleted an event!");
                    updateCalendar();
                }
                else{
                    alert("Request Forgery Detected");
                    exit;
                }
        }
    })
    .catch(err => console.error(err));
}

function editEvent(event) {

    const newtitle = document.getElementById("newtitle").value; 
    const newdate = document.getElementById("newdate").value; 
    const newtime = document.getElementById("newtime").value;
    const eventID = document.getElementById("eventID").value;

    const data = { 'newtitle': newtitle, 'newdate': newdate, 'newtime': newtime, 'eventID': eventID };

    fetch("editEvent-ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.success ? "You've edited an event!" : `Your event was not edited ${data.message}`)
            if (!data.success){
                alert(data.message);
                // document.getElementById('message').innerHTML = `${data.message}`;
                // document.getElementById("message").style.display = "block";
                updateCalendar();
            }
            else{
                let token = localStorage.getItem('token');

                if(token == data.token){
                    alert("You have edited an event!");
                    document.getElementById("editEventModal").style.display = "none";
                    updateCalendar();
                }
                else{
                    alert("Request Forgery Detected");
                    exit;
                }
            }
        })
            .catch(err => console.error(err));
    
}
function tagEvent(event) {
    let eventID;
    if (event && event.target && event.target.dataset.eventId) {
        eventID = event.target.dataset.eventId;
    } else {
        eventID = document.getElementById("eventID").value;
    }
    

}
