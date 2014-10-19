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

if (!array_key_exists('c', $_GET)) {
    die("You need to specify command.");
}

if (preg_match('/[a-zA-Z0-9\-]+/', $_GET['c'], $matches) != 1 || $_GET['c'] != $matches[0]) {
    die("Invalid command.");
}

$filename = realpath(getcwd() . "/php/" . $_GET['c'] . ".php");
if (!$filename) {
    die("Command does not exist.");
}

define('ZVG_PHP', true);
session_start();

require($filename);