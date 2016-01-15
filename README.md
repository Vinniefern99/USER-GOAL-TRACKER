# REST API - Slim Framework. By Vince Fernald.

# Summary
I used the Slim PHP framework to build this API, and MAMP to run the MySql server. To test the API, I used basic HTML forms, as you will see when you run my program.

To prevent any invalid input from the user, I created my web application to not allow any sort of text input. For example, if the user wants to add a visit, they must chose from the list of cities that are stored in the database. If I were to add functionality to, for example, let a user add a new city using a textbox, I would add sanitation logic to validate the user input. But for the sake of this application, I decided to keep it enclosed and protected.

To delete records, I used a POST. I've been trained to stay away from the DELETE http method because some browsers do not support it.

## To run the server locally:
1. Install MAMP on your local machine in order to set up the database. 
2. Clone my project in the /Applications/MAMP/htdocs/ dirctory (if you are using a mac). If you are using a PC, the MAMP folder is most likely in C:/Program Files
3. Open the MAMP application and click "Start Servers"
4. Go to http://localhost:8888/slim-rest-api/
	
I loaded the database schema here: /data/data_schema.txt. This file contains the inserts needed to create the database locally. Run these SQL statements to upload the  data to MySql.

Database name: rest_api_data. 

Four tables:
city, 
state, 
user, 
visits

If you have any issues, see the the Slim Framework user guide here: http://docs.slimframework.com/start/get-started/

## Possible next steps in this project:
- Use the Google Maps API to reverse geocode the states from the latitude and logitude of cities.
- Remove the functions from index.php and move them into Controllers.
- Organize my SQL statements into a model layer and move them out of index.php.
- Add functionality to let the user add and delete cities.


## Endpoints:
Display the homepage:
$app->get ( '/')

Get a list of all states:
$app->get ( '/state')

Get a list of all cities in a particular state:
$app->get ( '/state/:state/cities')

Get a list of all users:
$app->get ( '/user')

View all cities a user has visited:
$app->get ( '/user/:user/visits')

View the states that a user has visited:
$app->get ( '/user/:user/visits/states')

Create rows of data to indicate they have visited a particular city:
$app->post ( '/user/:user/visits')

Allow a user to remove an improperly pinned visit.
$app->post ( '/user/:user/visit/:visit')
