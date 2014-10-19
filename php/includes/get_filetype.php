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

function Mime($path)
{
    $result = false;

    

    return $result;
}

function get_filetype($path) {
    if (is_dir($path)) {
        return 'dir';
    }
    
    if (is_file($path) === true) {
    
        if (function_exists('finfo_open') === true) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if (is_resource($finfo) === true) {
                $result =  finfo_file($finfo, $path);
            }
            finfo_close($finfo);
            return $result;
        }

        if (function_exists('mime_content_type') === true) {
            return preg_replace('~^(.+);.*$~', '$1', mime_content_type($path));
        }

        if (function_exists('exif_imagetype') === true) {
            return image_type_to_mime_type(exif_imagetype($path));
        }
    }
    
    return 'unknown';
}