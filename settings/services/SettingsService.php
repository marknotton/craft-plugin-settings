<?php
namespace Craft;

class SettingsService extends BaseApplicationComponent {

  public $settings;

  public function init() {

    // Get plugin settings
    $pluginSettings = craft()->plugins->getPlugin('settings')->getSettings();

    // If plugin settings have defined a specific settings file, use this. Otherwise define a fallback file
    $settingsFile = !empty($pluginSettings['settingsFile']) ? $pluginSettings['settingsFile'] : '_includes/settings';

    // Check the settings file exists
    if (substr($settingsFile, -5) == '.twig' || substr($settingsFile, -4) == '.html') {
      if (!file_exists(craft()->path->getSiteTemplatesPath().$settingsFile)) {
        $settingsFile = false;
      }
    } else {
      foreach (['twig', 'html'] as $format) {
        if (file_exists(craft()->path->getSiteTemplatesPath().$settingsFile.'.'.$format)) {
          $settingsFile = $settingsFile.'.'.$format;
        }
      }
    }

    if (!empty($settingsFile)) {

      $settings = json_decode(craft()->templates->render($settingsFile), true);

      // Reformat the settings all first level arrays will have it's childen brought forward one level
      $newSettings = [];

      if (isset($settings) && is_array($settings)) {
        foreach ($settings as $key => $value) {
          if (is_array($value)) {
            foreach ($settings[$key] as $k => $v) {
              $newSettings[$k] = $v;
            }
          } else {
            $newSettings[$key] = $value;
          }
        }

        // Update settings
        $this->settings = !empty($newSettings) ? $newSettings : $settings;
      }
    }

    // foreach($this->settings as $key => $value) {
    //   if (is_array($value)) {
    //     echo "$key : ".implode($value)."<br>";
    //   } else {
    //     echo "$key : ".$value."<br>";
    //   }
    // }

  }

}
