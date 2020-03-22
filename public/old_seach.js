const search = (function() {

    let searchInputEl;
    let searchInput; 
    let searchDelay;


    const init = function(input_selector, delay_ms){

        $(document).on('change paste keyup', input_selector, function(){
            clearInterval(searchDelay);
            searchDelay = setInterval(function(){
                if (inputValueHasChanged()) {
                    search();
                }
                clearInterval(searchDelay);
            }, delay_ms);
        });
        searchInputEl = $(input_selector);
    }

    const inputValueHasChanged = function () {
        let result = searchInput !== searchInputEl.val();
        searchInput = searchInputEl.val();
        return result;
    }

    const search = function() {

        console.log('search for ' + searchInputEl.val());

        let params = {
            search: searchInputEl.val()
        };

        let request = new XMLHttpRequest();
        request.open('GET', '/search' + buildQuery(params), true);

        request.onload = function() {
            if (this.status >= 200 && this.status < 400) {
                // Success!
                console.log(this.response);
            } else {
                // We reached our target server, but it returned an error
                console.log(this.response);

            }
        };

        request.onerror = function() {
        // There was a connection error of some sort
        };
        request.send();
    }

    const buildQuery = function(params) {
        return '?' + Object.keys(params)
            .map(function(key) {
                return key + '=' + encodeURIComponent(params[key]);
            }).join('&');
    }




    /*
    var check_input = function() {
        var input = $('.search-input');
        var value = input.val().trim();
        var token = $('input[name="search_token"]').val();
        var select = input.closest('.search-container').find('select').val();
        if(value == '' || value == null || value.length < 2 ) {
            close_results_box();
            window.last_search_val = null;
            window.search_results = [];
        } else {
            if (value !== window.last_search_val) {
                window.last_search_val = value;
                search(value, select, token);
            }
        }
    }


    var hover_search_box = function ( toggle_on ) {
        var container = $('.search-container');
        if ( toggle_on ) {
            container.css({
                'box-shadow' : '0 5px 7px rgba(0,0,0,.1), 0 0 1px rgba(0,0,0,.1)'
            });
        } else {
            container.css({
                'box-shadow' : '0 2px 1px rgba(0,0,0,.1), 0 0 1px rgba(0,0,0,.1)'
            });
        }
    }


    var microtime = function (get_as_float) {
        var unixtime_ms = (new Date).getTime();
        var sec = Math.floor(unixtime_ms/10000);
        return unixtime_ms/10000;
    }


    var search = function( value, select, token ){
        if(typeof(select) == 'undefined') {
            var select = 'prod'
        }
        $.ajax({
            async   : true,
            url     : ddpHelpers.ajaxUrl('/ajax_index.php'),
            data    : {
                search      :   value,
                select      :   select,
                token       :   token,
                timestamp   :   microtime(),
                c           :   'SearchQuickResults'
            },
            beforeSend : function() {
                var spinner_display =
                    '<div class="search-quick-res-info--text-wrapper">' +
                        '<div class="idle-spinner">' +
                            '<div class="bounce1"></div>' +
                            '<div class="bounce2"></div>' +
                            '<div class="bounce3"></div>' +
                        '</div>'+
                        '<span class="search-quick-res-info--idle-info">Suche läuft</span>'+  
                    '</div>';
                var spinner_display_first_time =
                    '<input name="timestamp" type="hidden" value ="0" />' +
                    '<div class="search-quick-res-info">' + spinner_display + '</div>';
                if ($( '#search-results-box-holder' ).length == 0) {
                    get_results_box('.search-input');
                    var resultsBoxHolder = $( '#search-results-box-holder' );
                    var resultsBox = $( '#search-results-box' );
                    resultsBox.empty();
                    resultsBox.append(spinner_display_first_time);
                    resultsBoxHolder.css('display', 'block');
                    adjustLayout();
                } else {
                    var resultsBoxHolder = $( '#search-results-box-holder' );
                    var resultsBox = $( '#search-results-box' );
                    resultsBox.find('.search-headline').html(spinner_display);
                }
            },
            success : function(response) {
                if (typeof response.json !== 'undefined' && typeof response.html !== 'undefined') {
                    window.search_results.push(response);
                }
            }
        });
    }


    var show_results = function() {
        if (window.search_results.length > 0){
            window.search_results.sort(function(a, b){
                var a_time = a.json.timestamp;
                var b_time = b.json.timestamp;
                if(a_time < b_time) return 1;
                if(a_time > b_time) return -1;
                return 0;
            });

            var last_result = window.search_results[0];
            if ($( '#search-results-box-holder' ).length == 0) {
                get_results_box('.search-input');
                var resultsBoxHolder = $( '#search-results-box-holder' );
                var resultsBox = $( '#search-results-box' );
                resultsBox.empty();
                resultsBox.append(last_result.html);
                resultsBoxHolder.css('display', 'block');
                adjustLayout();
            } else {
                var resultsBoxHolder = $( '#search-results-box-holder' );
                var resultsBox = $( '#search-results-box' );
                var timestamp = resultsBox.find('input[name="timestamp"]').val();
                if ( last_result.json.timestamp > timestamp ) {
                    resultsBox.empty();
                    resultsBox.append(last_result.html);
                    adjustLayout();
                }
            }
        }
    }

    var get_results_box = function () {
        if ( $('#search-results-box-holder').length == 0 ) {
            var resultsBoxHolder = $( '<div id="search-results-box-holder"></div>').appendTo('body');
            var resultsBox = $( '<div id="search-results-box" class="box-shadow css-clearfix" ></div>').appendTo(resultsBoxHolder);
            resultsBoxHolder.css({
                'position'          :   'absolute',
                'left'              :   '0px' ,
                'right'             :   '0px' ,
                'display'           :   'none',
                'z-index'           :   '999'
            });
            resultsBox.css({
                'margin'            :   '0 auto',
                'background-color'  :   'white'
            });
        }
    };

    var close_results_box = function () {
        $('#search-results-box-holder').remove();
    };

    */

    return {
        init: init,
    }
})();