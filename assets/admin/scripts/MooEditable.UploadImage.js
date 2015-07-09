/*global MooEditable, $$, $, Element, Uploader, Request, StickyWin, console, system, Cookie, FileReader */

/*
 ---

 name: MooEditable.UploadImage

 description: Extends MooEditable to upload an image with manipulation options.

 license: MIT-style license

 authors:
 - Miguel Suárez

 requires:
 # - MooEditable
 # - MooEditable.UI
 # - MooEditable.Actions
 # - DropZone

 provides: [MooEditable.UI.UploadImageDialog, MooEditable.Actions.uploadimage]

 usage: |
 Add the following tags in your html
 <link rel="stylesheet" href="MooEditable.css">
 <link rel="stylesheet" href="MooEditable.UploadImage.css">
 <script src="mootools.js"></script>
 <script src="MooEditable.js"></script>
 <script src="MooEditable.UploadImage.js"></script>

 <script src="DropZone/Request.Blob.js"></script>
 <script src="DropZone/DropZone.js"></script>
 <script src="DropZone/DropZone.HTML5.js"></script>
 <script src="DropZone/DropZone.HTML4.js"></script>
 <script src="DropZone/DropZone.Flash.js"></script>

 <script>
 window.addEvent('domready', function(){
 var mooeditable = $('textarea-1').mooEditable({
 actions: 'bold italic underline strikethrough | uploadimage | toggleview'
 });
 });
 </script>

 ...
 */

MooEditable.Locale.define({
    imageAlt: 'alt',
    imageClass: 'class',
    imageAlign: 'align',
    imageAlignNone: 'none',
    imageAlignLeft: 'left',
    imageAlignCenter: 'center',
    imageAlignRight: 'right',
    addEditImage: 'Add/Edit Image'
});

MooEditable.UI.UploadImageDialog = function (editor) {
    "use strict";
    var html = '<fieldset id="upload-image-editor">' +
        '<legend>Subir Imágen</legend>' +
        '<div>' +
        '<input class="fileselect" type="file" name="fileselect[]" />' +
        '<div class="filedrag">o arrastre el archivo aquí</div>' +
        '</div>' +
        '<ul class="list"></ul>' +
        '</fieldset>' +
        '<button style="display:none" class="dialog-button dialog-ok-button">' + MooEditable.Locale.get('ok') + '</button> ' +
        '<button class="dialog-button dialog-cancel-button">' + MooEditable.Locale.get('cancel') + '</button>',
        sourcePath;

    return new MooEditable.UI.Dialog(html, {
        'class': 'mooeditable-uploadimage-dialog',
        onOpen: function () {

            //TODO Upload Image button is receiving various onClick events when you click on ok or cancel.

            var w = this.el.getElement('#upload-image-editor'),
                image,
                myUpload,
                dialogEl = this.el;

            myUpload = new Uploader(w, {
                url: system.base_url + 'assets/admin/php/upload.php',
                multiple: false,
                onItemAdded: function () {

                    if (w.getElements('li').length > 2) {
                        w.getElements('li')[0].destroy();
                    }

                    if (w.getElements('li').length > 1) {
                        w.getElements('li')[0].dissolve();
                    }

                },
                onItemComplete: function (file, result) {

                    var imagedata,
                        request,
                        reader = new FileReader(),
                        img;

                    img = document.createElement('img');

                    reader.onload = function () {

                        imagedata = {
                            name : file.name,
                            width : img.width,
                            height : img.height,
                            conserveName : true,
                            crop : false
                        };

                        request = new Request({
                            url: system.base_url + 'admin/imagen/contenido',
                            noCache: true,
                            data: {
                                imagedata: JSON.encode(imagedata),
                                'filedata': result,
                                csrf_test: Cookie.read('csrf_cookie')
                            },
                            onSuccess: function (responseText) {

                                var response = JSON.decode(responseText),
                                    container,
                                    select,
                                    win;

                                if (response.code === 1) { //Success
                                    sourcePath = response.path;
                                } else if (response.code === 101) { //File exists
                                    sourcePath = response.path;

                                    win = new StickyWin.ui('Imagen', response.message, {
                                        width : 400,
                                        buttons : [{
                                            text : 'cancelar',
                                            onClick : function () {

                                            }
                                        }]
                                    });

                                } else { //Some other error
                                    StickyWin.ui('Imagen', response.message, {
                                        width : 400,
                                        buttons : [{
                                            text : 'ok'
                                        }]
                                    });
                                }

                                container = new Element('div', {
                                    'class': 'dialog-container'
                                }).inject(w);

                                new Element('label', {
                                    text: MooEditable.Locale.get('imageAlt')
                                }).inject(container);

                                new Element('input', {
                                    'type': 'text',
                                    'class': 'dialog-alt',
                                    'size' : 8
                                }).inject(container);

                                new Element('label', {
                                    text: MooEditable.Locale.get('imageClass')
                                }).inject(container);

                                new Element('input', {
                                    'type': 'text',
                                    'class': 'dialog-class',
                                    'size' : 8
                                }).inject(container);

                                new Element('label', {
                                    text: MooEditable.Locale.get('imageAlign')
                                }).inject(container);

                                select = new Element('select', {
                                    'type': 'text',
                                    'class': 'dialog-align'
                                }).inject(container);

                                new Element('option', {
                                    text: MooEditable.Locale.get('imageAlignNone')
                                }).inject(select);

                                new Element('option', {
                                    text: MooEditable.Locale.get('imageAlignLeft')
                                }).inject(select);

                                new Element('option', {
                                    text: MooEditable.Locale.get('imageAlignCenter')
                                }).inject(select);

                                new Element('option', {
                                    text: MooEditable.Locale.get('imageAlignRight')
                                }).inject(select);

                                var reveal = new Fx.Reveal(dialogEl.getElement('.dialog-ok-button'), {display: 'inline-block'});
                                reveal.reveal();

                            },
                            onFailure: function () {
                                //TODO: Failure handler
                            }
                        });

                        request.send();
                    };

                    reader.readAsDataURL(file);

                }
            });

        },
        onClick: function (e) {

            if (e.target.tagName.toLowerCase() === 'button') {
                e.preventDefault();
            }

            var button = document.id(e.target),
                dialogAlignSelect,
                node,
                div;

            if (button.hasClass('dialog-cancel-button')) {

                this.close();
                this.el.getElement('#upload-image-editor .list').empty();
                this.el.getElement('.button.upload').reveal();
                this.el.getElements('.dialog-container').destroy();

            } else if (button.hasClass('dialog-ok-button')) {

                this.close();

                dialogAlignSelect = this.el.getElement('.dialog-align');
                node = editor.selection.getNode();

                if (node.get('tag') === 'img') {
                    node.set('src', sourcePath);
                    node.set('alt', this.el.getElement('.dialog-alt').get('value').trim());
                    node.className = this.el.getElement('.dialog-class').get('value').trim();
                    node.set('align', $(dialogAlignSelect.options[dialogAlignSelect.selectedIndex]).get('value'));
                } else {
                    div = new Element('div');

                    if (this.el.getElement('#upload-image-editor img')) {
                        new Element('img', {
                            src: sourcePath,
                            alt: this.el.getElement('.dialog-alt').get('value').trim(),
                            'class': this.el.getElement('.dialog-class').get('value').trim(),
                            align: $(dialogAlignSelect.options[dialogAlignSelect.selectedIndex]).get('value')
                        }).inject(div);
                        editor.selection.insertContent(div.get('html'));
                    }

                }

                this.el.getElement('#upload-image-editor .list').empty();
                this.el.getElements('.dialog-container').destroy();

            }



        }
    });
};

MooEditable.Actions.uploadimage = {
    title: MooEditable.Locale.get('addEditImage'),
    options: {
        shortcut: 'm'
    },
    dialogs: {
        prompt: function (editor) {
            "use strict";
            return MooEditable.UI.UploadImageDialog(editor);
        }
    },
    command: function () {
        "use strict";
        this.dialogs.uploadimage.prompt.open();
    }
};