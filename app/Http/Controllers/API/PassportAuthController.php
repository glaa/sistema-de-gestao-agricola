<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Endereco;
use App\Models\FotoMapa;
use App\Models\Propriedade;
use App\Models\AgendamentoReuniao;
use App\Models\Reuniao;
use App\Models\FotosReuniao;
use App\Models\Perfil;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PassportAuthController extends Controller
{
    public function register(Request $request){
        $this->validate($request,[
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'nome' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $token = $user->createToken('Laravel8PassportAuth')->accessToken;
  
        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request){
        $data = [
            'email' => base64_decode($request->email),
            'password' => base64_decode($request->password),
        ];
        
        if(auth()->attempt($data)){
            $token = auth()->user()->createToken('Laravel8PassportAuth')->accessToken;
            return response()->json(['token' => $token], 200);
        } else{
            return response()->json(['error' => 'Unauthorised'], 401);
            //return response($request->email,401)->header('Content-Type', 'text/plain');;
        }

    }

    public function userInfo(){
        $user = auth()->user();   
        //Passa a tabela produtor como atributo da variável $user
        $produtor =  $user->produtor;
        $foto = base64_encode(Storage::get($user->perfil->path));
        //$endereco = $user->endereco;
        return response()->json(['user' => $user, 'foto' => $foto], 200);
    }

    public function index(){
        return response()->json(['ok' => 'Online', 200]);
    }

    public function cadastrarPropriedade(Request $request){
        $user = auth()->user();
        $entrada = $request->all();
        $entrada['fonte_de_agua'] = base64_decode($request->fonte_de_agua);
        $entrada['nome_rua'] = base64_decode($request->nome_rua);
        $entrada['bairro'] = base64_decode($request->bairro);
        $entrada['cidade'] = base64_decode($request->cidade);
        $entrada['estado'] = base64_decode($request->estado);
        $entrada['cep'] = base64_decode($request->cep);
        $entrada['ponto_referencia'] = base64_decode($request->ponto_referencia);

        
        $validator_endereco = Validator::make($entrada, Endereco::$regras_validacao_api);
        if ($validator_endereco->fails()) {
            return response()->json(['erro' => 'endereco'],400);
        }

        $validator_propriedade = Validator::make($entrada, Propriedade::$regras_validacao_api);
        if ($validator_propriedade->fails()) {
            return response()->json(['erro' => 'propriedade'],400);
        }

        $endereco = new Endereco;
        $endereco->fill($entrada);
        $endereco->save();

        $propriedade = new Propriedade;
        $propriedade->fill($entrada);
        $propriedade->endereco_id = $endereco->id;
        $propriedade->user_id = $user->produtor->id;
        $propriedade->save();

        $fotoMapa = new FotoMapa();
        $fotoMapa->propriedade_id = $propriedade->id;

        if($request->has('mapa')){
            if($request->hasFile('mapa')){
                $file = $request->file('mapa'); 
                $fotoMapa->path = $file->storeAs('fotosMapas/'.$propriedade->id,time().".jpg");
                $fotoMapa->save();  
            }
        }        

        $produtor = $user->produtor;
        $produtor->primeiro_acesso = false;
        $produtor->save();

        return response()->json(['ok' => 'sucesso'],200);
    }

    public function listarReunioes(){
        $user = auth()->user();

        return response()->json(['reunioes' => $user->produtor->ocs->agendamentoReuniao],200);
    }

    public function agendarReuniao(Request $request){
        $user = auth()->user();
        if($user->tipo_perfil != "Coordenador"){
            return response()->json(['error' => 'Unauthorised'], 401);
        } 

        $entrada = $request->all();

        $agendamentoReuniao = new AgendamentoReuniao();
        $agendamentoReuniao->nome = base64_decode($entrada['nome']);
        $agendamentoReuniao->data = base64_decode($entrada['data']);
        $agendamentoReuniao->local = base64_decode($entrada['local']);
        $agendamentoReuniao->registrada = false;

        $agendamentoReuniao->ocs_id = $user->produtor->ocs_id;
        //return response()->json(['error' => 'Unauthorised'], 402);
        $agendamentoReuniao->save();
        
        return response()->json(['ok' => 'sucesso'],200);
    }

    public function editarReuniao(Request $request){
        $user = auth()->user();
        if($user->tipo_perfil != "Coordenador"){
            return response()->json(['error' => 'Unauthorised'], 401);
        } 
        
        $entrada = $request->all();

        $agendamentoReuniao = AgendamentoReuniao::find($request->id);
        $agendamentoReuniao->nome = base64_decode($request->nome);
        $agendamentoReuniao->data = base64_decode($request->data);
        $agendamentoReuniao->local = base64_decode($request->local);
        $agendamentoReuniao->registrada = false;

        $agendamentoReuniao->save();
        
        return response()->json(['ok' => 'sucesso'],200);
    }

    public function excluirReuniao($id){
        $user = auth()->user();
        if($user->tipo_perfil != "Coordenador"){
            return response()->json(['error' => 'Unauthorised'], 401);
        } 

        $reuniaoAgendada = AgendamentoReuniao::find($id);

        if($reuniaoAgendada->registrada == false){
            $reuniaoAgendada->delete();
            return response()->json(['ok' => 'sucesso'],200);
        }

        return response()->json(['erro' => 'erro'],406);
    }

    public function exibirReuniao($id){
        $user = auth()->user();

        $reuniaoAgendada = AgendamentoReuniao::find($id);
        $reuniao = $reuniaoAgendada->reuniaoRegistrada;
        $registrada = $reuniaoAgendada->reuniaoRegistrada;
        if($registrada){
            $registros_atas = DB::table('reuniaos')->where('agendamento_id',$id)->get();
            $atas = array();
            $fotos = array();
            for($i = 0; $i < count($registros_atas); $i++){
                $atas[] = base64_encode(Storage::get($registros_atas[$i]->ata));
                $registros_fotos = DB::table('fotos_reuniaos')->where('reuniao_id',$registros_atas[$i]->id)->get();
            
                for($j = 0; $j < count($registros_fotos); $j++){
                    $fotos[] = base64_encode(Storage::get($registros_fotos[$j]->path));
                }
            }
            
            return response()->json(['atas' => $atas, 'fotos' => $fotos], 200);
        } else {
            return response(['ata' => 'null'],200);
        }
    }

    public function registrarReuniao(Request $request){
        $user = auth()->user();
        if($user->tipo_perfil != "Coordenador"){
            return response()->json(['error' => 'Unauthorised'], 401);
        } 

        $reuniaoAgendada = AgendamentoReuniao::find($request->id_agenda);

        if($request->hasFile('ata')){
            $reuniao = new Reuniao();
            for($i = 0; $i < count($request->allFiles()['ata']); $i++){
                $file = $request->allFiles()['ata'][$i];
                $reuniao->agendamento_id = $request->id_agenda;
                //$reuniao->ata = $file->store('atas/' . $reuniao->agendamento_id);
                $reuniao->ata = $file->storeAs(
                    'atas/' . $reuniao->agendamento_id,$file->getClientOriginalName());
            }
            $reuniao->participantes = "";
            $reuniao->save();
        }

        if($request->hasFile('fotos')){
            $reuniao = DB::table('reuniaos')->where('agendamento_id',$request->id_agenda)->first();
            for($i = 0; $i < count($request->allFiles()['fotos']); $i++){
                $fotosReuniao = new FotosReuniao();
                $fotosReuniao->reuniao_id = $reuniao->id;
                $file = $request->allFiles()['fotos'][$i];
                //$fotosReuniao->path = $file->store('fotosReuniao/' . $fotosReuniao->reuniao_id);
                $fotosReuniao->path = $file->storeAs(
                    'fotosReuniao/' . $reuniao->agendamento_id,$file->getClientOriginalName());
                $fotosReuniao->save();
            }
        }

        $reuniaoAgendada->registrada = true;
        $reuniaoAgendada->save();
        return response()->json(['ok' => 'sucesso'],200);
    }

    public function getPropriedade(){
        $user = auth()->user();   
        //Passa a tabela produtor como atributo da variável $user
        $propriedade =  $user->produtor->propriedade;
        
        return response()->json(['propriedade' => $propriedade], 200);
    }

    public function atualizarPropriedade(Request $request){
        $user = auth()->user();
        $entrada = $request->all();
        $entrada['fonte_de_agua'] = base64_decode($request->fonte_de_agua);
        $propriedade =  $user->produtor->propriedade;
        $propriedade->fonte_de_agua = $entrada['fonte_de_agua'];
        $propriedade->tamanho_total = $entrada['tamanho_total'];
        $propriedade->save();
        
        return response()->json(['ok' => 'sucesso'],200);
    }

    public function getEndereco(){
        $user = auth()->user();   

        $endereco =  $user->produtor->propriedade->endereco;
        
        return response()->json(['endereco' => $endereco], 200);
    }

    public function atualizarEndereco(Request $request){
        $user = auth()->user();
        $entrada = $request->all();

        $entrada['nome_rua'] = base64_decode($request->nome_rua);
        $entrada['bairro'] = base64_decode($request->bairro);
        $entrada['cidade'] = base64_decode($request->cidade);
        $entrada['estado'] = base64_decode($request->estado);
        $entrada['cep'] = base64_decode($request->cep);
        $entrada['ponto_referencia'] = base64_decode($request->ponto_referencia);

        
        $validator_endereco = Validator::make($entrada, Endereco::$regras_validacao_api);
        if ($validator_endereco->fails()) {
            return response()->json(['erro' => 'endereco'],400);
        }

        $endereco = $user->produtor->propriedade->endereco;
        $endereco->fill($entrada);
        $endereco->save();

        return response()->json(['ok' => 'sucesso'],200);
    }

    public function getMapa($id){
        $user = auth()->user();

        $registro_mapa = FotoMapa::find($id);
           
        $mapa = base64_encode(Storage::get($registro_mapa->path));
       // for($i = 0; $i < count($registros_mapas); $i++){
       //     $mapas[] = [base64_encode(Storage::get($registros_mapas[$i]->path)),$registros_mapas[$i]->created_at];
        //}
        return response()->json(['mapa' => $mapa], 200);
    }

    public function getMapas(Request $request){
        $user = auth()->user();

        $registros_mapas = DB::table('foto_mapas')->where(
            'propriedade_id',$user->produtor->propriedade->id
        )->get();
        $mapas = array();
        for($i = 0; $i < count($registros_mapas); $i++){
            $mapas[] = [$registros_mapas[$i]->created_at,$registros_mapas[$i]->id];
        }
        return response()->json(['mapas' => $mapas],200);
    }

    public function salvarMapa(Request $request){
        $user = auth()->user();
        //return response()->json(['ok' => 'sucesso'],200);
        $propriedade_id = $user->produtor->propriedade->id;
        
        $fotoMapa = new FotoMapa();
        $fotoMapa->propriedade_id = $propriedade_id;

        if($request->has('mapa')){
            if($request->hasFile('mapa')){
                $file = $request->file('mapa'); 
                $fotoMapa->path = $file->storeAs('fotosMapas/'.$propriedade_id,time().".jpg");
                $fotoMapa->save();  
                return response()->json(['ok' => 'sucesso'],200);
            }
        }      
        return response()->json(['erro' => 'erro'],406);  
    }

    public function getInformacaoProdutor(Request $request){
        $user = auth()->user();
        $produtor = $user->produtor;

        return response()->json(['produtor' => $produtor], 200);
    }

    public function atualizarInformacaoProdutor(Request $request){
        $user = auth()->user();
        $user->telefone = $request->telefone;
        $user->email2 = $request->email;
        $user->save();
        
        $produtor = $user->produtor;
        $produtor->data_nascimento = base64_decode($request->data_nascimento);
        $produtor->rg = $request->rg;
        $produtor->nome_conjugue = base64_decode($request->nome_conjuge);
        $produtor->nome_filhos = base64_decode($request->nome_filhos);
        $produtor->save();

        if($request->has('foto')){
            if($request->hasFile('foto')){
                $file = $request->file('foto'); 
                $perfil = $user->perfil;
                if($perfil != null){
                    Storage::delete($perfil->path);
                    $perfil->path = $file->storeAs('fotosPerfil/'.$user->id,time().".jpg");
                    $perfil->save();
                } else {
                    $perfil = new Perfil();
                    $perfil->user_id = $user->id;
                    $perfil->path = $file->storeAs('fotosPerfil/'.$user->id,time().".jpg");
                    $perfil->save();
                }
            }
        }      
        

        return response()->json(['ok' => 'sucesso'],200);
    }
}
