@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
        @include(admin.partials.header)
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                    @include(admin.partials.sidebar)
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content  px-lg-3 ">
                            <div id="kt_app_content_container" class="app-container  container-fluid ">
                               
    <p>Welcome, {{ $user->fullname ?? '' }}!</p>
    

    <div class="row mt-5">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Website Visitors</h5>
                    <p class="mb-0">Total Visits: <b>{{ $visitor_count }}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Reviews</h5>
                    <p class="mb-1">Total: <b>{{ $review_count }}</b></p>
                    <p class="mb-1 text-warning">Pending: <b>{{ $pending_review_count }}</b></p>
                    <p class="mb-0 text-success">Approved: <b>{{ $approved_review_count }}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Tours</h5>
                    <p class="mb-0">Total: <b>{{ $tour_count }}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Pages</h5>
                    <p class="mb-0">Total: <b>{{ $page_count }}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Gallery</h5>
                    <p class="mb-0">Total: <b>{{ $gallery_count }}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Slides</h5>
                    <p class="mb-0">Total: <b>{{ $slide_count }}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Blogs</h5>
                    <p class="mb-0">Total: <b>{{ $blog_count }}</b></p>
                </div>
            </div>
        </div>
    </div>
    
<div id="chartdiv"></div>
<table class="table table-responsive">
  <thead>
  <tr>
    <th>Country</th>
    <th align='right'>Visits</th>
  </tr>
  </thead>
  <tbody id="visitors">

  </tbody>
</table>
                            </div>
                        </div>
                    </div>
                    <!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 600px
}
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>

</script>
<script>
  function calculatePercentage(total, value) {
    return (value / total) * 100;
  }

  fetch("<?= $_ENV['APP_URL']."/".$_ENV['PATH_ADMIN'] ?>/visitor")
  .then(response => response.json())
  .then(results => {
    am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create the map chart
// https://www.amcharts.com/docs/v5/charts/map-chart/
var chart = root.container.children.push(am5map.MapChart.new(root, {
  panX: "rotateX",
  panY: "rotateY",
  projection: am5map.geoOrthographic()
}));


// Create series for background fill
// https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/#Background_polygon
var backgroundSeries = chart.series.push(
  am5map.MapPolygonSeries.new(root, {})
);
backgroundSeries.mapPolygons.template.setAll({
  fill: root.interfaceColors.get("alternativeBackground"),
  fillOpacity: 0.1,
  strokeOpacity: 0
});
backgroundSeries.data.push({
  geometry:
    am5map.getGeoRectangle(90, 180, -90, -180)
});


// Create main polygon series for countries
// https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/
var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
  geoJSON: am5geodata_worldLow 
}));
polygonSeries.mapPolygons.template.setAll({
  fill: root.interfaceColors.get("alternativeBackground"),
  fillOpacity: 0.15,
  strokeWidth: 0.5,
  stroke: root.interfaceColors.get("background")
});


// Create polygon series for projected circles
var circleSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {}));
circleSeries.mapPolygons.template.setAll({
  templateField: "polygonTemplate",
  tooltipText: "{name}:{value}"
});

// Define data
var colors = am5.ColorSet.new(root, {});
var data = [];
var visitors = document.getElementById("visitors");
var html = "";
results.forEach(element => {
  data.push({
    "id": element.id,
    "name": element.name,
    "value": element.count,
    "polygonTemplate": { fill: colors.getIndex(0) }
  });
  var flag = element.id.toLowerCase();
  html += "<tr><td><img src='https://cdn.ipwhois.io/flags/"+flag+".svg' alt='" + element.name + "' width='30' height='20'> " + element.name + "</td><td align='right'>" + element.count + "</td></tr>";
});
visitors.innerHTML = html;
var valueLow = Infinity;
var valueHigh = -Infinity;

for (var i = 0; i < data.length; i++) {
  var value = data[i].value;
  if (value < valueLow) {
    valueLow = value;
  }
  if (value > valueHigh) {
    valueHigh = value;
  }
}

// radius in degrees
var minRadius = 0.5;
var maxRadius = 5;

// Create circles when data for countries is fully loaded.
polygonSeries.events.on("datavalidated", function () {
  circleSeries.data.clear();

  for (var i = 0; i < data.length; i++) {
    var dataContext = data[i];
    var countryDataItem = polygonSeries.getDataItemById(dataContext.id);
    var countryPolygon = countryDataItem.get("mapPolygon");

    var value = dataContext.value;

    var radius = minRadius + maxRadius * (value - valueLow) / (valueHigh - valueLow);

    if (countryPolygon) {
      var geometry = am5map.getGeoCircle(countryPolygon.visualCentroid(), radius);
      circleSeries.data.push({
        name: dataContext.name,
        value: dataContext.value,
        polygonTemplate: dataContext.polygonTemplate,
        geometry: geometry
      });
    }
  }
})


// Make stuff animate on load
chart.appear(1000, 100);

}); // end am5.ready()
  })
  .catch(error => {
    console.error("Error fetching data:", error);
  });
</script>

                    
@include(admin.partials.footer)
