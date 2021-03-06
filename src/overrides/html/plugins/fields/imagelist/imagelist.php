<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Imagelist
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

if ($field->value == '') {
	return;
}

$isResponsiveEnabled = false;
$class = $fieldParams->get('image_class');

if (JPluginHelper::isEnabled('content', 'responsive')) {
	JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php');
	$isResponsiveEnabled = true;
}

if ($class) {
	// space before, so if no class sprintf below works
	$class = ' class="' . htmlentities($class, ENT_COMPAT, 'UTF-8', true) . '"';
}

$value  = (array) $field->value;
$buffer = '';

foreach ($value as $path) {
	if (!$path || $path == '-1') {
		continue;
	}

	if ($fieldParams->get('directory', '/') !== '/') {
		$image = sprintf('<img loading="lazy" src="images/%s/%s"%s>',
			$fieldParams->get('directory'),
			htmlentities($path, ENT_COMPAT, 'UTF-8', true),
			$class
		);

		if ($isResponsiveEnabled) {
			$helper = new \Ttc\Freebies\Responsive\Helper;
			$image = $helper->transformImage($image, array(200, 320, 480, 768, 992, 1200, 1600, 1920));
		}

		$buffer .= $image;
	} else {
		$image = sprintf('<img loading="lazy" src="images/%s"%s>',
			htmlentities($path, ENT_COMPAT, 'UTF-8', true),
			$class
		);

		if ($isResponsiveEnabled) {
			$helper = new \Ttc\Freebies\Responsive\Helper;
			$image = $helper->transformImage($image, array(200, 320, 480, 768, 992, 1200, 1600, 1920));
		}

		$buffer .= $image;
	}
}

echo $buffer;
