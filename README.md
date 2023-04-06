![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

# Data Hub Foundation
> A shared package of base models and configurations used across all the Data Hub's data services

The [data aggregator](https://github.com/art-institute-of-chicago/data-aggregator) and related [microservices](https://github.com/art-institute-of-chicago/?q=data*&type=&language=) all have a number of shared features and configurations. This repository is a composer package intended to consolidate all the shared functionality into a single place.

## Features

* Base model, controller, command and other common classes
* Shared [`config`](config) files
* Exception classes for commonly used errors
* Commonly used [`middleware`](src/Middleware)

## Requirements

* PHP 7.2.5 or greater
* Laravel 7.0.0 or greater

# Installing

To install in your project, run
`composer require aic/data-hub-foundation:dev-laravel-8-support`, or add the
following to the `composer.json` and then run `composer update`:
```json
"require": {
    "aic/data-hub-foundation": "dev-laravel-8-support",
}
```

To utilize formatting and linting in your project, add the following to the
`composer.json`:
```json
"scripts" : {
    "format": [
        "@putenv COMPOSER=vendor/aic/data-hub-foundation/composer.json",
        "@composer format"
    ],
    "lint": [
        "@putenv COMPOSER=vendor/aic/data-hub-foundation/composer.json",
        "@composer lint"
    ]
}
```
These scripts can be run in your project  with `composer lint` and
`composer format`.

## Developing

To start developing this project, install it on your app. Make modifications within the `vendor/aic/data-hub-foundation` folder to test-run your changes, then make and commit those changes after they've been confirmed to work ok.

Alternatively, you can install the package, then delete the `vendor/aic/data-hub-foundation` folder and create a symlink to the repo elsewhere on your workstation.

```shell
git clone https://github.com/art-institute-of-chicago/data-hub-foundation.git
cd data-hub-foundation/
```

## Contributing

We encourage your contributions. Please fork this repository and make your changes in a separate branch. To better understand how we organize our code, please review our [version control guidelines](https://docs.google.com/document/d/1B-27HBUc6LDYHwvxp3ILUcPTo67VFIGwo5Hiq4J9Jjw).

```bash
# Clone the repo to your computer
git clone git@github.com:your-github-account/data-hub-foundation.git

# Enter the folder that was created by the clone
cd data-hub-foundation

# Start a feature branch
git checkout -b feature/good-short-description

# ... make some changes, commit your code

# Push your branch to GitHub
git push origin feature/good-short-description
```

Then on github.com, create a Pull Request to merge your changes into our
`master` branch.

This project is released with a Contributor Code of Conduct. By participating in
this project you agree to abide by its [terms](CODE_OF_CONDUCT.md).

We welcome bug reports and questions under GitHub's [Issues](issues). For other concerns, you can reach our engineering team at [engineering@artic.edu](mailto:engineering@artic.edu)

## Licensing

This project is licensed under the [GNU Affero General Public License
Version 3](LICENSE).
