//detect when animation ends
var animationEnd = ((el) => {
    var animations = {
      animation: 'animationend',
      OAnimation: 'oAnimationEnd',
      MozAnimation: 'mozAnimationEnd',
      WebkitAnimation: 'webkitAnimationEnd',
    };
  
    for (var t in animations) {
      if (el.style[t] !== undefined) {
        return animations[t];
      }
    }
  })(document.createElement('div'));

//hide loader bar
function hideLoader() {
    var loader = document.getElementById("loader");
    loader.classList.remove("fadeIn");
    loader.classList.add("fadeOut");
}

//show content
function showContent() {
    var content = document.getElementById("content");
    content.classList.add("animated");
    content.classList.add("delay-1s");
    content.classList.add("fadeIn");
}

//update loader bar
function updateProgress(evt){
    if (evt.lengthComputable){
       var percentComplete = (evt.loaded / evt.total)*100;  
        document.getElementById("loader-bar").style = "width:" + percentComplete + "%";
     } 
}

function generateFilterByGenreUI(artistsByGenre) {
  //prepare
  genres = Object.keys(artistsByGenre);

  //return elem
  filterByGenreUI = document.createElement('div');
  filterByGenreUI.id = "filterByGenreUI";
  
  //order genres by number of artists
  //....

  //generate filters
  filterByGenreUI = genres.reduce((total, current) => {
      let item = document.createElement('div');
      item.innerHTML = current;
      total.appendChild(item);
      return total;
  }, filterByGenreUI);

  //return elem with UI filters
  return filterByGenreUI;
}

function generateLayoutFromArtists(albumsByArtists) {
    //prepare
    genres = Object.keys(artistsByGenre);
}