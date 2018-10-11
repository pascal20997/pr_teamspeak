# TeamSpeak 3 monitor

## Requirements

- TYPO3 8.7 - 9.5
- TeamSpeak 3 Server

## Install

- Install using composer (recommended) `composer require crynton/pr-teamspeak`
- Install via extension manager `pr_teamspeak`
- Download extension from TER [open](https://extensions.typo3.org/extension/pr_teamspeak)

## Configure

1. Select page to display the monitor
2. Add a new content element
3. Select type "Insert plugin"
4. Open the tab "Plugin" and select the plugin "TeamsSpeak 3"
5. Fill in the fields from tab "Plugin > General"

Notice: The fields username and password are optional.

[PluginSettings]: Documentation/Images/PluginSettings.png "Plugin settings"

6. Edit the template of the target page or create a new extension template.
7. Include the static template(s) adapt to your needs

[IncludeTemplate]: Documentation/Images/IncludeTemplate.png "Include static templates"

## Screenshots

[Frontend]: Documentation/Images/Frontend.png "Frontend demo"

