<?php

// Generate a large random hexadecimal salt.
function generateRandomSalt()
{
    $randomSalt = NULL;
    if (function_exists("mcrypt_create_iv"))
    {
        $randomSalt = bin2hex(mcrypt_create_iv(256, MCRYPT_DEV_URANDOM));
    }
    else 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomSalt = NULL;
        for($i = 0; $i < 128; $i++) 
        { 
            $randomSalt .= $characters[mt_rand(0, strlen($characters) -1)];
        }
    }
    
    return $randomSalt;
}

function generateSaltFile($file)
{
    return file_put_contents($file,'<?php die(); /* |'.generateRandomSalt().'| */ ?>',LOCK_EX);
}

function getSaltFromFile($file)
{
    if(!is_file($file))
    {
        $gen = generateSaltFile($file);
        if($gen == false)
            return false;
    }

    $items = explode('|',file_get_contents($file));
    if(!isset($items[1]))
return false; // ?

return $items[1];
}


function getServerSalt()
{
    return getSaltFromFile('data/salt.php');
}



function getPasteSalt($pasteid)
{
    $file = dataid2path ( $pasteid ).$pasteid."_salt.php";
    return getSaltFromFile($file);
}

?>
