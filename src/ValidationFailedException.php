<?php

namespace mBear\Drive;

/**
 * Class ValidationFailedException
 *
 * @author  Nam Hoang Luu <nam@mbearvn.com>
 * @package App\Drive
 *
 */
class ValidationFailedException extends \Exception
{
    /**
     * @var string
     */
    private $errors;

    /**
     * @param string $errors
     */
    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
