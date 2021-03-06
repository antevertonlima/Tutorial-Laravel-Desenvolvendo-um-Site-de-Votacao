<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

/**Models**/
use App\Temas;
use App\Opcoes;

class TemaController extends Controller
{
  public function listar_por_usuario()
  {
    $temas = Temas::where('user_id','=',Auth::user()->id)->orderBy('created_at','desc')->get();
    return view('admin.tema.index')->with('temas',$temas)->with('titulo','Meus Temas');
  }

  public function listar_temas()
  {
    $temas = Temas::orderBy('created_at','desc')->get();
    return view('admin.tema.index')->with('temas',$temas)->with('titulo','Listar Temas');
  }

  public function create()
  {
    return view('admin.tema.create');
  }

  public function store(Request $request)
  {
    $tema = new Temas;
    $tema->user_id = Auth::user()->id;
    $tema->titulo = $request->input('titulo');
    $tema->descricao = $request->input('descricao');
    $tema->slug = $this->criar_slug($request->input('titulo'));
    $tema->save();

    $opcoes = explode(",", $request->input('opcoes'));
    foreach ($opcoes as $key => $value) {
      $opcao = new Opcoes;
      $opcao->tema_id = $tema->id;
      $opcao->opcao = $value;
      $opcao->save();
    }

    return back();
  }

  function criar_slug($titulo){
    $procurar =   ['ã','â','ê','é','í','õ','ô','ú',' ','?'];
    $substituir = ['a','a','e','e','i','o','o','u','-',''];
    return str_replace($procurar,$substituir,mb_strtolower($titulo));
  }

  public function destroy($id)
  {
    Temas::find($id)->delete();
    return back();
  }
}
