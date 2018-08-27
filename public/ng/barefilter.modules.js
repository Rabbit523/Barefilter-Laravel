'use strict';
var application = angular.module('barefilterApp.application', []);
var dashboard = angular.module('barefilterApp.dashboard', []);
var admin = angular.module('barefilterApp.admin', []);
var members = angular.module('barefilterApp.members', []);
var barefilterApp = angular.module('barefilterApp', [
    'ui.router',
    'barefilterApp.application',
    'barefilterApp.dashboard',
    'barefilterApp.admin',
    'barefilterApp.members'
]);