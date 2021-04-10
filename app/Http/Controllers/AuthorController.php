<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;

class AuthorController extends Controller
{
    use ApiResponser;

    public function index(): \Illuminate\Http\JsonResponse
    {
        $authors = Author::all();

        return $this->SuccessResponse($authors);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        $rules = [
            'name' => [
                'required',
                'string',
            ],
            'gender' => [
                'required',
                'string',
                function ($attribute, $value, $fail) { //regla personalizada para rechazar nombres duplicados
                    if(request('gender') == 'male')
                    {
                        $fail('Only female '.$attribute.' are allowed');
                    }
                },
            ],
            'country' => [
                'required',
                'string',
            ],
        ];

        Validator::make($request->all(), $rules)->validate();

        $author = Author::create($request->all());

        return $this->SuccessResponse($author,Response::HTTP_CREATED);

    }

    public function show(Author $author): \Illuminate\Http\JsonResponse
    {
        return $this->SuccessResponse($author);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Author $author, Request $request): \Illuminate\Http\JsonResponse
    {

        $rules = [
            'name' => [
                'required',
                'string',
            ],
            'gender' => [
                'required',
                'string',
                function ($attribute, $value, $fail) { //regla personalizada para rechazar nombres duplicados

                    $options = collect(['male', 'female']);

                    if(!$options->contains(request('gender'))){
                        $fail('Only male/female '.$attribute.' are allowed');
                    }
                },
            ],
            'country' => [
                'required',
                'string',
            ],
        ];

        Validator::make($request->all(), $rules)->validate();

        $author->update($request->all()); //actualizar el registro con los datos del request

        return $this->SuccessResponse($author);
    }

    /**
     * @throws \Exception
     */
    public function destroy(Author $author): \Illuminate\Http\JsonResponse
    {
        $author->delete();

        return $this->SuccessResponse($author);
    }

}
