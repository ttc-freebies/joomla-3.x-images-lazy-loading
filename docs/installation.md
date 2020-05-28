# Installation

Installation is an easy process:

- Backup your site, always!
- Download the latest version from here: [Download v1.0.0-beta](dist/plg_images_lazy_loading_1.0.0-beta.zip ':ignore')
- Drag and Drop the zipped file in the installation extension of the Joomla Extension Manager
- That's it, the plugin just installed some files and automatically uninstalled itself!

## Files

The files that were installed are:

### Backend templates
-  js/media/popup-imagemanager.js
-  js/media/popup-imagemanager.min.js
-  js/editors/tinymce/plugins/dragdrop/plugin.js
-  js/editors/tinymce/plugins/dragdrop/plugin.min.js

### Front End templates
-  html/layouts/joomla/content/full_image.php
-  html/layouts/joomla/content/intro_image.php
-  html/plugins/fields/imagelist/imagelist.php
-  html/plugins/fields/media/media.php

And also:
-  js/media/popup-imagemanager.js
-  js/media/popup-imagemanager.min.js
-  js/editors/tinymce/plugins/dragdrop/plugin.js
-  js/editors/tinymce/plugins/dragdrop/plugin.min.js

#### Explanations:
The `.js` files are the tinyMCE drag and drop and the Joomla's own media manager field.
The `.html` files are the overrides for:
- The intro (text) image
- The full (text) image
- The media custom field
- The image list custom field

> Note
If there were any of these overrides already in your template then backups were created in place.
In such case you need to check what you need to copy over to the new ones from the old overrides.
Could be done in the backend following the template style menu.
