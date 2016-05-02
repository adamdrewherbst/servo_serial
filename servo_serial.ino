#include <Servo.h>

Servo motor;
int motorSpeed;
typedef struct {
  int action;
  int value;
} Instruction;
Instruction program[100];

String serial;

void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
  motor.attach(9);
  motor.write(0);
}

void processSerial() {
  int start = 0, ind, count = 0;
  do {
    ind = serial.indexOf(' ', start);
    if(ind < 0) break;
    String action = serial.substring(start, ind);
    start = ind + 1;
    ind = serial.indexOf(',', start);
    if(ind < 0) break;
    String value = serial.substring(start, ind);
    start = ind + 1;
    
    Instruction &instruction = program[count];
    if(action.equals("speed")) {
      instruction.action = 0;
    } else if(action.equals("angle")) {
      instruction.action = 1;
    } else if(action.equals("delay")) {
      instruction.action = 2;
    }
    instruction.value = value.toInt();
    count++;
  } while(serial.charAt(start) != '!');
  program[count].value = -1;
  serial = "";
}

void loop() {
  // put your main code here, to run repeatedly:
  int count = 0, hasSerial = Serial.available();
  while(Serial.available() > 0) {
    serial += Serial.readString();
    if(serial.charAt(serial.length()-1) == '!') {
      Serial.println(serial);
      processSerial();
    }
  }

  int ind = 0;
  motorSpeed = 50;
  Instruction instruction = program[ind];
  while(instruction.value >= 0) {
    switch(instruction.action) {
      case 0: //set speed
        motorSpeed = instruction.value;
        break;
      case 1: //set angle
      {
        int prev = motor.read(), next = instruction.value;
        int dir = next > prev ? 1 : -1;
        for(int angle = prev; angle != next + dir; angle += dir) {
          motor.write(angle);
          delay(1000 / motorSpeed);
        }
        break;
      }
      case 2: //pause
        delay(instruction.value);
        break;
    }
    ind++;
    instruction = program[ind];
  }
  if(ind == 0) motor.write(0);
}
