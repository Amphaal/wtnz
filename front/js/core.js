function requestUserLib() {
    var request = new XMLHttpRequest(); 
    request.onprogress=updateProgress;
    request.onloadend = (e) => processLibAsJSON(e.target.responseText);
    request.open('GET', clientURLLibrary, true);
    request.send(null);
}

//detect when animation ends
var animationEnd = (function(el) {
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

function processLibAsJSON(JSONText) {
    var lib = JSON.parse(JSONText);
    var test = albumsByArtistsList(lib);
    debugger;

    hideLoader();
    showContent();
}

var albumsByArtistsList = (lib) => {
    return lib.reduce((total, currentVal) => {
        
        //prepare
        let artist = currentVal['Album Artist'];
        let album = currentVal['Album'];
        let genre = currentVal['Genre'];
        let year = currentVal['Year'];
        let trackNo = currentVal['Track Number'];
        let trackName = currentVal['Name'];

        //if first occurence artist
        if(total[artist] == undefined) {
            total[artist] = {
                "Genres" : new Set(),
                "Albums" : {}
            }
        }

        //add genre
        total[artist]["Genres"].add(genre);

        //if first occurence album
        if (total[artist]["Albums"][album] == undefined) {
            total[artist]["Albums"][album] = {
                "Year" : year,
                "Genre" : genre,
                "Tracks" : {}
            };
        }

        //add track
        total[artist]["Albums"][album]["Tracks"][trackNo] = trackName;
        
        return total;
    }, {});
}

//at startup
document.addEventListener("DOMContentLoaded", function() {
    requestUserLib();
});


  