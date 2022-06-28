$(document).ajaxStart(function() {
  $("#loading").show();
  var loadingHeight = window.screen.height;
  $("#loading, .right-col iframe").css('height', loadingHeight);
}).ajaxStop(function() {
  $("#loading").hide();
});

$( document ).ready(function() {
    function DataList() {
        this.initData = {};
        this.objJson = {};
        this.current_page = 1;
        this.records_per_page = 45;
        this.selectedIds = [];
        const PAGINATION_RANGE = 5;

        this.init = function (_initData) {
            this.initData = _initData;
            let initData = Object.keys(_initData).map((key) => [Number(key), _initData[key]]);
            this.objJson = initData.sort(function (a, b) {
                  var aDate = a[0].updated_at;
                  var bDate = b[0].updated_at; 
                  return new Date(aDate) > new Date(bDate) ? 1 : -1;
                });
            this.createPanigation();
            this.changePage(1);
            $("#select-all").off().on("click", this.selectAll.bind(this));
            $('#export-excel').on('click', this.exportExcel.bind(this));
            $('#print-data').on('click', this.printData.bind(this));
        }

        this.selectAll = function (event) {
            var selectedIds = [];
            $('.selected_item').prop('checked', event.target.checked);
            this.objJson.forEach(function(value, index) {
                value[1].selected = event.target.checked;

                //push selected item into array
                if (event.target.checked) {
                    selectedIds.push(value[1].id);
                }
            });
            this.selectedIds = selectedIds;
        }

        this.selectItem = function (event) {
            let _number = $(event.target).attr("data-no");
            this.objJson[_number][1].selected = event.target.checked

            //push selected item into array
            if (event.target.checked && this.selectedIds.indexOf(this.objJson[_number][1].id) === -1) {
                this.selectedIds.push(this.objJson[_number][1].id);
            } else {
                //remove unselected item
                let index = this.selectedIds.indexOf(this.objJson[_number][1].id);
                if (index > -1) {
                    this.selectedIds.splice(index, 1);
                }
            }
        }

        this.numPages = function () {
            return Math.ceil(this.objJson.length / this.records_per_page);
        }

        this.prevPage = function () {
            if (this.current_page > 1) {
                this.current_page--;
                this.changePage(this.current_page);
            }
        }

        this.nextPage = function () {
            if (this.current_page < this.numPages()) {
                this.current_page++;
                this.changePage(this.current_page);
            }
        }

        this.changePageAction = function (event) {
            var page = $(event.target).attr("data-page");
            this.changePage(page);
        }
        this.changePage = function (page) {
            let prevBtn = document.getElementById("btn_prev");
            let nextBtn = document.getElementById("btn_next");
            let listingTable = document.getElementById("listingTable");
            let pageSpan = document.getElementById("page");
            // Validate page
            if (page < 1) page = 1;
            if (page > this.numPages()) page = this.numPages();
            this.current_page = page;
            listingTable.innerHTML = "";
            for (let i = (page-1) * this.records_per_page; i < (page * this.records_per_page); i++) {
                if (this.objJson[i]) {
                    listingTable.innerHTML += this.createEL(this.objJson[i][1], i + 1, true);
                }
            }
            pageSpan.innerHTML = page + '/' + this.numPages();

            if (page == 1) {
                prevBtn.classList.add("disabled");
            } else {
                prevBtn.classList.remove("disabled");
            }

            if (page == this.numPages()) {
                nextBtn.classList.add("disabled");
            } else {
                nextBtn.classList.remove("disabled");
            }
            $(".page-number.active").removeClass('active');
            $("[data-page=" + page + "]").parent().addClass('active');
            $(".selected_item").on("click", this.selectItem.bind(this));

            //show pagination range
            if (this.numPages() > PAGINATION_RANGE*2) {
                let minPage = 0;
                let maxPage = 0;
                if ((this.numPages() - Number(page)) < Number(PAGINATION_RANGE)) {
                    minPage = Number(page) - Number(PAGINATION_RANGE)*2 + (this.numPages() - Number(page));
                } else {
                    minPage = Number(page) - Number(PAGINATION_RANGE);
                    if (minPage < 1) minPage = 1;
                }

                if ((Number(page) - Number(minPage)) < Number(PAGINATION_RANGE)) {
                    maxPage = Number(page) + Number(PAGINATION_RANGE)*2 - (Number(page) - Number(minPage));
                } else {
                    maxPage = Number(page) + Number(PAGINATION_RANGE);
                    if (maxPage > this.numPages()) maxPage = this.numPages();
                }
                $(".page-number").addClass('hide-item');
                for (let i = minPage; i <= maxPage; i++) {
                    $("[data-page=" + i + "]").parent().removeClass('hide-item');
                    $("[data-page=" + i + "]").text($("[data-page=" + i + "]").attr("data-page"));
                }
                $("[data-page=" + Number(minPage - 1) + "]").parent().removeClass('hide-item');
                $("[data-page=" + Number(minPage - 1) + "]").text("...");
                $("[data-page=" + Number(maxPage + 1) + "]").parent().removeClass('hide-item');
                $("[data-page=" + Number(maxPage + 1) + "]").text("...");
            }
            
        }

        this.numPages = function () {
            return Math.ceil(this.objJson.length / this.records_per_page);
        }

        this.createPanigation = function () {
            $("li.page-number").remove();
            for (let i = 1; i <= this.numPages(); i++) {
                if (i == 1) {
                    $("<li class='page-item page-number active'><a class='page-link' data-page='" + i + "' href='javascript:void(0);'>" + i + "</a></li>")
                .insertBefore("#btn_next");
                } else {
                    $("<li class='page-item page-number'><a class='page-link' data-page='" + i + "' href='javascript:void(0);'>" + i + "</a></li>")
                    .insertBefore("#btn_next");
                }
            }
            $("#btn_prev").off().on("click", this.prevPage.bind(this));
            $("#btn_next").off().on("click", this.nextPage.bind(this));
            $('.pagination').off().on("click", ".page-number", this.changePageAction.bind(this));
        }

        this.createEL = function ($data, $no, $type = false, $tableId = "clone-object") {
            let _el = $("#" + $tableId).find("#clone-item").clone();
            let _columns = _el.find("td");
            $.each(_columns, function( index, value ) {
                let _property = $(_columns[index]).attr("data-attr");
                let _value = $data[_property];
                if ($(_columns[index]).attr("format-number")) {
                    _numberFormated = fomatNumber(_value);
                    $(_columns[index]).text(_numberFormated);
                } else {
                    $(_columns[index]).text(_value);
                }
                
            });

            if($type) {
                //export and print case
                _el.find("#transaction_no").text($no);
            } else {
                // nomal case
                _el.find("#selected_item").attr("data-no", $no);
            }

            _el.find("#details-show-info__id__").attr("data-id", $data.id);
            _el.find("#details-show-info__id__").attr("href", "transaction/" + $data.id);
            
            if ($data.selected) {
                _el.find("#selected_item").attr('checked', true);
            } else {
                _el.find("#selected_item").attr('checked', false);
            }
            return _el.html();
        }

        this.exportExcel = function (event) {
            event.preventDefault();
            let _filename = $(event.target).attr("file-name");
            let _tableClone = $(event.target).attr("clone-table");
            let _rows = "";
            let count = 1;
            for (let i = 0; i < this.objJson.length; i++) {
                if (this.objJson[i][1].selected) {
                    _rows += '<tr>' + this.createEL(this.objJson[i][1], count, true, _tableClone) + '</tr>';
                    count++;
                }
            }
            
            let _tableExport = $("#" + _tableClone).clone();
            _tableExport.find('th:last-child').remove();
            _tableExport.find("#table-rows").html($.parseHTML(_rows));
            _tableExport.table2excel({
                exclude: ".no-export",
                name: "Worksheet Name",
                filename: _filename + ".xls", // do include extension
                preserveColors: false // set to true if you want background colors and font colors preserved
            });
        }

        this.printData = function (event) {
            event.preventDefault();
            let _tableClone = $(event.target).attr("clone-table");
            let _rows = "";
            let count = 1;
            for (let i = 0; i < this.objJson.length; i++) {
                if (this.objJson[i][1].selected) {
                    _rows += '<tr>' + this.createEL(this.objJson[i][1], count, true) + '</tr>';
                    count++;
                }
            }

            let _tableExport = $("#" + _tableClone).clone();
            _tableExport.find("#table-rows").html($.parseHTML(_rows));
            _tableExport.removeAttr('hidden');
            _tableExport.find('th:last-child, td:last-child').hide();
            var win = window.open();
            win.document.write(_tableExport.prop("outerHTML"));
            win.print();
            win.close()

        }

        fomatNumber = function ($value) {
            return new Intl.NumberFormat().format($value);
        }
    };


    //create object
    var transactionObj = new DataList();
    var _token = $('[name="_token"]').val();
    function initPage (_itemList) {
        if (transactionObj) {
            transactionObj = null;
        }
        transactionObj = new DataList();
        transactionObj.init(_itemList);

    }
    initPage(transactions);

    var selectMonth = $("#select-time").datepicker( {
        format: "yyyy-mm",
        startView: "months", 
        minViewMode: "months",
        autoclose: true
    });

    selectMonth.on('change', function (e) {
       let _month = $("#select-time").val();
        let data = {
            time : _month,
            _token: _token
        };
        $.ajax({
            type: "POST",
            url: getListByMonthUrl,
            data: data,
            success: function(data) {
                console.log(data);
                if (data['status'] == 200) {
                    console.log(data['data']);
                    initPage(data['data']['data']);
                    $("#total_transaction").text(data['data']['totalTransaction']);
                    $("#total_paid_amount").text(data['data']['totalAmount']);
                } else {
                    $("#errorModal").find(".msg_error").text(data['message']);
                    $("#errorModal").show();
                }
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $("#errorModal").show();
            },
         });
    });

    //search form
    $('#submit-data').on('click', function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        $("#submit-data").prop('disabled', true);

        var form = $("#search-form");
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data) {
                console.log(data);
                if (data['status'] == 200) {
                    console.log(data['data']);
                    initPage(data['data']['data']);
                    $("#total_transaction").text(data['data']['totalTransaction']);
                    $("#total_paid_amount").text(data['data']['totalAmount']);
                } else {
                    
                    $("#errorModal").find(".msg_error").text(data['message']);
                    $("#errorModal").show();
                }
                $("#submit-data").prop('disabled', false);
                $("#fillter-content").hide();

            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $("#submit-data").prop('disabled', false);
                $("#errorModal").find(".msg_error").text(msg);
                $("#errorModal").show();
            },
         });
    });

});