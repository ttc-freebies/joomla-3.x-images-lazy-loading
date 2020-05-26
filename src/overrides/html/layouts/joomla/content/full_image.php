<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$params = $displayData->params;
$images = json_decode($displayData->images);

if (empty($images->image_fulltext)) {
	return;
}

$imgfloat = empty($images->float_fulltext) ? $params->get('float_fulltext') : $images->float_fulltext;
$attr     = array(
	'loading'  => 'lazy',
	'src'      => $images->image_fulltext,
	'alt'      => $images->image_fulltext_alt,
	'itemprop' => 'image',
);

if ($images->image_fulltext_caption) {
	$attr['class'] = 'caption';
	$attr['title'] = $images->image_fulltext_caption;
}

$image = '<img '
. implode(
	' ',
	array_map(
		function ($k, $v) { return $k .'="'. htmlspecialchars($v, ENT_COMPAT, 'UTF-8') .'"'; },
		array_keys($attr), $attr
	)
)
. ' />';

if (JPluginHelper::isEnabled('content', 'responsive')) {
	JLoader::register('Ttc\Freebies\Responsive\Helper', JPATH_ROOT . '/plugins/content/responsive/helper.php', true);
	$helper = new \Ttc\Freebies\Responsive\Helper;
	$image = $helper->transformImage($image, array(200, 320, 480, 768, 992, 1200, 1600, 1920));
}

echo '<div class="pull-' . htmlspecialchars($imgfloat) . ' item-image">' . $image . '</div>';
