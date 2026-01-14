=== SWPM - Elementor Template Protection ===
Tags: elementor, swpm, simple membership
Requires at least: 4.6
Tested up to: 5.8
Stable tag: 1.0.0
License: GPLv2 or later

This plugin allows you to make any Elementor template responsive members log-in status and to any users's specific membership level (*use of Simple Membership* plugin)

== Description ==

### Guide

Only the templates registered as **sections** are displayed in the Elementor template list.

### Developers

**Here are the filters available within the plugin:**
`svilapp/elementor/swpm-elementor-template-protection/categories`: Allows you to assign the category of the elementor widget.
`svilapp/elementor/swpm-elementor-template-protection/template_results`: Allows you to filter the selectable Elementor templates.
`svilapp/elementor/swpm-elementor-template-protection/membership_results`: Allows you to filter the selectable Simple Membership memberships.
`svilapp/elementor/swpm-elementor-template-protection/widget/register_controls/template_type_condition`: Allows you to validate the template type.
`svilapp/elementor/swpm-elementor-template-protection/widget/render/condition`: Allows you to filter the rendering by an external condition.
`svilapp/elementor/swpm-elementor-template-protection/widget/render/content`: Allows you to modify the content output of the widget.

**Below are the actions available within the plugin:**
`svilapp/elementor/swpm-elementor-template-protection/widget/register_controls/before`: Runs before elementor controls are registered.
`svilapp/elementor/swpm-elementor-template-protection/widget/register_controls/content`: Runs after the main ** content ** controls are registered.
`svilapp/elementor/swpm-elementor-template-protection/widget/register_controls/after`: Executed after registering elementor controls.

### PRO version

The **PRO version** has in addition the possibility to add a **Logged-In** condition and the possibility to select multiple memberships with the **AND / OR** condition.

In addition, the list of available **Elementor** templates is extended to all registered templates.

== Installation ==

Upload the plugin to your site, activate it.

1, 2, 3: You're done!

== Changelog ==

= 1.0.0 =
*Release Date - 6 August 2021*

* Initial release

For older changelog entries, please see the [additional changelog.txt file](https://plugins.svn.wordpress.org/swpm-elementor-template-protection/trunk/changelog.txt) delivered with the plugin.
