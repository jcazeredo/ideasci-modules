// This script is loaded both on the frontend page and in the Visual Builder.

function sendRequest() {
  var inputField = $("input.ism_ajax_search_field");
  var searchTerm = inputField.val();

  jQuery.ajax({
    url: IdeasciModulesFrontendData.ajaxurl,
    type: "post",
    data: {
      action: "ajax_search_callback",
      search_term: searchTerm,
      post_type: inputField.data("search-post-type"),
      display_fields: inputField.data("display-fields"),
      number_of_results: inputField.data("number-of-results"),
      no_result_text: inputField.data("no-result-text"),
      order_by: inputField.data("orderby"),
      order: inputField.data("order"),
      date_format: inputField.data("date-format"),
    },
    success: function(response) {
      jQuery(".ism_ajax_search_results_wrap").html(response);
    },
    error: function(xhr, status, error) {
      console.log(xhr.responseText);
    },
  });
}

jQuery(document).ready(function($) {
  // It should initialize with items
  sendRequest();

  // Search on keyup
  jQuery("input.ism_ajax_search_field").keyup(function() {
    sendRequest();
  });
});

// Events
function registrationFormValidation() {
  jQuery(document).ready(function($) {
    // Function to validate email format
    function isValidEmail(email) {
      var emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
      return emailRegex.test(email);
    }

    // Function to check if all required fields are filled out
    function areRequiredFieldsFilled() {
      var requiredFields = [
        "username",
        "email",
        "confirm_email",
        "password",
        "first_name",
        "last_name",
        "affiliation",
        "city",
        "country",
      ];

      for (var i = 0; i < requiredFields.length; i++) {
        var fieldId = requiredFields[i];
        var fieldValue = $("#" + fieldId)
          .val()
          .trim();

        if (fieldValue === "") {
          return false;
        }
      }

      return true;
    }

    // Function to validate phone number format (custom validation can be added)
    function isValidPhoneNumber(phoneNumber) {
      // Example: var phoneRegex = /^[0-9]{10}$/;
      // return phoneRegex.test(phoneNumber);
      return true; // For now, assuming it's valid
    }

    // Function to display a validation message in the form message div
    function displayValidationMessage(message) {
      $(".ism-events-form-message").html(
        '<p class="error-message">' + message + "</p>"
      );
    }

    // Function to validate the form before submission
    function validateForm(event) {
      event.preventDefault(); // Prevent form submission

      // Validate email format
      var email = $("#email")
        .val()
        .trim();
      if (!isValidEmail(email)) {
        displayValidationMessage("Invalid email format");
        return;
      }

      // Validate confirm email
      var confirmEmail = $("#confirm_email")
        .val()
        .trim();
      if (email !== confirmEmail) {
        displayValidationMessage("Emails do not match");
        return;
      }

      // Validate phone number format (if provided)
      var contactNumber = $("#contact_number")
        .val()
        .trim();
      if (
        contactNumber !== "" &&
        !isValidPhoneNumber(contactNumber)
      ) {
        displayValidationMessage("Invalid phone number format");
        return;
      }

      // Check if required fields are filled out
      if (!areRequiredFieldsFilled()) {
        displayValidationMessage(
          "Please fill out all required fields"
        );
        return;
      }

      // If all validations pass, submit the form
      $("#registration_form").submit();
    }

    // Attach form validation to form submit button
    $("#registration_submit").click(validateForm);
  });
}
