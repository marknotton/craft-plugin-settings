<img src="http://i.imgur.com/50u9VsC.png" alt="Settings" align="left" height="60" />

# Settings *for Craft CMS*

Expose an array of cached variables globally. Useful for history API's that cause headaches when variables lose their scope.

### Installation

Create a file and define it's location in the plugins settings. Create an associative array with the variables you want to be able to access globally.

If the first value is an array, that key will be ignored and it's children will be defined as separate variables instead. This is to help categorise your variables.

###Example settings file

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
This example will allow you to access the following variables anywhere (apart from Macros)

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
Until I can get the getGlobals thing to work (see Todo), you will need to define the settings in your layout:

```
{{ settings() }}
```
If for any reason you need to ommit a variable, you can pass it like so:

```
{{ settings('theme') }}
```

This twig function will automatically define the variables. However, if you just want to return the settings array you can pass in ```true```

```
{{ settings(true) }}
```
or
```
{{ settings('theme', true) }}
```

##TODO:

- Have it so Twigs "getGlobals" function defines these variables globally. Allowing them to accessed via Macros too.
