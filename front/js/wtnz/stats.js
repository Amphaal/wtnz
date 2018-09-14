///
///ENTRYPOINT
///

function renderStats() {
    renderHCPie(dataFeeds.statsAlbums(), 'statsAlbums', 'Albums');
    renderHCPie(dataFeeds.statsArtists(), 'statsArtists', 'Artists');
}

///
///HELPERS UI
///

//render a single chart
function renderHCPie(data, divId, name) {

    //format to highcharts specifics
    data = data.map(function(val) {
      val["y"] = val["value"];
      delete val.value;
      return val;
    });

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
            pointFormat: '<b>{point.y}</b> or <b>{point.percentage:.1f}%</b> of {series.name}'
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
  
  function toggleStats(event) {
      let statsContainer = document.getElementById('statsContainer');
      let heightSwitch = event.currentTarget.checked ? statsContainer.scrollHeight + "px" : "0";
      statsContainer.style.height = heightSwitch;
  }