<?php
// Function to create custom roles
function create_custom_roles()
{
  // Create the Participant role
  add_role('participant', 'Participant', get_role('subscriber')->capabilities);

  // Create the Presenter role
  add_role('presenter', 'Presenter', get_role('subscriber')->capabilities);
}

// Hook the function to 'init' action
add_action('init', 'create_custom_roles');
