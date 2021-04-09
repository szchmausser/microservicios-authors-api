<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $authors = Author::all();
        return response()->json(['data' => ['authors' => $authors]]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        $rules = [
            'name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) { //regla personalizada para rechazar nombres duplicados
                    if(Author::where('name',request('name'))->count() > 0)
                    {
                        $fail('The '.$attribute.' has already been taken');
                    }
                },
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

        //Validar y enviar mensajes de error manualmente si falla | https://medium.com/@rosselli00/validating-in-laravel-7e88bbe1b627
//        $validator = Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            return response()->json(['data' => ['error' => $validator->errors()]]);
//        }

        //Validar y enviar mensajes de error si falla de forma automatica
        Validator::make($request->all(), $rules)->validate();

        //you actually do not need to check if the validator has failed, Laravel will do that and send back all the necessary messages to the view in the $errors
        //https://laravel.io/forum/12-11-2015-how-to-implement-laravel-request-validation
//        $this->validate($request, $rules);

        $author = Author::create($request->all()); //create funciona con array
//        $author = new Author($request->all());
//        $author->save(); //save funciona con instancias de modelo

        return response()->json(['data' => ['author' => $author, 'message' => 'Author successfully saved']], 201);
    }

    public function show(Author $author): \Illuminate\Http\JsonResponse
    {
        return response()->json(['data' => ['author' => $author]]);
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

        //https://arievisser.com/blog/differences-between-update-and-fill-in-laravel/
        //1er test
        $author->update($request->all()); //actualizar el registro con los datos del request
        //2do test
//        $author->fill($request->all()); //rellenar el registro con los datos del request
        //3er test
//        $author->name = 'modificado posterior'; //fill permite alterar la estructura del objeto antes de almacenarlo en bd
//        $author->save(); //si hemos modificado el objeto, ahora necesitamos guardarlo

        return response()->json(['data' => ['author' => $author, 'message' => 'Author successfully updated']], 200);
    }

    /**
     * @throws \Exception
     */
    public function destroy(Author $author): \Illuminate\Http\JsonResponse
    {
        $author->delete();

        return response()->json(['data' => ['author' => $author, 'message' => 'Author successfully deleted']], 200);
    }

}
