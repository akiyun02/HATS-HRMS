#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Adafruit_Fingerprint.h>
#include <SPI.h>
#include <MFRC522.h>

// --- CONFIGURATION ---
const char* ssid = "ROBERT3";
const char* password = "3Andrepanget3!";
const char* api_endpoint = "http://192.168.1.20:8000/api/biometric/attendance";
const char* api_token = "6fPLtGb0pctiXbh9fBJvgN6g7NefndVV";
const char* device_id = "ESP32_01";

// --- PINS ---
// RGB LED
#define RED_PIN 2
#define GREEN_PIN 4
#define BLUE_PIN 15

// RFID RC522
#define RST_PIN 27
#define SS_PIN 5

// Fingerprint AS608
#define FINGER_TX 16
#define FINGER_RX 17

// --- OBJECTS ---
LiquidCrystal_I2C lcd(0x27, 16, 2);
HardwareSerial mySerial(2); // Use UART2 for Fingerprint
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
MFRC522 mfrc522(SS_PIN, RST_PIN);

// State control
unsigned long lastScanTime = 0;
const unsigned long debounceDelay = 3000; // 3 seconds between scans

void setup() {
  Serial.begin(115200);
  
  // LED Setup
  pinMode(RED_PIN, OUTPUT);
  pinMode(GREEN_PIN, OUTPUT);
  pinMode(BLUE_PIN, OUTPUT);
  setLED(0, 0, 255); // Blue: booting/connecting
  
  // LCD Setup
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("System Booting..");

  // WiFi Setup
  connectWiFi();

  // SPI / RFID Setup
  SPI.begin();
  mfrc522.PCD_Init();
  
  // Fingerprint Setup
  mySerial.begin(57600, SERIAL_8N1, FINGER_RX, FINGER_TX);
  finger.begin(57600);
  
  if (finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor!");
  } else {
    Serial.println("Did not find fingerprint sensor :(");
    lcd.clear();
    lcd.print("F-Print Error!");
    setLED(255, 0, 0); // Red
    while (1) { delay(1); }
  }

  setLED(0, 0, 0); // Off
  displayReady();
}

void loop() {
  // Check WiFi
  if(WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }

  // Look for new RFID cards
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    if (millis() - lastScanTime > debounceDelay) {
      String uid = getRFIDUID();
      Serial.println("RFID Scanned: " + uid);
      processAuthentication(uid, "rfid");
      mfrc522.PICC_HaltA();
    }
  }

  // Look for fingerprint
  int fingerID = getFingerprintIDez();
  if (fingerID != -1 && (millis() - lastScanTime > debounceDelay)) {
    Serial.print("Fingerprint ID: "); Serial.println(fingerID);
    processAuthentication(String(fingerID), "fingerprint");
  }
}

void connectWiFi() {
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Connecting WiFi");
  setLED(0, 0, 255); // Blue
  
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nWiFi connected");
  lcd.clear();
  lcd.print("WiFi Connected!");
  delay(1000);
  displayReady();
}

void displayReady() {
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Scan RFID or");
  lcd.setCursor(0,1);
  lcd.print("Place Finger");
  setLED(0, 0, 0);
}

void setLED(int r, int g, int b) {
  analogWrite(RED_PIN, r);
  analogWrite(GREEN_PIN, g);
  analogWrite(BLUE_PIN, b);
}

String getRFIDUID() {
  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    uid += String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();
  return uid;
}

int getFingerprintIDez() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK)  return -1;
  
  return finger.fingerID;
}

void processAuthentication(String identifier, String authType) {
  lastScanTime = millis();
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Authenticating..");
  setLED(0, 0, 255); // Blue processing
  
  if(WiFi.status() == WL_CONNECTED){
    HTTPClient http;
    http.begin(api_endpoint);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");
    
    // JSON Payload
    String payload = "{\"device_id\":\"" + String(device_id) + "\",";
    payload += "\"employee_identifier\":\"" + identifier + "\",";
    payload += "\"auth_type\":\"" + authType + "\",";
    payload += "\"api_token\":\"" + String(api_token) + "\"}";
    
    int httpResponseCode = http.POST(payload);
    
    if(httpResponseCode > 0){
      String response = http.getString();
      Serial.println(httpResponseCode);
      Serial.println(response);
      
      if(httpResponseCode == 200 || httpResponseCode == 201) {
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("Access Granted");
        lcd.setCursor(0,1);
        lcd.print("Time Logged");
        setLED(0, 255, 0); // Green
        delay(2000);
      } else if (httpResponseCode == 429) {
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("Please Wait...");
        lcd.setCursor(0,1);
        lcd.print("Scan Debounce");
        setLED(255, 100, 0); // Orange/Yellow
        delay(2000);
      } else {
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("Access Denied!");
        lcd.setCursor(0,1);
        lcd.print("Unknown User");
        setLED(255, 0, 0); // Red
        delay(2000);
      }
    } else {
      Serial.print("Error on sending POST: ");
      Serial.println(httpResponseCode);
      lcd.clear();
      lcd.setCursor(0,0);
      lcd.print("Network Error!");
      setLED(255, 0, 0); // Red
      delay(2000);
    }
    http.end();
  } else {
    lcd.clear();
    lcd.print("No WiFi!");
    setLED(255, 0, 0); // Red
    delay(2000);
  }
  
  displayReady();
}
