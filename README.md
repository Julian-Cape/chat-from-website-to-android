# chat-website-to-android
The aim is to create a CHAT on your website and an ANDROID APP, so you can reply to "clients" easily with your phone (android + firebase cloud messaging)

IMPORTANT: sign up to Firebase Cloud Messaging console , register your app and take note of your app id, download json-token and put it in your app project folder 

index.php : it contains the code for the webchat -> layout to be personalized

SERVER_PHP folder: it contains php code to be uploaded to the website, it manages chat-communication from a client visiting the website
 - open points:
  -> datalog old chat and log errors
  -> store info of the client
  
MOBILE_SRC folder: it contains source code to be insert to an android app project, it manages chat-communication from the mobile app to the client who opened a chat on the website
 - open points:
  -> first time fails to send token -> app needs to be reloaded to send correctly the token to server 
  -> manage rotation of screen!
  -> manage in a better way notifications
  -> manage multiple incoming chats
  -> add close chat connection
