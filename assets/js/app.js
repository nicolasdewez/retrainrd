var $ = require('jquery');
require('bootstrap-sass');
require('moment');
require('bootstrap-datetimepicker-sass/src/js/bootstrap-datetimepicker');

$(document).ready(function() {
    $('input[type=date]').datetimepicker({
        format: 'YYYY-MM-DD',
        calendarWeeks: true,
        toolbarPlacement: 'top',
        showTodayButton: true,
        showClose: true,
        keepOpen: false,
        disabledHours: true
    });
});
