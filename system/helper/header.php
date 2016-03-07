<?php

function header404(){
    header("HTTP/1.1 404 Not Found");
}

function header403(){
    header("HTTP/1.1 404 Forbidden");
}