// Wifi
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>

ESP8266WiFiMulti WiFiMulti;

HTTPClient http;
#define USE_SERIAL Serial

#include <SimpleTimer.h>

SimpleTimer tinggiTimer;

// lcd
#include <LiquidCrystal_I2C.h>
LiquidCrystal_I2C lcd(0x27, 16, 2);

// SDA ---------------> D2
// SCL ---------------> D1
// VCC ---------------> VV
// GND ---------------> GND

// deklarasi pin Ultrasonik
#define tinggiPinTrigger D8
#define tinggiPinEcho D7

#define buzzer D0

// setting ketinggian
int tinggiBox = 60;

String host = "http://192.168.181.185/banjir/data";
String simpanSensor = host + "/save?ketinggian=";

String respon = "", relay;

String data;
char c;

#define LED_BUILTIN 16
#define SENSOR D4

long currentMillis = 0;
long previousMillis = 0;
int interval = 1000;
boolean ledState = LOW;
float calibrationFactor = 4.5;
volatile byte pulseCount;
byte pulse1Sec = 0;
float flowRate;
unsigned long flowMilliLitres;
unsigned int totalMilliLitres;
float flowLitres;

long duration, jarak, tinggiAir;

#define pinSelenoid D5
#define relay_off HIGH
#define relay_on LOW

void IRAM_ATTR pulseCounter()
{
  pulseCount++;
}

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);
  lcd.init();
  lcd.backlight();

  USE_SERIAL.begin(115200);
  USE_SERIAL.setDebugOutput(false);

  for(uint8_t t = 4; t > 0; t--) {
      USE_SERIAL.printf("[SETUP] Tunggu %d...\n", t);
      USE_SERIAL.flush();
      delay(1000);
  }

  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP("hp murah", "12345678"); // Sesuaikan SSID dan password ini

  for (int u = 1; u <= 5; u++)
  {
    if ((WiFiMulti.run() == WL_CONNECTED))
    {
      USE_SERIAL.println("Wifi Connected");
      USE_SERIAL.flush();
      
      lcd.setCursor(6, 0);
      lcd.print("WiFi");
      lcd.setCursor(2, 1);
      lcd.print("CONNECTED!!!");
      
      delay(1000);
    }
    else
    {
      lcd.setCursor(6, 0);
      lcd.print("WiFi");
      lcd.setCursor(0, 1);
      lcd.print("NOT CONNECTED");
      
      Serial.println("Wifi not Connected");
      delay(1000);
    }
  }

  lcd.clear();
  
  lcd.setCursor(3, 0);
  lcd.print("MONITORING");
  lcd.setCursor(5, 1);
  lcd.print("BANJIR");

  //  deklarasi pin ultrasonik
  pinMode(tinggiPinTrigger, OUTPUT);
  pinMode(tinggiPinEcho, INPUT);

  pinMode(buzzer, OUTPUT);

  pinMode(pinSelenoid, OUTPUT);
  digitalWrite(pinSelenoid, relay_off);

  tinggiTimer.setInterval(2000);

  pulseCount = 0;
  flowRate = 0.0;
  flowMilliLitres = 0;
  totalMilliLitres = 0;
  previousMillis = 0;

  attachInterrupt(digitalPinToInterrupt(SENSOR), pulseCounter, FALLING);

  delay(2000);
  lcd.clear();
}
void loop() {
  
  readWaterFlow();
  bacaTinggi();
  kirimSensor();

  Serial.println();

  if (relay == "ON") {
    Serial.println("Selenoid ON");
    digitalWrite(pinSelenoid, relay_on);
  } else {
    Serial.println("Selenoid OFF");
    digitalWrite(pinSelenoid, relay_off);
  }
  
  delay(1000);
}

void readWaterFlow()
{
  currentMillis = millis();
  if (currentMillis - previousMillis > interval)
  {
    pulse1Sec = pulseCount;
    pulseCount = 0;

    flowRate = ((1000.0 / (millis() - previousMillis)) * pulse1Sec) / calibrationFactor;
    previousMillis = millis();

    flowMilliLitres = (flowRate / 60) * 1000;
    flowLitres = (flowRate / 60);

    // Print the flow rate for this second in litres / minute
    Serial.print("Flow rate: ");
    Serial.print(float(flowRate)); // Print the integer part of the variable
    Serial.print("L/min");
    Serial.print("\t"); // Print tab space

    Serial.println();
  }
}

void kirimSensor() {
  if ((WiFiMulti.run() == WL_CONNECTED))
  {
    Serial.println(simpanSensor + (String) tinggiAir + "&debit=" + (String) flowRate);

    Serial.println();
    
    http.begin(simpanSensor + (String) tinggiAir + "&debit=" + (String) flowRate);
    
    USE_SERIAL.print("[HTTP] Kirim data ke database ...\n");
    int httpCode = http.GET();

    if(httpCode > 0)
    {
      USE_SERIAL.printf("[HTTP] kode response GET : %d\n", httpCode);

      if (httpCode == HTTP_CODE_OK) // code 200
      {
        respon = http.getString();
        relay = getValue(respon, '#', 1);
        
        USE_SERIAL.println("Respon kirim sensor : " + getValue(respon, '#', 0));
      }
    }
    else
    {
      USE_SERIAL.printf("[HTTP] GET data gagal, error: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();
  }

  Serial.println();
}

void bacaTinggi()
{
  if (tinggiTimer.isReady()) {
    Serial.println("Called every 3 sec");
    digitalWrite(tinggiPinTrigger, LOW);
    delayMicroseconds(2);
    digitalWrite(tinggiPinTrigger, HIGH);
    delayMicroseconds(10);
    digitalWrite(tinggiPinTrigger, LOW);
    duration = pulseIn(tinggiPinEcho, HIGH);
    
    //  Rumus pembacaan jarak tinggi
    jarak = (duration / 2) / 29.1;

    tinggiAir = tinggiBox - jarak;

    if(tinggiAir < 0) {
      
      tinggiAir = 0;
    }

    lcd.clear();
    
    Serial.print("Ketinggian Air : ");
    Serial.println(tinggiAir);

    lcd.setCursor(0, 0);
    lcd.print("TINGGI AIR:");
    lcd.setCursor(12, 0);
    lcd.print(tinggiAir);
    delay(1000);

    // kondisi status aman, siaga dan waspada
  if(tinggiAir <= 30) {
    Serial.println("AMAN");
    
    lcd.setCursor(0, 1);
    lcd.print("STATUS: ");
    lcd.setCursor(8, 1);
    lcd.print("AMAN");
    delay(1000);
    
  } else if(tinggiAir > 31 && tinggiAir <= 45) {
    Serial.println("SIAGA");

    lcd.setCursor(0, 1);
    lcd.print("STATUS: ");
    lcd.setCursor(8, 1);
    lcd.print("SIAGA");
    delay(1000);
  } else {
    Serial.println("WASPADA");
    
    lcd.setCursor(0, 1);
    lcd.print("STATUS: ");
    lcd.setCursor(8, 1);
    lcd.print("WASPADA");
    delay(1000);
    // mengulang buzzer sebanyak 5 kali
 
    digitalWrite(buzzer, relay_on);
    delay(500);
    digitalWrite(buzzer, relay_off);
    delay(100);
    
    Serial.println("Selenoid ON");
    digitalWrite(pinSelenoid, relay_on);
     
  }
  
    tinggiTimer.reset();
   }
}

String getValue(String data, char separator, int index)
{
  int found = 0;
  int strIndex[] = {0, -1};
  int maxIndex = data.length()-1;
 
  for(int i=0; i <= maxIndex && found <= index; i++){
    if(data.charAt(i) == separator || i == maxIndex){
        found++;
        strIndex[0] = strIndex[1]+1;
        strIndex[1] = (i == maxIndex) ? i+1 : i;
    }
  } 
 
  return found>index ? data.substring(strIndex[0], strIndex[1]) : "";
}
