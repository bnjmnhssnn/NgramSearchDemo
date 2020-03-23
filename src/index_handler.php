<?php
function run($http_client) : void
{
    header("HTTP/1.1 200 OK");
    echo
        '<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Titel</title>
                <link rel="stylesheet" href="styles.css">
                </script>
                <script type="text/javascript" src="search.js"></script>
                <script>
                    let initModules = function() {
                        search.init({
                            input_id: \'search-input\',
                            result_hook_id: \'result-hook\',
                            delay_ms: 250
                        });
                    }
                    if (document.readyState != \'loading\'){
                        initModules();
                    } else {
                        document.addEventListener(\'DOMContentLoaded\', initModules);
                    }
                </script>
            </head>
            <body>
                <table class="skeleton">
                    <tr>
                        <td>
                            <div class="main-box">
                                <form>
                                    <div>
                                        <h1>Ngram Search Demo</h1>
                                    </div>
                                    <div>
                                        <table class="stats-table">
                                            <tr>
                                                <td>API query time: <span id="stats-api-query">--</span></td> 
                                                <td>API response time: <span id="stats-api-response">--</span></td> 
                                            </tr>
                                        </table>
                                    </div>
                                    <div>
                                        <input 
                                            id="search-input"
                                            type="text"
                                            name="search"
                                            placeholder="Search"
                                            autocomplete="off"
                                        />
                                    </div>
                                </form>
                                <div id="result-hook"></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </body>
        </html>';
    exit;
}