/**
 * Created by Miguel on 03-Feb-17.
 */

angular.module('app')
    .filter('slugify', function () {
        return function (input) {
            if (!input)
                return;

            // make lower case and trim
            var slug = input.toLowerCase().trim();

            // replace invalid chars with spaces
            slug = slug.replace(/[^a-z0-9\s-]/g, '');

            // replace multiple spaces or hyphens with a single hyphen
            slug = slug.replace(/[\s-]+/g, '-');

            return slug;
        };
    });