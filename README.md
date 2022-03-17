# Avocado-Mold-Dreams
The Avocado Mold web store
created by Conor O'Brien, Pat Tagliavia, and Regina Vanata

## A BRIEF WALKTHROUGH AND DESCRIPTION:
Welcome to our web store! Upon entering, you will find a database-generated list of all items for sale. Click an item to add it to your shopping cart.

The 'my account' page allows you to create a new customer account or login to your active customer portal. It will route appropriately based on whether or not a customer account is an admin (customer to customer portal, admin to admin portal). Passwords are hashed and stored in the database.

If you log into an active customer account, you will be able to verify or alter your personal contact info and shipping information. You will also be able to review your order history and whether or not the items have shipped, below which you can see all submitted questions and their respective answers.

If you log into an active admin account, you will be able to perform a wide variety of functions, including answer customer questions, add new products to the database/server, archive or delete items, fulfill orders, alter user accounts from admin to non-admin and back, or review tables filled with inventory listings, customer questions and the respective data, a full history of orders, and a full table of users + user info.

You can send questions to the site admin from any page (send your fellow admins a message as well if you'd like).

One active user admin email + password:
conorepobrien@gmail.com
OrchOttersPass


## PROJECT REQUIREMENTS:
1. Separates all database/business logic using the MVC pattern.
 * Model: business logic and database processes are found in the model folder
 * View: the HTML file for each of the views can be found in the views folder
 * Controller: the controller folder contains the Controller class, which receives input from views and appropriately routes to data validation or database requests, while simultaneously communicating back to the views and filling them with data
 * the site's index.php calls routing functions in the Controller to appropriately direct requests
 * all classes are contained in a class folder
 * all javascript is contained within a scripts folder
 * all styling is contained within a styles folder

2. Routes all URLs and leverages a templating language using the Fat-Free framework.
 * All routes are found via the index page, and all leverage a the Fat-Free Framework templating

3. Has a clearly defined database layer using PDO and prepared statements. 
 * The model contains our database layer, which is used for most of the site's functions.
 * Prepared statements are used in the site's database-communicating functions.

4. Data can be added and viewed.
 * The account page, home page (with displayed products for sale), admin page, and customer page all have unique ways of retrieving, altering, and adding data to the database.

5. Has a history of commits from all team members to a Git repository. Commits are clearly commented.
 * Conor, Pat, and Regina all have commits in the shared Git repo. Each commit contains clear commentary.

6. Uses OOP, and utilizes multiple classes, including at least one inheritance relationship.
 * The page uses several classes for operations, including a standard user class, a premium user class (containing applicable discounts) that extends the standard user, and a cart class for creation of user orders.

7. Contains full Docblocks for all PHP files and follows PEAR standards.
 * All functions have docblocks, and the PHP follows PEAR standards.

8. Has full validation on the client side through JavaScript and server side through PHP.
 * JavaScript validation checks input from the client side, while the controller PHP file checks for all sorts of validation server side (in addition to the data validation class).

9. All code is clean, clear, and well-commented. DRY (Don't Repeat Yourself) is practiced.
 * The code is clean, clear, comments, and EXPANSIVE.

10. Your submission shows adequate effort for a final project in a full-stack web development course.
 * This submission shows a strong understanding + usage of the F3 framework, in addition to php, css, js, and html.
 * The database is complex and well-designed.

11. BONUS:  Incorporates Ajax that access data from a JSON file, PHP script, or API. If you implement Ajax, be sure to include how you did so in your readme file.
 * Our project incorporates Ajax to gather input of item addition to/subtraction from the shopping cart.


![Orchestrating Otters UML](https://user-images.githubusercontent.com/91850829/158900733-3ec02026-61a7-460d-823b-10bd8badc203.png)

