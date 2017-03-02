<?php

namespace App\Responses;

use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Http\ResponseFactory;

/**
 * Class GoogleJsonResponse
 * @see https://google.github.io/styleguide/jsoncstyleguide.xml
 * @package App\Responses
 */
class GoogleJsonResponse extends ResponseFactory {
    /**
     * Http header codes
     */
    const SUCCESS = 200;
    const BAD_SYNTAX = 400;

    /**
     * Returns response object
     * @return array Response
     */
    private function getResponseObject() {
        return [
            'apiVersion' => '1.0',
            'success' => true,
            'data' => (object) [],
            'error' => (object) [],
        ];
    }

    /**
     * Success response
     * @param array $data Response data
     * @param int $httpCode Http Code
     * @return array Response
     */
    public function success($data = [], $httpCode = self::SUCCESS) {
        $response = array_merge($this->getResponseObject(), ['data' => $data]);
        return $this->json($response, $httpCode);
    }

    /**
     * Error response
     * @param array $error Main error
     * @param array $errors Extra errors
     * @param int $httpCode Http Code
     * @return array Response
     */
    public function error($error, $errors = [], $httpCode = self::BAD_SYNTAX) {
        $error = ['message' => $error];

        if (!empty($errors)) {
            $error = array_merge($error, ['errors' => $errors]);
        }

        $response = array_merge($this->getResponseObject(), ['success' => false, 'error' => $error]);
        return $this->json($response, $httpCode);
    }

    /**
     * Pagination response
     * @param Collection $collection Collection
     * @return array Response
     */
    public function pagination(Collection $collection) {
        // TODO: Use limit and skip to paginate

        $pagination = [
            'items' => $collection->all(),
            'currentItemCount' => $collection->count(),
            'itemsPerPage' => $collection->count(),
            'startIndex' => 1,
            'totalItems' => $collection->count(),
            'pageIndex' => 1,
            'totalPages' => 1,
        ];

        return $this->success($pagination);
    }
}