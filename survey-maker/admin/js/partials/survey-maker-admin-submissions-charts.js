(function($){
    'use strict';
    $(document).ready(function(){



        // Survey per answer count 
        var perAnswerData = SurveyChartData.perAnswerCount;

        $.each(perAnswerData, function(){
            switch( this.question_type ){
                case "radio":
                case "yesorno":
                    forRadioType( this );
                break;
                case "checkbox":
                    forCheckboxType( this );
                break;
                case "select":
                    forRadioType( this );
                break;
            }
        });


        function forRadioType( item ){
            var questionId = item.question_id;
            var dataTypes = [['Answers', 'Percent']];
            for (var key in item.answers) {
                dataTypes.push([
                    item.answerTitles[key] + '', item.answers[key]
                ]);
            }
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            
            function drawChart() {

                var data = google.visualization.arrayToDataTable(dataTypes);
                var options = {
                    height: 250,
                    fontSize: 16,
                    chartArea: { 
                        width: '80%',
                        height: '80%'
                    }
                };

                var chart = new google.visualization.PieChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

                chart.draw(data, options);
                resizeChart(chart, data, options);
            }
        }
        
        function forCheckboxType( item ){
            var questionId = item.question_id;
            var dataTypes = [['Answers', 'Count']];
            var sum_of_answers_count = item.sum_of_answers_count;

            if( ! $.isEmptyObject( item.answers ) ){
                for (var key in item.answers) {
                    dataTypes.push([
                        item.answerTitles[key] + '', item.answers[key]
                    ]);
                }
            }else{
                dataTypes.push([
                    '', 0
                ]);
            }

            google.charts.load('current', {packages: ['corechart', 'bar']});
            google.charts.setOnLoadCallback(drawBasic);

            function drawBasic() {

                var data = google.visualization.arrayToDataTable(dataTypes);
                var groupData = google.visualization.data.group(
                    data,
                    [{column: 0, modifier: function () {return 'total'}, type:'string'}],
                    [{column: 1, aggregation: google.visualization.data.sum, type: 'number'}]
                );
                
                var formatPercent = new google.visualization.NumberFormat({
                    pattern: '#%'
                });
            
                var formatShort = new google.visualization.NumberFormat({
                    pattern: 'short'
                });
            
                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1, {
                    calc: function (dt, row) {
                        if( $.isEmptyObject( item.answers ) ){
                            return amount;
                        }
                        var amount =  formatShort.formatValue(dt.getValue(row, 1));
                        
                        if( sum_of_answers_count == 0 ){
                            var percent = 0;
                        } else {
                            var percent = formatPercent.formatValue(dt.getValue(row, 1) / sum_of_answers_count);
                        }
        
                        return amount + ' (' + percent + ')';
                    },
                    type: 'string',
                    role: 'annotation'
                }]);
            
                var options = {
                    // width: 700,
                    height: 300,
                    fontSize: 14,
                    chartArea: { 
                        width: '55%',
                        height: '80%'
                    },
                    annotations: {
                        alwaysOutside: true
                    },
                    bars: 'horizontal',
                    bar: { groupWidth: "50%" },
                    colors: [ SurveyChartData.chartColor ]
                };

                var chart = new google.visualization.BarChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

                chart.draw(view, options);
                resizeChart(chart, view, options);
            }
        }

        // AV Google charts
        $(document).find('.nav-tab').on('click', function(e) {
            var contValue = $('div#statistics').css('display');
            if (this.getAttribute('href') == '#statistics' || contValue == 'block') {

                //Reports count per day
                var perData = [
                    [0, 0],   [1, 10],  [2, 23],  [3, 17],  [4, 18],  [5, 9],
                    [6, 11],  [7, 27],  [8, 33],  [9, 40],  [10, 32], [11, 35],
                    [12, 30], [13, 40], [14, 42], [15, 47], [16, 44], [17, 48],
                    [18, 52], [19, 54], [20, 42], [21, 55], [22, 56], [23, 57],
                    [24, 60], [25, 50], [26, 52], [27, 51], [28, 49], [29, 53],
                    [30, 55], [31, 60], [32, 61], [33, 59], [34, 62], [35, 65],
                    [36, 62], [37, 58], [38, 55], [39, 61], [40, 64], [41, 65],
                    [42, 63], [43, 66], [44, 67], [45, 69], [46, 69], [47, 70],
                    [48, 72], [49, 68], [50, 66], [51, 65], [52, 67], [53, 70],
                    [54, 71], [55, 72], [56, 73], [57, 75], [58, 70], [59, 68],
                    [60, 64], [61, 60], [62, 65], [63, 67], [64, 68], [65, 69],
                    [66, 70], [67, 72], [68, 75], [69, 80]
                ];

                // for (var l = 0; l < perData.length; l++) {
                //     perData[l] = new Array(
                //         new Date(
                //             perData[l][0]
                //         ),
                //         perData[l][1]
                //     );
                // }
                
                google.charts.load('current', {
                  packages: ['corechart']
                }).then(function () {
                    var data = new google.visualization.DataTable();
                    data.addColumn('number', 'Date');
                    data.addColumn('number', 'Count');
                    
                    data.addRows(perData);

                    var populationRange = data.getColumnRange(1);

                    var logOptions = {
                        // title: '',
                        // legend: 'none',
                        width: 700,
                        height: 300,
                        fontSize: 14,
                        hAxis: {
                            title: 'Date',
                            gridlines: {count: 15}
                        },
                        vAxis: {
                            title: 'Count'
                        }
                    };

                    var logChart = new google.visualization.LineChart(document.getElementById('survey_chart1_div'));
                    logChart.draw(data, logOptions);
                });

                // Survey passed users
                var userCount = [
                    ['Administrator', 11],
                    ['Author', 2],
                    ['Editor', 5],
                    ['Guest', 2],
                    ['Subscriber', 7]
                ];
                var dataTypes = [['Users', 'Percent']];
                    
                for (var i = 0; i < userCount.length; i++) {
                    dataTypes.push([
                        userCount[i][0], userCount[i][1]
                    ]);
                }

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {

                    var data = google.visualization.arrayToDataTable(dataTypes);

                    var options = {
                      // title: 'My Daily Activities'
                        width: 700,
                        height: 300,
                        fontSize: 16,
                        chartArea: { 
                            width: '80%',
                            height: '80%',
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('survey_chart2_div'));

                    chart.draw(data, options);
                }

                // Device chart
                var devicesCount = {
                    "Desktop": 6,
                    "Mobile": 2,
                    "Tablet": 3,
                };

                var aysDevicesBarChartData = new Array(['', '']);
                for (var count in devicesCount) {
                    aysDevicesBarChartData.push([
                        count,
                        parseInt(devicesCount[count])
                    ]);
                }

                google.charts.load('current', {packages: ['corechart', 'bar']});
                google.charts.setOnLoadCallback(drawBasic);

                function drawBasic() {

                    var data = google.visualization.arrayToDataTable(aysDevicesBarChartData);
                    var groupData = google.visualization.data.group(
                                    data,
                                    [{column: 0, modifier: function () {return 'total'}, type:'string'}],
                                    [{column: 1, aggregation: google.visualization.data.sum, type: 'number'}]
                    );
            
                    var formatPercent = new google.visualization.NumberFormat({
                        pattern: '#%'
                    });
                
                    var formatShort = new google.visualization.NumberFormat({
                        pattern: 'short'
                    });
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1, {
                        calc: function (dt, row) {
                            if( groupData.getValue(0, 1) == 0 ){
                                return amount;
                            }
                            var amount =  formatShort.formatValue(dt.getValue(row, 1));
                            var percent = formatPercent.formatValue(dt.getValue(row, 1) / groupData.getValue(0, 1));
                            return amount + ' (' + percent + ')';
                        },
                        type: 'string',
                        role: 'annotation'
                    }]);
                
                    var options = {
                        width: 700,
                        height: 300,
                        fontSize: 14,
                        chartArea: { 
                            width: '50%',
                            height: '80%'
                        },
                        annotations: {
                            alwaysOutside: true
                        },
                        bars: 'horizontal',
                        bar: { groupWidth: "50%" },
                    };

                    var chart = new google.visualization.BarChart( document.getElementById( 'survey_chart3_div') );

                    chart.draw(view,options);
                }

                var countriesCount = {
                    "USA": 6,
                    "England": 2,
                    "Germany": 3,
                };
                    
                    var aysCountriesBarChartData = new Array(['', '']);
                    for (var count in countriesCount) {
                        aysCountriesBarChartData.push([
                          count,
                          parseInt(countriesCount[count])
                        ]);
                    }

                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart2);

                    function drawChart2() {

                        var data = google.visualization.arrayToDataTable(aysCountriesBarChartData);

                        var options = {
                          // title: 'My Daily Activities'
                            width: 700,
                            height: 300,
                            fontSize: 16,
                            chartArea: { 
                                width: '80%',
                                height: '80%',
                            }
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('survey_chart4_div'));

                        chart.draw(data, options);
                    }
            }
        });

        function resizeChart(chart, data, options){
    
            //create trigger to resizeEnd event     
            jQuery(window).resize(function() {
                if(this.resizeTO) clearTimeout(this.resizeTO);
                this.resizeTO = setTimeout(function() {
                    jQuery(this).trigger('resizeEnd');
                }, 100);
            });
        
            //redraw graph when window resize is completed  
            jQuery(window).on('resizeEnd', function() {
                chart.draw(data, options);
            });
            
        }
    });
})(jQuery);
