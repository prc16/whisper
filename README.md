<div align="center">

[![Whisper](https://raw.githubusercontent.com/prc16/whisper/master/.github/whisper-logo.png)](#readme)

### Whisper: A Privacy-Centric Social Media Platform.  

Whisper is a social media website that places user privacy at its core. In an era where data security and confidentiality are paramount concerns, Whisper provides a refreshing approach to online social interaction.  
  
</div>

## Key Features:  
  
* Anonymous Posting:  
    * Users can share their thoughts, photos, and updates without revealing their true identity.  
  
* End-to-End Encryption:  
    * Private messages and media files are protected with end-to-end encryption.  
  
* Customizable Privacy Settings:  
    * Tailor settings to individual privacy needs for a personalized online presence.  

* Community-driven Moderation:  
    * Empower users to actively participate in maintaining a safe and respectful online environment.  

* Self-Destructing Content:
    * Users can set a time limit on their posts, after which the content automatically disappears.
  
## Prerequisites  
Before getting started, ensure that you have the following prerequisites installed:  
  
XAMPP: [Download and install XAMPP](https://www.apachefriends.org/download.html)  

## Setup  
Follow these steps to set up the Whisper project on your local machine:  
  
### Clone the Project:  
Make sure you have a clean web hosting root directory. if you are using xampp, make sure htdocs directory is empty.  
Open a terminal or command prompt and clone this reposetory in your web hosting root:  

    git clone https://github.com/prc16/whisper.git C:\xampp\htdocs
  
### Start XAMPP:
  Start the XAMPP control panel and ensure that the Apache and MySQL modules are running.  
  Also make sure your apache mod_rewrite mode is enabled. In xampp it's enabled by default.  
  
### Database Configuration:  
Open phpmyadmin in your browser (http://localhost/phpmyadmin).  
Import database.sql file located in database directory.  
  
### Configuration File:  
Update your database connection details, uploads directory, etc...  in the 'config.php' located in 'database' directory.  
  
### Access the Website:
Open your web browser and navigate to http://localhost/whisper. You should see the Whisper website.
  
## Contributing
  
### If you would like to contribute to Whisper, please follow these steps:

Fork the repository.
Create a new branch for your feature or bug fix:

    git checkout -b feature-name

Make your changes and commit them:

    git commit -m "Description of changes"

Push your changes to your fork:

    git push origin feature-name

Open a pull request on the [Whisper](https://github.com/prc16/whisper) repository with a detailed description of your changes.
  
## License

This project is licensed under the GNU GENERAL PUBLIC LICENSE - see the LICENSE file for details.
