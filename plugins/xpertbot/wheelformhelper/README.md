# Wheelform Helper Plugin
This is an example Plugin that handles events for Craft CMS.

In order to install this plugin in your environtment.
Download these files and put them on a `plugins` folder next to your `vendor` folder.

Add next JSON Object to the repositories array on your main `composer.json` for your craft install.
(Note: you might need to adjust if "repositories" array is not empty)

```JSON
{
  "require": {
    "xpertbot/wheelformhelper": "*"
  },
  "repositories": [
    {
      "type": "path",
      "url": "plugins/wheelformhelper"
    }
  ]
}
```
This is just a path, the name of the "plugins" folder is irrelevant. You can make a relative path
from your `composer.json` file.

Note: the package name `xpertbot` and the plugin name `wheelformhelper` are irrelevant, you can change them to meet your needs.
Make sure you update the `composer.json` inside the plugin folder and change the `name` attribute to the one you chose.

Once the Path of the plugin is added to your `composer.json` you can run `composer update xpertbot\wheelformhelper`
(or whatever `package\name` you chose)

[More Information](https://docs.craftcms.com/v3/plugin-intro.html#getting-started)
