<?php
defined('ABSPATH') or die('No script kiddies please!');

if (!class_exists('ISM_Update')) {
  class ISM_Update
  {
    private $plugin_name;
    private $plugin_version;
    private $github_url;
    private $download_base_url;
    private $upgrade_transient;
    private $last_checked;
    private $last_checked_option;
    private $plugin_basename;
    private $use_cache;

    const CACHE_EXPIRATION = 43200;

    public function __construct()
    {
      $this->plugin_name          = 'ideasci-modules';
      $this->plugin_version       = ISM_VERSION;
      $this->plugin_basename      = ISM_BASENAME;
      $this->github_url           = 'https://api.github.com/repos/jcazeredo/ideasci-modules/releases/latest';
      $this->download_base_url    = 'https://github.com/jcazeredo/ideasci-modules/releases/download/';
      $this->upgrade_transient    = 'upgrade_ism';
      $this->last_checked_option  = 'ism_last_checked';
      $this->last_checked         = get_option($this->last_checked_option, 0);
      $this->use_cache            = true;

      add_action('upgrader_process_complete', array($this, 'update_complete'), 10, 2);
      add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
      add_filter('plugins_api', array($this, 'check_info'), 10, 3);
    }

    public function check_update($transient)
    {
      // Attempt to get cached response
      if ($this->use_cache) {
        $response = get_transient($this->upgrade_transient);
      }


      // Remove the plugin update from transient if the cached response is present
      if ($this->use_cache && $response && isset($transient->response[$this->plugin_basename])) {
        unset($transient->response[$this->plugin_basename]);
        return $transient;
      }

      // If no cached response and no checked updates, return the transient as is
      if ($this->use_cache && !$response && empty($transient->checked)) {
        return $transient;
      }

      // Check if the user can update plugins and if it's time to check for updates
      if (
        current_user_can('update_plugins') &&
        (
          (!$this->use_cache || !$response && ((time() - $this->last_checked) > self::CACHE_EXPIRATION)) ||
          (!$response && isset($_REQUEST['force-check']) && $_REQUEST['force-check'] == '1')
        )
      ) {

        // Fetch metadata from GitHub
        $latest_release_metadata = $this->get_latest_release_metadata();

        if ($latest_release_metadata && version_compare($this->plugin_version, $latest_release_metadata->version, '<')) {
          // Cache the response
          set_transient($this->upgrade_transient, $latest_release_metadata, self::CACHE_EXPIRATION);

          // Update the last checked time
          update_option($this->last_checked_option, time());
          $this->last_checked = time();

          // Add plugin update information to the transient
          $this->addPluginUpdateToTransient($transient, $latest_release_metadata);
        }
      }

      return $transient;
    }

    private function get_latest_release_metadata()
    {

      // Request github latest release
      $request_github = wp_remote_get(
        $this->github_url,
        array(
          'timeout' => 10,
          'headers' => array(
            'Accept' => 'application/json',
          ),
        )
      );

      if (is_wp_error($request_github)) {
        // Handle the error gracefully, log it, or return an appropriate response
        return false;
      }

      $response_github = json_decode(wp_remote_retrieve_body($request_github));

      // Create the required URLs
      $base_url = $this->download_base_url . "/" . $response_github->tag_name . "/";
      $metadata_url = $base_url . "info.json";

      // Request the metadata
      $request_metadata = wp_remote_get(
        $metadata_url,
        array(
          'timeout' => 10,
          'headers' => array(
            'Accept' => 'application/json',
          ),
        )
      );

      if (is_wp_error($request_metadata)) {
        // Handle the error gracefully, log it, or return an appropriate response
        return false;
      }

      $metadata = json_decode(wp_remote_retrieve_body($request_metadata));

      $metadata->download_link = $base_url . $metadata->zip_filename;;

      return $metadata;
    }


    private function addPluginUpdateToTransient(&$transient, $response)
    {
      $obj = new stdClass();
      $obj->name = $response->name;
      $obj->slug = $this->plugin_name;
      $obj->plugin = $this->plugin_basename;
      $obj->new_version = $response->version;
      $obj->tested = $response->tested;
      $obj->icons = array(
        '1x' => 'https://diviextended.com/wp-content/uploads/2018/04/elicus-128x128.png',
        '2x' => 'https://diviextended.com/wp-content/uploads/2018/04/elicus-256x256.png',
      );
      $obj->package = $response->download_link;

      $transient->response[$obj->plugin] = $obj;
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

          $metadata->version          = $response->version;
          $metadata->slug             = $response->slug;
          $metadata->name             = $response->name;
          $metadata->download_link    = $response->download_link;

          echo $metadata->download_link;
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
