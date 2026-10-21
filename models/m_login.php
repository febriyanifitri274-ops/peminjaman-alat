<?php

class M_login
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function login($username, $password)
    {
        $query = "SELECT * FROM users
                  WHERE username = '$username'
                  AND password = '$password'";

        return mysqli_query($this->koneksi, $query);
    }
}