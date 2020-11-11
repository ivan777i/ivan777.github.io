# BACKEND ASSESSMENT TEST - 2020 - Ivan Immanuel
=============
Project ini dibuat untuk kebutuhan test assessment Klikdaily (PT. Klik Teknologi Indonesia).
Nama: Ivan Immanuel
Email: ivanimmanuel03@gmail.com
Hp/Whatsapp: 081299415459

 ## Resources yang dibutuhkan:
 1. GIT (Windows: https://git-scm.com/download/win)
 2. XAMPP (https://www.apachefriends.org/download.html)
 
 ## Cara penggunaan:
 1. Install XAMPP atau sejenis untuk instalasi php dan juga MySQL
 2. Install GIT jika belum ada GIT terinstal
 3. Buat folder di htdocs (jika menggunakan xampp, biasanya terdapat pada C:/xampp/htdocs) dengan nama **klikdaily**
 4. Buka command prompt atau Terminal pada Visual Studio Code dengan membuka/mengarah pada folder klikdaily yang baru dibuat. Misalkan untuk command prompt, jalankan **cd C:\xampp\htdocs\klikdaily**. Path disesuaikan dengan posisi folder sesuai dengan tahap no.3
 5. Jalankan command dibawah ini pada command prompt atau terminal: 
    ```sh
    git init
    git remote add origin https://github.com/ivan777i/klikdaily.git
    git checkout -b main
    git pull origin main
    ```
 6. Setelah itu, nyalakan Apache dan MySQL dengan cara mengklik tombol start pada Apache dan MySQL di XAMPP
 7. Jika sudah running untuk Apache dan MySQLnya, buka browser seperti Google Chrome, lalu buka link: http://localhost/phpmyadmin
 8. Setelah halaman terbuka, buat sebuah database baru dengan nama **klikdaily_stocks**
 9. Setelah database selesai dibuat, klik pada database **klikdaily_stocks** dan klik tombol import pada menu tab bagian atas
 10. Lalu klik tombol choose file dan pilih file **klikdaily_stocks.sql** yang ada pada repo git ini
 11. Lalu klik Go di bagian bawah kanan
 12. Setelah selesai, silahkan mencoba untuk langsung menggunakan apinya dengan menggunakan postman atau tools lain untuk melakukan request. Untuk url, apabila menggunakan xampp biasanya menggunakan http://localhost/{api}

 ## LIST API ENDPOINTS
 ###  /klikdaily/stocks
 **Method**: GET
 **contoh curl**: 
 ```sh
 curl --location --request GET 'http://localhost/klikdaily/stocks'
 ```

 ###  /klikdaily/adjustment
 **Method**: POST
 **Body**: (JSON)
 ```sh
    [
        {
            "location_id": 1,
            "product": "Indomie Goreng",
            "adjustment": -10
        },
        {
            "location_id": 2,
            "product": "Kopi",
            "adjustment": 6
        }
    ]
 ```
 **contoh curl**: 
 ```sh
 curl --location --request POST 'http://localhost/klikdaily/adjustment' \
--header 'Content-Type: application/json' \
--data-raw '[
    {
        "location_id": 1,
        "product": "Indomie Goreng",
        "adjustment": -10
    },
    {
        "location_id": 2,
        "product": "Kopi",
        "adjustment": 2
    }
]'
 ```

 ###  /klikdaily/logs/{location_id}
 **Method**: GET
 **contoh curl**: 
 ```sh
 curl --location --request GET 'http://localhost/klikdaily/logs/1'
 ```
