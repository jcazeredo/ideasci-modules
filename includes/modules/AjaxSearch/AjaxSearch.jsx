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
      <div>
        <h2 className="ism-title">{this.props.title}</h2>
        <div className="ism-content">{this.props.content()}</div>
      </div>
    );
  }
}

export default AjaxSearch;
