  importScripts('https://www.gstatic.com/firebasejs/8.2.2/firebase-app.js');
   importScripts('https://www.gstatic.com/firebasejs/8.2.2/firebase-messaging.js');


  var firebaseConfig = {
      apiKey: "AIzaSyAf4TJWm7Nk9NH94a2zzsKEDOI5jWzm0I0",
      authDomain: "staging-pioneer.firebaseapp.com",
      projectId: "staging-pioneer",
      storageBucket: "staging-pioneer.appspot.com",
      messagingSenderId: "681167751688",
      appId: "1:681167751688:web:22597f59b0860e7d209fbd",
      measurementId: "G-EX6GGNTYR1"
  };

  firebase.initializeApp(firebaseConfig);

  const fcm = firebase.messaging();
  fcm.getToken({ vapidKey: 'BKsea2R5AiAxsWNwqy-GxvoP4no4ixydgJKMliOvb-BFbGqKKIPCLBsKujg3Flq05IJm2Twy3s6QAMBhD85CmZ4' }).then((currentToken) => {
      if (currentToken) {
          console.log('getToken: ',currentToken);
          // Send the token to your server and update the UI if necessary
          // ...
      } else {
          // Show permission request UI
          console.log('No registration token available. Request permission to generate one.');
  // ...
  }
  }).catch((err) => {
      console.log('An error occurred while retrieving token. ', err);
  });


  fcm.onBackgroundMessage((data) => {
      if (data) {
          console.log('onBackgroundMessage: ',data);
      } else {

          console.log('No registration token available. Request permission to generate one.');
  }
  });

