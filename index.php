<!DOCTYPE html>
<html>

<head>
  <title>Form Input</title>
</head>

<body>
  <form action="" method="POST">
    <label for="data">Data:</label>
    <input type="text" name="data" id="data" required>
    <button type="submit">Submit</button>
  </form>
</body>

</html>

<?php
// Fungsi untuk membersihkan input dari karakter-karakter yang tidak diinginkan
function cleanInput($input)
{
  $input = trim($input); // Menghapus spasi di awal dan akhir input
  $input = preg_replace('/\s+/', ' ', $input); // Mengganti multiple spasi dengan satu spasi
  return $input;
}

// Fungsi untuk mengubah format usia yang umum di Indonesia
function formatAge($age)
{
  $age = preg_replace('/(TAHUN|THN|TH)$/i', '', $age); // Menghapus 'TAHUN', 'THN', atau 'TH' di akhir kata (tidak case sensitive)
  return trim($age);
}

// Fungsi untuk mengubah nama dan kota menjadi uppercase
function uppercase($str)
{
  return strtoupper($str);
}

// Fungsi untuk menyimpan data ke dalam database
function saveToDatabase($name, $age, $city)
{
  $servername = "localhost"; // Ganti sesuai dengan konfigurasi server Anda
  $username = "root"; // Ganti dengan username database Anda
  $password = ""; // Ganti dengan password database Anda
  $dbname = "arkatama"; // Ganti dengan nama database Anda

  // Buat koneksi ke database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Cek koneksi
  if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
  }

  $sql = "INSERT INTO user (name, age, city) VALUES ('$name', '$age', '$city')";

  if ($conn->query($sql) === TRUE) {
    echo "Data berhasil disimpan ke dalam database.";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Menutup koneksi
  $conn->close();
}

// Mendapatkan data dari form input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $input = $_POST["data"];

  $age = preg_replace('/[^0-9]/', '', $input);
  $name = preg_replace('/[0-9].*$/', '', $input);
  $city = preg_replace('/^[^0-9]*[0-9]+/', '', $input);

  $cityClean = preg_replace('/\b(TAHUN|THN|TH)\b/i', '', $city);

  // Memisahkan input menjadi 3 bagian (nama, usia, dan kota)
  $nameList = cleanInput($name);
  $ageList = cleanInput($age);
  $cityList = cleanInput($cityClean);

  // Mengubah Nama dan Kota menjadi uppercase
  $nameFormat = uppercase($nameList);
  $cityFormat = uppercase($cityList);

  // Mengubah format Usia
  $ageFormat = formatAge($ageList);

  // Menyimpan data ke dalam database
  saveToDatabase($nameFormat, $ageFormat, $cityFormat);
}
