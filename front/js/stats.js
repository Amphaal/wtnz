//render a single chart
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


function renderStats(lib) {
    let artistsByGenre = artistsByGenreList(lib);
    let albumsByGenre = albumsByGenreList(lib);
    renderHCPie(descSortObj(albumsByGenre), 'statsAlbums', 'Albums');
    renderHCPie(descSortObj(artistsByGenre), 'statsArtists', 'Artists');
}