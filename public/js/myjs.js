var userLang = navigator.language || navigator.userLanguage;
const sort_asc = '&#9206;';
const sort_desc = '&#9207;';
const timeIntervals = [
    '00:00',	'00:15',	'00:30',	'00:45',
    '01:00',	'01:15',	'01:30',	'01:45',
    '02:00',	'02:15',	'02:30',	'02:45',
    '03:00',	'03:15',	'03:30',	'03:45',
    '04:00',	'04:15',	'04:30',	'04:45',
    '05:00',	'05:15',	'05:30',	'05:45',
    '06:00',	'06:15',	'06:30',	'06:45',
    '07:00',	'07:15',	'07:30',	'07:45',
    '08:00',	'08:15',	'08:30',	'08:45',
    '09:00',	'09:15',	'09:30',	'09:45',
    '10:00',	'10:15',	'10:30',	'10:45',
    '11:00',	'11:15',	'11:30',	'11:45',
    '12:00',	'12:15',	'12:30',	'12:45',
    '13:00',	'13:15',	'13:30',	'13:45',
    '14:00',	'14:15',	'14:30',	'14:45',
    '15:00',	'15:15',	'15:30',	'15:45',
    '16:00',	'16:15',	'16:30',	'16:45',
    '17:00',	'17:15',	'17:30',	'17:45',
    '18:00',	'18:15',	'18:30',	'18:45',
    '19:00',	'19:15',	'19:30',	'19:45',
    '20:00',	'20:15',	'20:30',	'20:45',
    '21:00',	'21:15',	'21:30',	'21:45',
    '22:00',	'22:15',	'22:30',	'22:45',
    '23:00',	'23:15',	'23:30',	'23:45'
];

var options = $.extend({},
    $.datepicker.regional["ja"],
    {
        dateFormat: "yy/mm/dd",
        changeMonth: true,
        changeYear: true,
        highlightWeek: true
    }
);
$("#btn-register").click(function () {
    $("#form-register").submit()
});

// gets the center of a table cell relative to the document
function getCellCenter(table, row, column, positionTop, periodIndex) {
    let tableRow = $(table).find('tr')[row];
    let tableCell = $(tableRow).find('td')[column];

    let offset = $(tableCell).offset();
    let width = $(tableCell).innerWidth();
    let height = $(tableCell).innerHeight();
    let xx, yy;
    if (periodIndex === -1) {
        xx = offset.left;
    } else if (periodIndex === 0) {
        xx = offset.left + width / 6;
    } else if (periodIndex === 1) {
        xx = offset.left + 3 * width / 6;
    } else if (periodIndex >= 2) {
        xx = offset.left + 5 * width / 6;
    } else if (!periodIndex) {
        xx = offset.left + width / 2;
    }
    yy = offset.top + height / positionTop;
    return {
        x: xx,
        y: yy
    }
}

// draws an arrow on the document from the start to the end offsets
function drawArrow(start, end, color, hasEndPoint, hasStartPoint) {
    // create a canvas to draw the arrow on
    let canvas = document.createElement('canvas');
    canvas.width = $(document).innerWidth() - 10;
    canvas.height = $('.wrapper').innerHeight() + 20;
    $(canvas).css('position', 'absolute');
    $(canvas).css('pointer-events', 'none');
    $(canvas).css('top', '0');
    $(canvas).css('left', '0');
    $(canvas).css('opacity', '0.85');
    $('body').append(canvas);

    // get the drawing context
    let ctx = canvas.getContext('2d');
    ctx.fillStyle = color;
    ctx.strokeStyle = color;

    // draw line from start to end

    if (start.x === end.x) {
        end.x = end.x + 10;
    }

    ctx.beginPath();
    ctx.moveTo(start.x, start.y);
    ctx.lineTo(end.x, end.y);
    ctx.lineWidth = 2;
    ctx.stroke();
    // draw circle at beginning of line
    if (hasStartPoint) {
        ctx.beginPath();
        ctx.arc(start.x, start.y, 4, 0, Math.PI * 2, true);
        ctx.fill();
    }
    // draw pointer at end of line (needs rotation)
    if (hasEndPoint) {
        ctx.beginPath();
        let angle = Math.atan2(end.y - start.y, end.x - start.x);
        ctx.translate(end.x + 3, end.y);
        ctx.rotate(angle);
        ctx.moveTo(0, 0);
        ctx.lineTo(-10, -7);
        ctx.lineTo(-10, 7);
        ctx.lineTo(0, 0);
        ctx.fill();
    }

    // reset canvas context
    ctx.setTransform(1, 0, 0, 1, 0, 0);

    return canvas;
}

function getPeriod(day) {
    return Math.floor(day / 10);
}

// < For theme view only
function findColumnPosition(id) {
    let pos = $('#' + id).index();
    if (pos == -1) {
        return $('#table-promotion-theme tbody tr:first-child td:last-child').index();
    } else {
        return pos;
    }
}

// < For theme view only
function findColumnPositionReal(id, isStart) {
    let pos = $('#' + id).index();
    if (pos == -1) {
        if (isStart) {
            return $('#table-promotion-theme tbody tr:first-child td:nth-child(2)').index();
        }
        return $('#table-promotion-theme tbody tr:first-child td:last-child').index();
    } else {
        return pos;
    }
}

// For theme view only >

// finds the center of the start and end cells, and then calls drawArrow
//data [table, startRow, startColumn, endRow, endColumn, color, positionTop, startPeriod, endPeriod, hasEndPoint, hasStartPoint];
function drawArrowOnTable(data) {
    drawArrow(
        //start
        getCellCenter($(data[0]), data[1], data[2], data[6], data[7]),
        //end
        getCellCenter($(data[0]), data[3], data[4], data[6], data[8]),
        //color
        data[5],
        //
        data[9], data[10]
    );
}

(function () {
    let throttle = function (type, name, obj) {
        obj = obj || window;
        let running = false;
        let func = function () {
            if (running) {
                return;
            }
            running = true;
            requestAnimationFrame(function () {
                obj.dispatchEvent(new CustomEvent(name));
                running = false;
            });
        };
        obj.addEventListener(type, func);
    };
    throttle("resize", "optimizedResize");
})();
let optimizedResize = (function () {
    let callbacks = [],
        running = false;

    // fired on resize event
    function resize() {
        if (!running) {
            running = true;
            if (window.requestAnimationFrame) {
                window.requestAnimationFrame(runCallbacks);
            } else {
                setTimeout(runCallbacks, 66);
            }
        }
    }

    function runCallbacks() {
        callbacks.forEach(function (callback) {
            callback();
        });
        running = false;
    }

    function addCallback(callback) {
        if (callback) {
            callbacks.push(callback);
        }
    }

    return {
        add: function (callback) {
            if (!callbacks.length) {
                window.addEventListener('resize', resize);
            }
            addCallback(callback);
        }
    }
}());

function resizehandle(callback) {
    (function (callback) {
        window.addEventListener("resize", resizeThrottler, false);
        let resizeTimeout;

        function resizeThrottler() {
            if (!resizeTimeout) {
                resizeTimeout = setTimeout(function () {
                    resizeTimeout = null;
                    actualResizeHandler();
                }, 70);
            }
        }

        function actualResizeHandler() {
            // handle the resize event
            //canvas marking
            //let coors = callback();
            $('canvas').first().addClass('trash');
            //redraw
            drawArrowOnTable(callback());
            //clear trash
            $('canvas').remove('.trash');
        }
    }(callback));

}

//Function get parameter from url
function getUrlParameter(sParam) {
    let sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}

let isAsc = true;

function Filter(own) {
    let url = document.location.href;
    let indexFilter = url.indexOf('&filter');
    if (indexFilter > -1) {
        let strReplace = url.substr(indexFilter, url.length);
        url = url.replace(strReplace, '');
    } else {
        let indexFilterQuestion = url.indexOf('?filter');
        if (indexFilterQuestion > -1) {
            let strReplace = url.substr(indexFilterQuestion, url.length);
            url = url.replace(strReplace, '');
        }
    }
    let indexQuestionMark = url.indexOf('?');
    if (indexQuestionMark > -1) {
        let part1 = url.slice(0, indexQuestionMark + 1);
        let indexApersand = url.indexOf('&');
        if (indexApersand > -1) {
            let keys = url.slice(url.indexOf('&') + 1, url.length);
            url = part1 + keys + '&filter=' + own.value;
        } else {
            keys = "";
            url = part1 + keys + 'filter=' + own.value;
        }
    } else {
        url += "?filter=" + own.value;
    }

    window.location.href = url;
}

function sortAndFilter(columns, index_old, view, sortInit, noClick) {
    let sortUrl = getUrlParameter('sort');
    let sortTypeUrl = getUrlParameter('sortType');
    if (sortUrl !== undefined && sortTypeUrl !== undefined) {
        let indexUrl = columns.indexOf(sortUrl);
        if (indexUrl > 0) {
            if (sortTypeUrl == 'asc') {
                $('#myTable tr:first-child th:nth-child(' + (indexUrl + 1) + ')').append(sort_asc);
            } else {
                $('#myTable tr:first-child th:nth-child(' + (indexUrl + 1) + ')').append(sort_desc);
            }
            index_old = indexUrl + 1;
            if (sortTypeUrl == 'desc') {
                isAsc = false;
            }
        } else {
            $('#myTable tr:first-child th:nth-child(' + index_old + ')').append(sort_asc);
        }
    } else {
        if (!sortInit) {
            $('#myTable tr:first-child th:nth-child(' + index_old + ')').append(sort_asc);
        } else {
            $('#myTable tr:first-child th:nth-child(' + index_old + ')').append(sort_desc);
        }
    }

    $('#myTable thead').on('click', 'th', function () {
        let index = $(this).index();
        if (noClick) {
            for (let i = 0; i < noClick.length; i++) {
                if (index == noClick[i]) {
                    index = 0;
                    break;
                }
            }
        }
        if (index != 0) {
            let sortType = "asc";
            if (index == (index_old - 1)) {
                isAsc = !isAsc;
            } else {
                isAsc = true;
            }
            if (!isAsc) {
                sortType = "desc";
            }
            let page = 'page=1';
            let pageNumber = getUrlParameter('page');
            if (pageNumber !== undefined) {
                page = 'page=' + pageNumber;
            }
            let url = view + "?" + page + "&sort=" + columns[index] + '&sortType=' + sortType;
            let filter = getUrlParameter('filter');
            if (filter !== undefined) {
                url += "&filter=" + filter;
            }
            window.location.href = url;
        }
    });
}


function getCellCenterToppage(table, row, column, positionTop, periodIndex) {
    let tableRow = $(table).find('tr')[row];
    let tableCell = $(tableRow).find('td')[column];

    let offset = $(tableCell).position();
    let width = $(tableCell).innerWidth();
    let height = $(tableCell).innerHeight();
    let xx, yy;
    if (periodIndex === -1) {
        xx = offset.left;
    } else if (periodIndex === 0) {
        xx = offset.left + width / 6;
    } else if (periodIndex === 1) {
        xx = offset.left + 3 * width / 6;
    } else if (periodIndex >= 2) {
        xx = offset.left + 5 * width / 6;
    } else if (!periodIndex) {
        xx = offset.left + width / 2;
    }
    yy = offset.top + height / positionTop;
    return {
        x: xx,
        y: yy
    }
}

function drawArrowToppage(start, end, color, hasEndPoint, hasStartPoint) {
    // create a canvas to draw the arrow on
    let canvaser = $('#canvas-container');
    let parr = $('#table-active-theme');
    let canvas = document.createElement('canvas');
    canvas.width = parr.innerWidth();
    canvas.height = parr.innerHeight();
    $(canvas).css('position', 'absolute');
    $(canvas).css('pointer-events', 'none');
    $(canvas).css('top', '0');
    $(canvas).css('left', '0');
    $(canvas).css('opacity', '0.85');
    canvaser.append(canvas);

    // get the drawing context
    let ctx = canvas.getContext('2d');
    ctx.fillStyle = color;
    ctx.strokeStyle = color;

    // draw line from start to end
    if (start.x === end.x) {
        end.x = end.x + 10;
    }
    ctx.beginPath();
    ctx.moveTo(start.x, start.y);
    ctx.lineTo(end.x, end.y);
    ctx.lineWidth = 2;
    ctx.stroke();
    // draw circle at beginning of line
    if (hasStartPoint) {
        ctx.beginPath();
        ctx.arc(start.x, start.y, 4, 0, Math.PI * 2, true);
        ctx.fill();
    }
    // draw pointer at end of line (needs rotation)
    if (hasEndPoint) {
        ctx.beginPath();
        let angle = Math.atan2(end.y - start.y, end.x - start.x);
        ctx.translate(end.x + 3, end.y);
        ctx.rotate(angle);
        ctx.moveTo(0, 0);
        ctx.lineTo(-10, -7);
        ctx.lineTo(-10, 7);
        ctx.lineTo(0, 0);
        ctx.fill();
    }

    // reset canvas context
    ctx.setTransform(1, 0, 0, 1, 0, 0);

    return canvas;
}

//data [table, startRow, startColumn, endRow, endColumn, color, positionTop, startPeriod, endPeriod, hasEndPoint, hasStartPoint];
function drawArrowOnTableToppage(data) {
    drawArrowToppage(
        //start
        getCellCenterToppage($(data[0]), data[1], data[2], data[6], data[7]),
        //end
        getCellCenterToppage($(data[0]), data[3], data[4], data[6], data[8]),
        //color
        data[5],
        //
        data[9], data[10]
    );
}

function resizehandleToppage(callback) {
    (function (callback) {
        window.addEventListener("resize", resizeThrottlerToppage, false);
        let resizeTimeout;

        function resizeThrottlerToppage() {
            if (!resizeTimeout) {
                resizeTimeout = setTimeout(function () {
                    resizeTimeout = null;
                    actualResizeHandlerToppage();
                }, 70);
            }
        }

        function actualResizeHandlerToppage() {
            // handle the resize event
            //canvas marking
            $('canvas').first().addClass('trash');
            //redraw
            drawArrowOnTableToppage(callback());
            //clear trash
            $('canvas').remove('.trash');
        }
    }(callback));

}

function getCellCenterApproval(table, row, column, positionTop, periodIndex) {
    let tableRow = $(table).find('tr')[row];
    let tableCell = $(tableRow).find('td')[column];

    let offset = $(tableCell).position();
    let width = $(tableCell).innerWidth();
    let height = $(tableCell).innerHeight();
    let xx, yy;
    if (periodIndex == -1) {
        xx = offset.left;
    } else if (periodIndex == 0) {
        xx = offset.left + width / 6;
    } else if (periodIndex == 1) {
        xx = offset.left + 3 * width / 6;
    } else if (periodIndex >= 2) {
        xx = offset.left + 5 * width / 6;
    } else if (!periodIndex) {
        xx = offset.left + width / 2;
    }
    yy = offset.top + height / positionTop;
    return {
        x: xx,
        y: yy
    }
}

function drawArrowApproval(start, end, color, hasEndPoint, hasStartPoint) {
    // create a canvas to draw the arrow on
    let canvaser = $('#approval-canvas');
    let canvas = document.createElement('canvas');
    canvas.width = canvaser.find('#table-activity-approval-theme').innerWidth();
    canvas.height = canvaser.find('#table-activity-approval-theme').innerHeight();
    $(canvas).css('position', 'absolute');
    $(canvas).addClass('view-approval');
    $(canvas).css('pointer-events', 'none');
    $(canvas).css('top', canvaser.find('#table-activity-approval-theme').position().top);
    $(canvas).css('left', '1px');
    $(canvas).css('opacity', '0.85');
    canvaser.append(canvas);

    // get the drawing context
    let ctx = canvas.getContext('2d');
    ctx.fillStyle = color;
    ctx.strokeStyle = color;

    // draw line from start to end
    if (start.x === end.x) {
        end.x = end.x + 10;
    }
    if (!hasEndPoint) {
        end.y = start.y
    }
    ctx.beginPath();
    ctx.moveTo(start.x, start.y);
    ctx.lineTo(end.x, end.y);
    ctx.lineWidth = 2;
    ctx.stroke();
    // draw circle at beginning of line
    if (hasStartPoint) {
        ctx.beginPath();
        ctx.arc(start.x, start.y, 4, 0, Math.PI * 2, true);
        ctx.fill();
    }
    // draw pointer at end of line (needs rotation)
    if (hasEndPoint) {
        ctx.beginPath();
        let angle = Math.atan2(end.y - start.y, end.x - start.x);
        ctx.translate(end.x + 3, end.y);
        ctx.rotate(angle);
        ctx.moveTo(0, 0);
        ctx.lineTo(-10, -7);
        ctx.lineTo(-10, 7);
        ctx.lineTo(0, 0);
        ctx.fill();
    }

    // reset canvas context
    ctx.setTransform(1, 0, 0, 1, 0, 0);

    return canvas;
}

//data [table, startRow, startColumn, endRow, endColumn, color, positionTop, startPeriod, endPeriod, hasEndPoint, hasStartPoint];
function drawArrowOnTableApproval(data) {
    drawArrowApproval(
        //start
        getCellCenterApproval($(data[0]), data[1], data[2], data[6], data[7]),
        //end
        getCellCenterApproval($(data[0]), data[3], data[4], data[6], data[8]),
        //color
        data[5],
        //
        data[9], data[10]
    );
}

function resizehandleApproval(callback) {
    (function (callback) {
        window.addEventListener("resize", resizeThrottler, false);
        let resizeTimeout;

        function resizeThrottler() {
            if (!resizeTimeout) {
                resizeTimeout = setTimeout(function () {
                    resizeTimeout = null;
                    actualResizeHandlerApproval();
                }, 70);
            }
        }

        function actualResizeHandlerApproval() {
            // handle the resize event
            //canvas marking
            //let coors = callback();
            $('.view-approval').first().addClass('trash');
            //redraw
            drawArrowOnTableApproval(callback());
            //clear trash
            $('canvas').remove('.trash');
        }
    }(callback));

}

function dtpicker() {
    $.datetimepicker.setLocale('ja');
    let config = {
        mask: false,
        minYear: 2010,
        maxYear: 2100,
        format: 'Y/m/d H:i',
        timepicker: true,
        step: 5,
    };

    $('#date_timepicker_start').datetimepicker(config);
    $('#date_timepicker_end').datetimepicker(config);


    $('#date_timepicker_start').datetimepicker({
        onShow: function (ct) {
            this.setOptions({
                maxDate: $('#date_timepicker_end').val() ? moment($('#date_timepicker_end').val()).format('YYYY/MM/DD') : false,
            })
        },
        onSelectDate: function () {
            let startDateTime = new Date($('#date_timepicker_start').val());
            let startDate = startDateTime.getFullYear() + '/' + startDateTime.getMonth() + '/' + startDateTime.getDate();
            let endDateTime = new Date($('#date_timepicker_end').val());
            let endDate = endDateTime.getFullYear() + '/' + endDateTime.getMonth() + '/' + endDateTime.getDate();
            this.setOptions({
                minTime: endDate == startDate ? moment($('#date_timepicker_start').val()).format('HH:mm') : false
            })
        }
    });
    $('#date_timepicker_end').datetimepicker({
        onShow: function (ct) {
            this.setOptions({
                minDate: $('#date_timepicker_start').val() ? moment($('#date_timepicker_start').val()).format('YYYY/MM/DD') : false,
            })
        },
        onSelectDate: function () {
            let startDateTime = new Date($('#date_timepicker_start').val());
            let startDate = startDateTime.getFullYear() + '/' + startDateTime.getMonth() + '/' + startDateTime.getDate();
            let endDateTime = new Date($('#date_timepicker_end').val());
            let endDate = endDateTime.getFullYear() + '/' + endDateTime.getMonth() + '/' + endDateTime.getDate();
            this.setOptions({
                minTime: endDate == startDate ? moment($('#date_timepicker_start').val()).format('HH:mm') : false
            })
        }
    });

    $('#picker-start-icon').on('click', function () {
        $('#date_timepicker_start').focus();
    });
    $('#picker-end-icon').on('click', function () {
        $('#date_timepicker_end').focus();
    });
}


// Restricts input for the given target to the given inputFilter.
(function ($) {
    $.fn.inputFilter = function (inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            }
        });
    };
}(jQuery));
// setInputFilter($('input[type=number]'), function(value) {
//     return /^\d*$/.test(value);
// });
// Integer values (both positive and negative):
//  /^-?\d*$/.test(value)
// Integer values (positive only):
//  /^\d*$/.test(value)
// Integer values (positive and up to a particular limit):
//  /^\d*$/.test(value) && (value === "" || parseInt(value) <= 500)
// Floating point values (allowing both . and , as decimal separator):
//  /^-?\d*[.,]?\d*$/.test(value)
// Currency values (i.e. at most two decimal places):
//  /^-?\d*[.,]?\d{0,2}$/.test(value)
// Hexadecimal values:
//  /^[0-9a-f]*$/i.test(value)
