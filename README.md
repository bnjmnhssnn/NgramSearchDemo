NgramSearchDemo
================

NgramSearchDemo is a demo client app for [NgramSearch]. It's purpose is to show the interaction with the NgramSearch api. The implementation of the 2nd sorting pass for the raw api search result is very basic and naive.


Install
-------
You need a running instance of [NgramSearch] with at least one search index to run the demo app.

**1. Clone from Github:**

```sh
git clone https://github.com/bnjmnhssnn/NgramSearchDemo.git
```

**2. Environment setup:**
Setup your webserver to expose the *public* folder and redirect all requests to *public/index.php*. For Apache, a *.htaccess* file is contained. Then create a file *src/env.php* with following content (replace the bracketed variables):

```php
<?php
define('SEARCH_API_URL', [your api url]);
define('SEARCH_API_INDEX', [your search index]);
define('SEARCH_API_USER', [api user]);
define('SEARCH_API_PASS', [api pass]);
```




[NgramSearch]: https://github.com/bnjmnhssnn/NgramSearch "NgramSearch"
