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

zvg.directive('zvgControls', function() {
    return {
        templateUrl: 'templates/controls.html'
    };
});

zvg.controller('ControlsController', function($scope, $pathList, $document, $rootScope) {
    var state = null;
    $pathList.get(function(s) {
        state = s;
        if (state.current !== null) {
            $scope.entry = state.entries_files[state.current];
            $scope.isFirst = state.current == 0;
            $scope.isLast = state.current == (state.entries_files.length - 1);
            $scope.next = $pathList.next;
            $scope.previous = $pathList.previous;
            $scope.last = $pathList.last;
            $scope.first = $pathList.first;
        }
    });
    
    // bind keys
    angular.element($document).unbind("keydown");
    angular.element($document).bind("keydown", function(event) {
        $rootScope.$apply(function() {
            if (event.which == 39) { // right key
                $pathList.next();
            } else if (event.which == 37) { // left key
                $pathList.previous();
            }
        });
    });
});
