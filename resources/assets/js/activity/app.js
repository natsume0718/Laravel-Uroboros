import flatpickr from "flatpickr";
const twitter = require('twitter-text')
var Chart = require('chart.js');
require("flatpickr/dist/themes/material_blue.css");

$(function () {
    const config = {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        disableMobile: "true",
        defaultHour: 0,
    }
    $('.js-flatpickr-time').flatpickr(config);

    $('#js-countText').on('keyup , change', function () {
        const content = $(this).val();
        const validContent = twitter.parseTweet(content);
        // 送信ボタン活性非活性
        $('#js-submitContent').prop('disabled', !validContent.valid || false);
        // 文字数カウント
        $('#js-showCountText').html(validContent.weightedLength / 2);
    })
    $('#js-fetchPrevContent').on('click', function () {
        $.ajax({
            type: "get",
            url: $(this).data('url'),
        }).then(function (data) {
            if (data.content) {
                $('.js-displayPrev').val(data.content).trigger('change');
            }
        }, function () {
                alert('投稿の取得に失敗しました');
        });
    })


})