UAMPPE
======

User Access Manager Private &amp; Public Extension for WordPress, forked from the User Access Manager Private Extension (http://wordpress.org/plugins/user-access-manager-private-extension/).

This plugin allows to define [private]-sections, as well as [public]-section, that are only being displayed to not-authroized users. Through that, it's easily possible to deliver two different contents within one page/post.

# Usage

For using the [private]-sections, please refer to the plugin's original "readme.txt" that comes with this. The usage of the [public]-section is pretty much the same. Yet, it doesn't take any arguments, though.

## Example

  [private group=Members]
  Hello Member!
  [private]
  
  [public]
  Hello Guest!
  [/public]
  
Pretty simple, isn't it?

# Credits

All credits for the original plugin go to "eberzosa". See the "readme.txt" for more information.