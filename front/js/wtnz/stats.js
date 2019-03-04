///
///ENTRYPOINT
///

function renderStats() {
    renderHCPie(_appDataFeeds.statsAlbums(), 'statsAlbums');
    renderHCPie(_appDataFeeds.statsArtists(), 'statsArtists');
}

///
///HELPERS UI
///

//render a single chart
function renderHCPie(data, divId) {

    //format to highcharts specifics
    data = Object.keys(data).map(function(genreName) {
      return {
          "name" : genreName,
          "y" : data[genreName]
      };
    });

    var name = document.getElementById(divId).getAttribute('data-def');

    //define chart
    let chart = {
        credits: false,
        chart: {
            type: 'pie',
            backgroundColor: 'rgba(255,255,255,0)',
            plotBackgroundColor: null,
            plotBorderWidth: null,
        },
        title: '',
        tooltip: {
            pointFormat: i18n["statsPointFormat"]
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                dataLabels: {
                    enabled: false,
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
    };

    //instanciate
    Highcharts.chart(divId, chart);
  }
  
  function switchPanel(event) {
      let statsContainer = document.getElementById('stats');
      let panelNo = event.currentTarget.dataset.phid;
      let containerHeight = statsContainer.clientHeight 
      statsContainer.scrollTop = containerHeight * panelNo;
  }
  
  function resizeStats() {
      return _resizeShutter(
          'statsContainer',
          document.getElementById("showStats").checked
      )['heightSwitch'];
  }

  function toggleStats() {
      return _toggleShutter('statsContainer', resizeStats, vNavigate);
  }