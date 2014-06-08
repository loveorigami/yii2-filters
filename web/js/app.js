var FATAL_ERROR = 'Fatal error';

$(function(){
    $.ajaxSetup({
        type    : 'POST',
        dataType: 'JSON',
        error   : function() {
            console.log(FATAL_ERROR);
        }
    });

    $(Filter.element)
        .on('change', function(){
            var id = $(this).data('id') || 0;
            if (id) {
                Filter.search(id);
            }
        });
});

var Filter = {
    element: '.j-filter',
    button : '.btn-fly',

    filterData: {},

    /**
     * Getting list of checked filters.
     */
    getChecked: function() {
        var checked = [];
        
        $(this.element)
            .filter(':checked')
            .each(function(i){
                var id = $(this).data('id') || 0;
                if (id) {
                    checked.push(id);
                }
            });
        
        return checked;
    },

    search: function(id) {
        this.filterData['filters'] = this.getChecked();
        this.getData(id);
    },

    /**
     * Sending request.
     */
    getData: function(id) {
        var _this = this;
        $.ajax({
            url: '/filter/get',
            data: _this.filterData,
            success: function(response) {
                if (response) {
                    var count   = response.count ? response.count : 0;
                    var filters = response.filters ? response.filters : false;

                    _this.showButton(id, count);
                    _this.disableFilters(filters);
                }
            }
        });
    },

    /**
     * Showing "Found" button.
     */
    showButton: function(id, count) {
        if ($(this.element).filter('[data-id="' + id + '"]').length) {
            var element      = $(this.element).filter('[data-id="' + id + '"]'),
                positionTop  = element.offset().top,
                positionLeft = element.offset().left + 100;

            this.buttonText(count);

            $(this.button)
                .css({
                    top: positionTop,
                    left: positionLeft
                })
                .removeClass('hide');
        }
    },

    /**
     * Disabling filters that are not related to found products.
     */
    disableFilters: function(filters) {
        // If there are related filters.
        if (filters) {
            $(this.element)
                .filter(':not(:checked)')
                .each(function(){
                    var id = $(this).data('id') || 0;
                    if (id) {
                        if (filters[id]) {
                            $(this)
                                .attr({disabled: false})
                                    .parent()
                                    .removeClass('disabled');
                        } else {
                            $(this)
                                .attr({disabled: true})
                                    .parent()
                                    .addClass('disabled');
                        }
                    }
                });
        }

        // No filters checked.
        else {
            $(this.element).attr({
                disabled: false
            });
        }
    },

    /**
     * Rendering button text.
     */
    buttonText: function(count) {
        var text = $(this.button).data('template') || '';
        if (text) {
            $(this.button).text(text.replace('%d', count));
        }
    }
};
