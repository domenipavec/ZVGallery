<?php
/*  ZVGallery
 *  =========
 *  Copyright 2014 Domen Ipavec <domen.ipavec@z-v.si>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
 
defined('ZVG_PHP') || die("No direct access allowed.");

require('includes/check_permission.php');
require('includes/check_path.php');
require_once('includes/get_filetype.php');

$fullpath = $_GET['p'];
$gallerypath = $_ZVG['gallery_folder'] . $fullpath;
$type = get_filetype($gallerypath);

if (!preg_match('/image\/?.*|video\/?.*/', $type)) {
    die("Not a multimedia file.");
}

// Hack to support unicode
// if there's no '/', we're probably dealing with just a filename
// so just put an 'a' in front of it
$gp = $gallerypath;
if (strpos($gp, '/') === false)
{
    $name = pathinfo('a'.$gp);
}
else
{
    $gp = str_replace('/', '/a', $gp);
    $name = pathinfo($gp);
}

header("X-Sendfile: $gallerypath");
header("Content-type: $type");
header('Content-Disposition: attachment; filename="' . substr($name["basename"],1) . '"');
header("Cache-control: public, max-age=3600");
header("Expires: " . gmdate('D, d M Y H:i:s', time() + 3600) . " GMT");
header_remove('Pragma');
