/*global $, Element, Asset, createManipWindow, Uploader, system, requestImageManipulation, createAlert, Request, failureRequestHandler, Fx , csrf_cookie, responseErrorHandler*/

/**
 * Handles the uploads to the server
 * @type {{target: null, image: Function, file: Function, onItemAddedHandler: Function}}
 */
var upload = {
    target: null,

    /**
     * Creates the Uploader for images, this will create an ImageManipulation window (if enabled in the parameter)
     * @param elemId
     * @param imageId
     * @param url {string} URL to image controller
     * @param cropWidth {integer}
     * @param cropHeight {integer}
     * @param showCropWindow {boolean} will show the ImageManipulation window
     */
    image: function (elemId, imageId, url, cropWidth, cropHeight, showCropWindow) {
        "use strict";
        var btnEdit,
            file,
            result,
            image,
            imageEl,
            myUpload;

        upload.target = $(elemId);

        if (showCropWindow) {

            btnEdit = new Element('div', {
                'class': 'button edit',
                text: 'Editar',
                events: {
                    click: function () {
                        var $this = this;
                        $this.addClass('loading');
                        var loader = new Asset.image($(imageId).get('data-orig'), {
                            onLoad: function (image) {
                                $this.removeClass('loading');
                                createManipWindow(imageId, $(elemId), cropWidth, cropHeight, file, result, image, url, true);
                            }
                        });

                    }
                }
            });

            if ($(elemId).getElement('li')) {
                btnEdit.inject($(elemId).getElement('li'));
            }

        }

        myUpload = new Uploader($(elemId), {
            url: system.baseUrl + 'assets/admin/php/upload.php',
            multiple: false,
            onItemAdded: function (file, item) {
                upload.onItemAddedHandler(item);
            },
            onItemComplete: function (file, result) {

                var imagedata,
                    name;

                if (showCropWindow && file.type.match('image')) {
                    createManipWindow(imageId, $(elemId), cropWidth, cropHeight, file, result, imageEl, url, false);
                } else {

                    imagedata = {
                        top : 0,
                        left : 0,
                        width : cropWidth,
                        height : cropHeight,
                        cropWidth : cropWidth,
                        cropHeight : cropHeight,
                        name : file.name,
                        crop : false
                    };

                    requestImageManipulation(url, imagedata, $(elemId), result);
                    name = file.name.split('.').pop();
                    $(imageId).set('value', name);
                }
            }
        });

    },

    /**
     * Creates the Uploader for files
     * @param elemId
     * @param fileId
     * @param url {string} URL to process the uploaded file
     * @param conserveName {boolean} Conserve the file's original name?
     */
    file: function (elemId, fileId, url, conserveName) {
        "use strict";
        var w = $(elemId),
            myUpload;

        myUpload = new Uploader(w, {
            url: system.baseUrl + 'assets/admin/php/upload.php',
            multiple: false,
            onItemAdded: function (file, item) {

                if (conserveName && file.name.test(/á|é|í|ó|ú|ñ|Á|É|Í|Ó|Ú|Ñ/)) {
                    createAlert('Error', 'El archivo cargado contiene caráteres especiales, <strong>evite el uso deá, é, í, ó, ú, ñ</strong> en el nombre del archivo');
                    return;
                }

                upload.onItemAddedHandler(item);

            },
            onItemComplete: function (file, result) {

                var request = new Request({
                    url: url,
                    noCache: true,
                    data: {
                        'imagedata': file,
                        'filedata': result,
                        csrf_test: csrf_cookie
                    },
                    onSuccess: function (responseText) {

                        var response = responseErrorHandler(responseText);

                        if (!response) {
                            return;
                        }

                        if (response.message === 'success') {
                            if (conserveName) {
                                $(fileId).set('value', response.name);
                            } else {
                                $(fileId).set('value', response.extension);
                            }

                        } else {
                            createAlert('Error', response.message);
                        }
                    },
                    onFailure: function (xhr) {
                        failureRequestHandler(xhr, url);
                    }
                });

                request.send();
            }
        });
    },

    gallery: function (elemId, method, crop_width, crop_height, level, modify_link, delete_link) {
        "use strict";

        var myUpload;

        upload.target = $(elemId);

        myUpload = new Uploader(upload.target, {
            url: system.baseUrl + 'assets/admin/php/upload.php',
            multiple: true,
            onItemAdded: function (file, item) {

                var link,
                    details = item.getElement('.details'),
                    children = details.getChildren(),
                    parag = item.getElement('p'),
                    audio = item.getElement('audio'),
                    video = item.getElement('video'),
                    ratio,
                    final_width,
                    final_height,
                     //remove extension from name
                    filename = file.name.substr(0, file.name.lastIndexOf('.')) || file.name;

                details.destroy();

                //Add the delete button
                link = new Element('a', {
                    'class': 'details ' + level,
                    href: ''
                }).inject(item);

                children.inject(link);

                //Proportional resize
                ratio = crop_width / crop_height;   // get ratio for scaling image
                if (ratio > 1) {
                    final_width = 100;
                    final_height = 100 / ratio;
                } else {
                    final_width = 100 * ratio;
                    final_height = 100;
                }

                //Is a file
                if (parag) {

                    parag.destroy();

                    new Element('div', {
                        'class': 'file',
                        'html': '<span class="extension">' + file.name.split('.').pop() + '</span>',
                        styles: {
                            height: final_height,
                            width: '100%'
                        }
                    }).inject(link);

                }

                if (audio) {

                    audio.destroy();

                    new Element('div', {
                        'class': 'file',
                        styles: {
                            height: final_height,
                            width: '100%'
                        }
                    }).inject(link);

                }

                if (video) {
                    video.setStyles({
                        'height': final_height,
                        'width': final_width
                    });
                    video.removeProperty('controls');
                }

                //Add the name
                new Element('div', {
                    'class': 'nombre',
                    html: '<span>' + filename.replace(/_|-|\+/g, ' ') + '</span>' //transform some usual special chars to spaces
                }).inject(link);

            },
            onItemComplete: function (file, result, item) {

                var imagedata,
                    b_delete,
                    details = item.getElement('.details'),
                    newImg;

                //Hide the progress bar
                item.getElement('.progress-bar').fade(0);

                //Add the delete button
                b_delete = new Element('a', {
                    'class': 'eliminar',
                    href: ''
                }).inject(item);

                if (file.type.contains('image')) { //It's an image

                    //Create a new image and load it to get the real image size
                    newImg = new Image();
                    newImg.onload = function () {

                        var crop_ratio = crop_width / crop_height,  // get ratio for scaling image
                            image_ratio = newImg.width / newImg.height,
                            resultSize;

                        // figure out which dimension hits first and set that to match
                        if (image_ratio < crop_ratio) {
                            resultSize = upload.calculateAspectRatioFit(newImg.width, newImg.height, crop_width, newImg.height);
                        } else {
                            resultSize = upload.calculateAspectRatioFit(newImg.width, newImg.height, newImg.width, crop_height);
                        }

                        imagedata = {
                            top : 0,
                            left : 0,
                            width : resultSize.width,
                            height : resultSize.height,
                            cropWidth : crop_width,
                            cropHeight : crop_height,
                            name : file.name,
                            crop : true
                        };

                        // Below is almost the same function as:
                        // requestImageManipulation(url, imagedata, item, result);
                        // TODO: fix duplicate code
                        var loader = new Element('div', {'class': 'loader'}),
                            last_img = item.getElements('img')[item.getElements('img').length - 1],
                            request,
                            dimensions = last_img.getSize(),
                            loader_fx = new Fx.Tween(loader),
                            url = system.baseUrl + 'admin/imagen/' + method;

                        request = new Request({
                            url: url,
                            noCache: true,
                            data: {
                                imagedata: JSON.encode(imagedata),
                                filedata: result,
                                csrf_test: csrf_cookie
                            },
                            onRequest: function () {
                                loader.inject(details);

                                details.setStyles({
                                    width: dimensions.x,
                                    height: dimensions.y
                                });

                                loader_fx.start('opacity', 1).chain(function () {
                                    last_img.destroy();
                                });

                            },
                            onSuccess: function (responseText) {

                                var response = responseErrorHandler(responseText),
                                    image,
                                    parent = details.getParent(),
                                    children;

                                loader.fade(0);

                                if (!response) {
                                    return;
                                }

                                if (response.message === 'success') {

                                    image =  new Element('img', {
                                        src: system.baseUrl + response.path
                                    });

                                    item.getElement('.details').set('href', modify_link + '/' + response.image_id);
                                    item.getElement('.eliminar').set('href', delete_link + '/' + response.image_id);

                                    item.set('id', response.image_id);

                                    image.addEventListener('load', function () {
                                        image.inject(details, 'top');
                                        details.setStyles({
                                            width: '',
                                            height: ''
                                        });

                                        children = details.getChildren()

                                        if (!response.modify_link) {
                                            parent.adopt(children);
                                            details.dispose();
                                        }

                                        if (parent.getParent().hasClass('list') && parent.getParent().hasClass('galeria')) {
                                            $(parent.getParent()).retrieve('sortable_instance').addItems(item);
                                        }

                                    });

                                } else {
                                    createAlert('Error', response.message);
                                }

                            },
                            onFailure: function (xhr) {
                                loader.fade(0);
                                failureRequestHandler(xhr, url);
                            }
                        });

                        request.send();

                    }

                    newImg.src = item.getElement('img').getProperty('src');

                } else { //It's a file and gallery section

                    var url = system.baseUrl + 'admin/archivo/' + method,
                        request;

                    request = new Request({
                        url: url,
                        noCache: true,
                        data: {
                            'imagedata': file,
                            'filedata': result,
                            csrf_test: csrf_cookie
                        },
                        onSuccess: function (responseText) {

                            var response = responseErrorHandler(responseText);

                            if (!response) {
                                return;
                            }

                            if (response.message === 'success') {
                                item.getElement('.details').set('href', modify_link + '/' + response.image_id);
                                item.getElement('.eliminar').set('href', delete_link + '/' + response.image_id);
                            } else {
                                createAlert('Error', response.message);
                            }
                        },
                        onFailure: function (xhr) {
                            failureRequestHandler(xhr, url);
                        }
                    });

                    request.send();

                }



            }
        });

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
     * Handles the event when we add the files to the Uploader
     */
    onItemAddedHandler: function (item) {
        "use strict";
        var parent = item.getParent();
        if (parent.getElements('li').length > 2) {
            parent.getElements('li')[0].destroy();
        }

        if (parent.getElements('li').length > 1) {
            parent.getElements('li')[0].dissolve();
        }
    }
};