<img src="http://i.imgur.com/50u9VsC.png" alt="Settings" align="left" height="60" />

# Settings *for Craft CMS*

> This plugin is no longer maintained. I'm committing to Craft 3 development only. Feel free to use the source code as you like. If you're looking for a Craft 3 version of this plugin, it's likely I've merged parts or all of this plugin into my [Helpers module.](https://github.com/marknotton/craft-module-helpers)

Cache all your global variables from a separate file.

Create a file and define it's location in the plugins settings. Create an associative array with the variables you want to be able to access globally.

### Example settings file

```
{% cache globally using key 'settings' %}
  {% set settings = {
    theme : '#125680',
    transforms : {
      background  : { mode:'crop', width:1920,  height:1080, quality:80, position:'center-center'},
      featured   : { mode:'crop', width:720,  height:1080, quality:80, position:'center-center'},
      featuredMobile  : { mode:'crop', width:480,  height:640, quality:60, position:'center-center'},
      thumb  : { mode:'crop', width:300,  height:300, quality:80, position:'center-center'},
      mobile : { mode:'crop', width:640,  height:1136, quality:60, position:'center-center'},
    },
    test      : 'This test is from the _includes/settings file',
    telephone : globals.telephone|default('555-1234'),
  }%}

  {{ settings|json_encode|raw }}
{% endcache %}

```
This will allow you to access the following variables anywhere.

```
{{ theme }}
{{ background }}
{{ featured }}
{{ featuredMobile }}
{{ thumb }}
{{ mobile }}
{{ test }}
{{ telephone }}
```

If the first settings key has a value which is an array; the key will be ignored and it's children will be defined as variables instead. This is so you can organise your variables. You can disable this in the plugins settings.

### Environmental Variables to Global Variables

Any [Environmental Variables](https://craftcms.com/docs/config-settings#environmentVariables) set in the general config file will be made accessible as a global variable within your Twig templating. You can disable this in the plugins settings.

### Get
To simply retreive the current settings array

```
{% set settings = craft.settings.get() %}
```

### Reset
One of the main purposes of this plugin was to accomodate some of the trickier aspects of websites built with a History API. You can reset the global variables on the fly with this:

```
{{ craft.settings.reset() }}
```

The reset also returns the current settings after they have been updated when you pass in ```true```.

```
{% set settings = craft.settings.reset(true) %}
```

### Advanced

If other plugins want to tap into this feature and make an associative array into global variables; you can call this function.

```
craft()->settings->addGlobals($this->getGlobals(), 'myPlugin');
```

Should the site be using a History API, you'll need to pass in your plugins handle so it can be cached. Remember to set a getGlobals function in your plugins root which returns an array;

```
public function getGlobals() {
  return array(
    'test' => 'This is a test'
  );
}
```
