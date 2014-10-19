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

zvg.config(function($routeProvider) {
    $routeProvider.when('/list', {
        templateUrl: 'templates/list.html',
        controller: 'ListController'
    });
});

zvg.controller('ListController', function($scope, $routeParams, $http, $filter) {
    var path = $routeParams['path'];
    if (path == undefined) {
        path = '/';
    }
    
    $scope.breadcrumbs = [{name: $filter('translate')('Home'), path: '/'}];
    var current_path = '';
    angular.forEach(path.split("/"), function(value) {
        if (value.length < 1) {
            return;
        }
        current_path += '/' + value;
        $scope.breadcrumbs.push({name:value, path:current_path});
    });
    $scope.activeBreadcrumb = $scope.breadcrumbs.pop().name;
    
    $http.get('backend.php?c=list&p='+path).success(function(data) {
        $scope.error = '';
        if (data.success == true) {
            $scope.entries = data.entries;
        } else {
            $scope.error = data.error;
        }
    });
    
    $scope.type2glyph = function(type) {
        var glyph = 'glyphicon-file';
        var types = {
            'glyphicon-picture': /image\/?.*/,
            'glyphicon-film': /video\/?.*/,
            'glyphicon-folder-close': /dir/
        };
        angular.forEach(types, function(value, key) {
            if (value.test(type)) {
                glyph = key;
            }
        });
        return glyph;
    };
    
    $scope.entrylink = function(entry) {
        if (entry.type == 'dir') {
            return '#/list?path=' + entry.fullpath;
        } else if (/image\/?.*/.test(entry.type)) {
            return '#/image?path=' + entry.fullpath;
        } else if (/video\/?.*/.test(entry.type)) {
            return '#/video?path=' + entry.fullpath;
        } else {
            return '#';
        }
    };
});