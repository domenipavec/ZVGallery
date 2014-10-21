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
    var state = {
        path: '',
        entries_files: [],
        entries_dirs: [],
        sort_type: 'auto',
        sort_reverse: false
    };
    
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
        
    var parse_entries = function(list) {
        state.entries_files = [];
        state.entries_dirs = [];
        angular.forEach(list, function(entry) {
            var parsed = {
                thumbnail: 'backend.php?c=thumbnail&p='+entry.fullpath,
                link: entry_link(entry),
                name: entry.name,
                glyph: entry_glyph(entry),
                date: entry.date
            };
            if (entry.type == 'dir') {
                state.entries_dirs.push(parsed);
            } else {
                state.entries_files.push(parsed);
            }
        });
        sort_entries();
    };
    
    var combine_entries = function() {
        state.entries = [].concat(state.entries_dirs).concat(state.entries_files);
    };
    
    var sort_entries = function() {
        if (state.sort_type == 'auto') {
            state.entries_files = $filter('orderBy')(state.entries_files, 'date', state.sort_reverse);
            state.entries_dirs = $filter('orderBy')(state.entries_dirs, 'name', state.sort_reverse);
        } else {
            state.entries_files = $filter('orderBy')(state.entries_files, state.sort_type, state.sort_reverse);
            state.entries_dirs = $filter('orderBy')(state.entries_dirs, state.sort_type, state.sort_reverse);
        }
        combine_entries();
    };
    
    return {
        get: function(callback) {
            var path = pathfun();
            if (state.path != path) {
                $http.get('backend.php?c=list&p='+path).success(function(data) {
                    $rootScope.error = '';
                    if (data.success == true) {
                        parse_entries(data.entries);
                        callback(state);
                    } else {
                        $rootScope.error = data.error;
                    }
                });
            } else {
                callback(state);
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
        },
        toolbar: {
            options: ['auto', 'name', 'date'],
            click: function(type) {
                if (state.sort_type == type) {
                    state.entries_files.reverse();
                    state.entries_dirs.reverse();
                    combine_entries();
                    state.sort_reverse = !state.sort_reverse;
                } else {
                    state.sort_type = type;
                    state.sort_reverse = false;
                    sort_entries();
                }
                this.class.auto = '';
                this.class.name = '';
                this.class.date = '';
                this.class[state.sort_type] = state.sort_reverse?'glyphicon-sort-by-attributes-alt':'glyphicon-sort-by-attributes';
            },
            class: {
                auto: 'glyphicon-sort-by-attributes',
                name: '',
                date: ''
            }
        }
    };
});
