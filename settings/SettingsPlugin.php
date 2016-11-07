<?php
namespace Craft;

class SettingsPlugin extends BasePlugin {

  public function getName() {
    return Craft::t('Settings');
  }

  public function getVersion() {
    return '0.1';
  }

  public function getSchemaVersion() {
    return '0.1';
  }

  public function getDescription() {
    return 'Cache all your global variables in a separate file.' ;
  }

  public function getDeveloper() {
    return 'Yello Studio';
  }

  public function getDeveloperUrl() {
    return 'http://yellostudio.co.uk';
  }

  public function getDocumentationUrl() {
    return 'https://github.com/marknotton/craft-plugin-settings';
  }

  public function getReleaseFeedUrl() {
    return 'https://raw.githubusercontent.com/marknotton/craft-plugin-settings/master/settings/releases.json';
  }

  public function getSettingsHtml() {
    return craft()->templates->render('settings/settings', array(
      'settings' => $this->getSettings()
    ));
  }

  protected function defineSettings() {
    return array(
      'settingsFile' => array(AttributeType::String, 'default' => ''),
    );
  }

  public function init() {
    // This will set the default settings as per the settings.twig file.
    // Warning: Assigning global variables in this way will overwrite the use of Crafts 'getGlobal();
    // function accross all installed plugins.
    if (!craft()->isConsole() && craft()->request->isSiteRequest()) {
      craft()->settings->setEnvironmentals();
      craft()->settings->setGlobals();
    }
  }
};
