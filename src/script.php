<?php
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class plgSystemImageslazyloadingInstallerScript {
    private $files = array(
        'html/layouts/joomla/content/full_image.php',
        'html/layouts/joomla/content/intro_image.php',
        'html/plg_fields_imagelist/imagelist.php',
        'html/plg_fields_media/media.php',
        'js/media/popup-imagemanager.js',
        'js/media/popup-imagemanager.min.js',
        'js/editors/tinymce/plugins/dragdrop/plugin.js',
        'js/editors/tinymce/plugins/dragdrop/plugin.min.js',
    );

  public function __construct(JAdapterInstance $adapter) {
    $this->minimumJoomla = '3.9';
    $this->minimumPhp = JOOMLA_MINIMUM_PHP;
  }

  public function install(JAdapterInstance $adapter) {
    $messages = array();
    $db = Factory::getDbo();

    $query = $db->getQuery(true)
    ->select('*')
    ->from('#__extensions')
    ->where('type = ' . $db->q('template'));

    $db->setQuery($query);

    try {
      $templates = $db->loadObjectList();
    } catch (\Exception $e) { }

    foreach($templates as $template) {
      $baseFolder = sprintf("%s/%stemplates/", JPATH_ROOT, ((int) $template->client_id === 1) ? 'administrator/' : '');

      foreach($this->files as $file) {
        // Skip the front end overrides for the admin templates
        if (substr($file, 0, 4 ) === 'html' && $template->client_id === 1) {
          return;
        }

        if (is_file($baseFolder  . $template->element . '/' . $file)) {
          if (!copy(
            $baseFolder  . $template->element . '/' . $file,
            $baseFolder . $template->element . '/' . $file . '.bak')
          ) {
            throw new \Exception('Failed to copy file, check your premissions');
          }
          $messages[] = $template->element . '/' . $file . '.bak'; 
        }
        
        if (!is_dir(pathinfo($baseFolder  . $template->element . '/' . $file)['dirname'])) {
          if (!mkdir(pathinfo($baseFolder  . $template->element . '/' . $file)['dirname'], 0755, true)) {
            echo 'This broke: ' . pathinfo($baseFolder  . $template->element . '/' . $file)['dirname'];
          }
        }

        if (!copy(
          JPATH_ROOT . '/plugins/system/imageslazyloading/overrides/' . $file,
          $baseFolder . $template->element . '/' . $file)
          ) {
          throw new \Exception('Failed to copy file, check your premissions');
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

      if (is_dir(JPATH_ROOT . '/plugins/system/imageslazyloading')) {
        self::delete_files(JPATH_ROOT . '/plugins/system/imageslazyloading');
      }
    }
  }

  private static function delete_files($target) {
    if (is_dir($target)) {
      $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

      foreach ($files as $file ) {
        self::delete_files($file);
      }

      if (is_dir($target)) {
        rmdir($target);
      }
    } elseif (is_file($target)) {
      unlink($target);  
    }
  }
}
