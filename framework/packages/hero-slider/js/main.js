/***
 * Hero Slider v1.0.0
 * http://codyhouse.co/gem/hero-slider/
 ***/

;(function($) {
    "use strict";

    $.fn.removeClassPrefix = function(prefix) {
        //remove all classes starting with 'prefix'
        this.each(function(i, el) {
            var classes = el.className.split(" ").filter(function(c) {
                return c.lastIndexOf(prefix, 0) !== 0;
            });
            el.className = $.trim(classes.join(" "));
        });
        return this;
    };

    $.fn.heroSlider = function(options) {

        var autoPlayId = 0,
            visibleSlidePosition = 0,
            settings,
            nextSlide,
            prevSlide,
            updateSliderNavigation,
            setAutoplay,
            autoplaySlider,
            uploadVideo,
            checkVideo,
            updateNavigationMarker,
            navigationHandler;

        settings = $.extend({
            primaryNav: ".cd-primary-nav",
            sliderNav: ".cd-slider-nav",
            navigationMarker: ".cd-marker",
            slideDrag: true,
            autoPlayDelay: 5000,
            autoPlay: false
        }, options);

        settings.primaryNav = $(settings.primaryNav);
        settings.sliderNav = $(settings.sliderNav);
        settings.navigationMarker = $(settings.navigationMarker);

        nextSlide = function(visibleSlide, container, pagination, n) {
            visibleSlide
                .removeClass("selected from-left from-right")
                .addClass("is-moving")
                .one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function() {
                    visibleSlide.removeClass("is-moving");
                });

            container
                .children("li")
                .eq(n)
                .addClass("selected from-right")
                .prevAll()
                .addClass("move-left");
            checkVideo(visibleSlide, container, n);
        };

        prevSlide = function(visibleSlide, container, pagination, n) {
            visibleSlide
                .removeClass("selected from-left from-right")
                .addClass("is-moving")
                .one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function() {
                visibleSlide.removeClass("is-moving");
            });

            container
                .children("li")
                .eq(n)
                .addClass("selected from-left")
                .removeClass("move-left")
                .nextAll()
                .removeClass("move-left");
            checkVideo(visibleSlide, container, n);
        };

        updateSliderNavigation = function(pagination, n) {
            var navigationDot = pagination.find(".selected");
            navigationDot.removeClass("selected");
            pagination.find("li").eq(n).addClass("selected");
        };

        setAutoplay = function(wrapper, length, delay) {
            if (wrapper.hasClass("autoplay") || settings.autoPlay) {
                clearInterval(autoPlayId);
                autoPlayId = window.setInterval(function() { autoplaySlider(wrapper, length); }, delay);
            }
        };

        autoplaySlider = function(wrapper, length) {
            if (visibleSlidePosition < length - 1) {
                nextSlide(wrapper.find(".selected"), wrapper, settings.sliderNav, visibleSlidePosition + 1);
                visibleSlidePosition += 1;
            } else {
                prevSlide(wrapper.find(".selected"), wrapper, settings.sliderNav, 0);
                visibleSlidePosition = 0;
            }
            updateNavigationMarker(settings.navigationMarker, visibleSlidePosition + 1);
            updateSliderNavigation(settings.sliderNav, visibleSlidePosition);
        };

        uploadVideo = function(container) {
            container.find(".cd-bg-video-wrapper").each(function() {
                var videoWrapper = $(this),
                    videoUrl,
                    video;
                if (videoWrapper.is(":visible")) {
                    // if visible - we are not on a mobile device
                    videoUrl = videoWrapper.data("video");
                    video = $("<video loop><source src='" +
                        videoUrl + ".mp4' type='video/mp4' /><source src='" +
                        videoUrl + ".webm' type='video/webm' /></video>");
                    video.appendTo(videoWrapper);
                    // play video if first slide
                    if (videoWrapper.parent(".cd-bg-video.selected").length > 0) {
                        video.get(0).play();
                    }
                }
            });
        };

        checkVideo = function(hiddenSlide, container, n) {
            //check if a video outside the viewport is playing - if yes, pause it
            var hiddenVideo = hiddenSlide.find("video"),
                visibleVideo;
            if (hiddenVideo.length > 0) {
                hiddenVideo.get(0).pause();
            }

            //check if the select slide contains a video element - if yes, play the video
            visibleVideo = container.children("li").eq(n).find("video");
            if (visibleVideo.length > 0) {
                visibleVideo.get(0).play();
            }
        };

        updateNavigationMarker = function(marker, n) {
            marker.removeClassPrefix("item").addClass("item-" + n);
        };

        navigationHandler = function(selectedItem, slidesWrapper, slidesNumber) {
            if (!selectedItem.hasClass("selected")) {
                // if it"s not already selected
                var selectedPosition = selectedItem.index(),
                    activePosition = slidesWrapper.find("li.selected").index();

                if (activePosition < selectedPosition) {
                    nextSlide(slidesWrapper.find(".selected"), slidesWrapper, settings.sliderNav, selectedPosition);
                } else {
                    prevSlide(slidesWrapper.find(".selected"), slidesWrapper, settings.sliderNav, selectedPosition);
                }

                //this is used for the autoplay
                visibleSlidePosition = selectedPosition;

                updateSliderNavigation(settings.sliderNav, selectedPosition);
                updateNavigationMarker(settings.navigationMarker, selectedPosition + 1);
                //reset autoplay
                setAutoplay(slidesWrapper, slidesNumber, settings.autoPlayDelay);
            }
        };

        return this.each(function() {

            var slidesWrapper = $(this),
                slidesNumber = slidesWrapper.children("li").length;

            //upload videos (if not on mobile devices)
            uploadVideo(slidesWrapper);

            //autoplay slider
            setAutoplay(slidesWrapper, slidesNumber, settings.autoPlayDelay);

            //on mobile - open/close primary navigation clicking/tapping the menu icon
            slidesWrapper.on("click", function(event) {
                if ($(event.target).is(".cd-primary-nav")) {
                    $(this).children("ul").toggleClass("is-visible");
                }
            });

            //change visible slide
            settings.sliderNav.on("click", "li", function(event) {
                event.preventDefault();
                navigationHandler($(this), slidesWrapper, slidesNumber);
            });

        });

    };

})(jQuery);
