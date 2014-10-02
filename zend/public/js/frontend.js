/**
 * @author Michal Gacki 
 * @description JS for AngularJS
 */

/**
 * Get current date in format Y-m-d H:i:s
 * like in SQL
 * @returns {String}
 */
function getDateNow() {
    var date = new Date();
    return date.getFullYear() + '-' + (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1)) + '-' + (date.getDay() < 10 ? '0' + date.getDay() : date.getDay()) + ' ' + (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':' + (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ':' + (date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds());
    
}

/**
 * Angular Wallet application
 * @type @exp;angular@call;module
 */
var WalletApp = angular.module('Wallet', []);

/**
 * Wallet application
 * Main controller
 * @param angular.$scope $scope
 * @param angular.$http $http
 */
WalletApp.controller('AppController',

    function AppController($scope, $http) {
        
        /**
         * No errors by default
         * @var {String}
         */
        $scope.errorMessage = '';
        
        /**
         * Get items
         * @todo Move this outside controller (hidden dependencies)
         */
        $scope.itemsActive = [];
        $scope.itemsRemoved = []
        
        var items_get = $http.get('https://www.partylover.uk/dev/tests/workangel/devtest_workangel/zend/public/wallet/items/').success(function (response) {
            $scope.itemsActive = response.active;
            $scope.itemsRemoved = response.removed;
        });
        
        /**
         * Total wallet amount
         * @returns {float}
         */
        $scope.grandTotal = function() {
            var total = 0;
            for (var i = 0; i < $scope.itemsActive.length; i++) {
                total += parseFloat($scope.itemsActive[i].amount);
            }
            
            return parseFloat(total);
        };
        
        /**
         * Remove amount from the wallet
         * @param {integer} item_id
         * @returns {undefined}
         */
        $scope.remove = function(item_id) {
            $scope.itemsActive.filter(function (item) {
                if (item.id == item_id) {
                    
                    /**
                     * Remove request
                     */
                    $http.post('https://www.partylover.uk/dev/tests/workangel/devtest_workangel/zend/public/wallet/remove/', {id: item_id});
                    
                    /**
                     * Scope display update
                     */
                    $scope.itemsActive.pop(item);
                    date = new Date();
                    item.dateRemoved = getDateNow();
                    $scope.itemsRemoved.push(item);
                }
            });
        };
        
        /**
         * Add new amount to the wallet
         * @param {object} newItem
         * @returns {undefined}
         */
        $scope.new = function(newItem) {
            
            $scope.errorMessage = '';
            newItem.amount = newItem.amount.replace(',', '.');
            
            /**
             * Check if providen amount is positive
             */
            if (newItem.amount <= 0) {
                $scope.errorMessage = 'Entered amount must be a positive number!';
                return null;
            }
            
            /**
             * Check if providen amount is float
             */
            if (parseFloat(newItem.amount) != newItem.amount) {
                $scope.errorMessage = 'Entered amount must be a number!';
                return null;
            }
            
            var new_item = {
                id: null,
                dateCreated: getDateNow(),
                dateRemoved: null,
                amount: parseFloat(newItem.amount)
            };
            
            /**
             * Add request
             */
            $http.post('https://www.partylover.uk/dev/tests/workangel/devtest_workangel/zend/public/wallet/new/', new_item).success(function(response) {
                new_item.id = parseInt(response.id);
            });
            
            /**
             * Scope display update
             */
            $scope.itemsActive.push(new_item);
            
            newItem.amount = '';
        };
        
    }
);