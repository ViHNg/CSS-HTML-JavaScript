function Month(year, month) {
    "use strict";

    this.year = year;
    this.month = month;

    this.nextMonth = function() {
        return new Month(year + Math.floor((month + 1) / 12), (month + 1) % 12);
    };

    this.prevMonth = function() {
        return new Month(year + Math.floor((month - 1) / 12), (month + 11) % 12);
    };

    this.getDateObject = function(d) {
        return new Date(this.year, this.month, d);
    };

    this.getWeeks = function() {
        var firstDay = this.getDateObject(1);
        var lastDay = this.nextMonth().getDateObject(0);

        var weeks = [];
        var currweek = new Week(firstDay);
        weeks.push(currweek);
        while (!currweek.contains(lastDay)) {
            currweek = currweek.nextWeek();
            weeks.push(currweek);
        }

        return weeks;
    };
}

function Week(initial_d) {
    "use strict";

    this.sunday = initial_d.getSunday();

    this.nextWeek = function() {
        return new Week(this.sunday.deltaDays(7));
    };

    this.prevWeek = function() {
        return new Week(this.sunday.deltaDays(-7));
    };

    this.contains = function(d) {
        return (this.sunday.valueOf() === d.getSunday().valueOf());
    };

    this.getDates = function() {
        var dates = [];
        for (var i = 0; i < 7; i++) {
            dates.push(this.sunday.deltaDays(i));
        }
        return dates;
    };
}

(function() {
    "use strict";

    /* Date.prototype.deltaDays(n)
     * 
     * Returns a Date object n days in the future.
     */
    Date.prototype.deltaDays = function(n) {
        return new Date(this.getFullYear(), this.getMonth(), this.getDate() + n);
    };

    /* Date.prototype.getSunday()
     * 
     * Returns the Sunday nearest in the past to this date (inclusive)
     */
    Date.prototype.getSunday = function() {
        return this.deltaDays(-1 * this.getDay());
    };
}());

// For our purposes, we can keep the current month in a variable in the global scope
var currentMonth = new Month(2023, 9); // October 2023

// To display the month names
var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

var currentView = "month"; // Default view
var currentWeek = new Week(new Date()); // Current week

// Once the DOM is fully loaded, the calendar is updated and event listeners are added for navigation buttons.
document.addEventListener("DOMContentLoaded", function() {

    // Change the month when the "next" button is pressed
    document.getElementById("nextmonth").addEventListener("click", function() {
        currentMonth = currentMonth.nextMonth();
        updateCalendar();
    });

    // Change the month when the "previous" button is pressed
    document.getElementById("prevmonth").addEventListener("click", function() {
        currentMonth = currentMonth.prevMonth();
        updateCalendar();
    });

	document.getElementById("setThemeColor").addEventListener("click", function() {
		const selectedColor = document.getElementById("themeColor").value;
		localStorage.setItem("themeColor", selectedColor);
		applyThemeColor();
	});
	
	function applyThemeColor() {
		const themeColor = localStorage.getItem("themeColor");
		if (themeColor) {
			document.body.style.backgroundColor = themeColor;
		}
	}
	document.getElementById("saveChanges").addEventListener("click", editEvent, false);

	applyThemeColor();  // To apply the theme color when the page loads	

    updateCalendar(); // This is already in your code, it's to initialize the calendar
});

function updateCalendar() {
    fetch("events-ajax.php", {
            method: 'POST',
            body: JSON.stringify({
                'month': currentMonth.month + 1,
                'year': currentMonth.year,
                'loggedIn': userIsLoggedIn   // Pass the user status to the server
            }),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
			if (data.success) {
				let events = data.events;
				if (currentView === "month") {
					renderCalendar(events);
				} else {
					renderWeekCalendar(events);
				}
			} else {
				if (currentView === "month") {
					renderCalendar([]);
				} else {
					renderWeekCalendar([]);
				}
			}
		})
        .catch(err => console.error(err));
}

function renderCalendar(events) {
    var weeks = currentMonth.getWeeks();
    var calendarBody = document.querySelector("#calendar-grid tbody");
    calendarBody.innerHTML = '';

    weeks.forEach(function(week) {
        var weekRow = document.createElement("tr");
        var days = week.getDates();

        days.forEach(function(day) {
            var dayCell = document.createElement("td");
            dayCell.setAttribute("data-date", day.getDate());

            /*function formatDateToMMDDYYYY(date) {
                const month = date.getMonth() + 1;
                const day = date.getDate();
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }
			
            let todaysEvents = events.filter(event => 
				event.date === formatDateToMMDDYYYY(day)
			);*/

			function formatDateToDDMMYYYY(date) {
				const day = String(date.getDate()).padStart(2, '0');
				const month = String(date.getMonth() + 1).padStart(2, '0');
				const year = date.getFullYear();
				return `${month}-${day}-${year}`;
			}
			
			//let todaysEvents = events.filter(event => 
			//	event.date === formatDateToDDMMYYYY(day)
			//);	
			let todaysEvents = [];		
			events.forEach(element => {
				// console.log(element.date + " " + formatDateToDDMMYYYY(day));
				if (element.date === formatDateToDDMMYYYY(day)) {
					todaysEvents.push(element);
				}
			});
			//console.log(events[0].date);
            todaysEvents.forEach(function(event) {

				// console.log(event); // does the event object even contain an id property

				let eventDiv = document.createElement("div");
				eventDiv.textContent = event.title;
				eventDiv.classList.add("event");
				
				let editBtn = document.createElement("button");
				editBtn.textContent = "Edit";
				editBtn.classList.add("edit-event-btn");
				editBtn.dataset.eventId = event.event_id;
			
				// for every event also create a delete button alongside it 
				let deleteBtn = document.createElement("button");
				deleteBtn.textContent = "Delete";
				// made css class so i can style it
				deleteBtn.classList.add("delete-event-btn");
				// attach event id so i can identify which event the button is associated w
				deleteBtn.dataset.eventId = event.event_id;

				// Cannot add tag
				// let tagBtn = document.createElement("button");
				// tagBtn.textContent = "Tag";
				// tagBtn.classList.add("tag-event-btn");
				// tagBtn.dataset.eventId = event.event_id;
                
				eventDiv.appendChild(editBtn);
				eventDiv.appendChild(deleteBtn);
                // eventDiv.appendChild(tagBtn);
			
				dayCell.appendChild(eventDiv);			
            });
            weekRow.appendChild(dayCell);

			let editButtons = document.querySelectorAll('.edit-event-btn');
			// select all buttons w class 'delete-event.btn'
			let deleteButtons = document.querySelectorAll('.delete-event-btn');

           // let tagButtons = document.querySelectorAll('.tag-event-btn');
			
			// when an edit button is clicked 
			editButtons.forEach(btn => {
				btn.addEventListener('click', function() {
					let eventId = this.dataset.eventId;
					let eventDetails = events.find(event => event.event_id == eventId);
					populateEventModal(eventDetails); 
				});
			});
														
			function populateEventModal(event) {
				document.getElementById("newtitle").value = event.title;
				document.getElementById("newdate").value = event.date;
				document.getElementById("newtime").value = event.time;
				document.getElementById("eventID").value = event.event_id;
			
				// Display the modal (assuming you're using a modal for editing)
				document.getElementById("editEventModal").style.display = "block";
			}			

			deleteButtons.forEach(btn => {
				btn.addEventListener('click', deleteEvent);
			});	
            // tagButtons.forEach(btn => {
			// 	btn.addEventListener('click', function() {
			// 		let eventId = this.dataset.eventId;
			// 		let eventDetails = events.find(event => event.event_id == eventId);
            //         let backgroundColor = 'yellow'; // Replace 'new-color' with the desired color

            //      // Change the background color of the event element
            //         if (eventDetails) {
            //             let eventElement = document.querySelector('.event[' + eventId + ']');
            //             if (eventElement) {
            //                 eventElement.style.backgroundColor = backgroundColor;
            //             } else {
            //                 console.log("Event element not found.");
            //             }
            //         }
			// 	});
			// });			

        });
        calendarBody.appendChild(weekRow);
    });
    document.querySelector("#currentmonthyear").textContent = `${monthNames[currentMonth.month]} ${currentMonth.year}`;
}

function populateEventModal(event) {
    document.getElementById("newtitle").value = event.title;
    document.getElementById("newdate").value = event.date;
    document.getElementById("newtime").value = event.time;
    document.getElementById("eventID").value = event.event_id;

    document.getElementById("editEventModal").style.display = "block";
}

// updates dislpay based on if user is logged in or not
function updateDisplayBasedOnLoginStatus(username) {
    const loggedIn = (username !== undefined && username !== null);

    const authSectionGuest = document.getElementById("authSectionGuest");
    const authSectionUser = document.getElementById("authSectionUser");
    const loginForm = document.getElementById("login");

    if (loggedIn) {
        authSectionGuest.style.display = "none";
        authSectionUser.style.display = "block";
        loginForm.style.display = "none";  // Hide the login form
        document.getElementById("UserIcon").textContent = username;
        document.getElementById("event-change").style.display = "block";
    } else {
        authSectionGuest.style.display = "block";
        authSectionUser.style.display = "none";
        loginForm.style.display = "block";  // Show the login form
        document.getElementById("event-change").style.display = "none";
    }
}

document.getElementById('exportJSON').addEventListener('click', function() {
    exportData('json');
});

document.getElementById('exportCSV').addEventListener('click', function() {
    exportData('csv');
});

// this is for creative portion: export json and csv files
function exportData(format) {
    // call backend to get the data
    fetch("exportEvents.php", {
        method: 'POST',
        body: JSON.stringify({
            'format': format
        }),
    })       
    .then(response => response.json())
    .then(data => {
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = `events.${format}`;
        a.click();
    })
    .catch(error => console.error('Error:', error));
}