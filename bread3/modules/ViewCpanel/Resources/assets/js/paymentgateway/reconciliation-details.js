$(document).ajaxStart(function() {
  $("#loading").show();
}).ajaxStop(function() {
  $("#loading").hide();
});

$( document ).ready(function() {

function Transaction() {
	this.initData = {};
	this.objJson = {};
	this.current_page = 1;
	this.records_per_page = 15;
	const PAGINATION_RANGE = 5;
    const TRANSACTION_PENDING = 1;
    const TRANSACTION_SUCCESS = 2;
    const CONTRACT_STATUS_PENDING = 1; //waiting for progressing
    const CONTRACT_STATUS_SUCCESS = 2; //paid the debt
    const PAYMENT_OPTION_TERM          = 1; // payment term
    const PAYMENT_OPTION_FINAL         = 2; // final settlement
    const PAYMENT_OPTION_INVESTOR      = 3; // Investor transaction request
    const TRANSACTION_UNCONFIRMED = 1;
    const TRANSACTION_CONFIRMED = 2;

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

	this.reconciliation = function () {
		let _selectedIds = [];
		let _selected = false;
		for (let i = 0; i < this.objJson.length; i++) {
			if (this.objJson[i][1].selected ) {
				_selected = true;
			}
			if (
				this.objJson[i][1].selected 
				&& this.objJson[i][1].confirmed == TRANSACTION_UNCONFIRMED 
				&& this.objJson[i][1].status == TRANSACTION_SUCCESS
			) {
				_selectedIds.push(this.objJson[i][1].id);
			}
		}
		console.log(_selectedIds);
		if (_selectedIds.length > 0) {
			$.ajax({
	           	type: "POST",
	           	url: createReconciliationUrl,
	           	data: {
	           		selectedIds: _selectedIds,
	           		_token: _token
	           	},
	           	success: function(data) {
		           	console.log(data);
		           	if (data['status'] == 200) {
		       			console.log(data['data']);
						$("#successModal").find(".msg_success").text(data['message']);
						$("#successModal").show();
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
			        $("#errorModal").find(".msg_error").text(msg);
		           	$("#errorModal").show();
			    },
	         });
		} else {
			if (_selected) {
				$("#errorModal").find(".msg_error").text("Giao dịch chọn đã được đối soát trước đó hoặc chưa được thanh toán");
			} else {
				$("#errorModal").find(".msg_error").text("Chưa có giao dịch nào được chọn");
			}
		    $("#errorModal").show();
		}
		
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
		let _el = $("#transaction-item").clone();
		_el.attr("id", "transaction-" + $data.id);
		_el.attr("data-id", $data.id);
		_el.find("[data-attr='selected_item']").val($data.id);
		if($type) {
			//export and print case
			_el.find("[data-attr='transaction_no']").text($no);
		} else {
			// nomal case
			_el.find("[data-attr='selected_item']").attr("data-no", $no);
		}
		
		if ($data.selected) {
			_el.find("[data-attr='selected_item']").attr('checked', true);
		} else {
			_el.find("[data-attr='selected_item']").attr('checked', false);
		}
		_el.find("[data-attr='transactionId']").text($data.transactionId);
		_el.find("[data-attr='paid_amount']").text(this.fomatNumber($data.paid_amount));
		_el.find("[data-attr='name']").text($data.name);
		_el.find("[data-attr='contract_code_disbursement']").text($data.contract_code_disbursement);
		_el.find("[data-attr='paid_date']").text($data.paid_date);
		_el.find("[data-attr='payment_option']").text($data.payment_option);
		if ($data.payment_option == PAYMENT_OPTION_TERM) {
			_el.find("[data-attr='payment_option']").text("Thanh toán kỳ");
		} else if ($data.payment_option == PAYMENT_OPTION_FINAL) {
			_el.find("[data-attr='payment_option']").text("Tất toán");
		} else if ($data.payment_option == PAYMENT_OPTION_INVESTOR) {
			_el.find("[data-attr='payment_option']").text("NĐT");
		} else {
			_el.find("[data-attr='payment_option']").text("");
		}

		_el.find("[data-attr='transaction_fee']").text(this.fomatNumber($data.transaction_fee));
		if (
			$data.payment_option == PAYMENT_OPTION_TERM
			|| $data.payment_option == PAYMENT_OPTION_FINAL
		) {
			if ($data.contract_status == CONTRACT_STATUS_PENDING) {
				_el.find("[data-attr='contract_status']").text("Đang xử lý");
			} else if ($data.contract_status == CONTRACT_STATUS_SUCCESS) {
				_el.find("[data-attr='contract_status']").text("Đã gạch nợ");
			} else {
				_el.find("[data-attr='contract_status']").text("Thất bại");
			}
		} else {
			_el.find("[data-attr='contract_status']").text("");
		}
		if ($data.status == TRANSACTION_PENDING) {
			_el.find("[data-attr='status']").text("Chưa thanh toán");
		} else if ($data.status == TRANSACTION_SUCCESS) {
			_el.find("[data-attr='status']").text("Đã thanh toán");
		} else {
			_el.find("[data-attr='status']").text("Unknown");
		}
		if ($data.confirmed == TRANSACTION_UNCONFIRMED) {
			_el.find("[data-attr='confirmed']").text("Chưa đối soát");
		} else if ($data.confirmed == TRANSACTION_CONFIRMED) {
			_el.find("[data-attr='confirmed']").text("Đã đối soát");
		} else {
			_el.find("[data-attr='confirmed']").text("Unknown");
		}
		_el.find("#details-show-info__id__").attr("data-id", $data.id);
		_el.find("#details-show-info__id__").attr("href", "/cpanel/momo/transaction/" + $data.id);
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

	var transactionObj = new Transaction();
	var _token = $('[name="_token"]').val();

	function initPage (_itemList) {
		if (transactionObj) {
			transactionObj = null;
		}
		transactionObj = new Transaction();
		console.log('initPage ', _itemList);
		
		transactionObj.init(_itemList);
		$("#btn_prev").click(function(){
			transactionObj.prevPage();
		});
		$("#btn_next").click(function(){
			transactionObj.nextPage();
		});

	    $('.pagination').on("click", ".page-number", function() {
	       	let _page = event.target;
	       	transactionObj.changePage($(_page).attr("data-page"));
	    });
	}

	initPage(transactions);

	$(".close").on('click', function(e) {
		$("#errorModal").hide();
	});

	$('#cancel').on('click', function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		let data = {
			id : reconciliationId,
			_token: _token
		};
	    $.ajax({
           	type: "POST",
           	url: deleteUrl,
           	data: data,
           	success: function(data) {
	           	console.log(data);
	           	if (data['status'] == 200) {
	           		$("#action-group").html('<button style="color: #000000;" type="button" class="btn" disabled="disabled">Đã Huỷ</button>');
	           		$("#successModal").find(".msg_error").text(data['message']);
	           		$("#successModal").show();
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
		        $("#errorModal").find(".msg_error").text(msg);
	           	$("#errorModal").show();
		    },
         });
	});

	$('#send-email').on('click', function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		let data = {
			id : reconciliationId,
			_token: _token
		};
	    $.ajax({
           	type: "POST",
           	url: sendEmail,
           	data: data,
           	success: function(data) {
	           	console.log(data);
	           	if (data['status'] == 200) {
	           		$("#action-group").html('<button style="color: #000000;" type="button" class="btn" disabled="disabled">Đã Gửi Email</button>');
	           		$("#successModal").find(".msg_success").text(data['message']);
	           		$("#successModal").show();
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
		        $("#errorModal").find(".msg_error").text(msg);
	           	$("#errorModal").show();
		    },
         });
	});

	$('#print-data').on('click',function(e){
		e.preventDefault();
		transactionObj.printData();
	})

	$('#export-excel').on('click', function(e) {
		e.preventDefault();
		transactionObj.exportExcel();
	})
	
	$('#reconciliation-data').on('click',function(e){
		e.preventDefault();
		$("#modal-confirm").show();
	})

	$("#cancel").on('click',function(e){
       e.preventDefault();
       $('#modal-confirm').hide();
    });

    $("#create-reconciliation").on('click',function(e){
       e.preventDefault();
       $('#modal-confirm').hide();
       transactionObj.reconciliation();
    });
});
