(function($) {
    $.widget("sylightsUI.switchButton", {
        options: {
            checked: undefined,
            show_labels: true,
            labels_placement: "both",
            on_label: "ON",
            off_label: "OFF",
            width: 25,
            height: 11,
            button_width: 12,
            clear: true,
            clear_after: null,
            on_callback: undefined,
            off_callback: undefined
        },

        _create: function() {
            if (this.options.checked === undefined) {
                this.options.checked = this.element.prop("checked");
            }

            this._initLayout();
            this._initEvents();
        },

        _initLayout: function() {
            this.element.hide();
            this.off_label = $("<span>").addClass("switch-button-label");
            this.on_label = $("<span>").addClass("switch-button-label");
            this.button_bg = $("<div>").addClass("switch-button-background");
            this.button = $("<div>").addClass("switch-button-button");

            this.off_label.insertAfter(this.element);
            this.button_bg.insertAfter(this.off_label);
            this.on_label.insertAfter(this.button_bg);
            this.button_bg.append(this.button);

            if (this.options.clear) {
                if (this.options.clear_after === null) {
                    this.options.clear_after = this.on_label;
                }
                $("<div>").css({ clear: "left" }).insertAfter(this.options.clear_after);
            }

            this._refresh();
            this.options.checked = !this.options.checked;
            this._toggleSwitch(true);
        },

        _refresh: function() {
            if (this.options.show_labels) {
                this.off_label.show();
                this.on_label.show();
            } else {
                this.off_label.hide();
                this.on_label.hide();
            }

            switch (this.options.labels_placement) {
                case "both":
                    if (this.button_bg.prev() !== this.off_label || this.button_bg.next() !== this.on_label) {
                        this.off_label.detach().insertBefore(this.button_bg);
                        this.on_label.detach().insertAfter(this.button_bg);
                        this.on_label.toggleClass("on", this.options.checked).toggleClass("off", !this.options.checked);
                        this.off_label.toggleClass("off", this.options.checked).toggleClass("on", !this.options.checked);
                    }
                    break;
                case "left":
                    if (this.button_bg.prev() !== this.on_label || this.on_label.prev() !== this.off_label) {
                        this.off_label.detach().insertBefore(this.button_bg);
                        this.on_label.detach().insertBefore(this.button_bg);
                        this.on_label.addClass("on").removeClass("off");
                        this.off_label.addClass("off").removeClass("on");
                    }
                    break;
                case "right":
                    if (this.button_bg.next() !== this.off_label || this.off_label.next() !== this.on_label) {
                        this.off_label.detach().insertAfter(this.button_bg);
                        this.on_label.detach().insertAfter(this.off_label);
                        this.on_label.addClass("on").removeClass("off");
                        this.off_label.addClass("off").removeClass("on");
                    }
                    break;
            }

            this.on_label.html(this.options.on_label);
            this.off_label.html(this.options.off_label);
            this.button_bg.width(this.options.width).height(this.options.height);
            this.button.width(this.options.button_width).height(this.options.height);
        },

        _initEvents: function() {
            var self = this;

            this.button_bg.on("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                self._toggleSwitch(false);
                return false;
            });

            this.button.on("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                self._toggleSwitch(false);
                return false;
            });

            this.on_label.on("click", function(e) {
                if (self.options.checked && self.options.labels_placement === "both") {
                    return false;
                }
                self._toggleSwitch(false);
                return false;
            });

            this.off_label.on("click", function(e) {
                if (!self.options.checked && self.options.labels_placement === "both") {
                    return false;
                }
                self._toggleSwitch(false);
                return false;
            });
        },

        _setOption: function(key, value) {
            if (key === "checked") {
                this._setChecked(value);
                return;
            }
            this.options[key] = value;
            this._refresh();
        },

        _setChecked: function(value) {
            if (value === this.options.checked) {
                return;
            }
            this.options.checked = !value;
            this._toggleSwitch(false);
        },

        _toggleSwitch: function(isInitializing) {
            if (!isInitializing && (this.element.prop('readonly') || this.element.prop('disabled'))) return;

            this.options.checked = !this.options.checked;
            var newLeft = "";
            if (this.options.checked) {
                this.element.prop("checked", true).change();
                var dLeft = this.options.width - this.options.button_width;
                newLeft = "+=" + dLeft;

                if (this.options.labels_placement === "both") {
                    this.off_label.removeClass("on").addClass("off");
                    this.on_label.removeClass("off").addClass("on");
                } else {
                    this.off_label.hide();
                    this.on_label.show();
                }
                this.button_bg.addClass("checked");

                if (typeof this.options.on_callback === 'function') this.options.on_callback.call(this);
            } else {
                this.element.prop("checked", false).change();
                newLeft = "-1px";

                if (this.options.labels_placement === "both") {
                    this.off_label.removeClass("off").addClass("on");
                    this.on_label.removeClass("on").addClass("off");
                } else {
                    this.off_label.show();
                    this.on_label.hide();
                }
                this.button_bg.removeClass("checked");

                if (typeof this.options.off_callback === 'function') this.options.off_callback.call(this);
            }

            this.button.animate({ left: newLeft }, 350, "swing");
        }
    });
})(jQuery);
