
self.onnotificationclick = (event) => {
    if(event.notification.data.FCM_MSG.data.click_action){
        event.notification.close();
        event.waitUntil(clients.matchAll({
            type: 'window'
        }).then((clientList) => {
            for (const client of clientList) {
                if (client.url === '/' && 'focus' in client)
                    return client.focus();
            }
            if (clients.openWindow)
                return clients.openWindow(event.notification.data.FCM_MSG.data.click_action);
        }));
    }
};
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

const firebaseConfig = {
    apiKey: "Your_API Key",
    authDomain: "appSecret",
    projectId: "Project_Id",
    storageBucket: "Storage_Bucket",
    messagingSenderId: "Sender_Id",
    appId: "App_Id",
    measurementId: "Measurement_Id"
};

const app = firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    if (payload.notification.background && payload.notification.background == 1) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        return self.registration.showNotification(
            title,
            options,
        );
    }
});
