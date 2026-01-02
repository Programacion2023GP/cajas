<?php

namespace App\Http\Controllers;

use App\Events\eventTurno;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

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

        if ($ultimoTurno->turno == 99) {

            DB::statement("TRUNCATE TABLE turnos;");
        }

        event(new eventTurno(json_encode($turnosAll)));


        /*      return response()->json($turnosAll); */

        return view('dashboard')->with('turno', $guardar->id);

        /*     DB::statement("ALTER TABLE books AUTO_INCREMENT = 14000;"); */
    }

    /**
     * Función para repetir anuncio de turno
     */
    public function repetirAnuncio(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'turno' => 'required|string',
            'caja' => 'required|string'
        ]);

        try {
            // Configurar Pusher
            $pusher = new Pusher(
                env('PUSHER_APP_KEY', 'b70b1baa91c69051cb75'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' => env('PUSHER_APP_CLUSTER', 'us2'),
                    'useTLS' => true
                ]
            );

            // Datos a enviar
            $datosAnuncio = [
                'usuario' => $request->usuario,
                'turno' => $request->turno,
                'caja' => $request->caja,
                'timestamp' => now()->format('H:i:s'),
                'tipo' => 'repetir_anuncio'
            ];

            // Enviar evento para anunciar en la pantalla
            $pusher->trigger('myCanal', 'myEvento', [
                'message' => json_encode([[
                    'caja' => $datosAnuncio['caja'],
                    'turno' => $datosAnuncio['turno']
                ]]),
                'tipo' => 'repetir-anuncio'
            ]);

            // Enviar evento de confirmación al usuario
            $pusher->trigger('myCanal', 'anuncio-repetido', [
                'usuario' => $datosAnuncio['usuario'],
                'mensaje' => 'Anuncio repetido exitosamente',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Anuncio enviado para repetir',
                'data' => $datosAnuncio
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al repetir anuncio: ' . $e->getMessage()
            ], 500);
        }
    }
}
