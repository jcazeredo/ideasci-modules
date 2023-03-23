// This script is loaded both on the frontend page and in the Visual Builder.

jQuery(function($) {});

function sendRequest() {
  var inputField = $('input.ism_ajax_search_field')
  var searchTerm = inputField.val();

  $.ajax({
    url: IdeasciModulesFrontendData.ajaxurl,
    type: 'post',
    data: {
      action: 'ajax_search_callback',
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
      console.log(response);
      $('.ism_ajax_search_results_wrap').html(response);
    },
    error: function(xhr, status, error) {
      console.log(xhr.responseText);
    }
  });
}

jQuery(document).ready(function($) {

  // It should initialize with items
  sendRequest();

  // Search on keyup
  $('input.ism_ajax_search_field').keyup(function() {
    sendRequest();
  });

});
  
