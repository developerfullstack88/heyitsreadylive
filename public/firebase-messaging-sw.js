/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/6.3.4/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
  apiKey: "AIzaSyAi3RTmC35PzSoM6chGFgXJqj-MCL4O3TY",
   authDomain: "ipixup-web-e4900.firebaseapp.com",
   databaseURL: "https://ipixup-web-e4900.firebaseio.com",
   projectId: "ipixup-web-e4900",
   storageBucket: "ipixup-web-e4900.appspot.com",
   messagingSenderId: "548664581545",
   appId: "1:548664581545:web:57abfb8ddda27b3912b8b8",
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});
