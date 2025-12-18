<?php

namespace App\Http\Controllers;

use App\Events\eventTurno;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TurnosController extends Controller
{
    public function store(Request $request)
    {
        $caja = $request->user;

        /*    $lista = [
            array("TURNO" => $turno, "CAJA" => $caja),
        ];
        event(new eventTurno(json_encode($lista))); */

        /*     $turnos = Turno::select('turno')->orderBy('turno', 'desc')->first();
        $ultimoTurno =$turnos->turno; */


        $guardar = new Turno();
        $guardar->caja = $caja;
        $guardar->save();

        $turnosAll = Turno::select('turno', 'caja')->orderBy('turno', 'desc')->get()->take(5);

        $ultimoTurno = $turnosAll[0];

        if ($ultimoTurno->turno == 999) {

            DB::statement("TRUNCATE TABLE turnos;");
        }

        event(new eventTurno(json_encode($turnosAll)));


        /*      return response()->json($turnosAll); */

        return view('dashboard')->with('turno', $guardar->id);

        /*     DB::statement("ALTER TABLE books AUTO_INCREMENT = 14000;"); */
    }
}
