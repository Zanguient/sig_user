<?php
$link = mysqli_connect(
    '40.40.0.79',
    'israel',
    '123456',
    'sistemaprojetosunimed');

if (!$link) {
    printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error());
    exit;
}