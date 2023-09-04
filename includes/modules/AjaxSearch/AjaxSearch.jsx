// External Dependencies
import React, { Component } from 'react';

class AjaxSearch extends Component {
  constructor(props) {
    super(props);
    this.sendRequest = this.sendRequest.bind(this);
  }

  static slug = 'ism_ajax_search';

  componentDidMount() {
    this.sendRequest();
  }
  
  async sendRequest() {
    let data_dict = {
      action: 'ajax_search_callback',
      search_term: '',
      post_type: '',
      display_fields: '',
      number_of_results: '',
      no_result_text: '',
      order_by: '',
      order: '',
      date_format: '',
   };
   fetch(window.IdeasciModulesFrontendData.ajaxurl, {
     method: "POST",
     data: JSON.stringify(data_dict)
   })
     .then(response => {
        console.log(response);
        let json = response.json();
        console.log(json);
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
     })
     .catch(error => {
       console.error(
         "There has been a problem with your fetch operation:",
         error
       );
     });
 }

  /**
   * Module render in VB
   * Basically ISM_Ajax_Search->render() equivalent in JSX
   */
  render() {

    var icon = <span class="ism_ajax_search_search_icon"><span class="et-pb-icon">&#x55;</span></span>;

    return (
      <div class="ism_ajax_search_wrap">
        <div class="ism_ajax_search_field_wrap">
          <input type="search" placeholder={this.props.search_placeholder} class="ism_ajax_search_field" id="ism_ajax_search_field" />
          {this.props.show_search_icon === "on" ? icon : ''}
        </div>

        <div class="ism_ajax_search_results_wrap">
          <div class="ism_ajax_search_results">
            <div class="ism_ajax_search_item">
              <div class="ism_ajax_search_item_title">
                <a target="_blank" rel="noopener noreferrer" href="https://doi.org/10.1098/rstb.2021.0067" class="display-block"> Below-ground traits mediate tree survival in a tropical dry forest restoration </a>
              </div>
              <div class="ism_ajax_search_item_date">
                May, 2017
              </div>
              <div class="ism_ajax_search_item_excerpt">
                Werden, L. K., Averill, C., Crowther, T. W., Calderón-Morales, E., Toro, L., Alvarado, J. P., Gutiérrez, L. M., Mallory, D. E., &amp; Powers, J. S. (2022). Below-ground traits mediate tree survival in a tropical dry forest restoration. Philosophical Transactions of the Royal Society B: Biological Sciences, (1867), 20210067. 
              </div>
            </div>
            <div class="ism_ajax_search_item">
              <div class="ism_ajax_search_item_title">
                <a target="_blank" rel="noopener noreferrer" href="https://doi.org/10.1098/rstb.2021.0067" class="display-block"> Below-ground traits mediate tree survival in a tropical dry forest restoration </a>
              </div>
              <div class="ism_ajax_search_item_date">
                May, 2017
              </div>
              <div class="ism_ajax_search_item_excerpt">
                Werden, L. K., Averill, C., Crowther, T. W., Calderón-Morales, E., Toro, L., Alvarado, J. P., Gutiérrez, L. M., Mallory, D. E., &amp; Powers, J. S. (2022). Below-ground traits mediate tree survival in a tropical dry forest restoration. Philosophical Transactions of the Royal Society B: Biological Sciences, (1867), 20210067. 
              </div>
            </div>
          </div>
        </div>
        <div></div>
      </div>
    );
  }
}

export default AjaxSearch;
