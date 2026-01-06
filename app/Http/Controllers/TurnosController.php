<?php

namespace App\Http\Controllers;

use App\Events\eventTurno;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;
use Pusher\Pusher;

class TurnosController extends Controller
{
    public $turnoParaReset = "J99";

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

    public function storeWhitLetter(Request $request)
    {
        $caja = $request->user;

        // Obtener el último turno de la DB o el ajustado de cache
        $ultimoTurnoDB = Turno::orderBy('id', 'desc')->first()->turno ?? null;
        // Log::info("ultimoTurnoDB: " . $ultimoTurnoDB);


        // Log::info("Cache('ajuste_turno'): " . Cache::get('ajuste_turno'));

        $baseTurno = Cache::get('ajuste_turno') ?? $ultimoTurnoDB;

        // Log::info("baseTurno: " . $baseTurno);

        $nuevoTurno = $this->generarSiguienteTurno($baseTurno);

        $guardar = new Turno();
        $guardar->turno = $nuevoTurno;
        $guardar->caja = $caja;
        $guardar->save();

        // Limpiar el cache después de usar
        Cache::forget('ajuste_turno');

        $turnosAll = Turno::select('turno', 'caja')->orderBy('id', 'desc')->take(5)->get();

        // Reset si llega a J99
        if ($nuevoTurno === $this->turnoParaReset) {
            DB::statement("TRUNCATE TABLE turnos;");
        }

        event(new eventTurno(json_encode($turnosAll)));

        return view('dashboard')->with('turno', $guardar->turno);
    }

    /**
     * Genera el siguiente turno en formato letra + número (ej: A01, A02, ..., J99)
     */
    private function generarSiguienteTurno($ultimoTurno = null)
    {
        if (!$ultimoTurno) {
            return 'A01';
        }

        // Separar letra y número
        $letra = substr($ultimoTurno, 0, 1);
        $numero = (int) substr($ultimoTurno, 1, 2);

        // Incrementar número
        $numero++;

        // Si llega a 100, pasar a siguiente letra
        if ($numero > 99) {
            $numero = 1;
            $letra = chr(ord($letra) + 1); // Siguiente letra
        }

        // Formatear número a 2 dígitos
        $numeroFormateado = str_pad($numero, 2, '0', STR_PAD_LEFT);

        $nuevoTurno = $letra . $numeroFormateado;

        // Si llega a J99, devolver J99 (el reset se maneja en storeWhitLetter)
        if ($nuevoTurno === $this->turnoParaReset) {
            return $this->turnoParaReset;
        }

        return $nuevoTurno;
    }

    public function ajustarTurno($turno)
    {
        // Log::info("turno: " . $turno);
        // Guardar el turno en cache para usarlo en el próximo turno generado

        // Separar letra y número
        $letra = substr($turno, 0, 1);
        $numero = (int) substr($turno, 1, 2);
        $numero--;
        // Si llega a 100, pasar a siguiente letra
        if ($numero > 99) {
            $numero = 1;
            $letra = chr(ord($letra) + 1); // Siguiente letra
        }

        // Formatear número a 2 dígitos
        $numeroFormateado = str_pad($numero, 2, '0', STR_PAD_LEFT);

        $nuevoTurno = $letra . $numeroFormateado;

        Cache::put('ajuste_turno', $nuevoTurno, now()->addHours(24)); // Expira en 24 horas
    }

    /**
     * Convierte un turno (ej: A01) a su ID correspondiente (1 para A01, 100 para B01, etc.)
     */
    public function turnoToId($turno)
    {
        if (!$turno || strlen($turno) != 3) {
            throw new \InvalidArgumentException('Turno inválido');
        }

        $letra = strtoupper(substr($turno, 0, 1));
        $numero = (int) substr($turno, 1, 2);

        if ($letra < 'A' || $letra > 'J' || $numero < 1 || $numero > 99) {
            throw new \InvalidArgumentException('Turno fuera de rango');
        }

        $letraIndex = ord($letra) - ord('A'); // A=0, B=1, ..., J=9
        $id = $letraIndex * 100 + $numero;

        return $id;
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
