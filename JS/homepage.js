const buttonForm = document.getElementById("usubmit");
const abgelaufenEvent = document.querySelectorAll(".abgelaufen");
const currUrl = window.location.href;
const myIndexPage = window.location.origin + "/schule/MyTippSpiel/index.php";

function changeUrl(page, url) {
  if (typeof history.pushState != "undefined") {
    let obj = { Page: page, Url: url };
    history.pushState(obj, obj.Page, obj.Url);
  }
}

function showLogs() {
  const userFormEnroll = document.getElementById("user-form-container");
  let error = document.querySelector(".error");
  let success = document.querySelector(".success");
  if (error && error.textContent != "") {
    userFormEnroll.style.display = "block";
  } else if (success && success.textContent != "") {
    userFormEnroll.style.display = "block";
  } else {
    userFormEnroll.style.display = "none";
  }
}

function reloadP() {
  sessionStorage.setItem("reloading", "true");
  document.location.reload();
}

window.onload = function () {
  let reloading = sessionStorage.getItem("reloading");
  if (reloading == "true") {
    sessionStorage.removeItem("reloading");
    showLogs();
    changeUrl(currUrl, myIndexPage);
  }
};

function showUserEnroll() {
  const userFormEnroll = document.getElementById("user-form-container");
  let style = window.getComputedStyle(userFormEnroll);
  let error = document.querySelector(".error");
  let success = document.querySelector(".success");

  if (style.display == "none") {
    userFormEnroll.style.display = "block";
  } else if (style.display == "block") {
    userFormEnroll.style.display = "none";
  }
  if (success) {
    success.textContent = "";
  } else if (error) {
    error.textContent = "";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("sign-up").addEventListener("click", function () {
    showUserEnroll();
    changeUrl(currUrl, myIndexPage);
  });
});

document.getElementById("usubmit").addEventListener("click", reloadP);

for (let i = 0, abgEvent; (abgEvent = abgelaufenEvent[i]); i++) {
  abgEvent.addEventListener("click", () => {
    alert(
      "Leider, das Event ist schon abgelaufen. Du kannst bei anderem Event Enrollen"
    );
  });
}
