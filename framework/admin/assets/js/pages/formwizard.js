var yimaPage = function() {
    var initilize = function() {
        jQuery(function($) {
            $('#simplewizard').wizard();
            $('#tabbedwizard').wizard();

            $('#btnWizardPrev').on('click', function() {
                $('#myWizard').wizard('previous');
            });
            $('#btnWizardNext').on('click', function() {
                $('#myWizard').wizard('next', 'foo');
            });
            $('#btnWizardStep').on('click', function() {
                var item = $('#myWizard').wizard('selectedItem');
                console.log(item.step);
            });
            $('#btnWizardSetStep').on('click', function() {
                $('#myWizard').wizard('selectedItem', {
                    step: 3
                });
            });
            $('#btnWizardSetStepByName').on('click', function() {
                $('#myWizard').wizard('selectedItem', {
                    step: "template"
                });
            });

            var emailSetupSamplePane = '<div class="bg-warning alert">' +
                '	<h4>Setup Message</h4>' +
                '	<p>Soko radicchio bunya nuts gram dulse silver beet parsnip napa cabbage ' +
                '	lotus root sea lettuce brussels sprout cabbage. Catsear cauliflower garbanzo yarrow ' +
                '	salsify chicory garlic bell pepper napa cabbage lettuce tomato kale arugula melon ' +
                '	sierra leone bologi rutabaga tigernut. Sea lettuce gumbo grape kale kombu cauliflower ' +
                '	salsify kohlrabi okra sea lettuce broccoli celery lotus root carrot winter purslane ' +
                '	turnip greens garlic. JÃ­cama garlic courgette coriander radicchio plantain scallion ' +
                '	cauliflower fava bean desert raisin spring onion chicory bunya nuts. Sea lettuce water ' +
                '	spinach gram fava bean leek dandelion silver beet eggplant bush tomato. </p>' +
                '</div>';

            $('#btnWizardAddSteps').on('click', function() {
                $('#myWizard').wizard('addSteps', 2, [
                    {
                        badge: '',
                        label: 'Setup',
                        pane: emailSetupSamplePane
                    }
                ]);
            });

            $('#btnWizardRemoveStep').on('click', function() {
                $('#myWizard').wizard('removeSteps', 1, 1);
            });

            $('#btnWizardDestroy').click(function() {
                var markup = $('#myWizard').wizard('destroy');
                console.log(markup);
                $(this).closest('.content-body').append(markup);
                $('#myWizard').wizard();
            });
        });
    }

    return {
        init: initilize
    }
}();

jQuery(document).ready(function() {
    if (yima.isAngular() === false) {
        yimaPage.init();
    }
});