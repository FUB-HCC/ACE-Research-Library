// MotoPress Filter&Layout jQuery Plugin
(function ($) {

    var motoItemFilter = {
        settings: {
            // Single filter
            itemSelector:         ".motopress-filterable-item",
            filterSelector:       ".motopress-filter-btn",
            contentSelector:      ".motopress-filterable-content", // Where to load the data
            // Multiple filters
            multifilter:          false,
            groupSelector:        ".motopress-filter-group", // Groups with filter buttons
            // "Load More" feature
            itemsLoader:          false,
            startPagesCount:      1,
            loaderSelector:       ".motopress-load-more",
            ajaxUrl:              "",
            ajaxAction:           "",
            queryData:            "",
            // Other data
            setFilter:            "",
            animationSpeed:       1000,
            activeClass:          "ui-state-active",
            onLoadClass:          "ui-state-loading",
            noItemsClass:         "no-items-found",
            viewAllFilter:        "[all]",
            preventDefault:       true,
            moreButtonAppearance: "showAfter", // "animate", "showBefore", "showAfter"
            // Callbacks and functions
            beforeCallback:       function() {}, // argument - "callback_data"
            afterCallback:        function() {}, // argument - "callback_data"
            filterCallback:       function() { return true; }
        },

        // Other private settings
        ajax_ready: false,
        paged: 1,
        pages_count: 1,
        $target: null,
        selector: "",
        $items: null,
        $not_found: null,
        $filter_buttons: null,
        $more_button: null,
        $more_btn_parent: null,
        $more_content: null,
        need_to_hide_more: false,
        action: "idle",
        callback_data: {
            selector: "",
            itemSelector: "",
            filterSelector: "",
            groupSelector: "",
            filters: ""
        },

        init: function(target, options, selector) {
            var $target = $(target);

            // Init plugin settings
            this.settings = $.extend({}, this.settings, options);
            this.settings.activeSelector = "." + this.settings.activeClass;
            this.settings.noItemsSelector = "." + this.settings.noItemsClass;

            // Init other private settings
            this.pages_count = this.settings.startPagesCount;
            this.selector = selector;

            this._initObjects($target);
            this._initCallbackData(selector);
            this._initAjax();

            // Set event handlers
            this._addEventHandlers();

            if( !this.ajax_ready || this.pages_count <= 1 ) {
                this.$more_button.hide();
                this.$more_btn_parent.hide();
            }

            // Init filter
            if( this.settings.setFilter !== "" ) {
                this.setFilter(this.settings.setFilter);
            }

            // Enable jQuery method chaining
            return this;
        },

        _addEventHandlers: function() {
            // Set "onclick" handler (function "onFilter" or "onLoadMore" by default)
            var self = this;
            if( !this.ajax_ready ) {
                this.$filter_buttons.on("click", function(event) { self._onFilter($(this), event); });
            } else {
                if( this.pages_count > 1 ) {
                    this.$filter_buttons.on("click", function(event) { self._onFilterMore($(this), event); });
                } else {
                    this.$filter_buttons.on("click", function(event) { self._onFilter($(this), event); });
                }
                this.$more_button.on("click", function(event) { self._onLoadMore($(this), event); });
            }
        },

        _initObjects: function($target) {
            // Init other private settings
            this.$target = $target;
            this.$items = $target.find(this.settings.itemSelector);
            this.$not_found = $target.find(this.settings.noItemsSelector);
            // Init filter buttons
            if( !this.settings.multifilter ) {
                this.$filter_buttons = $target.find(this.settings.filterSelector);
            } else {
                // Skip buttons without any group
                this.$filter_buttons = $target.find(this.settings.groupSelector + " " + this.settings.filterSelector);
            }
            // Init elements for AJAX requests
            this.$more_button = $target.find(this.settings.loaderSelector);
            this.$more_btn_parent = this.$more_button.parent();
            this.$more_content = $target.find(this.settings.contentSelector);
        },
        _initCallbackData: function(selector) {
            // Init filter data
            this.callback_data.selector       = selector;
            this.callback_data.itemSelector   = this.settings.itemSelector;
            this.callback_data.filterSelector = this.settings.filterSelector;
            this.callback_data.groupSelector  = this.settings.groupSelector;
            this.callback_data.filters        = this.settings.viewAllFilter;
        },
        _initAjax: function() {
            // Is AJAX ready?
            this.ajax_ready = false;
            if( this.settings.itemsLoader && this.settings.ajaxAction && this.settings.ajaxAction !== "" && this.settings.queryData && this.settings.queryData !== "" ) {
                this.ajax_ready = true;
            }
        },

        setFilter: function(filter) {
            var the_filter = this.parseFilter(filter);

            // Filter items
            this.doFilter(the_filter, 0);

            // Update filter buttons
            if( !this.settings.multifilter ) {
                this.$filter_buttons.removeClass(this.settings.activeClass);
                this.$filter_buttons.filter('[data-filter~="' + the_filter.full_filter + '"]').addClass(this.settings.activeClass);
            } else {

                this.$filter_buttons.removeClass(this.settings.activeClass);
                var activeClass = this.settings.activeClass,
                    viewAllFilter = this.settings.viewAllFilter;
                this.$target.find(this.settings.groupSelector).each(function() {
                    var group_filter = $(this).data("group");
                    if( typeof the_filter[group_filter] !== "undefined" ) {
                        $(this).find('[data-filter~="' + the_filter[group_filter] + '"]').addClass(activeClass);
                    } else {
                        $(this).find('[data-filter~="' + viewAllFilter + '"]').addClass(activeClass);
                    }
                });

            }
        },
        doFilter: function(filter, animation_speed) {
            this.$items.fadeOut(0);
            this.$not_found.fadeOut(0);

            if( !this.ajax_ready ) {
                // Filter and show items
                var $visible_items = this.filterItems(filter.full_filter);
                if( $visible_items.length > 0 ) {
                    $visible_items.fadeIn(animation_speed);
                } else {
                    this.$not_found.fadeIn(animation_speed/2);
                }
            } else {
                // Load items
                this.doAjax(filter, this.ajaxFilterSuccess);
            }
        },

        _onFilter: function($elem, event) {
            this.maybePreventDefault(event);
            if( this.isDisabled($elem) ) {
                return !this.settings.preventDefault;
            }

            this.action = "filter";

            // Disable all filter buttons and set new active button
            this.disable(this.$filter_buttons);
            this.activateFilter($elem);
            $elem.addClass(this.settings.onLoadClass);

            // Get filter
            var filter = this.getFilter($elem);
            this.callback_data.filters = filter;

            // Before callback
            this.settings.beforeCallback(this.callback_data);

            // Filter and show items
            this.$more_content.addClass(this.settings.onLoadClass);
            var $visible_items = this.filterItems(filter.full_filter);
            this.$items.fadeOut(0);
            this.$not_found.fadeOut(0);
            var self = this;
            if( $visible_items.length > 0 ) {
                // Show visible items
                $visible_items.fadeIn(this.settings.animationSpeed);
                // After callback. Wait for animation and enable filter buttons
                // (fadeIn callback is not an option - will be called several times)
                setTimeout(function() { self.afterAnimation(self); }, this.settings.animationSpeed);
            } else {
                // Nothing to show; show the "no-items-found" item
                this.$not_found.fadeIn(this.settings.animationSpeed/2);
                setTimeout(function() { self.afterAnimation(self); }, this.settings.animationSpeed/2);
            }
        },

        _onFilterMore: function($elem, event) {
            this.maybePreventDefault(event);
            if( this.isDisabled($elem) ) {
                return !this.settings.preventDefault;
            }

            this.action = "filter";
            this.paged = 1;

            // Disable all filter buttons and set new active button
            this.disable(this.$filter_buttons);
            this.disable(this.$more_button);
            this.activateFilter($elem);
            $elem.addClass(this.settings.onLoadClass);
            this.$more_button.hide();

            // Get filter
            var filter = this.getFilter($elem);
            this.callback_data.filters = filter;

            // Before callback
            this.settings.beforeCallback(this.callback_data);

            // Load items
            this.$more_content.addClass(this.settings.onLoadClass);
            this.doAjax(filter, this.ajaxFilterSuccess);
        },

        _onLoadMore: function($elem, event) {
            this.maybePreventDefault(event);
            if( this.isDisabled($elem) ) {
                return !this.settings.preventDefault;
            }

            this.action = "load_more";
            $(event.target).blur();
            this.paged += 1;

            // Disable all filter buttons and set new active button
            this.disable(this.$filter_buttons);
            this.disable(this.$more_button);
            $elem.parent().addClass(this.settings.onLoadClass);
            this.$more_button.hide();

            // Get filter
            var filter = this.getFilter($elem);
            this.callback_data.filters = filter;

            // Before callback
            this.settings.beforeCallback(this.callback_data);

            // Load items
            this.$more_content.addClass(this.settings.onLoadClass);
            this.$not_found.fadeOut(0);
            this.doAjax(filter, this.ajaxLoadSuccess);
        },

        activateFilter: function($new_active) {
            if( !this.settings.multifilter ) {
                this.$filter_buttons.removeClass(this.settings.activeClass);
            } else {
                var $group_buttons = $new_active.parents(this.settings.groupSelector).find(this.settings.filterSelector);
                $group_buttons.removeClass(this.settings.activeClass);
            }
            $new_active.addClass(this.settings.activeClass);
        },

        getFilter: function($active_button) {
            var the_filter = {
                full_filter: ""
            }
            var active_selector = this.settings.filterSelector + this.settings.activeSelector;

            if( this.settings.multifilter ) {
                var viewAllFilter = this.settings.viewAllFilter;
                this.$target.find(this.settings.groupSelector).each(function() {
                    var group = $(this).data("group");
                    // Get filter
                    var filter = $(this).find(active_selector).data("filter");
                    if( filter != viewAllFilter ) {
                        the_filter[group] = filter;
                        the_filter.full_filter += filter;
                    }
                });

                if( the_filter.full_filter === "" ) {
                    the_filter.full_filter = this.settings.viewAllFilter;
                }

            } else {
                var $group = this.$target.find(this.settings.groupSelector);
                if( $group.length > 0 ) {
                    var group = $group[0].data("group");
                    var filter = $group[0].find(active_selector).data("filter");
                    if( filter != this.settings.viewAllFilter ) {
                        the_filter[group] = filter;
                    }
                    the_filter.full_filter += filter;
                } else {
                    the_filter.full_filter = $active_button.data("filter");
                }
            }

            return the_filter;
        },
        parseFilter: function(filter) {
            var the_filter = {},
                full_filter = "";

            var matches = filter.match(/\w+=\w+/g);
            if( matches ) {
                $.each(matches, function(index, value) {
                    var args = value.match(/(\w+)=(\w+)/);
                    if( args ) {
                        var single_filter = "." + args[1] + "-" + args[2];
                        the_filter[args[1]] = single_filter;
                        full_filter += single_filter;
                    }
                });
            }

            the_filter.full_filter = full_filter;

            return the_filter;
        },

        filterItems: function(filter) {
            if( filter == this.settings.viewAllFilter ) {
                return this.$items;
            } else {
                return this.$items.filter(filter).filter(this.settings.filterCallback);
            }
        },

        afterAnimation: function(self) {
            // "this" is not available in setTimeout function
            self.settings.afterCallback(self.callback_data);
            self.enable(self.$filter_buttons);
            self.$more_content.removeClass(self.settings.onLoadClass);
            if( self.ajax_ready ) {
                self.enable(self.$more_button);
                self.$more_btn_parent.removeClass(self.settings.onLoadClass);
                if( self.need_to_hide_more ) {
                    self.$more_btn_parent.hide();
                } else {
                    if( self.action === "filter" ) {
                        self.$more_btn_parent.show();
                    }
                    if( self.settings.moreButtonAppearance === "showAfter" ) {
                        self.$more_button.show();
                    }
                }
            }
        },

        afterAjax: function() {
            this.need_to_hide_more = false;
            if( this.paged < this.pages_count ) {
                if( this.action === "load_more" || this.settings.moreButtonAppearance !== "showAfter" ) {
                    this.$more_btn_parent.show();
                }
                switch( this.settings.moreButtonAppearance ) {
                    case "animate":
                        this.$more_button.fadeIn(this.settings.animationSpeed);
                        break;
                    case "showBefore":
                        this.$more_button.show();
                        break;
                }
            } else {
                this.need_to_hide_more = true;
                if( this.action === "filter" ) {
                    this.$more_btn_parent.hide();
                }
            }
        },

        disable: function($items) {
            $items.attr("disabled", "disabled");
        },

        enable: function($items) {
            $items.removeAttr("disabled").removeClass(this.settings.onLoadClass);
        },

        isDisabled: function($item) {
            var disabled = $item.attr("disabled");
            if( $item.hasClass(this.settings.activeClass)
                    || (typeof disabled !== typeof undefined && disabled !== false) )
            {
                return true;
            } else {
                return false;
            }
        },

        maybePreventDefault: function(event) {
            if( this.settings.preventDefault ) {
                event.preventDefault();
            }
        },

        doAjax: function(filters, success_callback) {
            var self = this;
            $.ajax({
                type: "POST",
                dataType: "json",
                url: this.settings.ajaxUrl,
                data: {
                    action: this.settings.ajaxAction,
                    query: this.settings.queryData,
                    paged: this.paged,
                    filters: filters
                },
                success: function(response) {
                    if( response && response.html ) {
                        success_callback(self, response);
                    }
                    self.afterAjax();
                },
                error: function() {
                    self.afterAjax();
                    self.$more_content.removeClass(self.settings.onLoadClass);
                }
            });
        },

        ajaxFilterSuccess: function(self, response) {
            self.$items.fadeOut(0);
            self.$not_found.fadeOut(0);
            self.$more_content.empty();
            self.$items.empty();

            var $items = $(response.html).find(self.settings.itemSelector);

            if( $items.length > 0 ) {
                $items.hide();
                self.$more_content.append($items);
                $items.fadeIn(self.settings.animationSpeed);
                setTimeout(function() { self.afterAnimation(self); }, self.settings.animationSpeed);
                self.$items = $items;
            } else {
                self.$not_found.fadeIn(self.settings.animationSpeed/2);
                setTimeout(function() { self.afterAnimation(self); }, self.settings.animationSpeed/2);
            }

            self.pages_count = response.pages_count;
        },

        ajaxLoadSuccess: function(self, response) {
            var $items = $(response.html).find(self.settings.itemSelector);

            if( $items.length > 0 ) {
                $items.hide();
                self.$more_content.append($items);
                $items.fadeIn(self.settings.animationSpeed);
                setTimeout(function() { self.afterAnimation(self); }, self.settings.animationSpeed);
                self.$items = self.$target.find(self.settings.itemSelector);
            }
        },

    }; // motoItemFilter

    if( typeof Object.create !== "function" ) {
        Object.create = function (o) {
            function F() {}
            F.prototype = o;
            return new F();
        };
    }

    // Main plugin function
    $.fn.moto_layout_filter = function(options) {
        if( this.length ) {
            var self = this,
                selector = this.selector;
            this.each(function(index) {
                var currentSelector = selector;
                if( self.length > 1) {
                    currentSelector = selector + ":nth-of-type(" + (index + 1) + ")";
                }
                // Create a new motoItemFilter object via the Prototypal Object.create
                var myMotoItemFilter = Object.create(motoItemFilter);
                // Run the initialization function of the object
                myMotoItemFilter.init(this, options, currentSelector); // "this" refers to the element
                // Save the instance of the speaker object in the element's data store
                $.data(this, "moto-item-filter", myMotoItemFilter);
            });
        }

        // Enable jQuery method chaining
        return this;
    } // $.fn.moto_layout_filter function

}(jQuery));
