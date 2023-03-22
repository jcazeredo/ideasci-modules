// External Dependencies
import React, { Component } from 'react';

class AjaxSearch extends Component {

  static slug = 'ism_ajax_search';

  /**
   * Module render in VB
   * Basically ISM_Ajax_Search->render() equivalent in JSX
   */
  render() {

    return (
      <div class="ism_ajax_search_wrap">
        <div class="ism-ism_ajax_search_field_wrap-search"></div>
        <div class="ism_ajax_search_results_wrap">
          <div class="ism_ajax_search_results">
            <div class="ism_ajax_search_items">
              <div class="ism_ajax_search_item">
                <div class="ism_ajax_search_item_title">Title 1</div>
                <div class="ism_ajax_search_item_date">Date</div>
              </div>
              <div class="ism_ajax_search_item">
                <div class="ism_ajax_search_item_title">Title 2</div>
                <div class="ism_ajax_search_item_date">Date</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default AjaxSearch;
