// Рисует круг в указанной дивке
Circle = new function() {
    this.width = 700;
    this.height = 700;

    this.getTableValues = function(table) {
        var values = [],
            labels = [],
            table = $(table);

        $("tr",table).each(function () {
            values.push(parseInt($("td", this).text(), 10));
            labels.push($("th", this).text());
        });
        $(table).remove();

        return [values,labels];
    }

    this.init = function(container) {
        var paper = Raphael(container, this.width, this.height);

        paper.circle(350,350,180).attr({'fill' : '#ececec', 'stroke' : '#ccc'});

        this.chart1 = paper.pieChart(350, 350, 160, this.getTableValues('#circle2'), "#FFF", 'outer');
        this.chart2 = paper.pieChart(350, 350, 100, this.getTableValues('#circle1'), "#FFF", 'inner');

        paper.circle(350,350,45).attr({'fill' : 'white'});

        // Логотип SobNet в середине круга
        paper.image('/themes/images/comix/sobnet.png',312,316,73,70).hover(function(){
            this.stop().animate({transform : 'r45'}, 500,'>');
        },function(el){
            this.stop().animate({transform : 'r0'}, 800,'elastic');
        });

        // Линии по центрам координат
        //paper.path('M0,350L700,350').attr({ "stroke-width": 0.2});
        //paper.path('M350,0L350,700').attr({ "stroke-width": 0.2});
    }
}


Raphael.fn.pieChart = function (cx, cy, r, valuesLabels, stroke, type) {
    var paper = this,
        rad = Math.PI / 180,
        chart = this.set();

        function sector(cx, cy, r, startAngle, endAngle, params) {

            var x1 = cx + r * Math.cos(-startAngle * rad),
                x2 = cx + r * Math.cos(-endAngle * rad),
                y1 = cy + r * Math.sin(-startAngle * rad),
                y2 = cy + r * Math.sin(-endAngle * rad);

            return paper.path(["M", cx, cy, "L", x1, y1, "A", r, r, 0, +(endAngle - startAngle > 180), 0, x2, y2, "Z"]).attr(params);
        };

    var angle = 45,
        total = 0,
        start = 0,
        process = function (j) {
            var value = valuesLabels[0][j],
                angleplus = 360 * value / total,
                popangle = angle + (angleplus / 2),
                ms = 500,
                delta = (type == 'outer') ? -60 : -55,
                path = sector(cx, cy, r, angle, angle + angleplus,
                            {
                                fill: "#a42a21",
                                "fill-opacity": 1,
                                stroke: stroke,
                                "stroke-width": 1
                            }),

                txt = paper.text(
                    cx + (r + delta + 25) * Math.cos(-popangle * rad),
                    cy + (r + delta + 25) * Math.sin(-popangle * rad),
                    valuesLabels[1][j]).attr({
                                    fill: '#fff',
                                    stroke: "none",
                                    opacity: 0.7,
                                    "font-size": (type == 'inner') ? 13 : 11,
                                    'font-weight': 'bold',
                                    'font-family' : 'Tahoma'
                                });

                var addangle = popangle;

                if (type == 'outer')
                {
                    // Если это левая сторона, "перевернем" надпись для читабельности
                    if (addangle >= 120 && addangle <= 240)
                        addangle += 180;

                    txt.transform(['r',-addangle]);
                } else {
                    // Если это левая сторона, "перевернем" надпись для читабельности
                    if (addangle >= 240 && addangle <= 270) addangle += 180;
                    txt.transform(['r',-addangle+90]);
                }

            // Навесим события на объект

            if (type == 'inner')
            {
                path.mouseover(function () {
                    path.stop().animate({
                        transform: "s1.8 1.8 " + cx + " " + cy,
                        fill: "#BD2222",
                        'fill-opacity': 0.7
                    }, ms, "elastic");

                    var ccx = ((r + delta + 25) * Math.cos(-popangle * rad)) / 3;
                    var ccy = ((r + delta + 25) * Math.sin(-popangle * rad)) / 3;

                    txt.stop().animate({
                        opacity: 1,
                        transform: ['t',ccx,ccy,'r',0]
                        //'font-size': 17
                    }, ms, 'elastic');

                }).mouseout(function () {
                    path.stop().animate({
                        transform: "",
                        fill: '#a42a21',
                        'fill-opacity': 1
                    }, ms / 2, '<>');

                    txt.stop().animate({
                        opacity: 0.7,
                        transform: ['r',addangle-90]
                        //'font-size': 12
                    }, ms / 2, '<>');
                });
            } else {
                path.mouseover(function () {
                    path.stop().animate({
                        transform: "s1.3 1.3 " + cx + " " + cy,
                        fill: "#BD2222",
                        'fill-opacity': 0.9
                    }, ms, 'elastic');

                    var ccx = ((r + delta + 25) * Math.cos(-popangle * rad)) / 3;
                    var ccy = ((r + delta + 25) * Math.sin(-popangle * rad)) / 3;

                    txt.stop().animate({
                        opacity: 1,
                        transform: ['t',ccx,ccy,'r',0]
                        //'font-size': 17
                    }, ms, 'elastic');

                }).mouseout(function () {
                    path.stop().animate({
                        transform: "",
                        fill: '#a42a21',
                        'fill-opacity': 1
                    }, ms / 2, '<>');

                    txt.stop().animate({
                        opacity: 0.7,
                        transform: ['r',-addangle]
                        //'font-size': 12
                    }, ms / 2, '<>');
                });
            }

            angle += angleplus;
            chart.push(path);
            chart.push(txt);
            start += .1;
        };

    for (var i = 0, ii = valuesLabels[0].length; i < ii; i++) {
        total += valuesLabels[0][i];
    }

    for (i = 0; i < ii; i++) {
        process(i);
    }

    return chart;
};