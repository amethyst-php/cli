# Skeleton Amethyst Tool

A package has been created to speed-up the development. Install the package globally.

    composer global require railken/amethyst-skeleton
    
If this is your first global composer package, you have to add the composer path

    export PATH=$PATH:$HOME/.composer/vendor/bin

## Initialize a new package

Go inside the folder project
	
	cd <my-package>
    
Execute the command init and follow the steps

    amethyst init
    
Copy the .env.example and configure it

    cp .env.example .env
    
Install all vendors packages
    
    composer update
    
Launch the tests

    ./vendor/bin/phpunit

You're now good to go.

## Add a new data

Go inside the folder project
	
	cd <my-package>
    
Execute the command init and follow the steps

    amethyst data
    
That's it!