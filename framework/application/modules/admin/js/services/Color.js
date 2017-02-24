/**
 * @ngdoc service
 * @name app:Color
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Color', function(){

    /**
     * Check if the background is dark or light
     *
     * @link http://stackoverflow.com/questions/1855884/determine-font-color-based-on-background-color/1855903#1855903
     * @param colorArray
     * @returns {boolean}
     */
    this.isLight = function(colorArray) {

        if(!colorArray || colorArray.length !== 3) {
            colorArray = [0,0,0];
        }

        var r = colorArray[0],
            g = colorArray[1],
            b = colorArray[2];

        // Counting the perceptive luminance
        // human eye favors green color...
        var a = 1 - (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return (a < 0.5);

    };

});

