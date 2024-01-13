<div align="center">

[![Whisper](https://raw.githubusercontent.com/prc16/whisper/master/.github/logo.svg)](#readme)

</div>

# Whisper  
A Privacy-Centric Social Media Platform.
  
## Introduction  
Whisper is a social media website that places user privacy at
its core. In an era where data security and confidentiality are
paramount concerns, Whisper provides a refreshing approach
to online social interaction.
  
## Key Features:  
  
### Anonymous Posting:  
Users can share their thoughts, photos, and updates without revealing their true identity.  
  
### End-to-End Encryption:  
Private messages and media files are protected with end-to-end encryption.  
  
### Customizable Privacy Settings:  
Tailor settings to individual privacy needs for a personalized online presence.  

### Community-driven Moderation:  
Empower users to actively participate in maintaining a safe and respectful online environment.  

### Self-Destructing Content:
Users can set a time limit on their posts, after which the content automatically disappears.
  
## Prerequisites  
Before getting started, ensure that you have the following prerequisites installed:  
  
XAMPP: [Download and install XAMPP](https://www.apachefriends.org/download.html)  

## Setup  
Follow these steps to set up the Whisper project on your local machine:
  
### Empty htdocs Folder:  
  Make sure your htdocs folder in the XAMPP installation directory is empty.  
  
### Clone the Project:
Open a terminal or command prompt and run the following command:

    git clone https://github.com/prc16/whisper.git C:\xampp\htdocs
  
### Start XAMPP:
  Start the XAMPP control panel and ensure that the Apache and MySQL modules are running.  
  
### Database Configuration:  
Open phpmyadmin in your browser (http://localhost/phpmyadmin).  
Import the SQL file located in the root directory of the project.  
  
### Configuration File:  
Navigate to the config directory.  
Update the database connection details in config.php with your MySQL credentials.  

### Access the Website:
Open your web browser and navigate to http://localhost/. You should see the Whisper website.
  
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
