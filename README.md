# Installation instruction:
# Mysql database setup
mysql -u[YOUR_USERNAME] -p[YOUR_PASSWORD] < database.sql

# Change database connections in "config/config.php" file

# Launch server
php -S localhost:8080 -t public

# Open "localhost:8080" on your browser

# Project was made using the MVC arhitecture. 
# Structure:
# public - contains public files such as images, css and js files.
# lib - contains main project files including the Model-View-Controller files.
# config - contains config file.
# log - contains log file.