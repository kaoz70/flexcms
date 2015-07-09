/*global $, alert, console, Class, Events, Options */

/*filedrag.js - HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
Modified by Miguel Suarez
*/

var Uploader = new Class({

    Implements: [Events, Options],

    options: {
        maxFileSize: false,//Kb: set to "false" if no upload limit
        multiple: true,
        url: 'upload.php'
    },

    uploadBox: $('upload'),
    fileList: null,
    totalFiles: 0,
    files: [],

    initialize: function (target, options) {
        "use strict";

        if (window.File && window.FileList && window.FileReader) {

            //String or element
            if (typeof target === 'object') {
                this.uploadBox = target;
            } else {
                this.uploadBox = $(target);
            }

            this.setOptions(options);

            var fileselect = this.uploadBox.getElement('.fileselect'),
                filedrag = this.uploadBox.getElement('.filedrag'),
                xhr;

            this.fileList = this.uploadBox.getElement('.list');

            if (this.options.multiple) {
                fileselect.set('multiple', 'multiple');
            }

            // file select
            fileselect.addEvent("change", this._fileSelectHandler.bind(this));

            // is XHR2 available?
            xhr = new XMLHttpRequest();
            if (xhr.upload) {

                // file drop
                filedrag.addEventListener("dragover", this._fileDragHover, false);
                filedrag.addEventListener("dragleave", this._fileDragHover, false);
                filedrag.addEventListener("drop", this._fileSelectHandler.bind(this), false);
                filedrag.style.display = "block";

            }
        }
    },

    /*
     PRIVATE FUNCTIONS
     */
    // file selection
    _fileSelectHandler: function (e) {

        "use strict";

        // cancel event and hover styling
        this._fileDragHover(e);

        // fetch FileList object
        var files = e.target.files || e.dataTransfer.files;

        this.totalFiles = files.length;

        // process all File objects
        for (var i = 0, f; f = files[i]; i++) {
            this._parseFile(f);
        }

    },

    // file drag hover
    _fileDragHover: function (e) {
        "use strict";
        e.stopPropagation();
        e.preventDefault();

        if(e.type === 'dragover'){
            e.target.addClass('hover');
        } else {
            e.target.removeClass('hover');
        }

    },

    // http://kevin.vanzonneveld.net
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://crestidg.com)
    // +     bugfix by: Benjamin Lupton
    // +     bugfix by: Allan Jensen (http://www.winternet.no)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // *     example 1: _numberFormat(1234.5678, 2, '.', '');
    // *     returns 1: 1234.57
    _numberFormat: function ( number, decimals, dec_point, thousands_sep ) {

        "use strict";

        var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals,
            d = dec_point === undefined ? "," : dec_point,
            t = thousands_sep === undefined ? "." : thousands_sep, s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c), 10) + "", j = (j = i.length) > 3 ? j % 3 : 0;

        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");

    },

    //Got from: http://snipplr.com/views.php?codeview&id=5949
    _sizeFormat: function (filesize) {

        "use strict";

        if (filesize >= 1073741824) {
            filesize = this._numberFormat(filesize / 1073741824, 2, '.', '') + ' Gb';
        } else {
            if (filesize >= 1048576) {
                filesize = this._numberFormat(filesize / 1048576, 2, '.', '') + ' Mb';
            } else {
                if (filesize >= 1024) {
                    filesize = this._numberFormat(filesize / 1024, 0) + ' Kb';
                } else {
                    filesize = this._numberFormat(filesize, 0) + ' bytes';
                }
            }
        }
        return filesize;
    },

    _uploadErrorhandler: function (item, text) {

        "use strict";

        item.getElement('.progress-bar').addClass('error');
        item.getElement('.message').set('text', text);
    },

    _addItem: function (item, file, ev) {
        item.inject(this.fileList);
        this.files.push(file);
        this.fireEvent('onItemAdded', [file, item, ev]);
        this._startUpload(file, item);
    },

    // output file information
    _parseFile: function (file) {

        "use strict";

        var returnResult,
            instance = this;

        if(this.options.maxFileSize !== false && file.size > this.options.maxFileSize) {
            console.log('Sólamente se pueden subir archivos de hasta: ' + this._sizeFormat(this.options.maxFileSize));
            returnResult = false;
        } else {

            var reader = new FileReader(),
                item = new Element('li'),
                itemDetails = new Element('div', {
                    class: 'details',
                    text: 'loading'
                }).inject(item);

            // display an image
            if (file.type.indexOf("image") === 0) {

                reader.onload = function(e) {

                    item.addClass('image');
                    itemDetails.set('html', '<img src="' + e.target.result + '" />');

                    instance._addItem(item, file, e);
                };
                reader.readAsDataURL(file);
            }

            // display text
            else if (file.type.indexOf("text") === 0) {
                reader.onload = function(e) {

                    item.addClass('text');
                    itemDetails.set('html', "<pre>" + e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") + "</pre>");

                    instance._addItem(item, file, e);
                };
                reader.readAsText(file);
            }

            //Audio
            else if(file.type.indexOf("audio") === 0) {
                reader.onload = function(e) {

                    item.addClass('audio');
                    itemDetails.set('html', '<audio controls src="' + e.target.result + '"></audio>');

                    instance._addItem(item, file, e);
                };
                reader.readAsDataURL(file);
            }

            //Video
            else if(file.type.indexOf("video") === 0) {
                reader.onload = function(e) {

                    item.addClass('video');
                    itemDetails.set('html', '<video controls src="' + e.target.result + '"></video>');

                    instance._addItem(item, file, e);
                };
                reader.readAsDataURL(file);
            }

            else
            {
                reader.onload = function(e) {

                    item.addClass('default');
                    itemDetails.set('html', "<p>Size: <strong>" + instance._sizeFormat(file.size) + ":</strong></p>");

                    instance._addItem(item, file, e);
                };
                reader.readAsText(file);

            }

            returnResult = item;

        }

        return returnResult;

    },

    _startUpload: function (file, item) {

        "use strict";

        var xhr = new XMLHttpRequest(),
            instance = this;

        if (xhr.upload) {

            // create progress bar
            var progress,
                bar,
                message;

            progress = new Element('div', {
                'class': 'progress-bar green stripes'
            }).inject(item);

            bar = new Element('span', {
                'style': 'width: 0%'
            }).inject(progress);

            message = new Element('div', {
                'text': "uploading: " + file.name,
                'class': 'message'
            }).inject(progress);

            // progress bar
            xhr.upload.addEventListener("progress", function(e) {
                var perc = 100 - parseInt(100 - (e.loaded / e.total * 100), 10);
                bar.setStyle('width', perc + "%");
                instance.fireEvent('onItemProgress', [file, perc]);
            }, false);

            // file received/failed
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {

                    if(xhr.status === 200) {
                        progress.addClass('success');
                        message.set('text', 'File uploaded');
                    } else {
                        instance._uploadErrorhandler(item, 'Error uploading file, error: ' + xhr.status);
                    }

                    instance.fireEvent('onItemComplete', [file, JSON.decode(xhr.response), item]);

                    //Check and see if all files have been uploaded
                    instance.totalFiles--;

                    if(instance.totalFiles === 0) {
                        instance.fireEvent('onComplete', [instance.files]);
                        // reset the input's value so that we can upload the same file again
                        instance.uploadBox.getElement('.fileselect').set('value', null);
                    }

                }
            };

            // start upload
            xhr.open("POST", this.options.url, true);
            xhr.setRequestHeader("FILENAME", file.name);
            xhr.ontimeout = function () {
                this._uploadErrorhandler(item, 'Request Timeout');
            };
            xhr.send(file);

        }

    }

});