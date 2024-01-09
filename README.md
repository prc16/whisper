# Socialmedia  
A project dedicated for creeating best social media site in the world! 

## Introduction  
Welcome to SocialMedia, a social media website created using XAMPP. This project provides a platform for users to connect, share, and engage with others in a social networking environment.  

## Prerequisites  
Before getting started, ensure that you have the following prerequisites installed:  

XAMPP: Download and install XAMPP  
Git: Download and install Git  

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
    2. Create a new database named socialmedia.
    3. Import the SQL file located in the database directory of the project.

### Configuration File:  

    Navigate to the config directory.
    Update the database connection details in config.php with your MySQL credentials.

### Access the Website:
Open your web browser and navigate to http://localhost/. You should see the SocialMedia website.
