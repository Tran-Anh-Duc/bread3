$(document).ajaxStart(function() {
  $("#loading").show();
}).ajaxStop(function() {
  $("#loading").hide();
});

$( document ).ready(function() {

function TransactionReconciliation() {
	this.initData = {};
	this.objJson = {};
	this.current_page = 1;
	this.records_per_page = 15;
	const PAGINATION_RANGE = 5;

	this.init = function (_initData) {
		this.initData = _initData;
		let initData = Object.keys(_initData).map((key) => [Number(key), _initData[key]]);
		this.objJson = initData.sort(function (a, b) {
			  var aDate = a[0].updated_at;
			  var bDate = b[0].updated_at; 
			  return new Date(aDate) > new Date(bDate) ? 1 : -1;
			});
		console.log(this.objJson);
		this.createPanigation();
		this.changePage(1);
		$("#select-all").on("click", this.selectAll.bind(this));
	}

	this.selectAll = function (event) {
		$('.selected_item').prop('checked', event.target.checked);
		this.objJson.forEach(function(value, index) {
			value[1].selected = event.target.checked;
			
		});
	}

	this.selectItem = function (event) {
		let _number = $(event.target).attr("data-no");
		this.objJson[_number][1].selected = event.target.checked
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
	    for (var i = (page-1) * this.records_per_page; i < (page * this.records_per_page); i++) {
	    	if (this.objJson[i]) {
	    		listingTable.innerHTML += this.createEL(this.objJson[i][1], i);
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

	this.exportExcel = function () {
		let _rows = "";
		let count = 1;
		for (let i = 0; i < this.objJson.length; i++) {
			if (this.objJson[i][1].selected) {
				_rows += '<tr>' + this.createEL(this.objJson[i][1], count, true) + '</tr>';
				count++;
			}
		}

		let _tableExport = $("#clone-object").clone();
		_tableExport.find("#listingTable").html($.parseHTML(_rows));
		_tableExport.find('th:last-child, td:last-child').hide();
		_tableExport.table2excel({
		    exclude: ".no-export",
		    name: "Worksheet Name",
		    filename: "transactions.xls", // do include extension
		    preserveColors: false // set to true if you want background colors and font colors preserved
		});
	}

	this.printData = function () {
		let _rows = "";
		let count = 1;
		for (let i = 0; i < this.objJson.length; i++) {
			if (this.objJson[i][1].selected) {
				_rows += '<tr>' + this.createEL(this.objJson[i][1], count, true) + '</tr>';
				count++;
			}
		}

		let _tableExport = $("#clone-object").clone();
		_tableExport.find("#listingTable").html($.parseHTML(_rows));
		_tableExport.removeAttr('hidden');
		_tableExport.find('th:last-child, td:last-child').hide();
	    var win = window.open();
	    win.document.write(_tableExport.prop("outerHTML"));
	    win.print();
	    win.close()

	}

	this.createEL = function ($data, $no, $type = false) {
		let _el = $("#reconciliation-item").clone();
		_el.attr("id", "reconciliation-" + $data.id);
		_el.attr("data-id", $data.id);
		_el.find("[data-attr='selected_item']").val($data.id);
		if($type) {
			//export and print case
			_el.find("[data-attr='reconciliation_no']").text($no);
		} else {
			// nomal case
			_el.find("[data-attr='selected_item']").attr("data-no", $no);
		}
		
		if ($data.selected) {
			_el.find("[data-attr='selected_item']").attr('checked', true);
		} else {
			_el.find("[data-attr='selected_item']").attr('checked', false);
		}
		_el.find("[data-attr='code']").text($data.code);
		_el.find("[data-attr='pay_amount']").text(this.fomatNumber($data.pay_amount));
		_el.find("[data-attr='paid_amount']").text(this.fomatNumber($data.paid_amount));
		let _remaining_amount = $data.pay_amount - $data.paid_amount;
		if(_remaining_amount > 0) {
			_el.find("[data-attr='remaining_amount']").text(this.fomatNumber(_remaining_amount));
		} else {
			_el.find("[data-attr='remaining_amount']").text("0");
		}
		_el.find("[data-attr='created_at']").text(($data.created_at).substring(0, 10));
		_el.find("[data-attr='paid_date']").text($data.paid_date);
		_el.find("[data-attr='status_text']").text($data.status_text);
		_el.find("#details-show-info__id__").attr("data-id", $data.id);
		_el.find("#details-show-info__id__").attr("href", "/cpanel/momo/reconciliation/details/" + $data.id);
		return _el.html();
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
	}

	this.fomatNumber = function ($value) {
		return new Intl.NumberFormat().format($value);
	}
}

	var reconciliationObj = new TransactionReconciliation();
	var _token = $('[name="_token"]').val();

	function initPage (_itemList) {
		if (reconciliationObj) {
			reconciliationObj = null;
		}
		reconciliationObj = new TransactionReconciliation();
		console.log('initPage ', _itemList);
		
		reconciliationObj.init(_itemList);
		$("#btn_prev").off().on("click", function(){
			reconciliationObj.prevPage();
		});
		$("#btn_next").off().on("click", function(){
			reconciliationObj.nextPage();
		});

	    $('.pagination').off().on("click", ".page-number", function() {
	    	let _page = event.target;
	       	reconciliationObj.changePage($(_page).attr("data-page"));
	    });
	}

	initPage(reconciliations);

	var dp = $("#start-date, #end-date").datepicker( {
	    format: "yyyy-mm-dd",
	    autoclose: true
	});
    var dp = $("#select-time").datepicker( {
	    format: "yyyy-mm",
	    startView: "months", 
	    minViewMode: "months",
	    autoclose: true
	});
	dp.on('change', function (e) {
	   let _month = $("#select-time").val();
	    console.log(getListByMonthUrl);
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
	       			$("#totalPayAmount").text(data['data']['totalPayAmount']);
	       			$("#totalPaidAmount").text(data['data']['totalPaidAmount']);
	       			$("#remainingAmount").text(data['data']['remainingAmount']);
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
	$(".close").on('click', function(e) {
		$("#errorModal").hide();
	});


	$('#print-data').on('click',function(e){
		e.preventDefault();
		reconciliationObj.printData();
	})

	$('#export-excel').on('click', function(e) {
		e.preventDefault();
		reconciliationObj.exportExcel();
	})
	
	$('#reconciliation-data').on('click',function(e){
		e.preventDefault();
		$("#modal-confirm").show();
	})

	$("#cancel").on('click',function(e){
       e.preventDefault();
       $('#modal-confirm').hide();
    });

});
