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

if (!preg_match('/image\/?.*/', $type)) {
    die("Not an image.");
}
if (array_key_exists('d', $_GET) && $_GET['d'] == 'ccw') {
    $rotate = '-90';
} else {
    $rotate = '90';
}

$imagepath = $_ZVG['tmp_folder'] . '/images' . $fullpath;
$thumbnailpath = $_ZVG['tmp_folder'] . '/thumbnails' . rtrim($fullpath, '/') . '.jpg';

$cmd = 'convert  "';
$cmd .= $gallerypath;
$cmd .= '" -rotate ';
$cmd .= $rotate;
$cmd .= ' "';
$cmd .= $gallerypath;
$cmd .= '"';
exec($cmd);

if (is_file($imagepath)) {
    unlink($imagepath);
}
if (is_file($thumbnailpath)) {
    unlink($thumbnailpath);
}

print "Successful.";