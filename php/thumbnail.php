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

function create_thumbnail($source, $destination) {
    global $_ZVG;
    
    $cmd = 'convert -define jpeg:size=';
    $cmd .= 2*$_ZVG['thumbnail_width'];
    $cmd .= 'x';
    $cmd .= 2*$_ZVG['thumbnail_height'];
    $cmd .= ' "';
    $cmd .= $source;
    $cmd .= '" -thumbnail \'';
    $cmd .= $_ZVG['thumbnail_width'];
    $cmd .= 'x';
    $cmd .= $_ZVG['thumbnail_height'];
    $cmd .= '>\' -background ';
    $cmd .= $_ZVG['thumbnail_background'];
    $cmd .= ' -gravity center -extent ';
    $cmd .= $_ZVG['thumbnail_width'];
    $cmd .= 'x';
    $cmd .= $_ZVG['thumbnail_height'];
    $cmd .= ' -strip -quality ';
    $cmd .= $_ZVG['thumbnail_quality'];
    $cmd .= ' "';
    $cmd .= $destination;
    $cmd .= '"';
    exec($cmd);
}

function get_thumbnail($fullpath) {
    global $_ZVG;
    
    $gallerypath = $_ZVG['gallery_folder'] . $fullpath;
    $type = get_filetype($gallerypath);
    if ($type == 'unknown') {
        return FALSE;
    }
    
    $thumbnailpath = $_ZVG['tmp_folder'] . '/thumbnails' . rtrim($fullpath, '/') . '.jpg';

    // make thumbnail subdir if does not exist
    $dir = pathinfo($thumbnailpath);
    $dir = $dir['dirname'];
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    if (!is_file($thumbnailpath)) {
        // image
        if (preg_match('/image\/?.*/', $type)) {
            create_thumbnail($_ZVG['gallery_folder'] . $fullpath, $thumbnailpath);
        }
        // video
        else if (preg_match('/video\/?.*/', $type)) {
            if (!is_dir($_ZVG['tmp_folder'] . '/tmp')) {
                mkdir($_ZVG['tmp_folder'] . '/tmp', 0777, true);
            }
            $tmppath = $_ZVG['tmp_folder'] . '/tmp/';
            $tmppath .= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
            $tmppath .= '.png';
            exec("ffmpeg -i \"$gallerypath\" -r 1 -t 00:00:01 -f image2 \"$tmppath\"");
            create_thumbnail($tmppath, $thumbnailpath);
            unlink($tmppath);
        }
        // folder
        else if ($type == 'dir') {
            $i = 0;
            $images = array();
            $handle = opendir($gallerypath);
            while (false !== ($entry = readdir($handle)) && $i < 4) {
                if ($entry == "." || $entry == "..") {
                    continue;
                }
                $thumb = get_thumbnail($fullpath . "/" . $entry);
                if ($thumb !== FALSE) {
                    $images[] = $thumb;
                    $i++;
                }
            }
            closedir($handle);
            
            if (count($images) > 0) {
                $cmd = 'montage "';
                $cmd .= implode('" "', $images);
                $cmd .= '" -quality ';
                $cmd .= $_ZVG['thumbnail_quality'];
                $cmd .= ' -background "';
                $cmd .= $_ZVG['thumbnail_dir_background'];
                $cmd .= '" -tile 2x2 -geometry ';
                $cmd .= $_ZVG['thumbnail_width']/2-4;
                $cmd .= 'x';
                $cmd .= $_ZVG['thumbnail_height']/2-4;
                $cmd .= '+2+2 "';
                $cmd .= $thumbnailpath;
                $cmd .= '"';
            } else {
                $cmd = 'convert -size ';
                $cmd .= $_ZVG['thumbnail_width'];
                $cmd .= 'x';
                $cmd .= $_ZVG['thumbnail_height'];
                $cmd .= ' xc:';
                $cmd .= $_ZVG['thumbnail_dir_background'];
                $cmd .= ' "';
                $cmd .= $thumbnailpath;
                $cmd .= '"';
            }
            exec($cmd);
        }
        else {
            return FALSE;
        }
    }
    
    return $thumbnailpath;
}


if (($thumbnailpath = get_thumbnail($_GET['p'])) === FALSE) {
    die("Invalid filetype.");
}

header("X-Sendfile: $thumbnailpath");
header("Content-type: image/jpeg");
header("Cache-control: public, max-age=3600");
header("Expires: " . gmdate('D, d M Y H:i:s', time() + 3600) . " GMT");
header_remove('Pragma');
