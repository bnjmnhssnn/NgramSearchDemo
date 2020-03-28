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
                <title>NgramSearch Demo</title>
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
                                        <h1>NgramSearch Demo</h1>
                                        <p class="sub-heading">Search ~50.000 movie titles</p>
                                    </div>
                                    <div>
                                        <table class="stats-table">
                                            <tr>
                                                <td>Query time: <span id="stats-api-query">--</span></td> 
                                                <td>Response time: <span id="stats-api-response">--</span></td> 
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
                                <div class="footer">
                                    <a class="github-link" href="https://github.com/bnjmnhssnn/NgramSearch">
                                        <table>
                                            <tr>
                                                <td>
                                                    <svg height="16" viewBox="0 0 16 16" version="1.1" width="16">
                                                        <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path>
                                                    </svg>
                                                </td>
                                                <td>
                                                    NgramSearch on Github
                                                </td>
                                            </tr>
                                        </table>
                                    </a>
                                    <br>
                                    <a class="github-link" href="https://github.com/bnjmnhssnn/NgramSearchDemo">
                                        <table>
                                            <tr>
                                                <td>
                                                    <svg height="16" viewBox="0 0 16 16" version="1.1" width="16">
                                                        <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path>
                                                    </svg>
                                                </td>
                                                <td>
                                                    Demo app on Github
                                                </td>
                                            </tr>
                                        </table>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </body>
        </html>';
    exit;
}