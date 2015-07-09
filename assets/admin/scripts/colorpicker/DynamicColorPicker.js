/** Wrapper for John Dyer's Photoshop-like color picker, that dynamically
 * creates the required HTML.
 *
 * http://johndyer.name/post/2007/09/26/PhotoShop-like-JavaScript-Color-Picker.aspx
 *
 * N.B:
 *
 * - The 'change' event will be fired when the color on the picker changes.
 *
 * - If you apply styles to the colorpicker container class
 *   (colorpicker-container), use !important to override the default styles.
 * 
 * - It's possible to enable the colorpicker on any number of textfields, by
 *   calling DynamicColorPicker.auto(".class", [options]). The
 *   DynamicColorPicker instance will be stored in the text field's colorPicker
 *   (MooTools) property.
 *
 * - If MooTools More is included, DynamicColorPicker autoloads the rest of the
 *   required JS files, so you don't have to include them in your HTML file.
 */

DynamicColorPicker = new Class({

    Implements: [Options, Events],

    options: {
        containerClass: "colorpicker-container",
        textField: null,
        startMode:'h',
        startHex:'ff0000',
        pickerPath: 'colorpicker'
    },

    initialize: function(options) {
        this.setOptions(options);
        this.options.clientFilesPath = this.options.pickerPath + '/images/';

        var self = this;

        DynamicColorPicker.autoLoad(this.options.autoLoadPath, function() {
            self.container = new Element("div", { "class": self.options.containerClass, styles: {
                position: "absolute",
                left: "0px",
                top: "0px",
                display: "none",
                backgroundColor: "#FFFFFF",
                border: "solid 1px #333333"
            }}).injectInside(document.body);

            self.id = DynamicColorPicker.generateId();
            self.setContainerHtml(self.id);

            self.picker = new Refresh.Web.ColorPicker(self.id, self.options);
            self.picker.addEvent('change', self._onColorChange.bind(self));

            self.open = false;
            self.clickCloses = false;

            self.container
                .addEvent('mouseout', function() { self.clickCloses = true; })
                .addEvent('mouseover', function() { self.clickCloses = false; });

            self.hide();

            window.addEvent('click', function() {
                if (self.open && self.clickCloses) self.hide();
            });
        });
    },

    setColor: function(color) {
        if (color.substring(0, 1) == "#") color = color.substring(1);
        this.picker._cvp._hexInput.value = color;
        this.picker._cvp.setValuesFromHex();
    },

    show: function(x, y) {
        if (x && y) this.container.setStyles({ "left": x + "px", "top": y + "px" });
        this.picker.show();
        this.container.setStyles({ "display": "block" });
        this.open = true;
        this.clickCloses = false;
        if (this.options.textField) this.setColor(this.options.textField.value);
        this.picker.setColorMode(this.picker.ColorMode); // If we reset this after we show, it positions the selectors properly
        this.picker.updateVisuals();
        (function() { this.clickCloses = true; }).delay(0.001); /* Set this one so we don't have to mouse over first before the click will work */
    },

    hide: function() {
        this.container.setStyles({ "display": "none" });
        this.picker.hide();
        this.open = false;
    },

    toggle: function(x, y) {
        if (this.open) this.hide(); else this.show(x, y);
    },

    _onColorChange: function() {
        var newHex = '#' + this.picker._cvp._hexInput.value;
        if (this.options.textField) this.options.textField.set('value', newHex);
        this.fireEvent('change', newHex);
    },

    setContainerHtml: function(id) {
        // This HTML was copy-pasted from John's website, and we do a search-replace on the ID to make it unique for multiple instances :)
        this.container.set("html", '<table>\
                <tr>\
                    <td valign="top">\
                        <div id="cp1_ColorMap"></div>\
                    </td>\
                    <td valign="top">\
                        <div id="cp1_ColorBar"></div>\
                    </td>\
        \
                    <td valign="top">\
        \
                        <table>\
                            <tr>\
                                <td colspan="3">\
                                    <div id="cp1_Preview" style="background-color: #fff; width: 60px; height: 60px; padding: 0; margin: 0; border: solid 1px #000;">\
                                        <br />\
                                    </div>\
                                </td>\
                            </tr>\
                            <tr>\
                                <td>\
                                    <input type="radio" id="cp1_HueRadio" name="cp1_Mode" value="0" />\
                                </td>\
                                <td>\
                                    <label for="cp1_HueRadio">H:</label>\
                                </td>\
                                <td>\
                                    <input type="text" id="cp1_Hue" value="0" style="width: 40px;" /> &deg;\
                                </td>\
                            </tr>\
        \
                            <tr>\
                                <td>\
                                    <input type="radio" id="cp1_SaturationRadio" name="cp1_Mode" value="1" />\
                                </td>\
                                <td>\
                                    <label for="cp1_SaturationRadio">S:</label>\
                                </td>\
                                <td>\
                                    <input type="text" id="cp1_Saturation" value="100" style="width: 40px;" /> %\
                                </td>\
                            </tr>\
        \
                            <tr>\
                                <td>\
                                    <input type="radio" id="cp1_BrightnessRadio" name="cp1_Mode" value="2" />\
                                </td>\
                                <td>\
                                    <label for="cp1_BrightnessRadio">B:</label>\
                                </td>\
                                <td>\
                                    <input type="text" id="cp1_Brightness" value="100" style="width: 40px;" /> %\
                                </td>\
                            </tr>\
        \
                            <tr>\
                                <td colspan="3" height="5">\
        \
                                </td>\
                            </tr>\
        \
                            <tr>\
                                <td>\
                                    <input type="radio" id="cp1_RedRadio" name="cp1_Mode" value="r" />\
                                </td>\
                                <td>\
                                    <label for="cp1_RedRadio">R:</label>\
                                </td>\
                                <td>\
                                    <input type="text" id="cp1_Red" value="255" style="width: 40px;" />\
                                </td>\
                            </tr>\
        \
                            <tr>\
                                <td>\
                                    <input type="radio" id="cp1_GreenRadio" name="cp1_Mode" value="g" />\
                                </td>\
                                <td>\
                                    <label for="cp1_GreenRadio">G:</label>\
                                </td>\
                                <td>\
                                    <input type="text" id="cp1_Green" value="0" style="width: 40px;" />\
                                </td>\
                            </tr>\
        \
                            <tr>\
                                <td>\
                                    <input type="radio" id="cp1_BlueRadio" name="cp1_Mode" value="b" />\
                                </td>\
                                <td>\
                                    <label for="cp1_BlueRadio">B:</label>\
                                </td>\
                                <td>\
                                    <input type="text" id="cp1_Blue" value="0" style="width: 40px;" />\
                                </td>\
                            </tr>\
        \
        \
                            <tr>\
                                <td>\
                                    #:\
                                </td>\
                                <td colspan="2">\
                                    <input type="text" id="cp1_Hex" value="FF0000" style="width: 60px;" />\
                                </td>\
                            </tr>\
        \
                        </table>\
                    </td>\
                </tr>\
            </table>'.replace(/cp1/g, id));
    }
});

// "Static" function on the DynamicColorPicker class for generating IDs
DynamicColorPicker.generateId = (function() {
    var nr = 0;

    return function() {
        nr++;
        return "colpicker" + nr;
    };
})();

DynamicColorPicker.auto = function(spec, options) {
    $$(spec).each(function(el) {
        var cp = new DynamicColorPicker($extend(options || {}, { textField: el }));

        var button = new Element("img", { src: cp.options.clientFilesPath + "/colorwheel.png", styles: { marginLeft: 3, marginBottom: -5, cursor: "pointer" }})
            .injectAfter(el)
            .addEvent('click', function() {
                var p = el.getPosition();
                cp.toggle(p.x, p.y + el.getSize().y);
            });

        el.store('colorPicker', cp);
    });
};

// Try autoloading
DynamicColorPicker.autoLoad = (function() {
    var loadStage = 0; // 0 = not loaded, 1 = loading, 2 = loaded
    var callbacks = [];

    return function(path, onload) {
        // If loaded, immediately done
        if (loadStage == 2) { if (onload) onload(); return; }

        if (loadStage == 1) {
            if (onload) callbacks.push(onload);
            return;
        }

        // loadStage == 0
        
        // If loading not possible because of missing MooTools More, return immediately and fire event
        if (!window.Asset) { if (onload) onload(); return; }

        // Do loading
        loadStage = 1;
        callbacks.push(onload);

        // Otherwise load the files and fire the event when all four required files are loaded
        var filesLoaded = 0;
        var onFileLoaded = function() {
            filesLoaded++;
            if (filesLoaded == 4) loadStage = 2;
            if (loadStage == 2)
                callbacks.each(function(f) { f(); });
        };

        path = path || "colorpicker";
        Asset.javascript(path + "/ColorPicker.js", { onload: onFileLoaded });
        Asset.javascript(path + "/ColorValuePicker.js", { onload: onFileLoaded });
        Asset.javascript(path + "/ColorMethods.js", { onload: onFileLoaded });
        Asset.javascript(path + "/Slider.js", { onload: onFileLoaded });
    };
})();
