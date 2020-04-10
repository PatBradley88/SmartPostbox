import RPi.GPIO as GPIO
from datetime import datetime
import time
import grovepi
import math
from grovepi import *
import threading
import paho.mqtt.client as mqtt #importing client 1
import json
from threading import Thread

#
temp_humidity_sensor    = 2     #Temperature/Humidity sensor Port D2
light_sensor = 0 # light using A0
ultrasonic_ranger = 4 # ultrasonic sensor using D4
blue_led = 5  # blue led using D5

#Setting state variables
publishing = True
listening = True
publishing_thread = None
listen_thread = None
terminate = False

#MQTT setting
BROKER_ADDRESS = 'broker.mqttdashboard.com'
LISTEN_CLIENT_ID = 'clientId-jpDkAhpofo'
PUBLISH_CLIENT_ID = 'clientId-yRmPLzgtO9'
TOPIC = 'PBNCI/publish1'
SUBSCRIBER = 'PBNCI/listen1'

client = None

pinMode(temp_humidity_sensor, "INPUT")
pinMode(light_sensor, "INPUT")
pinMode(ultrasonic_ranger, "INPUT")
pinMode(blue_led, "OUTPUT")

blue=0
white=1
therm_version = blue            # If you change the thermometer, this is where you redefine.

time_for_sensor  = 1*60*60  # Take sensor data every 1 hour

time_to_sleep       = 10        # The main loop runs every 10 seconds.

def read_light_sensor():
    light_level = grovepi.analogRead(light_sensor)
    return(light_level)

def read_ultrasonic_ranger():
    distant = ultrasonicRead(ultrasonic_ranger)
    print(distant, 'cm')
    if distant <= 10:
            digitalWrite(blue_led,1)
            print("You've got mail!")
    else:
            digitalWrite(blue_led,0)
            print("Postbox is Empty")
    return(distant)

def read_temp_humidity_sensor():
    try:
        temp,humidity = grovepi.dht(temp_humidity_sensor,therm_version)   # Here we're using the thermometer version.
            #Return -1 in case of bad temp/humidity sensor reading
        if math.isnan(temp) or math.isnan(humidity):        #temp/humidity sensor sometimes gives nan
            return [-1,-1,-1]

        return [temp,humidity]

    except (IOError,TypeError) as e:
            return [-1,-1,-1]

def on_connect(client, userdata, flags, reasonCode, properties):
    print('Connected to MQTT Broker')
    print('Connection flags=%s' % flags)
    print('Reason Code=%s' % reasonCode)
    print('Properties=%s' % properties)

def on_publish(client, userdata, mid):
    print('Published to=%s' % mid)

def on_subscribe(client, userdata, mid, reasonCode, properties):
    print('Subscribed to=%s' % mid)
    print('Reason Code=%s' % reasonCode)
    print('Properties=%s' % properties)

def on_message(client, userdata, message):
    str_message = str(message.payload.decode('utf-8'))
    print('Message received=%s' % str_message)
    print('Message topic=%s' % (message.topic))
    #try
    message = json.loads(str_message) #pass the message as json
    global publishing
    global publishing_thread

    if message.get('terminate'):
        global terminate
        global listening
        listening = False
        publishing = False
        client.disconnect()
        client.loop_stop()
        terminate = True

    if message.get('publishing'):
        publishing = True
        print('publish')
        if not  publishing_thread.is_alive():
            print('thread not alive')
            publishing_thread = Thread(target=publish)
            publishing_thread.daemon = True
            publishing_thread.start()
        else:
            publishing = False
            print("Not Publishing")

    #except ValuerError as value_error:
    #    print('Message received has wrong formed data object')
    #except Exception as error:
    #    print ('An error occurred')
    #    print (error)


def on_disconnect(client, userdata, reasonCode, properties):
    #need to add functionality

    print('Disconnected from MQTT Broker')
    print('Reascon Code =%s' % reasonCode)
    print('Properties=%s' % properties)

def on_socket_close(client, userdata, reasonCode, properties):
    #add functionality

    print('MQTT Broker Socket Closed')
    print('Reason Code=%s' % reasonCode)
    print('Properties=%s' % properties)

def on_socket_unregister_write(client, userdata, reasonCode, properties):
    #add functionality
    print('MQTT Broker Socket Closed')
    print('Reason Code=%s' % reasonCode)
    print('Properties=%s' % properties)

def publish():
     #reading all sensors and publishing on mqtt broker

    client = start_client(PUBLISH_CLIENT_ID)
    global publishing
    while publishing:
        print('publishing now')
        light = read_light_sensor()
        distance = read_ultrasonic_ranger()
        temperature = read_temp_humidity_sensor()
        humidity = read_temp_humidity_sensor()
        #light = 0
        #distance = 0
        #temperature = 0
        #humidity = 0
        
        
        readings = {
            'timestamp': datetime.now().isoformat(),
            'light': light,
            'distance': distance,
            'temperature': temperature,
            'humidity': humidity
        }
        
        client.publish(TOPIC, json.dumps(readings))
        print('Published readings: ', readings)
        client.loop(.1)
        time.sleep(10)
    print('Stop publishing')
    
def listen(publisher):

    #listening messages for the subscribed topic

    client = start_client(LISTEN_CLIENT_ID)
    client.subscribe(SUBSCRIBER)
    print('Subscribed to topic.')
    while listening:
        client.loop(.1)
    
def start_client(client_id):
    #start mqtt client then connect to the borker and start looping
    client = mqtt.Client(client_id)
    client.connect(BROKER_ADDRESS)
    #setting mqtt methods
    client.on_connect=on_connect
    client.on_publish=on_publish
    client.on_subscribe=on_subscribe
    client.on_message=on_message
    client.on_disconnect=on_disconnect
    client.on_socket_close=on_socket_close
    client.on_socket_unregister_write=on_socket_unregister_write
    return client

def main():
    global publishing_thread
    global listen_thread
    publishing_thread = Thread(target=publish)
    publishing_thread.daemon = True
    listen_thread = Thread(target=listen, args=(publishing_thread,))
    listen_thread.daemon = True
    listen_thread.start()
    
    while terminate == False:
        pass
    
if __name__ == '__main__':
    try:
        main()
        
    except KeyboardInterrupt:
        sys.exit(0)
        