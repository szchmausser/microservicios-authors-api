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

    public function index()
    {
        $authors = Author::all();
        return $this->SuccessResponse($authors);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string'],
            'gender' => ['required', 'string',
                function ($attribute, $value, $fail) { //regla personalizada para rechazar nombres duplicados
                    if (!in_array($value, ['male', 'female'])) {
                        $fail('Only female '.$attribute.' are allowed');
                    }
                },],
            'country' => ['required', 'string',],
        ];

        Validator::make($request->all(), $rules)->validate();

        $author = Author::create($request->all());

        return $this->SuccessResponse($author,Response::HTTP_CREATED);
    }

    public function show(Author $author)
    {
        return $this->SuccessResponse($author);
    }

    public function update(Author $author, Request $request)
    {
        $rules = [
            'name' => ['required', 'string',],
            'gender' => ['required', 'string',
                function ($attribute, $value, $fail) { //regla personalizada para rechazar nombres duplicados
                    $options = collect(['male', 'female']);
                    if (!$options->contains($value)) {
                        $fail('Only male/female '.$attribute.' are allowed');
                    }
                },],
            'country' => ['required', 'string',],
        ];

        Validator::make($request->all(), $rules)->validate();

        $author->update($request->all()); //actualizar el registro con los datos del request

        return $this->SuccessResponse($author);
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return $this->SuccessResponse($author);
    }

}
