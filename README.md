# Skeleton Amethyst Tool

A package has been created to speed-up the development. Install the package globally.

    composer global require railken/amethyst-skeleton
    
If this is your first global composer package, you have to add the composer path

    export PATH=$PATH:$HOME/.composer/vendor/bin

# Create your package

Go inside the folder project
	
	cd <my-package>
    
Now you can execute the command 

    amethyst init <my-package>

Create a new data

    amethyst data <my-package> <my-data>
    
Copy the .env.example and configure it

    cp .env.example .env
    
Install all vendors packages
    
    composer update
    
Launch the tests

    ./vendor/bin/phpunit
    