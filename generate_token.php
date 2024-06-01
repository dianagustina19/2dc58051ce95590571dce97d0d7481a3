<?php
        require 'jwt.php';

        $user_id = 1;
        $token = generate_jwt($user_id);

        echo $token;
?>