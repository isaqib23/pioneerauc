<?php
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>Pioneer Auction</title>
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
</head>
<body>
<h3><center>Page Moved.</center></h3>


<script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.2/firebase-messaging.js"></script>

<script type="module">

//    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.4.1/firebase-app.js";
//    import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/9.4.1/firebase-messaging.js";

    var firebaseConfig = {
        // apiKey: "AIzaSyAf4TJWm7Nk9NH94a2zzsKEDOI5jWzm0I0",
        // authDomain: "staging-pioneer.firebaseapp.com",
        // projectId: "staging-pioneer",
        // storageBucket: "staging-pioneer.appspot.com",
        // messagingSenderId: "681167751688",
        // appId: "1:681167751688:web:22597f59b0860e7d209fbd",
        // measurementId: "G-EX6GGNTYR1"
        // // old vapikey = BKsea2R5AiAxsWNwqy-GxvoP4no4ixydgJKMliOvb-BFbGqKKIPCLBsKujg3Flq05IJm2Twy3s6QAMBhD85CmZ4

        //// new
        // apiKey: "AIzaSyCNWYg84yIe4GuEiyRyrOncRGAbV1QV61g",
        // authDomain: "pioneer-auctions-web.firebaseapp.com",
        // projectId: "pioneer-auctions-web",
        // storageBucket: "pioneer-auctions-web.appspot.com",
        // messagingSenderId: "660060683320",
        // appId: "1:660060683320:web:ec5c352ff2515a4879bacd",
        // measurementId: "G-KP6SW4TMZ6"
        //// end
        //// new vapikey = BD8lVtDFxsNZ5oEa4qJwvzMfMVfrI9MUVmDSA6Nr4gO_EslOmixxeYS3Fd2VBiqETz9kAtqWhhxAZjiKIzGB8O4


        //// new new
  apiKey: "AIzaSyAM8UgQdXaQOeAfEKOtVyuz3l9ojSd0lPM",
  authDomain: "pioneer-auctions-bd5f4.firebaseapp.com",
  projectId: "pioneer-auctions-bd5f4",
  storageBucket: "pioneer-auctions-bd5f4.appspot.com",
  messagingSenderId: "4940584713",
  appId: "1:4940584713:web:dafedc1f9e3424f8a865d8",
  measurementId: "G-VZ4FQ4GJVZ"

        //// end
        //// new new vapikey = BGmJ4N_ZZ5rO1hhzkY7wPmGbGNCoGxmpfmPf-LEBntj8euTzeP-9xHY6RVrZnsBNJ2Mz4oEs9TAAAGYGJ1n5FC0

    };

    firebase.initializeApp(firebaseConfig);

    const fcm = firebase.messaging();
     fcm.getToken({ vapidKey: 'BOoRPIDKFwDeATvSaYQTJ-DIh0RHDRFkVZ3VxWhUD3BQTNnjBPnkY8lft4IymKGIQ8qjoYzuBYuGrt0O5pM2gSk'}).then((currentToken) => {
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

    fcm.onMessage((data) => {
        if (data) {
            console.log('onMessage: ',data);
        } else {

            console.log('No registration token available. Request permission to generate one.');
        }
    });



   




</script>


<!---->
<!--<script src="https://www.gstatic.com/firebasejs/9.4.1/firebase-app.js"></script>-->
<!--<script src="https://www.gstatic.com/firebasejs/9.4.1/firebase-messaging.js"></script>-->





<!--<script type="module">-->
<!---->
<!--    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.4.1/firebase-app.js";-->
<!--    import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/9.4.1/firebase-messaging.js";-->
<!---->
<!--    const firebaseConfig = {-->
<!--        apiKey: "AIzaSyAf4TJWm7Nk9NH94a2zzsKEDOI5jWzm0I0",-->
<!--        authDomain: "staging-pioneer.firebaseapp.com",-->
<!--        projectId: "staging-pioneer",-->
<!--        storageBucket: "staging-pioneer.appspot.com",-->
<!--        messagingSenderId: "681167751688",-->
<!--        appId: "1:681167751688:web:22597f59b0860e7d209fbd",-->
<!--        measurementId: "G-EX6GGNTYR1"-->
<!--    };-->
<!---->
<!---->
<!--    initializeApp(firebaseConfig);-->
<!---->
<!--    const fcm = firebase.messaging();-->
<!---->
<!---->
<!--</script>-->


</body>
</html>
