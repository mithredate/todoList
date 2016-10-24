<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/24/2016
 * Time: 3:46 PM
 */

namespace App\Modules\TodoList\Exceptions;


use App\Modules\TodoList\Http\ErrorResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    public function render($request, Exception $e){
        $e = $this->prepareException($e);
        if ($e instanceof HttpResponseException) {
            return $this->prepareErrorResponse(['title' => class_basename($e), 'code' => 400, 'message' => 'Bad Request'], 400);
        } elseif ($e instanceof AuthenticationException) {
            return $this->prepareErrorResponse(['title' => 'Unauthenticated',
                'code' => 401,
                'message' => 'Access is denied'], 401);
        } elseif ($e instanceof ValidationException) {
            $errors = $e->validator->errors()->getMessages();
            $er = [];
            foreach ($errors as $field => $message){
                $er[] = ['title' => 'validation error', 'message' => $message, 'code' => 422];
            }
            return $this->prepareErrorResponse($er, 422);
        } elseif( $e instanceof UnauthorizedException){
            return $this->prepareErrorResponse(['title' => 'Unauthorized!', 'code' => 403, 'message' => $e->getMessage()],403);
        }

        return $this->prepareResponse($request, $e);
    }







    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    /**
     * @param $errors
     * @param $code
     * @return array
     */
    private function prepareErrorResponse($errors, $code)
    {
        $errorResponse = resolve(ErrorResponse::class);
        $response = $errorResponse->render(action('\App\Modules\TodoList\Controllers\TodoListController@index'), [], null, null, [], [], $errors);
        return response()->collectionJson($response, $code);
    }

}