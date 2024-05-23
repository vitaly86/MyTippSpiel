const events = document.querySelectorAll(".host-events");
const users = document.querySelectorAll(".users");
console.log(events);
console.log(users);

for (let i = 0, event; (event = events[i]); i++) {
  event.addEventListener("click", function () {
    for (let table = 0; table < users.length; table++) {
      if (i == table) {
        if (users[table].style.display == "table") {
          users[table].style.display = "none";
        } else {
          users[table].style.display = "table";
        }
      } else {
        users[table].style.display = "none";
      }
    }
  });
}
