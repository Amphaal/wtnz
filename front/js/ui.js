
//hide loader bar
function hideLoader() {
    let loader = document.getElementById("loader");
    loader.classList.remove("fadeIn");
    loader.classList.add("fadeOut");
}

//show content
function showContent() {
    let content = document.getElementById("content");
    content.classList.add("animated");
    content.classList.add("delay-1s");
    content.classList.add("fadeIn");
}

//update loader bar
function updateProgress(evt){
    if (evt.lengthComputable){
        let percentComplete = (evt.loaded / evt.total)*100;  
        document.getElementById("loader-bar").style = "width:" + percentComplete + "%";
    } 
}

function generateAlbumsFilteredUI(albumsList) {
    //return elem
    let albumsFilteredUI = document.createElement('div');
    albumsFilteredUI.id = "albumsFilteredUI";

    //generate filters
    albumsList.reduce(function(total, current) {
        let item = document.createElement('div');
        item.innerHTML = current;
        total.push(item);
        return total;
    }, [])
    .sort(function(a, b){return b.dataset.count - a.dataset.count})
    .forEach(function(item) { albumsFilteredUI.appendChild(item)});



    //return elem with UI filters
    return albumsFilteredUI;
}

function generateFilterByArtistsUI(albumsByArtists) {
    //return elem
    let filterByArtistsUI = document.createElement('div');
    filterByArtistsUI.id = "filterByArtistsUI";

    //generate filters
    Object.keys(albumsByArtists)
    .reduce(function(total, current) {
        let item = document.createElement('div');
        item.innerHTML = current;
        item.dataset.count = albumsByArtists[current].size;
        total.push(item);
        return total;
    }, [])
    .sort(function(a, b){return b.dataset.count - a.dataset.count})
    .forEach(function(item) { filterByArtistsUI.appendChild(item)});

    //return elem with UI filters
    return filterByArtistsUI;
}

function generateFilterByGenreUI(albumsByGenre) {
    //return elem
    let filterByGenreUI = document.createElement('div');
    filterByGenreUI.id = "filterByGenreUI";

    //generate filters
    Object.keys(albumsByGenre)
    .reduce(function(total, current) {
        let item = document.createElement('div');
        item.innerHTML = current;
        item.dataset.genre = current;
        item.dataset.count = albumsByGenre[current];
        item.onclick = applyFilterGenre;
        total.push(item);
        return total;
    }, [])
    .sort(function(a, b){return b.dataset.count - a.dataset.count})
    .forEach(function(item) { filterByGenreUI.appendChild(item)});



    //return elem with UI filters
    return filterByGenreUI;
}

function renderHCPie(data, divId, name) {
  
  //format
  data = data.map(function(val) {
    val["y"] = val["value"];
    delete val.value;
    return val;
  });

  //instanciate
  Highcharts.chart(divId, {
    credits: false,
    chart: {
        type: 'pie',
        backgroundColor: 'rgba(255,255,255,0)'
    },
    title: null,
    tooltip: {
        pointFormat: '<b>{point.percentage:.1f}%</b> of Total {series.name}'
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y}',
                style : {
                    textOutline : null
                }
            }
        }
    },
    series: [{
        name : name,
        data : data
    }]
  });
}

function switchPanel(event, panelNo) {
    let statsContainer = document.getElementById('stats');
    let containerHeight = statsContainer.clientHeight 
    statsContainer.scrollTop = containerHeight * panelNo;
}

function toggleStats(event) {
    let statsContainer = document.getElementById('statsContainer');
    let heightSwitch = event.target.checked ? statsContainer.scrollHeight + "px" : "0";
    statsContainer.style.height = heightSwitch;
}

function applyFilterGenre(event) {
    let genreFilter = event.currentTarget.dataset.genre;
}