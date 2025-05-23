document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        editable: true,
        droppable: true,
        events: '/backend/get_events.php',
        eventReceive: function(info) {
            var event = info.event;
            var eventData = {
                title: event.title,
                start: event.start.toISOString(),
                end: event.end ? event.end.toISOString() : null
            };
            fetch('/backend/add_event.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(eventData)
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Event added successfully!');
                  } else {
                      alert('Failed to add event.');
                  }
              });
        },
        eventClick: function(info) {
            var event = info.event;
            if (confirm('Are you sure you want to delete this event?')) {
                fetch('/backend/delete_event.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: event.id })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          event.remove();
                          alert('Event deleted successfully!');
                      } else {
                          alert('Failed to delete event.');
                      }
                  });
            }
        }
    });
    calendar.render();
});
