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

  public function addTwigExtension() {
    Craft::import('plugins.settings.twigextensions.settings');
    //Craft::import('plugins.settings.twigextensions.Settings_Globals');
    return array(
      new settings(),
      //new Settings_Globals()
    );
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

  // public function init() {

    // var_dump(craft()->settings->settings); die;

    // $twig = craft()->templates->getTwig(null, ['safe_mode' => false]);
    // foreach(craft()->settings->settings as $var => $val){
    //   $twig->addGlobal($var, $val);
    // }

    // $twig->addGlobal('test1', 'test-1-ok');

    // craft()->urlManager->setRouteVariables(
    //   array('test' => 'test that doesn't work in macros' )
    // );

  // }
};
