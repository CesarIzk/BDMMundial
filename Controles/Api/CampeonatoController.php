<?php

namespace Controles\Api;

use Controles\Models\Campeonato;

class CampeonatoController
{
    protected $campeonato;

    public function __construct()
    {
        $this->campeonato = new Campeonato();
    }

    public function index()
    {
        $ediciones = $this->campeonato->all();
        $equipos = $this->campeonato->getEquiposExitosos();
        $jugadores = $this->campeonato->getJugadoresDestacados();

        // 👇 Esta vista ya recibirá las variables correctamente
        return view('campeonatos.php', [
            'ediciones' => $ediciones,
            'equipos' => $equipos,
            'jugadores' => $jugadores
        ]);
    }

    public function show($anio)
    {
        $campeonato = $this->campeonato->findByYear($anio);

        if (!$campeonato) {
            return view('404.php', ['mensaje' => 'Campeonato no encontrado']);
        }

        return view('detalleCampeonato.php', ['campeonato' => $campeonato]);
    }
}
