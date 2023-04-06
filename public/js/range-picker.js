// $(function () {
function dtpicker() {
    let localize = {
        format: 'YYYY/MM/DD HH:mm',
        applyLabel: 'OK',
        cancelLabel: 'キャンセル',
        daysOfWeek: [
            "日",
            "月",
            "火",
            "水",
            "木",
            "金",
            "土"
        ],
        monthNames: [
            '1月',
            '2月',
            '3月',
            '4月',
            '5月',
            '6月',
            '7月',
            '8月',
            '9月',
            '10月',
            '11月',
            '12月'
        ]
    };

    this.pickerOption = {
        opens: 'left',
        autoUpdateInput: false,
        singleDatePicker: true,
        timePicker: true,
        showDropdowns: true,
        minYear: 2010,
        maxYear: 2100,
        timePicker24Hour: true,
        locale: localize
    };

    var parent = this;
    let startPicker = $('#date_timepicker_start'),
        endPicker = $('#date_timepicker_end');

    let startPicker_icon = $('#picker-start-icon'),
        endPicker_icon = $('#picker-end-icon');

        startPicker_icon.daterangepicker(parent.pickerOption);
        endPicker_icon.daterangepicker(parent.pickerOption);

        startPicker_icon.on('apply.daterangepicker', function (ev, picker) {
            startPicker.val(picker.startDate.format('YYYY/MM/DD HH:mm'));
            parent.endRe();
        });
        endPicker_icon.on('apply.daterangepicker', function (ev, picker) {
            endPicker.val(picker.startDate.format('YYYY/MM/DD HH:mm'));
            parent.startRe();
        });

    this.endRe = function endRe() {
        let min_date = startPicker.val();
        endPicker_icon.daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            singleDatePicker: true,
            timePicker: true,
            showDropdowns: true,
            minYear: 2010,
            maxYear: 2100,
            timePicker24Hour: true,
            locale: localize,
            minDate: min_date
        });
        endPicker_icon.on('apply.daterangepicker', function (ev, picker) {
            endPicker.val(picker.startDate.format('YYYY/MM/DD HH:mm'));
            parent.startRe();
        });
    };

    this.startRe = function startRe() {
        let max_date = endPicker.val();
        startPicker_icon.daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            singleDatePicker: true,
            timePicker: true,
            showDropdowns: true,
            minYear: 2010,
            maxYear: 2100,
            timePicker24Hour: true,
            locale: localize,
            maxDate: max_date
        });
        startPicker_icon.on('apply.daterangepicker', function (ev, picker) {
            startPicker.val(picker.startDate.format('YYYY/MM/DD HH:mm'));
            parent.endRe();
        });
    }

// });
}

function onHandInput() {
    let main = new dtpicker();
    $('#date_timepicker_start').focusout(function () {
            main.endRe();
    });
    $('#date_timepicker_end').focusout(function () {
            main.startRe();
    });
}

function iconClick() {
    let main = new dtpicker();
    $('#picker-start-icon').one('mouseover', function () {
        main.startRe();
        // main.endRe();
    });
    $('#picker-end-icon').one('mouseover', function () {
        // main.startRe();
        main.endRe();
    });
}

function validate() {
    let startPicker = $('#date_timepicker_start'),
        endPicker = $('#date_timepicker_end');
    let msg = $('#invalidMsg');
    startPicker.focusout(function () {
        if (startPicker.val() && !moment(startPicker.val()).isValid()) {
            msg.text('start date is invalid');
        } else {
            msg.text("");
        }
    });
    endPicker.focusout(function () {
        if (endPicker.val() && !moment(endPicker.val()).isValid()) {
            msg.text('end date is invalid');
        } else {
            msg.text('');
        }
    });
}

function allInOnePicker() {
    dtpicker();
    onHandInput();
    // validate(); validate in BE
}

function allInOnePickerEdit() {
    dtpicker();
    onHandInput();
    iconClick();
    // validate(); validate in BE
}