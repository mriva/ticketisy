<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedAPIRequestException extends Exception {

    public function render() {
        return response('Unauthorized API request', 401);
    }

}
