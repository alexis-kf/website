This file contains instructions for updating your sites that leverage the Ignite Features package.

Once an Ignite Features is installed, all configuration is "owned" by your site and will be left alone by subsequent updates to this project.

In cases where any manual steps are required to upgrade your Ignite Features configuration, we will provide detailed instructions below under the "Update Instructions" heading.

## Update Instructions

These instructions describe how to update your site to bring it in line with a newer version of Ignite Features.

### Update to 1.16
In this update drupal/node_revision_delete is added with some default configuration for content types to leverage revisiong pruning. For upgrades you will need to make this
configuration manually.

#### Added dependencies
* drupal/node_revision_delete

### Update to 1.23
This update will replace entity_browser configuration with core media_library for new installs (existing installs are unaffected). See the main Ignite project UPDATE.md for information about deprecated modules. We have also modified the ignite_map feature to now integrate with the address module and use open source map configuration by default.

### New features
- ignite_block
- ignite_columns

### New module
- address
