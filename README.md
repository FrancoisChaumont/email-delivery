# Email - send emails

![GitHub release](https://img.shields.io/github/release/FrancoisChaumont/email-delivery.svg)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/FrancoisChaumont/email-delivery/issues)
[![GitHub issues](https://img.shields.io/github/issues/FrancoisChaumont/email-delivery.svg)](https://github.com/FrancoisChaumont/email-delivery/issues)
[![GitHub stars](https://img.shields.io/github/stars/FrancoisChaumont/email-delivery.svg)](https://github.com/FrancoisChaumont/email-delivery/stargazers)
![Github All Releases](https://img.shields.io/github/downloads/FrancoisChaumont/email-delivery/total.svg)

PHP libray to send emails to several recipients

## Getting started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Requirements
PHP 7.1+ | a mail server

### Installation
Install this package with composer by simply adding the following to your composer.json file:  
```
"repositories": [
    {
        "url": "https://github.com/FrancoisChaumont/email-delivery.git",
        "type": "git"
    }
]
```
and running the following command:  
```
composer require francoischaumont/email-delivery
```

## Testing
Under the folder named *tests* you will find a test script ready to use.  
Only run in web browser, not CLI.

## Built with
* Visual Studio Code

## Authors
* **Francois Chaumont** - *Initial work* - [FrancoisChaumont](https://github.com/FrancoisChaumont)

See also the list of [contributors](https://github.com/FrancoisChaumont/email-delivery/graphs/contributors) who particpated in this project.

## License
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Notes
Todo: 
* Allow to add attachments to emails
* Handle exceptions instead of error numbers

