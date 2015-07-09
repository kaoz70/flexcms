/*global Class, Events, Options, $, $$, Element, Drag, Tips, Slider, Fx */

/**
 * Image Manipulation Class
 * @type {Class}
 */
var ImageManipulation = new Class({

    Implements: [ Events, Options],

    options: {
        maskOpacity: 0.5,
        cropWidth: 600,
        cropHeight: 400,
        wrapperWidth: 950,
        wrapperHeight: 600,
        controlsZindex: 11,

        //Starting image coords / dimensions
        top: 0,
        left: 0,
        width: 0,
        height: 0,
        scale: 100,

        onComplete: function () {
            "use strict";
        },
        onDestroy: function () {
            "use strict";
        },
        keepRatio: true,
        wrapperClassName: 'cropImageWrapper',
        maskClassName: 'cropImageMask'
    },

    origWidth: 0,
    origHeight: 0,
    origImageWidth: 0,
    origImageHeight: 0,
    handles: {},

    /**
     * Initializes the class
     * @param target
     * @param options
     */
    initialize: function (target, options) {
        "use strict";

        var wrapperSize,
            imageSize = {},
            origImageSize = target.getSize();

        //target is the image we want to crop
        if (typeof target === 'object') {
            this.target = target;
        } else {
            this.target = $(target);
        }

        //just an idiot check before anything else, if target is not an image, just exits.
        /*if (!this.target.match('img')) {
        return false;
        }*/

        this.setOptions(options);

        this.wrapper = new Element('div', {
            'class': this.options.wrapperClassName,
            styles: {
                'position': 'relative',
                'width': this.options.wrapperWidth,
                'height': this.options.wrapperHeight,
                'overflow': 'hidden'
            }
        }).wraps(this.target);

        wrapperSize = this.wrapper.getSize();

        this.maskWrapper = new Element('div', {
            'id': 'maskWrapper',
            styles: {
                'width': wrapperSize.x,
                'height': wrapperSize.y
            }
        }).inject(this.wrapper);

        this.origImageWidth = origImageSize.x;
        this.origImageHeight = origImageSize.y;

        if (this.options.width !== 0 && this.options.height !== 0) {
            imageSize.x = this.options.width;
            imageSize.y = this.options.height;
        } else {
            imageSize = origImageSize;
        }

        target.removeProperties('width', 'height');

        this.width = imageSize.x;
        this.height = imageSize.y;
        this.top = this.options.top;
        this.left = this.options.left;

        this.origWidth = this.width;
        this.origHeight = this.height;

        this.createMask();
        this.createControls();

        this.drag = new Drag.Move(this.target, {
            handle: this.maskWrapper,
            onDrag: function (el) {
                this.handles.container.position({relativeTo: el});
            }.bind(this)
        });

        this.target.setStyles({
            'width': imageSize.x,
            'height': imageSize.y
        });

        this.handles.container.setStyles({
            'width': imageSize.x,
            'height': imageSize.y
        });

    },

    /**
     * Creates the mask that goes over the image and lets us see the crop size
     */
    createMask: function () {
        "use strict";

        var cropTop = (this.options.wrapperHeight - this.options.cropHeight) / 2,
            cropLeft = (this.options.wrapperWidth - this.options.cropWidth) / 2,
            maskCoord;

        //Create Mask
        this.crop = new Element('div', {
            'id': 'cropImageMask',
            'class': 'mask',
            styles: {
                'top': cropTop,
                'left': cropLeft,
                'width': this.options.cropWidth,
                'height': this.options.cropHeight
            }
        }).inject(this.maskWrapper);

        maskCoord = this.crop.getCoordinates(this.maskWrapper);

        new Element('div', {
            'class': 'mask left',
            styles: {
                'top': 0,
                'left': 0,
                'width': maskCoord.left,
                'bottom': 0
            }
        }).inject(this.maskWrapper);

        new Element('div', {
            'class': 'mask top',
            styles: {
                'top': 0,
                'left': maskCoord.left,
                'width': maskCoord.width,
                'height': cropTop
            }
        }).inject(this.maskWrapper);

        new Element('div', {
            'class': 'mask',
            styles: {
                'top': cropTop + this.options.cropHeight,
                'left': maskCoord.left,
                'width': maskCoord.width,
                'bottom': 0
            }
        }).inject(this.maskWrapper);

        new Element('div', {
            'class': 'mask right',
            styles: {
                'top': 0,
                'left': maskCoord.left + maskCoord.width,
                'right': 0,
                'bottom': 0
            }
        }).inject(this.maskWrapper);

        this.target.setPosition({
            x: cropLeft - this.options.left,
            y: cropTop - this.options.top
        });

        // Create handles
        this.handles.container = new Element('div', {
            'id': 'handle_container',
            styles: {
                'top': cropTop - this.options.top,
                'left': cropLeft - this.options.left,
                'width': this.options.width,
                'height': this.options.height
            }
        }).inject(this.maskWrapper);

        //Disabled until I can make other handles
        /*this.handles.top_left = new Element('div', {
            'class': 'handle top left'
        }).inject(this.handles.container);

        this.handles.top_right = new Element('div', {
            'class': 'handle top right'
        }).inject(this.handles.container);

        this.handles.bottom_left = new Element('div', {
            'class': 'handle bottom left'
        }).inject(this.handles.container);*/

        this.handles.bottom_right = new Element('div', {
            'id': 'handle',
            'class': 'handle bottom right',
            events: {
                mousedown: function () {
                    this.drag.detach();
                }.bind(this),
                mouseup: function () {
                    this.drag.attach();
                }.bind(this)
            }
        }).inject(this.handles.container);

        this.handles.container.makeResizable({
            handle: this.handles.bottom_right,
            onDrag: function (el) {

                var elSize = el.getSize(),
                    size,
                    options;

                if (this.options.width === 0 || this.options.height === 0) {
                    size = this.calculateAspectRatioFit(this.width, this.height, elSize.x, elSize.y);
                } else {
                    size = this.calculateAspectRatioFit(this.options.width, this.options.height, elSize.x, elSize.y);
                }

                options = {
                    'width': size.width,
                    'height': size.height
                }

                el.setStyles(options);
                this.target.setStyles(options);

            }.bind(this),
            onComplete: function () {
                this.drag.attach();
            }.bind(this)
        });

    },

    /**
     * Creates the controls for the image manipulation
     */
    createControls: function () {
        "use strict";

        var controlBar,
            tips;

        controlBar = new Element('div', {
            'class': 'control_bar'
        });

        controlBar.injectBefore(this.wrapper);

        this.createScaleSlider(controlBar);
        this.createAutoAdjustButtom(controlBar);
        this.createHorizontaAdjustButton(controlBar);
        this.createVerticalAdjustButton(controlBar);
        this.createResetButton(controlBar);
        this.createApplyButton();

        tips = new Tips($$('div.control'), {
            fixed: false
        });

        //console.log(this);

        new Element('div', {
            'class': 'dimensions',
            'text': 'Dimensiones de corte: ' + this.options.cropWidth + ' x ' + this.options.cropHeight,
            styles: {
                'position': 'absolute',
                'z-index': this.options.controlsZindex
            }
        }).inject(controlBar);

    },

    /**
     * Creates the scale slider
     * @param controlBar
     */
    createScaleSlider: function (controlBar) {
        "use strict";

        var knob,
            timer,
            up,
            slider,
            count = 1;

        knob = new Element('div', {
            'class': 'knob'
        });

        slider = new Element('div', {
            'class': 'slider',
            'id': 'slider',
            styles: {
                'position': 'absolute',
                'z-index': this.options.controlsZindex
            }
        });

        knob.inject(slider);
        slider.inject(controlBar);

        this.slider = new Slider(slider, knob, {
            range: [-100, 100],
            initialStep: this.options.scale,
            steps: slider.getDimensions().x, //Set the step per pixel (slider width)
            onChange: function (value) {
                this.scale = value;
            }.bind(this),
            onComplete: function () {
                this.set(0);
            }
        });

        up = function () {

            var size = this.target.getSize(),
                multiplier = 500,
                maxWidth = Math.ceil(size.x + (count * (this.scale / multiplier))),
                maxHeight = Math.ceil(size.y + (count * (this.scale / multiplier))),
                resultSize,
                options;

            if (maxWidth < 50 || maxHeight < 50) {
                count = 1;
                return;
            }

            resultSize = this.calculateAspectRatioFit(size.x, size.y, maxWidth, maxHeight);

            options = {
                'width': resultSize.width,
                'height': resultSize.height
            };

            this.handles.container.setStyles(options);
            this.target.setStyles(options);

            count++;

        }.bind(this);

        knob.addEvents({
            'mousedown': function (event) {
                event.preventDefault();
                timer = up.periodical(30);
            },
            'mouseup': function () {
                $clear(timer);
                count = 1;
            }
        });

        knob.addEvent('mousedown', function (ev) {
            console.log(this.scale);
        }.bind(this));

    },

    /**
     * Resize arbitary width x height region to fit inside another region.
     * Source: http://opensourcehacker.com/2011/12/01/calculate-aspect-ratio-conserving-resize-for-images-in-javascript/
     *
     * Conserve aspect ratio of the orignal region. Useful when shrinking/enlarging
     * images to fit into a certain area.
     *
     * @param {Number} srcWidth Source area width
     * @param {Number} srcHeight Source area height
     * @param {Number} maxWidth Fittable area maximum available width
     * @param {Number} srcWidth Fittable area maximum available height
     * @return {Object} { width, heigth }
     *
     */
    calculateAspectRatioFit: function (srcWidth, srcHeight, maxWidth, maxHeight) {
        "use strict";
        var ratio = [maxWidth / srcWidth, maxHeight / srcHeight ];
        ratio = Math.min(ratio[0], ratio[1]);
        return {
            width : srcWidth * ratio,
            height : srcHeight * ratio
        };
    },

    /**
     * Creates the Button that fits the image's best size to the crop area
     * @param controlBar
     */
    createAutoAdjustButtom: function (controlBar) {
        "use strict";

        this.placeButton = new Element('div', {
            'class': 'placeImage control',
            'rel': 'ajuste automático',
            styles: {
                'position': 'absolute',
                'z-index': this.options.controlsZindex
            },
            events: {
                click: function () {

                    var imageAspectRatio,
                        containerAspectRatio,
                        morphTarget,
                        morphHandles,
                        maskCoord,
                        resultSize,
                        options,
                        morphOptions = {
                            duration: 1000,
                            transition: Fx.Transitions.Cubic.easeOut
                        };

                    imageAspectRatio = this.height / this.width;
                    containerAspectRatio = this.options.cropHeight / this.options.cropWidth;

                    morphTarget = new Fx.Morph(this.target, morphOptions);
                    morphHandles = new Fx.Morph(this.handles.container, morphOptions);

                    maskCoord = this.crop.getCoordinates(this.wrapper);

                    // figure out which dimension hits first and set that to match
                    if (imageAspectRatio > containerAspectRatio) {
                        resultSize = this.calculateAspectRatioFit(this.width, this.height, this.options.cropWidth, 20000);
                    } else {
                        resultSize = this.calculateAspectRatioFit(this.width, this.height, 20000, this.options.cropHeight);
                    }

                    options = {
                        'height': resultSize.height,
                        'width': resultSize.width,
                        'top': maskCoord.top,
                        'left': maskCoord.left
                    };

                    morphTarget.start(options);
                    morphHandles.start(options);

                }.bind(this)
            }
        });

        this.placeButton.inject(controlBar, 'top');

    },

    /**
     * Creates the Button that adjusts the image horizontally
     * @param controlBar
     */
    createHorizontaAdjustButton: function (controlBar) {
        "use strict";

        this.horizontalPlaceButton = new Element('div', {
            'class': 'h_placeImage control',
            'rel': 'ajuste horizontal',
            styles: {
                'position': 'absolute',
                'z-index': this.options.controlsZindex
            },
            events: {
                click: function () {

                    var morphTarget,
                        morphHandles,
                        maskCoord,
                        resultSize,
                        options,
                        morphOptions = {
                            duration: 1000,
                            transition: Fx.Transitions.Cubic.easeOut
                        };

                    morphTarget = new Fx.Morph(this.target, morphOptions);
                    morphHandles = new Fx.Morph(this.handles.container, morphOptions);

                    maskCoord = this.crop.getCoordinates(this.wrapper);

                    resultSize = this.calculateAspectRatioFit(this.width, this.height, this.options.cropWidth, 20000);

                    options = {
                        height: resultSize.height,
                        'width': resultSize.width,
                        'left': maskCoord.left
                    };

                    morphTarget.start(options);
                    morphHandles.start(options);

                }.bind(this)
            }
        });

        this.horizontalPlaceButton.inject(controlBar, 'top');
    },

    /**
     * Creates the Button that adjusts the image vertically
     * @param controlBar
     */
    createVerticalAdjustButton: function (controlBar) {
        "use strict";

        this.verticalPlaceButton = new Element('div', {
            'class': 'v_placeImage control',
            'rel': 'ajuste vertical',
            styles: {
                'position': 'absolute',
                'z-index': this.options.controlsZindex
            },
            events: {
                click: function () {

                    var morphTarget,
                        morphHandles,
                        options,
                        maskCoord,
                        resultSize,
                        morphOptions = {
                            duration: 1000,
                            transition: Fx.Transitions.Cubic.easeOut
                        };

                    morphTarget = new Fx.Morph(this.target, morphOptions);
                    morphHandles = new Fx.Morph(this.handles.container, morphOptions);

                    maskCoord = this.crop.getCoordinates(this.wrapper);
                    resultSize = this.calculateAspectRatioFit(this.width, this.height, 20000, this.options.cropHeight);

                    options = {
                        width: resultSize.width,
                        'height': resultSize.height,
                        'top': maskCoord.top
                    };

                    morphTarget.start(options);
                    morphHandles.start(options);

                }.bind(this)
            }
        });

        this.verticalPlaceButton.inject(controlBar, 'top');
    },

    /**
     * Creates the Button that resets the image to its original size
     * @param controlBar
     */
    createResetButton: function (controlBar) {
        "use strict";
        this.resetButton = new Element('div', {
            'class': 'resetImage control',
            'rel': 'tamaño completo',
            styles: {
                'position': 'absolute',
                'z-index': this.options.controlsZindex
            },
            events: {
                click: function () {

                    var morphTarget,
                        morphHandles,
                        maskCoord,
                        options,
                        width,
                        height,
                        morphOptions = {
                            duration: 1000,
                            transition: Fx.Transitions.Cubic.easeOut
                        };

                    morphTarget = new Fx.Morph(this.target, morphOptions);
                    morphHandles = new Fx.Morph(this.handles.container, morphOptions);

                    maskCoord = this.crop.getCoordinates(this.wrapper);

                    if (this.origWidth === 0 && this.origHeight === 0) {
                        width = this.origWidth;
                        height = this.origHeight;
                    } else {
                        width = this.origImageWidth;
                        height = this.origImageHeight;
                    }

                    options = {
                        'width': width,
                        'height': height,
                        'top': maskCoord.top,
                        'left': maskCoord.left
                    };

                    morphTarget.start(options);
                    morphHandles.start(options);

                }.bind(this)
            }
        });

        this.resetButton.inject(controlBar, 'top');
    },

    /**
     * Saves the coordinates to the instance variables (disabled)
     */
    createApplyButton: function () {
        "use strict";
        //var cropPosition = this.crop.getPosition();

        this.applyButton = new Element('div', {
            'class': 'applyButton control',
            'rel': 'aplicar',
            styles: {
                'position': 'absolute',
                'z-index': this.options.controlsZindex
            },
            events: {
                click: function () {
                    this.width = this.target.getStyle('width').toInt();
                    this.height = this.target.getStyle('height').toInt();
                    this.top = -this.target.getPosition(this.crop).y;
                    this.left = -this.target.getPosition(this.crop).x;

                    this.fireEvent('onComplete', [this.top, this.left, this.width, this.height]);
                }.bind(this)
            }
        });

        //this.applyButton.inject(controlBar, 'top');
    },

    /**
     * Removes the instance from memory
     */
    destroy: function () {
        "use strict";
        //Remove the cropper when done
        (function () {
            this.target.replaces(this.wrapper);
            this.fireEvent('onDestroy', this.target);
        }).delay(600, this);
    }

});
