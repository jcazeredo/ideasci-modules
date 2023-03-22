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
      <div class="ism-ajax-search">
        <div class="ism-ajax-search-wrap">
          <li><a href="link">Title - Date</a></li>
          <li><a href="link">Title - Date</a></li>
        </div>
      </div>
    );
  }
}

export default AjaxSearch;
