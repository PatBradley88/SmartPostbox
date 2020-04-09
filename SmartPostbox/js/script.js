$(document).ready(function(){
    let cache = (localStorage.getItem('data') == undefined
            || localStorage.getItem('data') == null) ? {
        'timestamp': '',
        'light' : 0,
        'distance' : 0,
        'temperature' : 0,
        'humidity' : 0
    } : localStorage.getItem('data');
    let data = {'payloadString': JSON.stringify(cache)}
    onMessageArrived(data)
    let BROKER = 'broker.mqttdashboard.com';
    let PORT = 8000;
    let CLIENTID = 'clientId-PIOVRy9yKe';
    let SUBSCRIBER = 'PBNCI/publish1';
    client = new Paho.MQTT.Client(BROKER, PORT, CLIENTID);
    client.connect({onSuccess:onConnect});
    client.onMessageArrived = onMessageArrived;


    // called when the client connects
    function onConnect() {
        // Once a connection has been made, make a subscription and send a message.
        console.log("onConnect");
        client.subscribe(SUBSCRIBER);
    }

    function onMessageArrived(message) {
        console.log("onMessageArrived:"+message.payloadString);
        let readings = JSON.parse(message.payloadString);
        $('#light_value').text(readings['light']);
        $('#distance_value').text(readings['distance']);

        var distance = readings['distance'];
        console.log(distance);

        if (distance < 10 && distance != 0) {
            document.getElementById("postbox").innerHTML = "You've got Mail!";
          } else {
              document.getElementById("postbox").innerHTML = "Postbox Empty!";
          }
        $('#temperature_value').text(readings['temperature']);
        $('#humidity_value').text(readings['humidity']);
        localStorage.setItem('light', readings['light']);
        localStorage.setItem('distance', readings['distance']);
        localStorage.setItem('temperature', readings['temperature']);
        localStorage.setItem('humidity', readings['humidity']);
    }

    function getCachedData(){
        let light = localStorage.getItem('light') != undefined ? localStorage.getItem('light') : "-";
        let distance = localStorage.getItem('distance') != undefined ? localStorage.getItem('distance') : "-";
        let temperature = localStorage.getItem('temperature') != undefined ? localStorage.getItem('temperature') : "-";
        let humidity = localStorage.getItem('humidity') != undefined ? localStorage.getItem('humidity') : "-";
        return {
            'timestamp': new Date().toISOString(),
            'light': light,
            'distance': distance,
            'temperature': temperature,
            'humidity': humidity,
        };
    }


})