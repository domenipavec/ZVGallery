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

// Hardcoded for now
$users = array('user' => 'pass');

$status = array('success' => false);

$status['test'] = $_POST;

if (array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {
    if (array_key_exists($_POST['username'], $users) && $users[$_POST['username']] == $_POST['password']) {
        $status['success'] = true;
        $status['username'] = $_POST['username'];
        
        $_SESSION['username'] = $_POST['username'];
    }
}

print json_encode($status);