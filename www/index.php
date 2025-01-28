<?php
require "global.php";
  if (preg_match("/^\/?$/i", $_SERVER['REQUEST_URI'])) {
    $page = "index";
    include "ynm/index.php";
  } elseif (preg_match("/^\/adatbazis\/?$/i", $_SERVER['REQUEST_URI'])) {
    $page = "index";
    include "adatok/index.php";
  } elseif (preg_match("/^\/adatbazis\/([A-Za-z0-9]+)\/?$/i", $_SERVER['REQUEST_URI'], $match)) {
    $page = strtolower($match[1]);
    include "adatok/index.php";
  } elseif (preg_match("/^\/([A-Za-z0-9]+)\/?$/i", $_SERVER['REQUEST_URI'], $match)) {
    $page = strtolower($match[1]);
    include "ynm/index.php";
  } elseif (preg_match("/^\/adatbazis\/([A-Za-z0-9]+)\/([A-Za-z0-9]+)\/?$/i", $_SERVER['REQUEST_URI'], $match)) {
    $page = strtolower($match[1]);
    $arg = strtolower($match[2]);
    include "adatok/index.php";
  } else {
    $page = "404";
    include "ynm/index.php";
  }
