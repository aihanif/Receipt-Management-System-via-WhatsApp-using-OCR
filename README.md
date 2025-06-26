# Receipt-Management-System-via-WhatsApp-using-OCR
Receipt Management System via WhatsApp using OCR

This project is a complete web-based receipt management system that allows users to **send receipt images through WhatsApp**, extract the information using **OCR (Optical Character Recognition)**, and automatically **store the data into a MySQL database** via a REST API.


## 🌟 Features

- 📷 Send receipt images directly through WhatsApp (Twilio Sandbox)
- 🧠 Extract receipt data using OCR.Space API (free tier)
- 🗃️ Store extracted data into a MySQL database using a PHP API
- 🖥️ Responsive CRUD web interface with **Bootstrap 5** (Mobile & Desktop friendly)
- ✍️ Edit and delete receipts with AJAX modal popups
- 🔔 Toast notifications for successful add/edit actions
- 📆 Automatically captures date and time of receipt

## 📦 Technologies Used

- **PHP** (Backend)
- **MySQL** (Database)
- **JavaScript + AJAX** (Frontend Interactivity)
- **Bootstrap 5** (Responsive Design)
- **Twilio WhatsApp API** (Message Gateway)
- **OCR.Space API** (Text Recognition from Image)


## 📸 Workflow Overview

1. User sends a photo of a receipt via **WhatsApp**
2. Webhook (`whatsapp_webhook.php`) receives and processes the media
3. Receipt image is sent to **OCR.Space API**
4. Parsed data is returned and sent to the **PHP API (insert.php)** for storage
5. Data is viewable and editable via the **CRUD web app**


## ⚙️ Setup Instructions

### 🔑 Prerequisites

- PHP 7+ and MySQL
- A hosted server 
- Twilio account with WhatsApp Sandbox enabled - https://www.twilio.com/en-us
- OCR.Space API Key (free plan works) - https://ocr.space/ocrapi


### 🚀 Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/receipt-whatsapp-ocr.git
   cd receipt-whatsapp-ocr

MySQL Table
Create a table named TBL_Receipt with the following structure:

sql
CREATE TABLE TBL_Receipt (
  ID INT PRIMARY KEY AUTO_INCREMENT,
  Header VARCHAR(1000),
  Body VARCHAR(2000),
  Create_datetime VARCHAR(100),
  Address VARCHAR(500),
  Item VARCHAR(1000),
  Quantity INT,
  Price DECIMAL(11,2),
  Tax DECIMAL(11,2)
);


Testing
1)Send a receipt image to your Twilio WhatsApp number
![whatsap-resit](https://github.com/user-attachments/assets/ca1911a2-4128-41fe-91c1-819d8bf3b43c)
2)Data will be extracted and stored in your database
![image](https://github.com/user-attachments/assets/50fa3973-435b-4ffb-a3ee-0d709bf1fd0d)
3)Open WebApp_Receipt.php to view, add, edit or delete records
![image](https://github.com/user-attachments/assets/625d32af-9620-4f42-bc81-18dd0d63fc16)

Author
Developed by Hanif Wahab
Feel free to contribute, fork or open an issue!

License
This project is open-source and available under the MIT License.
