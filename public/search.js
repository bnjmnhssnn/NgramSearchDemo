const search = (function() {

    let searchInputEl;
    let searchInput; 
    let searchDelay;

    const init = function(input_id, delay_ms) {

        document.querySelector('#' + input_id).addEventListener(
            'input',
            function() {
                clearInterval(searchDelay);
                searchDelay = setInterval(function(){
                    if (inputValueHasChanged()) {
                        search();
                    }
                    clearInterval(searchDelay);
                }, delay_ms);
            }
        );
        searchInputEl = document.getElementById(input_id);
    };

    const inputValueHasChanged = function () {
        let result = searchInput !== searchInputEl.value;
        searchInput = searchInputEl.value;
        return result;
    };

    const search = function() {

        console.log('search for ' + searchInput);

        let params = {
            search: searchInput
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