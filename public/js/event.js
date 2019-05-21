
handleAllDayEvent();

function handleAllDayEvent() {

    let allDay           = document.getElementById('all-day-event');
    let startTime        = document.getElementById('start-time');
    let startPlaceholder = document.getElementById('start-placeholder');
    let endRow           = document.getElementById('end-row');

    let handler = () => {
        if(allDay.checked) {
            startPlaceholder.style.display = 'initial';
            startTime.style.display        = 'none';
            endRow.style.display           = 'none';
        }
        else {
            startPlaceholder.style.display = 'none';
            startTime.style.display        = 'initial';
            endRow.style.display           = 'flex';
        }
    };

    // call handler once for initialization
    handler();

    // setup handler when checkbox is toggled
    allDay.onchange = handler;
}