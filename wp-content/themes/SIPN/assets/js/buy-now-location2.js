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
let address3Field;
let postalField;

var componentForm = {
  
  administrative_area_level_1: 'short_name'
  
};
function initAutocomplete() {
  address1Field = document.querySelector(".bn_address2");
 
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

    //address3Field = document.querySelector(".bn_address2");
 
  // Create the autocomplete object, restricting the search predictions to
  // addresses in the US and Canada.
  autocomplete2 = new google.maps.places.Autocomplete(address3Field, {
    componentRestrictions: { country: ["us", "ca"] },
    fields: ["address_components", "geometry"],
    /*types: ["address", ""],*/
  });
  //address3Field.focus();
  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete2.addListener("place_changed", fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
    const place = autocomplete.getPlace();
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
  address1Field.focus();
}
