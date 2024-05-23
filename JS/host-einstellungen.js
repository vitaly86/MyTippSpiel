const currUrl = window.location.href;
const myIndexPage =
  window.location.origin + "/schule/MyTippSpiel/PHP/einstellungen-host.php";

function changeUrl(page, url) {
  if (typeof history.pushState != "undefined") {
    let obj = { Page: page, Url: url };
    history.pushState(obj, obj.Page, obj.Url);
  }
}

changeUrl(currUrl, myIndexPage);
