// This script is loaded both on the frontend page and in the Visual Builder.

jQuery(function($) {});

jQuery(document).ready(function($) {

    // Search on keyup
    $('#s').keyup(function() {
      var query = $(this).val();
      if ( query.length > 2 ) {
        $.ajax({
          url: IdeasciModulesFrontendData.ajaxurl,
          type: 'post',
          data: {
            action: 'my_search_query',
            query: query
          },
          success: function(response) {
            $('#search-results').html(response);
          }
        });
      } else {
        $('#search-results').empty();
      }
    });
  
  });

// $.fn.requestResults = function(){
//     var query = $(this).val();

//     $.ajax({
//         url: IdeasciModulesFrontendData.ajaxurl,
//         type: 'post',
//         data: {
//             action: 'ajax_search_results',
//             query: query
//         },
//         success: function(response) {
//         $('#search-results').html(response);
//         }
//     });
//  }

// jQuery(document).ready(function($) {
    
//     $.fn.requestResults();

//     $('#s').keyup(function() {
//         $.fn.requestResults();
//     });
// });
  
