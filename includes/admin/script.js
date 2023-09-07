// admin-scripts.js
// document.addEventListener("DOMContentLoaded", function() {
//   const showTableCheckbox = document.getElementById(
//     "enable_events_extension"
//   );
//   const configurationTable = document.querySelector(".form-table");

//   showTableCheckbox.addEventListener("change", function() {
//     if (showTableCheckbox.checked) {
//       configurationTable.style.display = "table";
//     } else {
//       configurationTable.style.display = "none";
//     }
//   });
// });

function setupDisplayRequiredCheckboxes() {
  // Add an event listener to the display checkboxes
  jQuery('input[id^="display_"]').change(function() {
    var fieldName = jQuery(this)
      .attr("id")
      .replace("display_", "");
    var requiredCheckbox = jQuery("#required_" + fieldName);

    if (!jQuery(this).is(":checked")) {
      requiredCheckbox.prop("checked", false); // Uncheck the required checkbox
    }

    // Enable/disable the required checkbox based on the display checkbox state
    requiredCheckbox.prop("disabled", !jQuery(this).is(":checked"));
  });
}
