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

$status = array('success' => false, 'error' => 'Invalid path.');

$dir_path = $_ZVG['gallery_folder'] . $_GET['p'];
if (get_filetype($dir_path) == 'dir') {
    $status['success'] = true;
    $status['entries'] = array();
    
    $handle = opendir($dir_path);
    while (false !== ($entry = readdir($handle))) {
        if ($entry == "." || $entry == "..") {
            continue;
        }
        
        $fullpath = rtrim($_GET['p'], '/') . '/' . $entry;
        $gallerypath = $_ZVG['gallery_folder'] . $fullpath;
        
        $type = get_filetype($gallerypath);
        if (!preg_match('/image\/?.*|video\/?.*|dir/', $type)) {
            continue;
        }
        
        $date = '';
        if (function_exists('exif_read_data')) {
            if (($exif = @exif_read_data($gallerypath, 'EXIF')) !== FALSE) {
                if (array_key_exists('DateTimeOriginal', $exif)) {
                    $date = $exif['DateTimeOriginal'];
                }
            }
            if ($date == '') {
                if (($exif = @exif_read_data($gallerypath, 'IFD0')) !== FALSE) {
                    if (array_key_exists('DateTime', $exif)) {
                        $date = $exif['DateTime'];
                    }
                }
            }
        }
        if ($date == '') {
            $date = date('Y:m:d H:i:s', filemtime($gallerypath));
        }
        
        $name = pathinfo($gallerypath);
        
        $status['entries'][] = array(
            'name' => $name['filename'],
            'file' => $name['basename'],
            'fullpath' => $fullpath,
            'type' => $type,
            'date' => $date
        );
    }
    closedir($handle);
}

print json_encode($status);
