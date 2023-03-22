 $(document).ready(function () {
 // toat popup js
    $.toast({
        heading: 'به صفحه مدیریتی خود خوش آمدید'
        , text: 'در اینجا می توانید به تمامی موارد مدیریتی وب سایت خود دسترسی داشته باشید.'
        , position: 'top-right'
        , loaderBg: '#fff'
        , icon: 'warning'
        , hideAfter: 3500
        , stack: 6
    })
$('#calendar').fullCalendar('option', 'height', 745);       
// Dashboard 1 Morris-chart
Morris.Area({
    element: 'morris-area-chart2'
    , data: [{
            period: '1391'
            , SiteA: 50
            , SiteB: 0
        , }, {
            period: '1392'
            , SiteA: 160
            , SiteB: 100
        , }, {
            period: '1393'
            , SiteA: 110
            , SiteB: 60
        , }, {
            period: '1394'
            , SiteA: 60
            , SiteB: 200
        , }, {
            period: '1395'
            , SiteA: 130
            , SiteB: 150
        , }, {
            period: '1396'
            , SiteA: 200
            , SiteB: 90
        , }
        , {
            period: '1397'
            , SiteA: 100
            , SiteB: 150
        , }]
    , xkey: 'period'
    , ykeys: ['SiteA', 'SiteB']
    , labels: ['Site A', 'Site B']
    , pointSize: 0
    , fillOpacity: 0.1
    , pointStrokeColors: ['#79e580', '#2cabe3']
    , behaveLikeLine: true
    , gridLineColor: '#ffffff'
    , lineWidth: 2
    , smooth: true
    , hideHover: 'auto'
    , lineColors: ['#79e580', '#2cabe3']
    , resize: true
});

//ct-bar-chart
new Chartist.Bar('#ct-daily-sales', {
  labels: ['دوشنبه', 'سه شنبه', 'چهارشنبه', 'چهارشنبه', 'جمعه', 'شنبه', 'یکشنبه'],
  series: [
    [5, 4, 3, 7, 5, 2, 3]
    
  ]
}, {
  axisX: {
    showLabel: false,
    showGrid: false,
    // On the x-axis start means top and end means bottom
    position: 'start'
  },
  
  chartPadding: {
    top:-20,
    left:45,
  },
  axisY: {
    showLabel: false,
    showGrid: false,
    // On the y-axis start means left and end means right
    position: 'end'
  },
  height:335,
  plugins: [
    Chartist.plugins.tooltip()
  ]
});

// ct-weather
var chart = new Chartist.Line('#ct-weather', {
  labels: ['1', '2', '3', '4', '5', '6'],
  series: [
    [1, 0, 5, 3, 2, 2.5]
    
  ]
}, {
  showArea: true,
  showPoint: false,
  
  chartPadding: {
    left: -20
  },
  axisX: {
    showLabel: false,
    showGrid: false
  },
  axisY: {
    showLabel: false,
    showGrid: true
  },
  fullWidth: true,
  
});
//ct-visits
new Chartist.Line('#ct-visits', {
  labels: ['1387', '1388','1389', '1390', '1391', '1392', '1393', '1394'],
  series: [
    [5, 2, 7, 4, 5, 3, 5, 4],
    [2, 5, 2, 6, 2, 5, 2, 4]
  ]
}, {
  top:0,
  
  low:1,
  showPoint: true,
  
  fullWidth: true,
  plugins: [
    Chartist.plugins.tooltip()
  ],
  axisY: {
    labelInterpolationFnc: function(value) {
      return (value / 1) + 'k';
    }
  },
  showArea: true
});
// counter
$(".counter").counterUp({
        delay: 100,
        time: 1200
    });

});