import dweepy # Import the dweepy module
import grovepi # Import the grovepi module
from grovepi import * # Import everything from the grovepi module
from threading import Thread # Import the Thread class from the threading module


led = 5 # Connect the LED to digital port D5
grovepi.pinMode(led,"OUTPUT") # Set pin mode for port D5 as an output

button_pressed = 0

# Method to listen for dweets from a specific thing called PBturnOnLed
def listen():
    print("I am working!")
    global button_pressed

    for dweet in dweepy.listen_for_dweets_from("PBturnOnLed"): # For loop listens for dweets from a specific thing called PBturnOnLed
        content = dweet["content"] # Store the content from each dweet into a variable called content
        print(str(content))
        try:
            button_pressed = content["ButtonPressed"]
        except:
            print("An exception occurred")
        thing = dweet["thing"] # Store the thing from each dweet into a variable called thing
        print("Reading from PBturnOnLed: " + str(content))
        print(thing) # Print the variable called thing
        print("")

        try:
            # if int(button_pressed) == 1:
            #     print("On")
            #     brightness = 255
            #     grovepi.analogWrite(led,brightness) # Give PWM output to LED
            # else:
            #     print("Off")
            #     brightness = 0
            #     grovepi.analogWrite(led,brightness) # Give PWM output to LED

            if int(button_pressed) == 0:
                print("Off")
                brightness = 0
                grovepi.analogWrite(led,brightness) # Give PWM output to LED
        except:
            print("An exception occurred")
    print("Listening Ending!") # Print Listening Ending!

listener_thread = Thread(target=listen) # Create a new listener thread passing in the listen() method


listener_thread.start() # Start listener thread