<?php
namespace Controles\Api;

use Core\App;

class PaisController
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve('Core\Database');
    }

    // ✅ LISTAR TODOS LOS PAÍSES DISPONIBLES
    public function index()
    {
        $paises = $this->db->query("SELECT * FROM paises ORDER BY nombre ASC")->get();

        return view('equipo.php', [
            'paises' => $paises
        ]);
    }

    // ✅ MOSTRAR DETALLE DE UN PAÍS
    public function show($params)
    {
        $codigo = $params['pais'] ?? null;

        $pais = $this->db->query(
            "SELECT * FROM paises WHERE codigo = ?",
            [$codigo]
        )->find();

        if (!$pais) {
            return view('404.php', [
                'mensaje' => "El país solicitado no existe en la base de datos."
            ]);
        }

        $imagenes = $this->db->query(
            "SELECT * FROM pais_imagenes WHERE idPais = ?",
            [$pais['idPais']]
        )->get();

        $videos = $this->db->query(
            "SELECT * FROM pais_videos WHERE idPais = ?",
            [$pais['idPais']]
        )->get();

        return view('pais_detalle.php', [
            'pais' => $pais,
            'imagenes' => $imagenes,
            'videos' => $videos
        ]);
    }
}
