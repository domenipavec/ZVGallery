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

zvg.factory('$pathList', function ($route, $rootScope, $http, $location, $filter) {
    var loaded_path = '';
    var loaded_entries = [];
    
    var pathfun = function() {
        var path = $route.current.params['path'];
        if (typeof path !== 'string' || path.length < 2) {
            path = '/';
        } else {
            path = path.replace(/\/+$/,'');
        }
        return path;
    };
    
    var entry_link = function(entry) {
        if (entry.type == 'dir') {
            return '#/list?path=' + entry.fullpath;
        } else if (/image\/?.*/.test(entry.type)) {
            return '#/image?path=' + pathfun() + '&file=' + entry.file;
        } else if (/video\/?.*/.test(entry.type)) {
            return '#/video?path=' + pathfun() + '&file=' + entry.file;
        } else {
            return '#';
        }
    };
    
    var entry_glyph = function(entry) {
        var glyph = 'glyphicon-file';
        var types = {
            'glyphicon-picture': /image\/?.*/,
            'glyphicon-film': /video\/?.*/,
            'glyphicon-folder-close': /dir/
        };
        angular.forEach(types, function(value, key) {
            if (value.test(entry.type)) {
                glyph = key;
            }
        });
        return glyph;
    };
    
    var entry_sortname = function(entry) {
        if (entry.type == 'dir') {
            return 'd' + entry.name;
        } else {
            return 'f' + entry.name;
        }
    };
    
    var parse_entries = function(list) {
        var parsed = [];
        angular.forEach(list, function(entry) {
            parsed.push({
                thumbnail: 'backend.php?c=thumbnail&p='+entry.fullpath,
                link: entry_link(entry),
                name: entry.name,
                glyph: entry_glyph(entry),
                sortname: entry_sortname(entry)
            });
        });
        return sort_entries(parsed);
    };
    
    var sort_entries = function(list) {
        return $filter('orderBy')(list, 'sortname');
    };
    
    return {
        get: function(callback) {
            var path = pathfun();
            if (loaded_path != path) {
                $http.get('backend.php?c=list&p='+path).success(function(data) {
                    $rootScope.error = '';
                    if (data.success == true) {
                        loaded_entries = parse_entries(data.entries);
                        callback(loaded_entries);
                    } else {
                        $rootScope.error = data.error;
                    }
                });
            } else {
                callback(loaded_entries);
            }
        },
        path: pathfun,
        file: function() {
            var file = $route.current.params['file'];
            if (typeof file !== 'string' || file.length < 1) {
                file = '';
            }
            return file;
        },
        path_file: function() {
            return this.path() + '/' + this.file();
        }
    };
});
