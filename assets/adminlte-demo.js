require('../vendor/kevinpapst/adminlte-bundle/Resources/assets/admin-lte');

require('bootstrap')();
require('datatables.net-dt');
require( 'datatables.net-bs4' )();

// ------ for charts ------
const Chart = require('chart.js/dist/Chart.min');
global.Chart = Chart;

$(document).ready(
    function ()
    {
        $('[data-datepickerenable="on"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: "YYYY-MM-DD",
                firstDay: 1
            }
        });
    }
);
