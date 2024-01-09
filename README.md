# Socialmedia  
A project dedicated for creeating best social media site in the world! 
  
## Introduction  
Welcome to SocialMedia, a social media website created using XAMPP. This project provides a platform for users to connect, share, and engage with others in a social networking environment.  
  
## Prerequisites  
Before getting started, ensure that you have the following prerequisites installed:  
  
XAMPP: [Download and install XAMPP](https://www.apachefriends.org/download.html)  
Git: [Download and install Git](https://git-scm.com/downloads)  

## Setup  
Follow these steps to set up the SocialMedia project on your local machine:
  
### Empty htdocs Folder:  
  Make sure your htdocs folder in the XAMPP installation directory is empty.  
  
### Clone the Project:
Open a terminal or command prompt and run the following command:

    git clone https://github.com/prc16/socialmedia.git C:\xampp\htdocs
  
### Start XAMPP:
  Start the XAMPP control panel and ensure that the Apache and MySQL modules are running.  
  
Database Configuration:  
  
    1. Open phpmyadmin in your browser (http://localhost/phpmyadmin).
    2. Create a new database named 'socialmedia_db' with COLLATE=latin1_swedish_ci.
    3. Select 'socialmedia_db' and Import the SQL file located in the database directory of the project.
  
### Configuration File:  
Navigate to the config directory.  
Update the database connection details in config.php with your MySQL credentials.  

### Access the Website:
Open your web browser and navigate to http://localhost/. You should see the SocialMedia website.
  
## Contributing

### If you would like to contribute to SocialMedia, please follow these steps:

Fork the repository.
Create a new branch for your feature or bug fix:

    git checkout -b feature-name

Make your changes and commit them:

    git commit -m "Description of changes"

Push your changes to your fork:

    git push origin feature-name

Open a pull request on the [SocialMedia](https://github.com/prc16/socialmedia) repository with a detailed description of your changes.
  
## License

This project is licensed under the GNU GENERAL PUBLIC LICENSE - see the LICENSE file for details.
