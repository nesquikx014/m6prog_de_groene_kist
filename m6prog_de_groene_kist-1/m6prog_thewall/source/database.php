<?php

include_once "config.php";

function database_connect()
{
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA_NAME);
    if($connection->connect_errno)
    {
        die("failed to connect: " . $connection->connect_error);
    }
    return $connection;
}

function getMessages()
{
    $connection = database_connect();
    $query = "SELECT * FROM messages ORDER BY created_at DESC";
    $result = $connection->query($query);
    
    $messages = [];
    while($row = $result->fetch_assoc())
    {
        $messages[] = $row;
    }
    
    $connection->close();
    return $messages;
}
