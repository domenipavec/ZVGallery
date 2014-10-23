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

zvg.config(function($translateProvider) {
    $translateProvider.translations('en', {
        'ZVGallery': 'ZVGallery',
        'Login': 'Login',
        'Logout': 'Logout',
        'Username': 'Username',
        'Password': 'Password',
        'Invalid path.': 'Invalid path.',
        'You need to be logged in to access this.': 'You need to be logged in to access this.',
        'Home': 'Home',
        'Sort': 'Sort',
        'sort-name': 'by Name',
        'sort-date': 'by Date',
        'sort-auto': 'Automatic',
        'Not a valid file.': 'Not a valid file.',
        'First': 'First',
        'Previous': 'Previous',
        'Next': 'Next',
        'Last': 'Last',
        'Download': 'Download',
        'Rotate CW': 'Rotate CW',
        'Rotate CCW': 'Rotate CCW',
        'Fullscreen': 'Fullscreen'
    });
    
    $translateProvider.translations('sl', {
        'ZVGallery': 'ZVGalerija',
        'Login': 'Prijava',
        'Logout': 'Odjavi me',
        'Username': 'Uporabniško ime',
        'Password': 'Geslo',
        'Invalid path.': 'Neveljavna pot.',
        'You need to be logged in to access this.': 'Za dostop se morate prijaviti.',
        'Home': 'Domov',
        'Sort': 'Razvrsti',
        'sort-name': 'po Imenu',
        'sort-date': 'po Datumu',
        'sort-auto': 'Samodejno',
        'Not a valid file.': 'Datoteka ni veljavna.',
        'First': 'Prva',
        'Previous': 'Prejšnja',
        'Next': 'Naslednja',
        'Last': 'Zadnja',
        'Download': 'Prenesi na disk',
        'Rotate CW': 'Zasukaj desno',
        'Rotate CCW': 'Zasukaj levo',
        'Fullscreen': 'Čez cel zaslon'
    });
    
    $translateProvider.registerAvailableLanguageKeys(['en', 'sl'], {
        'en_US': 'en',
        'en_UK': 'en',
        'sl': 'sl'
    });
    
    $translateProvider.determinePreferredLanguage();
});
