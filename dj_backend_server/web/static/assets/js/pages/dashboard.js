/*
Template Name: TailFox - Responsive Tailwind Admin Dashboard
Author: Myra Studio
Website: https://myrathemes.com/
Contact: myrathemes@gmail.com
File: Dashboard
*/

$(function () {
    'use strict';
    if ($("#morris_discussion_counts").length) {
        Morris.Bar({
            element: 'morris_discussion_counts',
            barColors: ['#ebeef1', '#20b799'],
            data: [
                {
                    y: '2010',
                    a: 80,
                    b: 100
                },
                {
                    y: '2011',
                    a: 110,
                    b: 130
                },
                {
                    y: '2012',
                    a: 90,
                    b: 110
                },
                {
                    y: '2013',
                    a: 80,
                    b: 100
                },
                {
                    y: '2014',
                    a: 110,
                    b: 130
                },
                {
                    y: '2015',
                    a: 90,
                    b: 110
                },
                {
                    y: '2016',
                    a: 120,
                    b: 140
                },
                {
                    y: '2017',
                    a: 110,
                    b: 125
                },
                {
                    y: '2018',
                    a: 170,
                    b: 190
                },
                {
                    y: '2019',
                    a: 120,
                    b: 140
                }
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            hideHover: 'auto',
            gridLineColor: '#eef0f2',
            resize: true,
            barSizeRatio: 0.4,
            labels: ['iPhone 8', 'Samsung Gallexy']
        });
    }



    if ($("#morris-donut-example").length) {
        Morris.Donut({
            element: 'morris-donut-example',
            resize: true,
            backgroundColor: 'transparent',
            colors: ['#20b799', '#346ee0', '#e9ecef'],
            data: [{
                label: "Samsung Company",
                value: 12
            },
            {
                label: "Apple Company",
                value: 30
            },
            {
                label: "Vivo Mobiles",
                value: 20
            }
            ]
        });
    }

    if ($('#morris-line-example').length) {
        Morris.Line({
            element: 'morris-line-example',
            gridLineColor: '#eef0f2',
            lineColors: ['#f15050', '#e9ecef'],
            data: [{
                y: '2013',
                a: 80,
                b: 100
            },
            {
                y: '2014',
                a: 110,
                b: 130
            },
            {
                y: '2015',
                a: 90,
                b: 110
            },
            {
                y: '2016',
                a: 120,
                b: 140
            },
            {
                y: '2017',
                a: 110,
                b: 125
            },
            {
                y: '2018',
                a: 170,
                b: 190
            },
            {
                y: '2019',
                a: 120,
                b: 140
            }
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            hideHover: 'auto',
            resize: true,
            labels: ['Series A', 'Series B']
        });
    }
});