// require('../vendor/kevinpapst/adminlte-bundle/Resources/assets/admin-lte');
const $ = require('jquery');
global.$ = global.jQuery = $;

require('jquery-ui');
require('bootstrap-sass');
require('jquery-slimscroll');
require('bootstrap-select');

const Moment = require('moment');
global.moment = Moment;
require('daterangepicker');

// ------ AdminLTE framework ------
require('../vendor/kevinpapst/adminlte-bundle/Resources/assets/admin-lte.scss');
require('admin-lte/dist/css/AdminLTE.css');
require('./_admin-lte-all-skins.css');
require('../vendor/kevinpapst/adminlte-bundle/Resources/assets/admin-lte-extensions.scss');

global.$.AdminLTE = {};
global.$.AdminLTE.options = {};
require('admin-lte/dist/js/adminlte.min');

// ------ Theme itself ------
require('../vendor/kevinpapst/adminlte-bundle/Resources/assets/default_avatar.png');

// ------ icheck for enhanced radio buttons and checkboxes ------
require('icheck');
require('icheck/skins/square/blue.css');

require('datatables.net-dt');

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
