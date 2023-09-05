<?php
defined('ABSPATH') or die('No script kiddies please!');

if (!class_exists('ISM_Update')) {
  class ISM_Update
  {
    /**
     * Plugin Name.
     *
     * @since 	 1.0.2
     * @access   private
     * @var 	 string
     */
    private $plugin_name;

    /**
     * Plugin Version.
     *
     * @since 	 1.0.2
     * @access   private
     * @var 	 string
     */
    private $plugin_version;

    /**
     * Metadata Url.
     *
     * @since 	 1.0.2
     * @access   private
     * @var 	 string
     */
    private $metadata_url;

    /**
     * Plugin Upgrade Transient.
     *
     * @since 	 1.0.2
     * @access   private
     * @var 	 string
     */
    private $upgrade_transient;

    /**
     * Last checked update.
     *
     * @since 	 1.0.2
     * @access   private
     * @var 	 string
     */
    private $last_checked;

    /**
     * Last checked update option.
     *
     * @since 	 1.0.2
     * @access   private
     * @var 	 string
     */
    private $last_checked_option;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.2
     * @param    string    $plugin_name   	The name of this plugin.
     * @param    string    $version  		The version of this plugin.
     */
    public function __construct()
    {
      $this->plugin_name      = 'ideasci-modules';
      $this->plugin_version    = ISM_VERSION;
      $this->plugin_basename    = ISM_BASENAME;
      $this->metadata_url     = 'https://raw.githubusercontent.com/jcazeredo/ideasci-modules/master/info.json';
      $this->upgrade_transient    = 'upgrade_ism';
      $this->last_checked_option  = 'ism_last_checked';
      $this->last_checked     = get_option($this->last_checked_option, 0);

      add_action('upgrader_process_complete', array($this, 'update_complete'), 10, 2);
      add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
      add_filter('plugins_api', array($this, 'check_info'), 10, 3);
    }

    /**
     * Check for updates.
     *
     * @since    1.0.0
     */
    public function check_update($transient)
    {
      // trying to get from cache first, to disable cache comment 10,20,21,22,24
      $response = get_transient($this->upgrade_transient);

      if (!$response && isset($transient->response[$this->plugin_basename])) {
        unset($transient->response[$this->plugin_basename]);
        return $transient;
      }

      if (!$response && empty($transient->checked)) {
        return $transient;
      }

      if (
        current_user_can('update_plugins') &&
        (
          (false === $response && ((time() - $this->last_checked) > 43200)) ||
          (false === $response && isset($_REQUEST['force-check']) && $_REQUEST['force-check'] == '1')
        )
      ) {

        $request = wp_remote_get(
          $this->metadata_url,
          array(
            'timeout' => 10,
            'headers' => array(
              'Accept' => 'application/json'
            )
          )
        );

        if (!is_wp_error($request) && !empty($request['body'])) {
          $response = json_decode(wp_remote_retrieve_body($request));
          if (version_compare($this->plugin_version, $response->version, '<')) {
            set_transient($this->upgrade_transient, $response, 43200); // 12 hours cache
          }
        }

        update_option($this->last_checked_option, time());
        $this->last_checked = time();
      }

      if ($response && current_user_can('update_plugins')) {
        if (version_compare($this->plugin_version, $response->version, '<')) {
          $obj            = new stdClass();
          $obj->name       = $response->name;
          $obj->slug       = $this->plugin_name;
          $obj->plugin     = $this->plugin_basename;
          $obj->new_version   = $response->version;
          $obj->tested     = $response->tested;
          $obj->icons         = array(
            '1x' => 'https://diviextended.com/wp-content/uploads/2018/04/elicus-128x128.png',
            '2x' => 'https://diviextended.com/wp-content/uploads/2018/04/elicus-256x256.png',
          );
          $obj->package     = $response->download_url;

          $transient->response[$obj->plugin] = $obj;
        }
      }

      return $transient;
    }

    /**
     * Display metadata in popup.
     *
     * @since    1.0.0
     */
    public function check_info($res, $action, $args)
    {

      // do nothing if this is not about getting plugin information
      if ('plugin_information' !== $action) {
        return $res;
      }

      // do nothing if it is not our plugin	
      if ($this->plugin_name !== $args->slug) {
        return $res;
      }

      $response = get_transient($this->upgrade_transient);

      if ($response && current_user_can('update_plugins')) {
        if (version_compare($this->plugin_version, $response->version, '<')) {
          $metadata            = new stdClass();

          $metadata->sections = array(
            'description' => $response->sections->description,
            'installation' => $response->sections->installation,
            'changelog' => $response->sections->changelog
          );

          if (!empty($response->banners)) {
            $metadata->banners = array(
              'low' => $response->banners->low,
              'high' => $response->banners->high
            );
          }

          $metadata->version  = $response->version;
          $metadata->slug  = $response->slug;
          $metadata->name  = $response->name;
          $metadata->download_link   = $response->download_url;
          return $metadata;
        }
      }

      return $res;
    }

    /**
     * Delete transient of update completed
     *
     * @since    1.0.0
     */
    public function update_complete($upgrader_object, $options)
    {

      if ('update' === $options['action'] && 'plugin' === $options['type']) {
        // just clean the cache when new plugin version is installed
        foreach ($options['plugins'] as $plugin) {
          if ($plugin === $this->plugin_basename) {
            delete_transient($this->upgrade_transient);
            update_option($this->last_checked_option, time());
            $this->last_checked = time();
          }
        }
      }
    }
  }
  new ISM_Update;
}
