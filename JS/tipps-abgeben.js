const genauActions = document.querySelectorAll(".action_genau");
const tendenzActions = document.querySelectorAll(".action_tendenz");
const tendenzTippOptions = document.querySelectorAll(".tendenz_tipp_option");
const teams = document.querySelectorAll(".team");
const genauButton = document.getElementById("genauer_tipp");
const tendenzButton = document.getElementById("tendenz_tipp");

function setUrltoStorage() {
  const url = new URL(window.location.href);
  const get_params = url.search;
  localStorage.setItem("current_user_tipp", get_params);
}

function changeUrl() {
  if (typeof history.pushState != "undefined") {
    const currUrl = window.location.href;
    let getStorageParams = localStorage.getItem("current_user_tipp");
    const mySpielePage =
      window.location.origin +
      "/schule/MyTippSpiel/PHP/abgeben_tipps.php" +
      getStorageParams;
    let obj = { Page: currUrl, Url: mySpielePage };
    history.pushState(obj, obj.Page, obj.Url);
  }
}

function reloadP() {
  sessionStorage.setItem("reloading", "true");
  document.location.reload();
}

document.getElementById("genauer_tipp").addEventListener("click", () => {
  reloadP();
  setUrltoStorage();
});

document.getElementById("tendenz_tipp").addEventListener("click", () => {
  reloadP();
  setUrltoStorage();
});

window.onload = function () {
  let reloading = sessionStorage.getItem("reloading");
  if (reloading == "true") {
    sessionStorage.removeItem("reloading");
    changeUrl();
  }
};

function clearOtherOptions(current, array) {
  if (current.checked) {
    for (let j = 0, other; (other = array[j]); j++) {
      if (other != current) {
        other.checked = false;
      }
    }
  }
}

function clearOtherForm(array) {
  for (let j = 0, other; (other = array[j]); j++) {
    console.log(other.checked);
    if (other.checked) {
      console.log(other.checked);
      other.checked = false;
    }
  }
}

for (let i = 0, button; (button = genauActions[i]); i++) {
  button.addEventListener("click", function () {
    clearOtherOptions(button, genauActions);
    clearOtherForm(tendenzTippOptions);
    clearOtherForm(tendenzActions);
    clearOtherForm(teams);
  });
}

for (let i = 0, tipp; (tipp = tendenzTippOptions[i]); i++) {
  tipp.addEventListener("click", function () {
    clearOtherOptions(tipp, tendenzTippOptions);
    clearOtherForm(genauActions);
  });
}

for (let i = 0, button; (button = tendenzActions[i]); i++) {
  button.addEventListener("click", function () {
    clearOtherOptions(button, tendenzActions);
    clearOtherForm(genauActions);
  });
}

for (let i = 0, team; (team = teams[i]); i++) {
  team.addEventListener("click", function () {
    clearOtherForm(genauActions);
  });
}
