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

    public function show($params)
    {
        $codigo = $params['pais'] ?? null;

        $pais = $this->db->query(
            "SELECT * FROM paises WHERE codigo = ?",
            [$codigo]
        )->find();

        if (!$pais) {
            abort(404); // puedes usar tu helper de errores
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
