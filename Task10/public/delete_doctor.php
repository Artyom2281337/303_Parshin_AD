<?php

require_once '../app/repositories/DoctorsRepository.php';
require_once '../vendor/autoload.php';

use RedBeanPHP\R;
const DB_NAME = '../data/hospital.db';

$connectionString = 'sqlite:' . realpath(DB_NAME);

R::setup($connectionString);

$doctorRepository = new DoctorsRepository();

$id = (int)$_POST['doctorId'];

$doctorRepository->deleteById($id);

echo 'Doctor deleted' . PHP_EOL;
echo '<a href="doctors.php">Return bach.</a>';

