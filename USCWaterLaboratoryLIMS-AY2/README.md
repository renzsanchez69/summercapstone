# USCWaterLaboratoryLIMS-AY2018-2019
USC Water Laboratory Sample and Inventory Information Management System Using Barcode Technology

// PROJECT: USC Water Laboratory Inventory and Sample Information Management System Using Barcode Technology
// Developed by:
//	Klevin P. Cinco
//	Daryne T. Mayol
//	Arjune Tristram C. Orbiso
//	Nap Quinleeh Vale

!--READ ALL THE INFORMATION BEFORE EXECUTING THE INSTRUCTIONS--!

Instructions:

1. Go to [project] > [software installers] 
2. Install all softwares unless you already have them.
3. Follow the User's Installation Guide found in the document or in [project] > [documentation](After the Curriculum Vitae)
	-Optional : check Chapter 2 of the guide for the System Requirements
	-Proceed directly to Chapter 3 Installation and follow the steps.
	tl:dr You must have a github account.

A '.env' file is provided for you in the [project] folder. Copy/cut the file and place it in the cloned project folder. (Important: This skips the [cp .env.example .env] command)

Commands list:
- git clone https://github.com/Orbilat/project_ict146.git (asks for github credentials)
- cp .env.example .env (skippable)
- composer update
- php artisan key:generate
- php artisan migrate:fresh --seed
- composer dump-autoload (optional; may fix certain seeding errors)
- php artisan serve

!! Note: Sublime is being used instead of VSC in the software installers. So instead of using the built-in terminal of VSC you can just use the Git bash terminal instead. (refers to Step 10 in Chapter 3 Installation) !!

!! Note: To use Nexmo you must create your own account and add the API key and secret to your .env file !!

!! Note: Change APP_DEBUG = TRUE to FALSE when deploying the project online in the .env file !!

!! Note: There are some errors that may appear due to special characters in the $PATH_NAME of the project. Rename [project_ict146] after cloning by removing special characters like '_' and ' ' (underscores and spaces) !!
