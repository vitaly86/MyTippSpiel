const container = document.querySelectorAll(".c2-items1");
const events = document.querySelectorAll(".events");
const eventLaufen = document.querySelectorAll(".event-laufen");
const eventAbgelaufen = document.querySelectorAll(".event-abgelaufen");
const eventMixed = document.querySelectorAll(".event-mixed");
const eventStatus = document.querySelectorAll(".js-status");
const divSpiele = document.querySelectorAll(".spiele-den");
const currentSpiele = document.querySelectorAll(".contents_spiel");
const jaOption = document.getElementById("ja-enroll");
const neinOption = document.getElementById("nein-enroll");
const table = document.getElementById("table");
let style = window.getComputedStyle(table);

console.log(jaOption);
console.log(neinOption);
console.log(table);

function findMixedEvents(indexEvent) {
  let indexEventsLaufen = [];
  let indexEventsAblaufen = [];
  let indexEL = 0;
  let indexEA = 0;
  for (let i = 0, currCont; (currCont = container[i]); i++) {
    if (eventStatus[i].innerText == "Status: Laufen") {
      indexEventsLaufen.push(indexEL);
      indexEL += 1;
    } else if (eventStatus[i].innerText == "Status: Abgelaufen") {
      indexEventsAblaufen.push(indexEA);
      indexEA += 1;
    }
  }

  for (let i = 0, currCont; (currCont = container[i]); i++) {
    if (i == indexEvent) {
      if (eventStatus[i].innerText == "Status: Laufen") {
        for (let lEvent = 0; lEvent < indexEventsLaufen.length; lEvent++) {
          if (eventLaufen[lEvent].contains(eventStatus[i])) {
            if (eventLaufen[lEvent].style.display == "block") {
              eventLaufen[lEvent].style.display = "none";
            } else {
              eventLaufen[lEvent].style.display = "block";
            }
          }
        }
      } else if (eventStatus[i].innerText == "Status: Abgelaufen") {
        for (let aEvent = 0; aEvent < indexEventsAblaufen.length; aEvent++) {
          if (eventAbgelaufen[aEvent].contains(eventStatus[i])) {
            if (eventAbgelaufen[aEvent].style.display == "block") {
              eventAbgelaufen[aEvent].style.display = "none";
            } else {
              eventAbgelaufen[aEvent].style.display = "block";
            }
          }
        }
      }
    } else {
      eventMixed[i].style.display = "none";
    }
  }
}

for (let i = 0, spiel; (spiel = divSpiele[i]); i++) {
  spiel.addEventListener("click", function () {
    for (let container = 0; container < currentSpiele.length; container++) {
      if (i == container) {
        if (currentSpiele[container].style.display == "flex") {
          currentSpiele[container].style.display = "none";
        } else {
          currentSpiele[container].style.display = "flex";
        }
      }
    }
  });
}

for (let i = 0, event; (event = events[i]); i++) {
  event.addEventListener("click", function () {
    if (eventLaufen.length > 0 && eventAbgelaufen.length == 0) {
      for (let container = 0; container < eventLaufen.length; container++) {
        if (i == container) {
          if (eventLaufen[container].style.display == "block") {
            eventLaufen[container].style.display = "none";
          } else {
            eventLaufen[container].style.display = "block";
          }
        } else {
          eventLaufen[container].style.display = "none";
        }
      }
    } else if (eventLaufen.length == 0 && eventAbgelaufen.length > 0) {
      for (let container = 0; container < eventAbgelaufen.length; container++) {
        if (i == container) {
          if (eventAbgelaufen[container].style.display == "block") {
            eventAbgelaufen[container].style.display = "none";
          } else {
            eventAbgelaufen[container].style.display = "block";
          }
        } else {
          eventAbgelaufen[container].style.display = "none";
        }
      }
    } else {
      findMixedEvents(i);
    }
  });
}

document.getElementById("ja-enroll").addEventListener("click", function () {
  console.log(table.style);
  if (style.display == "none") {
    table.style.display = "table";
  }
});

document.getElementById("nein-enroll").addEventListener("click", function () {
  if (style.display == "table") {
    table.style.display = "none";
  }
});
