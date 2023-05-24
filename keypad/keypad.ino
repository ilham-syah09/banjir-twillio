#include <Arduino.h>

// Wifi
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>


const int busyPin = 4;

#define USE_SERIAL Serial
ESP8266WiFiMulti WiFiMulti;
WiFiClient client;
HTTPClient http;

// URL WEB IOT
String simpan = "http://192.168.41.231/paket/data/cekResi?resi=";
String updateCam = "http://192.168.41.231/paket/data/updateCamera?cam=";

String respon, respon2;

#include <Wire.h>
#include <Keypad_I2C.h>
#include <Keypad.h>
#define I2CADDR 0x3E

// sda = D2
// SCL = D1

const byte ROWS = 4; // four rows
const byte COLS = 4; // three columns
char keys[ROWS][COLS] = {
    {'1', '2', '3', 'A'},
    {'4', '5', '6', 'B'},
    {'7', '8', '9', 'C'},
    {'*', '0', '#', 'D'}};

// Digitran keypad, bit numbers of PCF8574 i/o port
byte rowPins[ROWS] = {0, 1, 2, 3}; // connect to the row pinouts of the keypad
byte colPins[COLS] = {4, 5, 6, 7}; // connect to the column pinouts of the keypad

Keypad_I2C kpd(makeKeymap(keys), rowPins, colPins, ROWS, COLS, I2CADDR, PCF8574);

String resi = "";

// lcd
#include <LiquidCrystal_I2C.h>
LiquidCrystal_I2C lcd(0x27, 16, 2);

// buzzer
#define buzzer D0

// selenoid
#define selenoid D5
#define relay_on HIGH
#define relay_off LOW

// magnet
#define magnet D6
int nilaiMagnet;

void setup()
{
  Wire.begin();
  kpd.begin(makeKeymap(keys));
  Serial.begin(115200);

  USE_SERIAL.begin(115200);
  USE_SERIAL.setDebugOutput(false);

  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP("Apasiii", "dini12345"); // Sesuaikan SSID dan password ini

  Serial.println();

  lcd.init();
  lcd.backlight();

  for (int u = 1; u <= 5; u++)
  {
    lcd.clear();

    if ((WiFiMulti.run() == WL_CONNECTED))
    {
      USE_SERIAL.println("WiFi Connected");
      USE_SERIAL.flush();

      lcd.setCursor(6, 0);
      lcd.print("WiFi");
      lcd.setCursor(2, 1);
      lcd.print("CONNECTED!!!");
      delay(1000);
    }
    else
    {
      USE_SERIAL.println("WiFi not Connected");
      USE_SERIAL.flush();

      lcd.setCursor(6, 0);
      lcd.print("WiFi");
      lcd.setCursor(0, 1);
      lcd.print("NOT CONNECTED");

      delay(1000);
    }
  }
  pinMode(buzzer, OUTPUT);
  digitalWrite(buzzer, LOW);

  pinMode(selenoid, OUTPUT);
  digitalWrite(selenoid, relay_off);

  pinMode(magnet, INPUT_PULLUP);

  lcd.setCursor(3, 0);
  lcd.print("MONITORING");
  lcd.setCursor(1, 1);
  lcd.print("PAKET NO.RESI");

  Serial.println();
  Serial.println("start");



  lcd.clear();
}

void loop()
{
  nilaiMagnet = digitalRead(magnet);

  if (nilaiMagnet == HIGH)
  {
    Serial.println("Pintu dibuka secara paksa !!");

    lcd.setCursor(0, 0);
    lcd.print("DIBUKA PAKSA");
    lcd.setCursor(0, 1);
    lcd.print("TUTUP KEMBALI !!");

    digitalWrite(buzzer, HIGH);
    delay(1500);
    digitalWrite(buzzer, LOW);

    delay(500);
    lcd.clear();
  }
  else
  {
    lcd.setCursor(1, 0);
    lcd.print("INPUT NO.RESI");
  }

  char key = kpd.getKey();

  if (key)
  {
    if ((String)key == "*")
    {
      resi = "";
    }
    else if ((String)key == "#")
    {
      if (resi.length() > 0)
      {
        int lastIndex = resi.length() - 1;

        resi.remove(lastIndex);
      }
    }
    else if ((String)key == "D")
    {
      if (resi != "")
      {
        Serial.println("Ok cek database");

        lcd.clear();

        lcd.setCursor(0, 0);
        lcd.print("OK CEK DATABASE");

        cekResi(resi);

        if (respon == "Paket ditemukan")
        {
          

          lcd.setCursor(0, 1);
          lcd.print("PAKET DITEMUKAN");

          digitalWrite(selenoid, relay_on);
          delay(200);

          digitalWrite(buzzer, HIGH);
          delay(500);
          digitalWrite(buzzer, LOW);
          delay(50);
          digitalWrite(buzzer, HIGH);
          delay(500);
          digitalWrite(buzzer, LOW);
          delay(50);
          digitalWrite(buzzer, HIGH);
          delay(500);
          digitalWrite(buzzer, LOW);

          delay(3000);

          nilaiMagnet = digitalRead(magnet);

          lcd.clear();

          while (nilaiMagnet == HIGH)
          {
            Serial.println("Silahkan tutup pintu kembali !!");

            lcd.setCursor(0, 0);
            lcd.print("TUTUP KEMBALI");
            lcd.setCursor(0, 1);
            lcd.print("PINTU PAKET");

            nilaiMagnet = digitalRead(magnet);
            delay(200);
          }

          updateOffCam(resi);

          digitalWrite(selenoid, relay_off);
          
        }
        else
        {
          lcd.setCursor(0, 1);
          lcd.print("TIDAK DITEMUKAN");

          

          digitalWrite(selenoid, relay_off);

          digitalWrite(buzzer, HIGH);
          delay(2000);
          digitalWrite(buzzer, LOW);
          delay(200);
          digitalWrite(buzzer, HIGH);
          delay(2000);
          digitalWrite(buzzer, LOW);
        }

        delay(1500);

        lcd.clear();
        respon = "";
      }

      resi = "";
    }
    else
    {
      resi += (String)key;
    }

    if ((String)key != "D")
    {
      Serial.println("Nomor resi : " + resi);

      lcd.clear();

      lcd.setCursor(1, 0);
      lcd.print("INPUT NO.RESI");
      lcd.setCursor(0, 1);
      lcd.print(resi);
    }
  }

  delay(200);
}

void cekResi(String no_resi)
{
  // cek no resi ke website

  if ((WiFiMulti.run() == WL_CONNECTED))
  {
    USE_SERIAL.print("[HTTP] Memulai...\n");

    http.begin(client, simpan + (String)no_resi);

    USE_SERIAL.print("[HTTP] Mengecek data resi ke database ...\n");
    int httpCode = http.GET();

    if (httpCode > 0)
    {
      USE_SERIAL.printf("[HTTP] kode response GET : %d\n", httpCode);

      if (httpCode == HTTP_CODE_OK)
      {
        respon = http.getString();
        USE_SERIAL.println("Respon : " + respon);
        delay(100);
      }
    }
    else
    {
      USE_SERIAL.printf("[HTTP] GET data gagal, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();
  }
}

void updateOffCam(String no_resi)
{
  if ((WiFiMulti.run() == WL_CONNECTED))
  {
    USE_SERIAL.print("[HTTP] Memulai...\n");

    Serial.println(updateCam + "off&resi=" + (String)no_resi);

    http.begin(client, updateCam + "off&resi=" + (String)no_resi);

    USE_SERIAL.print("[HTTP] Update OFF Camera ke database ...\n");
    int httpCode = http.GET();

    if (httpCode > 0)
    {
      USE_SERIAL.printf("[HTTP] kode response GET : %d\n", httpCode);

      if (httpCode == HTTP_CODE_OK)
      {
        respon2 = http.getString();
        USE_SERIAL.println("Respon Update Cam : " + respon2);
        delay(100);
      }
    }
    else
    {
      USE_SERIAL.printf("[HTTP] GET data gagal, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();
  }
}
