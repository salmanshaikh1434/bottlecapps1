// This sample uses the Places Autocomplete widget to:
// 1. Help the user select a place
// 2. Retrieve the address components associated with that place
// 3. Populate the form fields with those address components.
// This sample requires the Places library, Maps JavaScript API.
// Include the libraries=places parameter when you first load the API.
// For example: <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
let autocomplete;
let autocomplete2;
let address1Field;
let address2Field;
let address3Field;
let postalField;

var componentForm = {

	administrative_area_level_1: 'short_name'

};
function initAutocomplete() {
	address1Field = document.querySelector(".bn_address");

	// Create the autocomplete object, restricting the search predictions to
	// addresses in the US and Canada.
	autocomplete = new google.maps.places.Autocomplete(address1Field, {
		componentRestrictions: { country: ["us", "ca"] },
		fields: ["address_components", "geometry"],
		/*types: ["address", ""],*/
	});
	address1Field.focus();
	// When the user selects an address from the drop-down, populate the
	// address fields in the form.
	autocomplete.addListener("place_changed", fillInAddress);

	address3Field = document.querySelector(".bn_address2");

	// Create the autocomplete object, restricting the search predictions to
	// addresses in the US and Canada.
	autocomplete2 = new google.maps.places.Autocomplete(address3Field, {
		componentRestrictions: { country: ["us", "ca"] },
		fields: ["address_components", "geometry"],
		/*types: ["address", ""],*/
	});
	address3Field.focus();
	// When the user selects an address from the drop-down, populate the
	// address fields in the form.
	autocomplete2.addListener("place_changed", fillInAddress);
}

function fillInAddress() {
	// Get the place details from the autocomplete object.
	const place = autocomplete2.getPlace();
	for (var i = 0; i < place.address_components.length; i++) {
		var addressType = place.address_components[i].types[0];
		if (componentForm[addressType]) {
			$val = place.address_components[i][componentForm[addressType]];
			//alert($val);

		}
	}
	// alert($val);
	$("#administrative_area_level_1").val($val);

	let address1 = "";
	let postcode = "";
	let lat = place.geometry.location.lat();
	let lng = place.geometry.location.lng();
	$("#lat").val(lat);
	$("#lng").val(lng);
	// After filling the form with address components from the Autocomplete
	// prediction, set cursor focus on the second address line to encourage
	// entry of subpremise information such as apartment, unit, or floor number.
	address2Field.focus();
}

$(document).on("click", ".buy-now-btn", function () {
	$(".loader").show();
	var lat = $("#lat").val();
	var lng = $("#lng").val();
	var stat = $("#administrative_area_level_1").val();
	var key = $("#search_input").val();

	if (lat && lng) {
		$(".loader").show();

		var post_data = {
			"ProductId": prod_id,
			"Latitude": lat,
			"Longitude": lng,
			"RadiusinMiles": 100,
			//"Keyword": bn_upc,
			"State": stat
		};

		var http = new XMLHttpRequest();
		var url = 'https://liquorapps.com/BCAPI/api/Product/GetStoreListByProduct';
		// var url = 'https://stagingtest.liquorapps.com/bcapi/api/product/GetStoreListByProduct';
		http.open('POST', url, true);

		// Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/json;charset=UTF-8');
		http.setRequestHeader('ClientSecretKey', 'JYIXHKQMRCPT1S406B2NWFE38');

		http.onreadystatechange = function () {
			// Call a function when the state changes
			if (http.readyState == 4 && http.status == 200) {
				var data = $.parseJSON(http.responseText);

				if (data.StoreCount == 0) {
					var buynow_data = "Keyword=" + bn_upc + "&Key=" + key;

					// Creating Our XMLHttpRequest object
					var xhr = new XMLHttpRequest();

					// Making our connection
					var url1 = 'https://sipnbourbon.com/wp-json/timeline/v2/ajaxaddlocationbuynow';
					xhr.open("POST", url1, true);
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

					// Function executed after request is successful
					xhr.onreadystatechange = function () {
						if (this.readyState == 4 && this.status == 200) {
							console.log(this.responseText);
						}
					};
					// Sending our request
					xhr.send(buynow_data);
				}

				var store_html = '';
				store_html += '<h3 class="storeresults-heading">' + data.StoreCount + ' Results</h3>';
				store_html += '<div class="storelocationstop" style="height: 0px;">';

				$.each(data.ListStore, function (k, store) {
					if (store.BottleRoverLink) {
						store_html += '<div class="store-locations-list ourstores">';
						store_html += '<div class="store-locations-list-sub">';
						store_html += '<div class="store-locations-address">';
						store_html += '<span class="location-address1">' + store.StoreName + '</span>';
						store_html += '<span class="store-location-distance">' + store.Distance_Display + '</span>';
						store_html += '<span class="location-address2">' + store.Address1 + '</span>';
						store_html += '<span class="location-address3">' + store.City + ' ' + store.State + ' ' + store.Zip + '</span>';
						store_html += '<span class="store-contact">' + store.StoreContactNo + '</span>';
						store_html += '</div>';

						store_html += '<div class="store-location-icons">';
						store_html += '<div class="store-location-icons-list">';

						store_html += '<div class="imgclass"></div>';
						if (!store.IsAllocatedProduct) {
							store_html += '<a target="_blank" href="' + store.BottleRoverLink + '?fromsipn=Y">';
							store_html += '<span class="store-bottlerover">Buy Now</span>';
							store_html += '</a>';
						} else {
							store_html += '<a href="javascript:void(0);">';
							store_html += '<span class="store-bottlerover">InStore Only</span>';
							store_html += '</a>';
						}
						store_html += '<div class="results-price text-right">$' + store.StoreProductPrice + '</div>';
						store_html += '<div class="sampleclass">';
						store_html += '<ul>';

						if (store.IsPickupStatus && !store.IsAllocatedProduct) {
							store_html += '<a target="_blank" href="' + store.BottleRoverLink + '?fromsipn=Y">';
							store_html += '<li><img class="icon-store" src="/wp-content/themes/SIPN/assets/images/store.png"></li>';
							store_html += '</a>';
						}
						if (store.IsDeliveryStatus && !store.IsAllocatedProduct) {
							store_html += '<a target="_blank" href="' + store.BottleRoverLink + '?fromsipn=Y">';
							store_html += '<li><img class="icon-store truck" src="/wp-content/themes/SIPN/assets/images/truck1.png"></li>';
							store_html += '</a>';
						}


						store_html += '</ul>';
						store_html += '</div>';
						store_html += '</div>';
						store_html += '</div>';
						store_html += '</div>';
						store_html += '</div>';
					}
				});

				store_html += '</div>';
				$(".stores-sub-div").html(store_html);
				$(".loader").hide();
			} else {
				$(".loader").show();
			}
		};
		http.send(JSON.stringify(post_data));
	} else {
		$(".loader").hide();
	}
});
