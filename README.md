## PHP Push notifications
The product was build to be used with any Mobile Application that requires a users to register and send push notifications to devices.
Will be adding a Xcode and Appcelerator projects showing the usage in a Mobile App  

## Motivation
This project is to show how to register users for push notification on your MySQL database.


## Tech/framework used
ApnsPHP for notifications to iOS - https://github.com/immobiliare/ApnsPHP

<b>Built with</b>
- PHP

## Features
Register users to a local MySQL database with App version, platform and device token
Unsubscribe users from push notification and remove them from the database  
Send Push notification to iOS users 
Send Push notification to Android users
Send Push notification to all users
 
## Installation
Import push_notifications.sql 
Configure your database setting in dbconnect.php
Configure $ENV = "PRODUCTION"; // Set SANDBOX or PRODUCTION in config.php for iOS
Configure $googleKey in config.php with your Google Developer Console key

## API Reference

Register User: http://localhost/demo-push-notifications/users.php
Method: POST
Header: Content:application/json
<code><br>
{ <br>
"request":"register",<br>
"deviceToken":"Android-Device-Token",<br>
"appVersion":"1",<br>
"appOS":"ANDROID"<br>
}
</code>
<br>
Register User: http://localhost/demo-push-notifications/push.php
Method: POST
Header: Content:application/json
<code><br>
{<br>
"request":"iOS",<br>
"message":"Hello world"<br>
}
</code>
<br>
Register User: http://localhost/demo-push-notifications/push.php
Method: POST
Header: Content:application/json
<code><br>
{<br>
"request":"Android",<br>
"title":"Android title",<br>
"message":"Hello world"<br>
}
</code>
<br>
Register User: http://localhost/demo-push-notifications/push.php
Method: POST
Header: Content:application/json
<code><br>
{<br>
"request":"All",<br>
"title":"Android title",<br>
"message":"Hello world"<br>
}
</code>
<br>
You can also install the Chrome extension Rest less Client https://chrome.google.com/webstore/detail/restlet-client-rest-api-t/aejoelaoggembcahagimdiliamlcdmfm?hl=en<br>
Then import tests.json

## How to use?
To your Google Key for $googleKey in config.php:

Open the Google Developers Console.
If you haven't created an API project yet, click Create Project.
Get your key from the API credentials

For iOS to add your production and development .pem certificates
https://github.com/immobiliare/ApnsPHP/blob/master/Doc/CertificateCreation.md 

## Credits
ApnsPHP - https://github.com/immobiliare/ApnsPHP 

#### Anything else that seems useful

## License
A short snippet describing the license (MIT, Apache etc)

MIT © [Roy Sinclair]()