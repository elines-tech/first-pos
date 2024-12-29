<?php
session_start();

// Default language
$language = 'en';

// Check for language selection
if (isset($_GET['lang'])) {
    $language = $_GET['lang'];
    $_SESSION['lang'] = $language;
} elseif (isset($_SESSION['lang'])) {
    $language = $_SESSION['lang'];
}

// Load the language file
$langFile = "/home/said/Desktop/first-pos/restaurant/lang/$language.php";

if (file_exists($langFile)) {
    $translations = include $langFile;
} else {
    $translations = include "/home/said/Desktop/first-pos/restaurant/lang/en.php";
}
