require('../vendor/kevinpapst/adminlte-bundle/Resources/assets/admin-lte');

require('datatables.net-dt');
// require('popper')
// require('child_process')
// require('fs')

require('bootstrap')();
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
