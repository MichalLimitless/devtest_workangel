/**
 * @author Michal Gacki 
 * @description JS for AngularJS
 */
var WalletApp = angular.module('Wallet', []);

WalletApp.controller(
    "AppController",
    function( $scope, $http, transformRequestAsFormPost ) {
 
        $scope.cfdump = "";
 
        var request = $http({
            method: "post",
            url: "https://www.partylover.uk/dev/tests/workangel/devtest_workangel/zend/public/wallet/new/",
            transformRequest: transformRequestAsFormPost,
            data: {
                id: 4,
                name: "Kim",
                status: "Best Friend"
            }
        });
 
        request.success(
            function( html ) {
                $scope.cfdump = html;
            }
        );
    }
);

WalletApp.factory(
    "transformRequestAsFormPost",
    function() {

        function transformRequest( data, getHeaders ) {
 
            var headers = getHeaders();
            headers[ "Content-type" ] = "application/x-www-form-urlencoded; charset=utf-8";
            return( serializeData( data ) );

        }

        return( transformRequest );
 
 
        function serializeData( data ) {
 
            if ( ! angular.isObject( data ) ) {
                return( ( data == null ) ? "" : data.toString() );
            }
            var buffer = [];

            for ( var name in data ) {
                if ( ! data.hasOwnProperty( name ) ) {
                    continue;
                }

                var value = data[ name ];

                buffer.push(
                    encodeURIComponent( name ) +
                    "=" +
                    encodeURIComponent( ( value == null ) ? "" : value )
                );

            }

            var source = buffer
                .join( "&" )
                .replace( /%20/g, "+" )
            ;
            return( source );

        }
    }
);
