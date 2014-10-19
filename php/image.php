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

$imagepath = $_ZVG['tmp_folder'] . '/images' . $fullpath;

// make image subdir if does not exist
$dir = pathinfo($imagepath);
$dir = $dir['dirname'];
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

if (!is_file($imagepath)) {
    $cmd = 'convert  "';
    $cmd .= $gallerypath;
    $cmd .= '" -resize ';
    $cmd .= $_ZVG['image_width'];
    $cmd .= 'x';
    $cmd .= $_ZVG['image_height'];
    $cmd .= '\\> "';
    $cmd .= $imagepath;
    $cmd .= '"';
    exec($cmd);
}


header("X-Sendfile: $imagepath");
header("Content-type: $type");