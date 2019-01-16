# Skeleton Amethyst Tool

[![Build Status](https://travis-ci.org/railken/cli-amethyst.svg?branch=master)](https://travis-ci.org/railken/cli-amethyst)

A package has been created to speed-up the development. Install the package globally.

    composer global require railken/cli-amethyst
    
If this is your first global composer package, you have to add the composer path

    export PATH=$PATH:$HOME/.composer/vendor/bin

## Initialize a new package

Add all the needed file to create an amethyst-package

### Steps

Go inside the folder project
	
	cd <my-package>
    
Execute the command lib:init and follow the steps

    amethyst lib:init
    
Copy the .env.example and configure it

    cp .env.example .env
    
Install all vendors packages
    
    composer update
    
Launch the tests

    ./vendor/bin/phpunit

You're now good to go.

## Add new entity

Models, Validators, Controllers, migrations and more... Generate all of this with one simple command.

### Steps

Go inside the folder project
	
	cd <my-package>
    
Execute the command and follow the steps

    amethyst lib:data
    
That's it!

## Test

Test your package using the standard rules. Runs phpstan, php-cs-fixer and phpunit

### Steps

Go inside the folder project
    
    cd <my-package>
    
Execute the command and follow the steps

    amethyst test
    
That's it!