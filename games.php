<?php
// Import script autoload agar bisa menggunakan library
require_once('./vendor/autoload.php');
// Import library
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

// Load dotenv
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Atur jenis response
header('Content-Type: application/json');

// Cek method request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(405);
  exit();
}

$headers = getallheaders();

// Periksa apakah header authorization-nya ada
if (!isset($headers['Authorization'])) {
  http_response_code(401);
  exit();
}

// Mengambil token
list(, $token) = explode(' ', $headers['Authorization']);

try {
  // Men-decode token. Dalam library ini juga sudah sekaligus memverfikasinya
  JWT::decode($token, $_ENV['ACCESS_TOKEN_SECRET'], ['HS256']);
  
  $games = [
    [
      'title' => 'Dota 2',
      'genre' => 'Strategy'
    ],
    [
      'title' => 'Ragnarok',
      'genre' => 'Role Playing Game'
    ]
  ];


  echo json_encode($games);
} catch (Exception $e) {
  // Bagian ini akan jalan jika terdapat error saat JWT diverifikasi atau di-decode
  echo json_encode([
    'success' => false,
    'message' => 'Access Token Expired'
  ]);
  exit();
}
