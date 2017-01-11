<?php
namespace Craft;

class SettingsVariable  {

  // {{ craft.settings.get() }}
  public function get() {
    return craft()->settings->settings;
  }

  // Retrigger the variable settings
  // {{ craft.settings.reset() }}
  // Any plugin handles that were passed as a second paramter was cached. So websites with History API's can call this function
  // to reset all the global variables with the latest relivent settings.
  public function reset() {
    foreach (craft()->settings->cache as &$handle) {
      craft()->settings->addGlobals(craft()->plugins->getPlugin($handle)->getGlobals());
    }
    craft()->settings->addGlobals();
  }

}
