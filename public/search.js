const search = (function() {

    let searchInputEl;
    let resultHookEl;
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
        resultHookEl = document.getElementById(options.result_hook_id);
    };

    const inputValueHasChanged = function () {
        let result = searchInput !== searchInputEl.value;
        searchInput = searchInputEl.value;
        return result;
    };

    const search = function() {

        if(searchInput == '') {
            removeResults();
            return;
        }
        let params = {
            search_string: searchInput
        };
        let request = new XMLHttpRequest();
        request.open('GET', '/search' + buildQuery(params), true);

        request.onload = function() {
            if (this.status >= 200 && this.status < 400) {
                // Success
                showResult(JSON.parse(this.response.trim()));
            } else {
                // Error
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

    const showResult = function(result) {
        var wrapper = document.createElement('div');
        wrapper.innerHTML = result.html;
        removeResults();
        resultHookEl.appendChild(wrapper);
    }

    const removeResults = function() {
        while(resultHookEl.firstChild) {
            resultHookEl.removeChild(resultHookEl.firstChild);
        }
    }

    return {
        init: init,
    };
})();