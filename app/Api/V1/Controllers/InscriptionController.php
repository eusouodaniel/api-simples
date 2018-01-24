<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use App\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InscriptionController extends Controller{
    
    use Helpers;

    public function __construct(){

    }

    public function index(){
    	$currentUser = JWTAuth::parseToken()->authenticate();

        return $currentUser
                    ->inscription()
                    //testando ordenação
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->toArray();
    }

    public function show($id){
        $currentUser = JWTAuth::parseToken()->authenticate();

        $inscription = $currentUser->inscription()->find($id);

        if (!$inscription) {
            throw new NotFoundHttpException;
        }

        return $inscription;
    }

    public function store(Request $request){
        $currentUser = JWTAuth::parseToken()->authenticate();

        $inscription = new Inscription;
        $inscription->name_curso = $request->get('name_curso');
        $inscription->teacher_curso = $request->get('teacher_curso');

        if ($currentUser->inscription()->save($inscription)) {
            return response()->json($inscription,200);
        }

        return $this->response->error('Erro ao criar', 500);
    }

    public function update(Request $request, $id){
        $currentUser = JWTAuth::parseToken()->authenticate();
        $inscription = $currentUser->inscription()->find($id);

        if (!$inscription){
            throw new NotFoundHttpException;
        }

        $inscription->fill($request->all());

        if ($inscription->save()){
            return $inscription;
        }

        return $this->response->error('Erro ao editar', 500);
    }

    public function destroy($id){
        $currentUser = JWTAuth::parseToken()->authenticate();
        $inscription = $currentUser->inscription()->find($id);

        if (!$inscription){
            throw new NotFoundHttpException;
        }

        if ($inscription->delete()){
            return response()->json(['message' => 'Inscrição deletada'], 200);
        }

        return $this->response->error('Erro ao deletar', 500);
    }
}