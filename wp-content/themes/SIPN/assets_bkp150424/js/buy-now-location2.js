// This sample uses the Places Autocomplete widget to:
// 1. Help the user select a place
// 2. Retrieve the address components associated with that place
// 3. Populate the form fields with those address components.
// This sample requires the Places library, Maps JavaScript API.
// Include the libraries=places parameter when you first load the API.
// For example: <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
let autocomplete2;
let address34Field;


var componentForm1 = {
  
  administrative_area_level_1: 'short_name'
  
};


function fillInAddress2() {
  // Get the place details from the autocomplete object.

  const place = autocomplete2.getPlace();
    for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm1[addressType]) {
      $val = place.address_components[i][componentForm1[addressType]];
      //alert($val);
     
    }
  }
 // alert($val);
  $("#administrative_area_level_1").val($val);
 
 

  // After filling the form with address components from the Autocomplete
  // prediction, set cursor focus on the second address line to encourage
  // entry of subpremise information such as apartment, unit, or floor number.
  address34Field.focus();
}

