
$(document).ready(function () {

	if($('input[name="shipping_method"][value="paczkomaty.paczkomaty"]').closest('label').find('.selected_paczkomat').length){
		$('input[name="shipping_method"][value="paczkomaty.paczkomaty"]').closest('label').find('.selected_paczkomat').remove();
	}

    $('select[name="province"]').change(function () {
		selectProvince($('select[name="province"]'))
    });

    $('select[name="citys"]').change(function () {
		selectCity($('select[name="citys"]'))
	});
	listValue('#province-list','block');
});

function listValue(type,val){
	setTimeout(function(){
		document.querySelector(type).style.display = val;
	},300);
	

}

function displayValue(val){
	document.querySelectorAll('li[data-filter]').forEach(function(item){
		item.style.display = val;
	})
}
function filterList(selector,elem){

	var search = elem.value.toLowerCase()
	console.log(search)
	if(search.length){
		displayValue('none');
		document.querySelectorAll(selector+' li[data-filter*="'+search+'"]').forEach(function(item){
			item.style.display = 'block';
		})
	}else{
		displayValue('block');
	}
	
}

function selectProvince(value){
	$.ajax({
		url: 'index.php?route=extension/shipping/paczkomaty/city',
		type: 'get',
		data: {province:value},
		dataType: 'json',
		beforeSend: function () {
			document.querySelector('[name="province"]').value = value;
			listValue('#province-list','none');
		},
		success: function (json) {
			if (json['error'] !== undefined) {
				document.getElementById('map').innerHTML = json['error'];
			} else {
				var citySelect = document.querySelector('#citys-list ul');
				citySelect.innerHTML = "";
				
				for (var i = 0; i < json['citys'].length; i++) {
					option = document.createElement('li');
					// option.value = json['citys'][i].id;
					option.innerHTML = json['citys'][i].city;
					option.setAttribute('data-filter', json['citys'][i].city.toLowerCase());
					option.onclick = function(e){
						selectCity(e.target.getAttribute('data-filter'))
					};
					citySelect.appendChild(option);
				}
				listValue('#citys-list','block');
			}
		}
	});
}

function selectCity(value){
	$.ajax({
		url: 'index.php?route=extension/shipping/paczkomaty/machine',
		type: 'get',
		data: {citys:value},
		dataType: 'json',
		beforeSend: function () {
			document.querySelector('[name="citys"]').value = value;
			listValue('#citys-list','none');
		},
		success: function (json) {
		if (json['error']) {
			document.getElementById('map').innerHTML = json['error'];
		} else {
			var mapa = null;
			mapa = initMap(json['machines'][0]);
			for (var i = 0; i < json['machines'].length; i++) {

			setMarkers(mapa, json['machines'][i])
			}
			
		}
		}
	});
}

var markers = [];
function initMap(paczkomat) {

    document.querySelector('#map').outerHTML = '<div id="map"></div>';


    var map = new L.map('map').setView([parseFloat(paczkomat['longitude']), parseFloat(paczkomat['latitude'])], 14);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
	maxZoom: 18,
	attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
		'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
		'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox.streets'
    }).addTo(map);

	//var popup = L.popup();

    return map;
}

function setMarkers(map, paczkomat) {

    var contentString = '<div class="tooltip-inpost"><h5><strong>Paczkomat ' + paczkomat['machine'] + '</strong></h5>' +
	    '<p class="capital">' + paczkomat['street'] + ' ' + paczkomat['building_number'] + '</p>' +
	    '<p class="capital">' + paczkomat['postcode'] + ' ' + $("#citys option:selected").text() + '</p>' +
	    '<a onclick="paczkomat(this)"  data-adress="' + paczkomat['street'] + '" data-nr="' + paczkomat['building_number'] + '" data-postcode="' + paczkomat['postcode'] + '" data-miasto="' + paczkomat['city'] + '" data-name="' + paczkomat['machine'] + '">wybierz</a></div>';

    var myIcon = L.icon({
	iconUrl: 'catalog/view/javascript/paczkomaty/parcel_locker.png',
	iconSize: [20, 30],
	iconAnchor: [20, 30],
	popupAnchor: [-20, -30],
    });
    var machine_name = paczkomat['machine'] + ' (' + paczkomat['street'] + ' ' + paczkomat['building_number'] + ')';

    var machine_location = [parseFloat(paczkomat['longitude']), parseFloat(paczkomat['latitude'])];

    markers[paczkomat['id']] = L.marker(machine_location, {icon: myIcon}).bindTooltip(machine_name, {offset: [-10, -20]}).openTooltip().addTo(map);

    markers[paczkomat['id']].on('click', function (e) {
	var popup = L.popup()
		.setLatLng(e.latlng)
		.setContent(contentString)
		.openOn(map);
    });
}

function paczkomat(data) {
    var d = document;
    var adres = 'NR paczkomatu (' + data.getAttribute('data-name') + '), ' + data.getAttribute('data-adress') + ' ' + data.getAttribute('data-nr') + ', ' + data.getAttribute('data-postcode') + ' ' + data.getAttribute('data-miasto');
    $.ajax({
		url: 'index.php?route=extension/shipping/paczkomaty/save',
		type: 'get',
		data: {'machine': adres},
		dataType: 'json',
		beforeSend: function () {
		},
		success: function (json) {
			if (json['error'] == 'true') {
				document.getElementById('map').innerHTML = 'Proszę wybrać paczkomat jeszcze raz.';
			} else {
				window.select_paczkomat = true;
				selectedPaczkomat = selectedPaczkomat || 'paczkomaty.paczkomaty';
				resetPaczkomatText('paczkomaty.paczkomaty');
				resetPaczkomatText('paczkomaty.paczkomaty1');
				resetPaczkomatText('paczkomaty.paczkomaty2');
				

				var text = document.querySelector('[data-quote="' + selectedPaczkomat + '"] span');
				if(text){
					text.innerHTML = "Paczkomaty - Wybierz paczkomat<br/>"+adres;
				}
				document.querySelector('.paczkomat-box-background').outerHTML = '';
			}
		}
    });
}

function resetPaczkomatText(selector){
	// 'paczkomaty.paczkomaty'
	var element = document.querySelector('[data-quote="' + selector + '"]');
	if(element){
		var span = document.querySelector('[data-quote="' + selector + '"] span');
		
		if(span){
			span.innerHTML = "Paczkomaty - Wybierz paczkomat - {cost} zł".replace('{cost}',element.getAttribute('data-cost'));
		}
		
	}
}

function renderModal(content) {
    $('#modal-shipping').remove();
    html = '<div id="modal-shipping" class="modal show">';
    html += '  <div class="modal-dialog">';
    html += '    <div class="modal-content">';
    html += '      <div class="modal-header">';
    html += '        <h4 class="modal-title">Wybierz paczkomat.</h4>';
    html += '      </div>';
    html += '      <div class="modal-body">';
    html += content;
    html += '      </div>';
    html += '      <div class="modal-footer">';
    html += '        <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>';
    html += '        <input type="button" value="Wybierz sposób wysyłki" id="button-shipping" data-loading-text="Ładowanie..." class="btn btn-primary" />';
    html += '      </div>';
    html += '    </div>';
    html += '  </div>';
    html += '</div> ';
    $('body').append(html);
}


