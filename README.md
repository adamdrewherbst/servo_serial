Gives you a simple web interface for programming a low-power servo with an Arduino (I'm using the TowerPro SG92R with the Arduino Uno).

Set up a local web server on your computer, then place this repo as a subdirectory under your server root.

On OSX you would instead set it up as a site for your user:
https://discussions.apple.com/docs/DOC-3083

You must have Python installed and configure the web server to allow Python scripts to run as CGI from this directory.  Instructions vary by server/OS.

Also you must install pyserial to allow Python to communicate with the arduino board.
https://github.com/pyserial/pyserial#installation

Download the Arduino IDE and use it to upload the .ino file to your board.

Go to index.php in your web browser and you should be able to drag and drop the instructions to generate a repeating pattern of motion for the Servo motor.
