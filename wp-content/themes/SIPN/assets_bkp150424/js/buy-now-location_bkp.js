// This sample uses the Places Autocomplete widget to:
// 1. Help the user select a place
// 2. Retrieve the address components associated with that place
// 3. Populate the form fields with those address components.
// This sample requires the Places library, Maps JavaScript API.
// Include the libraries=places parameter when you first load the API.
// For example: <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
let autocomplete;
let address1Field;
let address2Field;
let postalField;

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
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  const place = autocomplete.getPlace();
  let address1 = "";
  let postcode = "";
  let lat = place.geometry.location.lat();
  let lng = place.geometry.location.lng();
  $("#lat").val(lat);
  $("#lng").val(lng);
  // After filling the form with address components from the Autocomplete
  // prediction, set cursor focus on the second address line to encourage
  // entry of subpremise information such as apartment, unit, or floor number.
  address1Field.focus();
}

$(".buy-now-btn").on("click", function(){
	$(".loader").show();
	var lat = $("#lat").val();
    var lng = $("#lng").val();
	
	if(lat && lng){
		var post_data = {
		  "ProductId": "",
		  "Latitude": lat,
		  "Longitude": lng,
		  "RadiusinMiles": 100,
		  "Keyword": bn_upc
		};
		
		var http = new XMLHttpRequest();
		var url = 'https://liquorapps.com/BCAPI/api/Product/GetStoreListByProduct';
		//var url = 'https://stagingtest.liquorapps.com/bcapi/api/product/GetStoreListByProduct';
		http.open('POST', url, true);

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/json;charset=UTF-8');
		http.setRequestHeader('ClientSecretKey', 'JYIXHKQMRCPT1S406B2NWFE38');

		http.onreadystatechange = function() {//Call a function when the state changes.
			if(http.readyState == 4 && http.status == 200) {
				var data = $.parseJSON(http.responseText);
				//console.log(data.ListStore);
				var store_html = '';
				store_html += '<h3 class="storeresults-heading">'+data.StoreCount+' Results</h3><div class="storelocationstop" style="height: 0px;">';
				$.each(data.ListStore, function(k, store) {
					/// do stuff
					//console.log(store.StoreName+"<br>\n");
					if(store.BottleRoverLink){
						store_html += '<div class="store-locations-list ourstores"><div class="store-locations-list-sub"><div class="store-locations-address"><span class="location-address1">'+store.StoreName+'</span><span class="store-location-distance">'+store.Distance_Display+'</span><span class="location-address2">'+store.Address1+'</span><span class="location-address3">'+store.City+' '+store.State+' '+store.Zip+'</span><span class="store-contact">'+store.StoreContactNo+'</span></div><div class="store-location-icons"><div class="store-location-icons-list"><a target="_blank" href="'+store.BottleRoverLink+'"><div class="imgclass"><!--<img class="" src="https://www.bransoncognac.com/wp-content/themes/backend-theme/assets/techmatic/images/br-logo.png">--></div><span class="store-bottlerover">Buy Now</span><div class="sampleclass"><img class="icon-store" src="/wp-content/themes/SIPN/assets/images/store.png"></div></a></div></div></div></div>';
					}
				});
				store_html += '</div>';
				$(".stores-sub-div").html(store_html);
				$(".loader").hide();
			}
		}
		http.send(JSON.stringify(post_data));    
    }
	$(".loader").hide();
});