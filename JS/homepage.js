const abgelaufenEvent = document.querySelectorAll(".abgelaufen");
const currUrl = window.location.href;
const myIndexPage = window.location.origin + "/schule/MyTippSpiel/index.php";

function changeUrl(page, url) {
  if (typeof history.pushState != "undefined") {
    let obj = { Page: page, Url: url };
    history.pushState(obj, obj.Page, obj.Url);
  }
}

function showUserEnroll() {
  const userFormEnroll = document.getElementById("user-form-container");
  let style = window.getComputedStyle(userFormEnroll);
  let error = document.querySelector(".error");
  let success = document.querySelector(".success");
  console.log(success);
  if (style.display == "none") {
    userFormEnroll.style.display = "block";
  } else {
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

for (let i = 0, abgEvent; (abgEvent = abgelaufenEvent[i]); i++) {
  abgEvent.addEventListener("click", () => {
    alert(
      "Leider, das Event ist schon abgelaufen. Du kannst bei anderem Event Enrollen"
    );
  });
}
changeUrl(currUrl, myIndexPage);

// console.log(currUrl);
// console.log(myIndexPage);
