<?php

namespace Controles\Models;

use Core\App;

class Campeonato
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve('Core\Database');
    }

    /** Obtener todas las ediciones */
    public function all()
    {
        return $this->db->query(
            "SELECT * FROM campeonatos ORDER BY anio ASC"
        )->get();
    }

    /** Obtener equipos exitosos */
    public function getEquiposExitosos()
    {
        return $this->db->query(
            "SELECT * FROM equipos_exitosos ORDER BY titulos DESC"
        )->get();
    }

    /** Obtener jugadores destacados */
    public function getJugadoresDestacados()
    {
        return $this->db->query(
            "SELECT * FROM jugadores_destacados ORDER BY nombre ASC"
        )->get();
    }

    /** Buscar campeonato por año */
    public function findByYear($anio)
    {
        return $this->db->query(
            "SELECT * FROM campeonatos WHERE anio = ?",
            [$anio]
        )->find();
    }

    /** Agregar nuevo campeonato (para futuro panel admin) */
    public function create($data)
    {
        $this->db->query(
            "INSERT INTO campeonatos (anio, paisSede, bandera, campeon, subcampeon, golesTotales, equiposParticipantes, descripcion)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['anio'],
                $data['paisSede'],
                $data['bandera'],
                $data['campeon'],
                $data['subcampeon'],
                $data['golesTotales'],
                $data['equiposParticipantes'],
                $data['descripcion']
            ]
        );
    }
}
