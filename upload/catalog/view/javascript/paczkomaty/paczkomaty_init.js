var selectedPaczkomat = 'paczkomaty.paczkomaty';
$(document).ready(function(){
	$(document).on('click', '[name="shipping_method"]',function(){

		if ($(this).val() == "paczkomaty.paczkomaty" ||
			$(this).val() == "paczkomaty.paczkomaty1" ||
			$(this).val() == "paczkomaty.paczkomaty2") {
				selectedPaczkomat = $(this).val();
			getProvince();
		}
	});
});

function getProvince() {
    $.ajax({
	url: 'index.php?route=extension/shipping/paczkomaty',
	type: 'post',
	data: {},
	dataType: 'json',
	beforeSend: function () {

	},
	success: function (json) {
	    $('body').append(json['view']);
	},
	error: function (xhr, ajaxOptions, thrownError) {
	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
    });
}

