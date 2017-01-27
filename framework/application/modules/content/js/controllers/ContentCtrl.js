/**
 * @ngdoc controller
 * @name App:LanguageCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')

    .controller('ContentEditCtrl', function($scope, $rootScope, content, $routeSegment, WindowFactory, $routeParams, $filter, $mdConstant, Content){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        //We store the original data here in case the user closes the panel (cancel)
        var origData = angular.copy(content.data);

        WindowFactory.add($scope);
        
        $scope.content = content.data;

        //Keyword creation keys
        $scope.keys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA];

        //Find the content by id in the records array
        var ct = $filter('filter')($scope.$parent.items, {
            id: parseInt($routeParams.id, 10)
        }, true);

        $scope.content.publication_start = new Date($scope.content.publication_start);
        $scope.content.publication_end = new Date($scope.content.publication_end);

        //Change the name in the item list
        $scope.$watch('content.translations[0].translation.name', function(v){
            ct[0].name = v;
        });

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                Content.update({id: $scope.content.id}, $scope.content);
            }

        };

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                Content.update({id: $scope.content.id}, $scope.content);
                WindowFactory.back($scope);
            }

        };

        /**
         * Handler used when the user clicks on the close panel button
         */
        $scope.closeHandler = function () {
            //Reset the data
            angular.copy(origData, ct[0]);
        };

    })

    .controller('ContentCreateCtrl', function($scope, $rootScope, Content, $routeSegment, WindowFactory, $routeParams, languages){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        var isNew = true;
        var $parenScope = $scope.$parent;

        WindowFactory.add($scope);

        $scope.content = {
            enabled: true,
            important: false,
            category_id: $routeParams.page_id,
            translations: languages.data
        };
        $scope.editorInit = false;

        $scope.tinymceOptions = {
            plugins: 'link image code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
        };

        /**
         * Executed after a successful save
         * @param response
         */
        var onSave = function (response) {
            //Set the new ID
            $scope.content.id = response.data.content.id;
            $parenScope.items = response.data.items;
        };

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                if(isNew) {
                    Content.save($scope.content, onSave);
                    isNew = false;
                } else {
                    Content.update({id: $scope.content.id}, $scope.content);
                }
            }

        };

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {

                if(isNew) {
                    Content.save($scope.content, onSave);
                }else {
                    Content.update({id: $scope.content.id}, $scope.content);
                }

                WindowFactory.back($scope);

            }

        };

    })

;