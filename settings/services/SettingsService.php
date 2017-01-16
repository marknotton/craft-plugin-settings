<?php
namespace Craft;

class SettingsService extends BaseApplicationComponent {

  public  $settings;
  public  $environmentVariables;
  public  $cache = [];
  private $twig;
  private $allowed;

  // Pass in an associative array. Keys will become variables, and values as... values.
  public function addGlobals($variables=null, $cache=null) {
    if (!is_null($cache)) {
      array_push($this->cache,$cache);
    }
    if ($this->allowed && is_array($variables) || is_null($variables) )  {
      $variables = is_null($variables) ? $this->settings : $variables;
      foreach($variables as $var => $val){
        $this->twig->addGlobal($var, $val);
      }
    } else {
      return [];
    }
  }

  public function setEnvironmentals() {

    if ($this->allowed)  {
      // If theese don't exist, add them too
      $fallbackVariables = array(
        'systemPath' => getcwd(),
        'uploads'    => '/assets/uploads',
        'images'     => '/assets/images',
        'sprites'    => '/assets/images/sprites',
        'css'        => '/assets/css',
        'js'         => '/assets/js',
        'videos'     => '/assets/videos',
        'locale'     => craft()->i18n->getLocaleById(craft()->language)
      );

      $this->environmentVariables = array_unique(array_merge($fallbackVariables, craft()->config->get('environmentVariables')), SORT_REGULAR);

      $this->addGlobals($this->environmentVariables);
    }
  }


  public function setGlobals($cleanup=true) {

    if ($this->allowed)  {

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

        // Get settings from settings file
        $settings = json_decode(craft()->templates->render($settingsFile), true);

        // Reformat the settings so all first level arrays will have it's childen brought forward one level
        if ($cleanup === true) {
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
            $settings = !empty($newSettings) ? $newSettings : $settings;
          }
        }

        if (!is_null($this->environmentVariables)) {
          $settings = array_unique(array_merge($this->environmentVariables, $settings), SORT_REGULAR);
        }

        $this->settings = $settings;


      }

      $this->addGlobals();
    }
  }

  public function init() {

    // Check this init is being called ont he front end and not in the CMS
    $this->allowed = !craft()->isConsole() && craft()->request->isSiteRequest();

    if ($this->allowed)  {
      // Create a new twig envionment so we can take advantage of the addGlobal fucntion
      $this->twig = craft()->templates->getTwig(null, ['safe_mode' => false]);
    }

  }
}
