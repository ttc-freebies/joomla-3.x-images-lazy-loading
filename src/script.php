<?php
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\File;

class plgSystemImageslazyloadingInstallerScript extends \Joomla\CMS\Installer\InstallerScript {
    public function __construct() {
        $this->minimumJoomla = '3.9';
        $this->minimumPhp    = JOOMLA_MINIMUM_PHP;
        $this->files = array(
          'html/layouts/joomla/content/full_image.php',
          'html/layouts/joomla/content/intro_image.php',
          'html/plugins/fields/imagelist/imagelist.php',
          'html/plugins/fields/media/media.php',
          'js/media/popup-imagemanager.js',
          'js/media/popup-imagemanager.min.js',
          'js/editors/tinymce/plugins/dragdrop/plugin.js',
          'js/editors/tinymce/plugins/dragdrop/plugin.min.js',
        );

          $this->deleteFolders = array(
            '/plugins/system/imageslazyloading',
          );
    }

    public function install(JAdapterInstance $adapter) {
      $messages = array();
      $db = Factory::getDbo();

      $query = $db->getQuery(true)
        ->select('*')
        ->from('#__extensions')
        ->where('type = ' . $db->quote('template'));

      $db->setQuery($query);
      $db->execute();

      try {
        $templates = $db->loadObjectList();
      } catch (\Exception $e) { }

      foreach($templates as $template) {
        $baseFolder = sprintf("%s/%stemplates/", JPATH_ROOT, ((int) $template->client_id === 1) ? 'administrator/' : '');

        foreach($this->files as $file) {
          // Skip the front end overrides for the admin templates
          if (substr($file, -3, 3 ) === 'php' && (int) $template->client_id === 1) {
            continue;
          }

          if (is_file($baseFolder  . $template->element . '/' . $file)) {
            File::copy($baseFolder  . $template->element . '/' . $file, $baseFolder . $template->element . '/' . $file . '.bak');
            $messages[] = $template->element . '/' . $file . '.bak';
          }

          if (!is_dir(pathinfo($baseFolder  . $template->element . '/' . $file)['dirname'])) {
            if (Folder::create(pathinfo($baseFolder  . $template->element . '/' . $file)['dirname'], 0755)) {
              $messages[] = pathinfo($baseFolder  . $template->element . '/' . $file)['dirname'];
              // echo 'This broke: ' . pathinfo($baseFolder  . $template->element . '/' . $file)['dirname'];
            }
          }

          if (File::copy(
            JPATH_ROOT . '/plugins/system/imageslazyloading/overrides/' . $file,
            $baseFolder . $template->element . '/' . $file)
          ) {
              // throw new \Exception('Failed to copy file, check your premissions');
          }
        }
      }

      if (!empty($messages)) {
        echo 'Some overrides already existed, the installer replaced files but also created backups: <br>' . implode('<br>', $messages);
      }
    }

    public function postflight($type, $parent) {
      if ($type === 'install' || $type === 'discover_install') {
        $db = Factory::getDbo();

        $query = $db->getQuery(true)
          ->delete('#__extensions')
          ->where('type = ' . $db->q('plugin'))
          ->where('element = ' . $db->q('imageslazyloading'))
          ->where('folder = ' . $db->q('system'));

        $db->setQuery($query);

        try {
          $db->execute();
        } catch (\Exception $e) { }

        $this->removeFiles();
      }
    }
}
