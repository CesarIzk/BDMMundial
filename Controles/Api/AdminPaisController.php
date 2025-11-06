<?php
namespace Controles\Api;

use Core\App;

class AdminPaisController {
    protected $db;

    public function __construct() {
        $this->db = App::resolve('Core\Database');
    }

    // ====================
    // LISTAR PAÍSES
    // ====================
    public function index() {
        $paises = $this->db->query("SELECT * FROM paises ORDER BY nombre")->get();
        return view('admin/paises_lista.php', ['paises' => $paises]);
    }

    // ====================
    // FORMULARIO CREAR
    // ====================
    public function create() {
        return view('admin/paises_crear.php');
    }

    // ====================
    // GUARDAR NUEVO
    // ====================
    public function store() {
        $this->db->query("
            INSERT INTO paises (codigo, nombre, descripcion, historia, titulos, participaciones, continente, entrenador, mejorJugador, bandera)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $_POST['codigo'],
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['historia'],
            $_POST['titulos'] ?? 0,
            $_POST['participaciones'] ?? 0,
            $_POST['continente'],
            $_POST['entrenador'],
            $_POST['mejorJugador'],
            $_POST['bandera']
        ]);

        header("Location: /admin/paises");
        exit;
    }

    // ====================
    // EDITAR EXISTENTE
    // ====================
    public function edit($params) {
        $id = $params['id'] ?? null;
        $pais = $this->db->query("SELECT * FROM paises WHERE idPais = ?", [$id])->find();

        if (!$pais) abort(404);

        return view('admin/paises_edit.php', ['pais' => $pais]);
    }

    // ====================
    // ACTUALIZAR EXISTENTE
    // ====================
    public function update($params) {
        $id = $params['id'] ?? null;

        $this->db->query("
            UPDATE paises SET
                nombre = ?,
                descripcion = ?,
                historia = ?,
                titulos = ?,
                participaciones = ?,
                continente = ?,
                entrenador = ?,
                mejorJugador = ?,
                bandera = ?
            WHERE idPais = ?
        ", [
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['historia'],
            $_POST['titulos'],
            $_POST['participaciones'],
            $_POST['continente'],
            $_POST['entrenador'],
            $_POST['mejorJugador'],
            $_POST['bandera'],
            $id
        ]);

        header("Location: /admin/paises");
        exit;
    }
    
}
