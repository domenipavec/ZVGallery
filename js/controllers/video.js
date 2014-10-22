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
    $routeProvider.when('/video', {
        templateUrl: 'templates/video.html',
        controller: 'VideoController'
    });
});

zvg.controller('VideoController', function($scope, $pathList) {
    var state = null;
    $pathList.get(function(s) {
        state = s;
        if (state.current === null) {
            $rootScope.error = "Not a valid file.";
        } else {
            $scope.show = true;
            
            var player = _V_("video-player");
            player.ready(function() {
                player.pause();
                $("#video-player-source").attr("src", state.entries_files[state.current].video);
                player.load();
            });
            
            $scope.$on('$destroy', function() {
                player.dispose();
            });
        }
        $scope.nextImage = $pathList.next;
        $scope.previousImage = $pathList.previous;
    });
});
