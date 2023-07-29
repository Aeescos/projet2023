<?php
namespace App\Utils;

 class SECURITY_UTILS
{
    

    public static function typeSenegale(string $phS): string
    {
        $phone = "none";
        if (strlen(trim($phS)) == 9 && SECURITY_UTILS::AnalyseDonne(trim($phS))) {
            $phone = "";
            for ($i = 0; $i < strlen(trim($phS)); $i++) {
                if ($i === 2) 
                    $phone .= "-" . trim($phS)[$i];
                elseif ($i === 5) 
                    $phone .= "-" . trim($phS)[$i];
                elseif ($i === 7) 
                    $phone .= "-" . trim($phS)[$i];
                else 
                    $phone .= trim($phS)[$i];
            }
            return trim($phone);
        } 
        else return $phone;
    }

    public static function typeComorers(string $phC): string
    {
        $phone = "none";
        if (strlen(trim($phC)) == 7 && SECURITY_UTILS::AnalyseDonne(trim($phC)) ){
            $phone = "";
            for ($i = 0; $i < strlen(trim($phC)); $i++) {
                if ($i === 3)
                    $phone .= "-" . trim($phC)[$i];
                elseif ($i === 5)
                    $phone .= "-" . trim($phC)[$i];
                else
                    $phone .= trim($phC)[$i];
            }
            return trim($phone);
        } 
        else return $phone;
    }

    public static function CODE_IN_DATA(string $code): string
    {
        $sembleurFordata = "none";
        if (strlen(trim($code)) == 8 ){
            $sembleurFordata = "";
            for ($i = 0; $i < strlen($code); $i++) {
                if ($i % 2 === 0 && $i > 0) 
                    $sembleurFordata .= "^-^" . trim($code)[$i];
                else 
                    $sembleurFordata .= trim($code)[$i];
            }
            return trim($sembleurFordata);
        } 
        else return $sembleurFordata;
    }

    public static function CODE_FOR_USER(string $code): string
    {
        $sembleur = "none";
        if (strlen(trim($code)) == 8) {
            $sembleur = "";
            for ($i = 0; $i < strlen(trim($code)); $i++) {
                if ($i % 2 === 0 && $i > 0) 
                    $sembleur .= "~" . trim($code)[$i];
                else 
                    $sembleur .= trim($code)[$i];
                
            }
            return trim($sembleur);
        } 
        else return $sembleur;
    }

    public static function isEmailValid(string $email, Array $tableEmail): bool
    {
        array_push($tableEmail, "@gmail.com");
        array_push($tableEmail, "@yahoo.com");
        
        $validatorNameEmail = false;
        $pattern = '/[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+/';
        if (SECURITY_UTILS::AnalyseDonne(trim($email)) == false) {
            $tableEmail = array();
            return $validatorNameEmail;
        }
        else if (preg_match($pattern, trim($email))) {
            foreach ($tableEmail as $emailT) {
                if (strpos(trim($email), $emailT) !== false) {
                    $NameEmail = substr(trim($email), 0, strpos(trim($email), $emailT));
                    $validatorNameEmail = SECURITY_UTILS::validator($NameEmail);
                }
            }
        }
        $tableEmail = array();
        return $validatorNameEmail;
    }

    public static function validator(string $value): bool
    {
        $validatorNameEmail = false;

        if (strlen(trim($value)) > 3 && trim($value) !== "") {
            $validatorNameEmail = SECURITY_UTILS::getTypeValue(trim($value));
        }

        return $validatorNameEmail;
    }

    public static function getTypeValue(string $value): bool
    {
        if (is_null(trim($value))) return false;
        else {
            try {
                floatval(trim($value));
            } catch (\InvalidArgumentException $e) {
                if (SECURITY_UTILS::AnalyseDonne(trim($value))) return true;
            }

            return false;
        }
    }

    public static function checkPassord(string $value): bool
    {
        if (is_null(trim($value))) return false;
        else if(strlen(trim($value)) > 8)
            try {
                floatval($value);
            } catch (\InvalidArgumentException $e) {
                if (SECURITY_UTILS::AnalyseDonne(trim($value))) return true;
            }
            return false;
    }

    public static function AnalyseDonne(string $value): bool
    {
        $tableSql = array();
        array_push($tableSql, "admin' OR '1'='1'--");
        array_push($tableSql, "electronics' OR '1'='1'--");
        array_push($tableSql, "1 OR 1=1");
        array_push($tableSql, "users; DROP TABLE orders--");
        array_push($tableSql, "users; DROP TABLE orders--"); 
        array_push($tableSql, "'; DROP DATABASE mydatabase--"); 
        if (is_null(trim($value))) return false;
        else 
            foreach ($tableSql as $sql) {
                if (strpos(trim($value), $sql) == false  ){
                    $tableSql = array();
                   return true;
                }
            }
        $tableSql = array();
        return false;
    }




}
