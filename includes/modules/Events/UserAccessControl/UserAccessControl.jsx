import React, { Component } from 'react';
import './styles.css';

class UserAccessControl extends Component {
  static slug = 'ism_events_uac';

  render() {
    const { registration_form_fields, registration_form_title } = this.props;

    return (
      <div>
        <div className="ism-events-form-container">
          <h2>{registration_form_title}</h2>
          <form id="registration_form" className="ism-events-registration-form" method="post">
            {/* Add your form nonce and hidden fields here */}
            <input type="hidden" name="registration_flag" value="1" />

            {/* Render form fields */}
            <div className="form-group">
              <label htmlFor="email" className="ism-events-form-required-field">Email</label>
              <input type="email" id="email" name="email" required />
            </div>

            <div className="form-group">
              <label htmlFor="confirm_email" className="ism-events-form-required-field">Confirm Email</label>
              <input type="email" id="confirm_email" name="confirm_email" required />
            </div>

            <div className="form-group">
              <label htmlFor="password" className="ism-events-form-required-field">Password</label>
              <input type="password" id="password" name="password" required />
            </div>

            <div className="form-group">
              <label htmlFor="confirm_password" className="ism-events-form-required-field">Confirm Password</label>
              <input type="password" id="confirm_password" name="confirm_password" required />
            </div>

            {Object.keys(registration_form_fields).map(fieldSlug => {
              const fieldInfo = registration_form_fields[fieldSlug];
              const display = this.props[`display_${fieldSlug}`] === 'on';
              const inputType = fieldInfo.type;
              const required = this.props[`require_${fieldSlug}`] === 'on';
                
              return display ? (
                <div className="form-group" key={fieldSlug}>
                  <label htmlFor={fieldSlug} className={required ? 'ism-events-form-required-field' : ''}>
                    {fieldInfo.label}
                  </label>
                  <input
                    type={inputType}
                    id={fieldSlug}
                    name={fieldSlug}
                    required={required}
                  />
                </div>
              ) : null;
            })}

            {/* Additional form fields, e.g., terms and conditions */}
            <div className="form-group">
              <label htmlFor="terms_conditions" className='ism-events-form-required-field'>
                <input type="checkbox" id="terms_conditions" name="terms_conditions" />
                I agree to the terms and conditions
              </label>
            </div>

            {/* Submit button */}
            <input type="submit" name="registration_submit" id="registration_submit" value="Register" />
          </form>
        </div>
        <div className="ism-events-form-message"></div>
      </div>
    );
  }
}

export default UserAccessControl;
