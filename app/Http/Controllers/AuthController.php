<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $req){
        $vd = $req->validate([
            'name' => 'required|string|max:255|min:5',
            'email' => 'required|string|email|max:255|min:5',
            'password' => 'required|string|max:255|min:8'
        ]);
        $user = User::where('email', $req->email)->first();
        if (!empty($user->id))
            return response()->json(['message' => 'Usuario previamente registrado'], 401);
        $user = User::create([
            'name' => $vd['name'],
            'email' => $vd['email'],
            'password' => Hash::make($vd['password'])
        ]);
        return response()->json($this->refreshToken($user));
    }
    public function login(Request $req){
        if(!Auth::attempt($req->only('email','password')))
            return response()->json(['message' => 'Usuario inválido'], 401);
        $user = User::where('email', $req->email)->firstOrFail();
        if (empty($user->id))
            return response()->json(['message' => 'Usuario inválido'], 401);
        return response()->json($this->refreshToken($user));
    }
    private function refreshToken($user = null){
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];
    }
    public function exchange(Request $req){
        $dat = $req->user();
        return "hoa";
        return (empty($req->user())) ? 'vacio' : 'lleno';

    }
    public function index(){
        /*
        $response = Http::post('http://data.fixer.io/api/latest?access_key=e4d45f70cd6cb80d01fc5afe1faf0cb8&format=1');
        $body = json_decode($response->body(), true);
        $data['rates']['provider_1']['source'] = 'fixer';
        $data['rates']['provider_1']['last_update'] = date('d/m/Y H:i:s', $body['timestamp']);
        $data['rates']['provider_1']['exchange_type'] = 'MXN';
        $data['rates']['provider_1']['value'] = $body['rates']['MXN'];
        $databanx = file_get_contents('https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/oportuno?token=0185638d23517867594c4261f86df8c52569f516803cc076eb885b5dc342df80');
        $databanx = json_decode($databanx, true);
        $data['rates']['provider_2']['source'] = 'banxico';
        $data['rates']['provider_2']['last_update'] = $databanx['bmx']['series'][0]['datos'][0]['fecha'];
        $data['rates']['provider_2']['exchange_type'] = 'MXN';
        $data['rates']['provider_2']['value'] = (float)$databanx['bmx']['series'][0]['datos'][0]['dato'];
        $content = file_get_contents('http://dof.gob.mx/indicadores_detalle.php?cod_tipo_indicador=158&dfecha='.date('d').'%2F04%2F2022&hfecha='.date('d').'%2F04%2F2022');
        $aux1 = explode('DOLAR', $content);
        preg_match_all('!\d+!', $aux1[2], $aux2);
        $dof_value = (float)($aux2[0][0].'.'.$aux2[0][1]);
        $data['rates']['provider_3']['source'] = 'Diario-oficial-federación';
        $data['rates']['provider_3']['last_update'] = date('d/m/Y');
        $data['rates']['provider_3']['exchange_type'] = 'MXN';
        $data['rates']['provider_3']['value'] = $dof_value;
        return response()->json($data);
        */
    }
}
