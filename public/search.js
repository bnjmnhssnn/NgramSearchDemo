const search = (function() {

    let searchInputEl;
    let searchInput; 
    let searchDelay;

    const init = function(options) {

        document.querySelector('#' + options.input_id).addEventListener(
            'input',
            function() {
                clearInterval(searchDelay);
                searchDelay = setInterval(function(){
                    if (inputValueHasChanged()) {
                        search(options.api_url);
                    }
                    clearInterval(searchDelay);
                }, options.delay_ms);
            }
        );
        searchInputEl = document.getElementById(options.input_id);
    };

    const inputValueHasChanged = function () {
        let result = searchInput !== searchInputEl.value;
        searchInput = searchInputEl.value;
        return result;
    };

    const search = function(api_url) {

        console.log('search for ' + searchInput);

        let params = {
            search_string: searchInput
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
    };

    const buildQuery = function(params) {
        return '?' + Object.keys(params)
            .map(function(key) {
                return key + '=' + encodeURIComponent(params[key]);
            }).join('&');
    };

    return {
        init: init,
    };
})();