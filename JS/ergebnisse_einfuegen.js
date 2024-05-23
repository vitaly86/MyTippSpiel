function setUrltoStorage() {
  const url = new URL(window.location.href);
  const get_params = url.search;
  localStorage.setItem("current_event_spiel", get_params);
}

function changeUrl() {
  if (typeof history.pushState != "undefined") {
    const currUrl = window.location.href;
    let getStorageParams = localStorage.getItem("current_event_spiel");
    const mySpielePage =
      window.location.origin +
      "/schule/MyTippSpiel/PHP/eintragen_ergebnisse.php" +
      getStorageParams;
    let obj = { Page: currUrl, Url: mySpielePage };
    history.pushState(obj, obj.Page, obj.Url);
  }
}

function reloadP() {
  sessionStorage.setItem("reloading", "true");
  document.location.reload();
}

document.getElementById("add_ergebniss").addEventListener("click", () => {
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
