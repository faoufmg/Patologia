<?php

include_once('../../config/db.php');
session_start();

function gerarSenha($length = 12)
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&'), 0, $length);
}

// Gera a senha
$senha = '12345';
$options = [
    'cost' => 12
];
$senha_criptografada = password_hash($senha, PASSWORD_BCRYPT, $options);

echo $senha_criptografada;