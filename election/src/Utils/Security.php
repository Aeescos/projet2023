<?php
namespace App\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;

class Security
{
    private $tableEmail;
	private Globals $globals;

	public function __construct(Globals $globals)
	{
		$this->globals = $globals;
		$this->tableEmail = array();
	}

    public function loadPhoneOrEmail(string $value): JsonResponse
    {
        if (!is_null($value)) {
            if (SECURITY_UTILS::isEmailValid($value, $this->tableEmail)) {
				return $this->globals->success([
					"email" => $value
				]);
            } else if (SECURITY_UTILS::validator(SECURITY_UTILS::typeSenegale($value))) {
				return $this->globals->success([
					"telephone" => SECURITY_UTILS::typeSenegale($value)
				]);
            } 
        }

		return $this->globals->success([
			"Message" => "this value (".$value.") is depresiade"
		]);
    }

    
}
