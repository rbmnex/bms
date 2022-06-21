(function (window, document, $) {
    $.ajax({
        url: getUrl()+'/total/bridge',
        dataType: 'json',
        type: 'GET',
        success: function(respone) {
            var options = {
                series: respone.total,
                chart: {
                width: '70%',
                type: 'pie',
                },
                labels: respone.state,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                        width: 200
                        },
                        legend: {
                        position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#pie-demo"), options);
            chart.render();
        },
        error: function(error) {
            alert('There is something wrong!');
        }
    });

    $.ajax({
        url: getUrl()+"/total/material",
        dataType : "json",
        success: function(resp) {
            options = {
                chart: {
                    type: 'bar',
                    width: '70%'
                },
                series: [{
                    name : "Total",
                    data : resp
                }],
                plotOptions: {
                    bar: {
                        dataLabels: {
                            orientation: 'vertical',
                            position: 'center'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            colors: ['#333']
                        },
                        offsetY: 300
                    }
                },
                legend: {
                    show: true
                }
            };

            var chart2 = new ApexCharts(document.querySelector("#material-bar"), options);
            chart2.render();

        }
    });

    $.ajax({
        url: getUrl()+"/total/system",
        dataType : "json",
        success: function(resp) {
            options = {
                chart: {
                    type: 'bar',
                    width: '70%'
                },
                series: [{
                    name : "Total",
                    data : resp
                }],
                plotOptions: {
                    bar: {
                        dataLabels: {
                            orientation: 'horizontal',
                            position: 'top',
                            offsetY: 150,
                            offsetX: 100,
                            style: {
                                fontSize: '10pt',
                                colors: ['#333']
                            },
                        }
                    },
                    dataLabels: {
                        position: 'top',
                        enabled: true,
                        textAnchor: 'start',
                        style: {
                            fontSize: '10pt',
                            colors: ['#333']
                        },
                        offsetX: 100,
                        horizontal: true,
                        dropShadow: {
                            enabled: false
                        },
                        offsetY: 150
                    }
                },
                legend: {
                    show: true
                }
            };

            var chart3 = new ApexCharts(document.querySelector("#system-bar"), options);
            chart3.render();

        }
    });

    $.ajax({
        url: getUrl()+"/total/deck",
        dataType : "json",
        success: function(resp) {
            options = {
                chart: {
                    type: 'bar',
                    width: '70%'
                },
                series: [{
                    name : "Total",
                    data : resp
                }],
                plotOptions: {
                    bar: {
                        dataLabels: {
                            orientation: 'vertical',
                            position: 'center'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            colors: ['#333']
                        },
                        offsetY: 300
                    }
                },
                legend: {
                    show: true
                }
            };

            var chart2 = new ApexCharts(document.querySelector("#deck-bar"), options);
            chart2.render();

        }
    });
})(window, document, jQuery);
