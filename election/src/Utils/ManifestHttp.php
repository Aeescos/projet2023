<?php

   namespace App\Utils;



    class ManifestHttp
    {
        public const SUCCESS             = ["message" => "success"             , "status" => 1, "code" => 200];
        public const ERROR               = ["message" => "error"               , "status" => 0, "code" => 500];
        public const FROM_ERROR          = ["message" => "from invalid"        , "status" => 0, "code" => 400];
        public const ERROR_MANIFEST      = ["message" => "Error manifeste"     , "status" => 0, "code" => 400];
        public const USER_NOT_FOUND      = ["message" => "User Not Found"      , "status" => 0, "code" => 404];
        public const ROLE_NOT_FOUND      = ["message" => "ROLE Not Found"      , "status" => 0, "code" => 404];
        public const PASSWORD_WRONG      = ["message" => "Password wrong"      , "status" => 0, "code" => 500];
        public const EMAIL_USED          = ["message" => "Email used"          , "status" => 0, "code" => 500];
        public const ROLES_USED          = ["message" => "it already exists"   , "status" => 0, "code" => 500];
        public const PASSWORD_TOO_SHORT  = ["message" => "Password too short"  , "status" => 0, "code" => 500];
        public const ERROR_FORMAT_NUMBER = ["message" => "Error format Number" , "status" => 0, "code" => 500];
    }


?>