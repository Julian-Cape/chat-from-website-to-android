# chat-from-website-to-android
The project aim is to create a Chat on your website. The web admin can reply to users directly from his/her phone (android app)

SERVER_PHP folder: it contains php code to be uploaded to the website, it handles communication from a client visiting the website
 - open points:
  -> improve graphics
  -> add close chat connection
  -> datalog old chat and log errors
  -> store info from the client
  
MOBILE_SRC folder: it contains source code to be insert to an android app project, it handles communication from the mobile app to the client who opened a chat on the website
 - open points:
  -> manage app power saving
  -> wake up the app when there is a chat incoming
  -> manage multiple incoming chats
  -> add close chat connection
