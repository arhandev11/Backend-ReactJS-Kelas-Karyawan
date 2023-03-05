# Backend Final Project ini dikembangkan menggunakan Laravel

Berikut adalah tata cara instalasinya:
1. Download php dan web server bisa menggunakan XAMPP atau lainnya
2. Download Composer
3. Setelah semua berhasil didownload, cobalah untuk clone repository ini ke komputer kamu dengan menggunakan command git clone
4. Buatlah database yang nantinya akan digunakan untuk menyimpan datanya, buatlah database tersebut melalui phpmyadmin yang sudah terinstall bersama dengan xampp
5. rename file .env.example menjadi .env . Setelah itu masukan konfigurasi yang berkaitan dengan databasemu (key yang diawali dengan kata DB_ seperti DB_USERNAME , dll)
6. Jalankan command `composer update` pada folder project tersebut
7. Jika berhasil dan tidak ada error, jalankan command `php artisan key:generate` lalu setelah itu jalankan `php artisan config:cache`. Tapi jika saat update composer terdapat error, dapat menghubungi trainer
8. Terakhir cobalah untuk mengeksekusi `php artisan migrate`. Command ini berguna untuk generate database.
9. Jika sudah berhasil, aplikasi sudah bisa digunakan, untuk menyalakannya kamu dapat menggunakan command `php artisan serve`