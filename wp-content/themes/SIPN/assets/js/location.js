function loadGoogleMapsAPI(callback) {
  const script = document.createElement('script');
  script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk&callback=initAutocomplete&libraries=places&v=weekly";
  script.async = true;
  script.defer = true;
  script.onload = callback; // Once the script is loaded, call the callback
  document.head.appendChild(script);
}

// Initialize the autocomplete only after the API is loaded
loadGoogleMapsAPI(initAutocompleteprofile);

// Your autocomplete and address filling functions
let autocomplete1;
let address1Field1;
let address2Field1;
let postalField1;

function initAutocompleteprofile() {
  address1Field1 = document.querySelector("#ship-address");
  address2Field1 = document.querySelector("#address2");
  postalField1 = document.querySelector("#postcode");

  autocomplete1 = new google.maps.places.Autocomplete(address1Field1, {
    componentRestrictions: { country: ["us", "ca"] },
    fields: ["address_components", "geometry"],
    types: ["address"],
  });

  autocomplete1.addListener("place_changed", fillInAddressprofile);
}

function fillInAddressprofile() {
  const place = autocomplete1.getPlace();
  let address1 = "";
  let postcode = "";

  for (const component of place.address_components) {
    const componentType = component.types[0];

    switch (componentType) {
      case "street_number":
        address1 = `${component.long_name} ${address1}`;
        break;
      case "route":
        address1 += component.short_name;
        break;
      case "postal_code":
        postcode = `${component.long_name}${postcode}`;
        break;
      case "postal_code_suffix":
        postcode = `${postcode}-${component.long_name}`;
        break;
      case "locality":
        document.querySelector("#locality").value = component.long_name;
        break;
      case "administrative_area_level_1":
        document.querySelector("#state").value = component.short_name;
        break;
    }
  }

  address1Field1.value = address1;
  postalField1.value = postcode;

  address1Field1.focus();
}
