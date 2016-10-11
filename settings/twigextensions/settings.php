<?php
namespace Craft;

use Twig_Extension;

class Settings extends \Twig_Extension {

  public function getName() {
    return Craft::t('Settings');
  }

  public function getFunctions() {
    return array(
      'settings' => new \Twig_SimpleFunction('settings', array($this, 'settings'), ['needs_context' => true, ])
    );
  }

  public function settings(&$context, $exclude=null, $return=false) {
    $settings = craft()->settings->settings;
    if (!is_null($exclude) and $exclude !== true ) {
      if ( is_array($exclude) ) {
        $settings = array_diff($settings, $exclude);
      } else if ( is_string($exclude) ) {
        unset($settings[$exclude]);
      }
    }

    if ($return === true || $exclude === true) {
      return $settings;
    } else {
      foreach($settings as $k => $v) {
        $context[$k] = $v;
      }
    }
  }

}
