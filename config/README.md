General usage:

* Copy all files from `config/publish/` to you applications `config/`.
* Don't copy files from `config/default` unless you run into errors.

When upgrading package, the following files have customizations:

* default/aic.php
* default/app.php
* default/auth.php
* default/sentry.php
* publish/app.php
* publish/detabase.php

All other files can (probably) be overwritten entirely with new versions.
