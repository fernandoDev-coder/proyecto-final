<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Pelicula;

class PeliculaSeeder extends Seeder
{
    public function run()
    {
        // Desactiva restricciones de llaves foráneas y limpia la tabla
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('peliculas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');        

        $peliculas = [
            [
                'titulo' => 'Inception',
                'descripcion' => 'Un ladrón especializado en el robo de secretos a través del uso de la tecnología de la "invasión de sueños" recibe una tarea aparentemente imposible: plantar una idea en la mente de un CEO.',
                'genero' => 'Ciencia ficción, Acción, Aventura',
                'imagen' => 'https://image.tmdb.org/t/p/w500/edv5CZvWj09upOsy2Y6IwDhK8bt.jpg',
                'duracion' => 148,
                'clasificacion' => 'PG-13'
            ],
            [
                'titulo' => 'The Dark Knight',
                'descripcion' => 'Batman enfrenta a un criminal llamado "El Joker", quien se dedica a causar caos en Gotham City. El enfrentamiento culmina en una batalla moral entre el bien y el mal.',
                'genero' => 'Acción, Crimen, Drama',
                'imagen' => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'duracion' => 152,
                'clasificacion' => 'PG-13'
            ],
            [
                'titulo' => 'The Shawshank Redemption',
                'descripcion' => 'Andy Dufresne, condenado a dos cadenas perpetuas por el asesinato de su esposa y su amante, forja una inesperada amistad con el prisionero Ellis Redding mientras cumple su condena en la prisión de Shawshank.',
                'genero' => 'Drama',
                'imagen' => 'https://image.tmdb.org/t/p/w500/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg',
                'duracion' => 142,
                'clasificacion' => 'R'
            ],
            [
                'titulo' => 'The Godfather',
                'descripcion' => 'La saga de la familia Corleone, que narra la vida de Don Vito Corleone, un poderoso jefe de la mafia en Nueva York, y la transición del poder a su hijo Michael.',
                'genero' => 'Crimen, Drama',
                'imagen' => 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg',
                'duracion' => 175,
                'clasificacion' => 'R'
            ],
            [
                'titulo' => 'Pulp Fiction',
                'descripcion' => 'Las historias entrelazadas de un par de sicarios, un boxeador, una pareja de ladrones y otros personajes cuyas vidas se cruzan de forma impactante y a menudo absurda.',
                'genero' => 'Crimen, Drama',
                'imagen' => 'https://image.tmdb.org/t/p/w500/d5iIlFn5s0ImszYzBPb8JPIfbXD.jpg',
                'duracion' => 154,
                'clasificacion' => 'R'
            ],
            [
                'titulo' => 'Forrest Gump',
                'descripcion' => 'La extraordinaria vida de un hombre con bajo coeficiente intelectual, que involuntariamente influye en varios eventos históricos y culturales de Estados Unidos.',
                'genero' => 'Drama, Romance',
                'imagen' => 'https://image.tmdb.org/t/p/w500/arw2vcBveWOVZr6pxd9XTd1TdQa.jpg',
                'duracion' => 142,
                'clasificacion' => 'PG-13'
            ],
            [
                'titulo' => 'The Matrix',
                'descripcion' => 'Un hacker descubre que la realidad en la que vive es una simulación creada por máquinas para someter a la humanidad, y se une a un grupo de rebeldes que luchan por liberarla.',
                'genero' => 'Acción, Ciencia ficción',
                'imagen' => 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg',
                'duracion' => 136,
                'clasificacion' => 'R'
            ],
            [
                'titulo' => 'Gladiator',
                'descripcion' => 'Un general romano que es traicionado y reducido a la esclavitud lucha por vengarse del emperador corrupto que destruyó su vida.',
                'genero' => 'Acción, Aventura, Drama',
                'imagen' => 'https://image.tmdb.org/t/p/w500/ty8TGRuvJLPUmAR1H1nRIsgwvim.jpg',
                'duracion' => 155,
                'clasificacion' => 'R'
            ],
            [
                'titulo' => 'Interstellar',
                'descripcion' => 'Un grupo de astronautas viaja a través de un agujero de gusano en busca de un nuevo hogar para la humanidad, mientras enfrentan misteriosas fuerzas del espacio y el tiempo.',
                'genero' => 'Ciencia ficción, Drama',
                'imagen' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
                'duracion' => 169,
                'clasificacion' => 'PG-13'
            ],
            [
                'titulo' => 'Fight Club',
                'descripcion' => 'Un hombre insatisfecho con su vida establece un club clandestino en el que los hombres pueden luchar entre sí para liberar su frustración y explorar su virilidad.',
                'genero' => 'Drama',
                'imagen' => 'https://image.tmdb.org/t/p/w500/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg',
                'duracion' => 139,
                'clasificacion' => 'R'
            ],
            [
                'titulo' => 'The Silence of the Lambs',
                'descripcion' => 'Una joven aprendiz del FBI busca la ayuda de un asesino caníbal encarcelado para capturar a otro asesino en serie.',
                'genero' => 'Crimen, Drama, Suspenso',
                'imagen' => 'https://image.tmdb.org/t/p/w500/rplLJ2hPcOQmkFhTqUte0MkEaO2.jpg',
                'duracion' => 118,
                'clasificacion' => 'R'
            ],
            [
                'titulo' => 'Titanic',
                'descripcion' => 'Una historia de amor imposible que se desarrolla a bordo del trágico barco Titanic.',
                'genero' => 'Drama, Romance',
                'imagen' => 'https://image.tmdb.org/t/p/w500/9xjZS2rlVxm8SFx8kPC3aIGCOYQ.jpg',
                'duracion' => 194,
                'clasificacion' => 'PG-13'
            ],
            [
                'titulo' => 'Avatar',
                'descripcion' => 'Un ex-marine se adentra en el mundo de Pandora y debe elegir entre seguir órdenes o proteger a los nativos.',
                'genero' => 'Ciencia ficción, Aventura',
                'imagen' => 'https://image.tmdb.org/t/p/w500/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg',
                'duracion' => 162,
                'clasificacion' => 'PG-13'
            ],
            [
                'titulo' => 'Avengers: Endgame',
                'descripcion' => 'Los Vengadores restantes luchan por deshacer el daño causado por Thanos en una épica batalla final.',
                'genero' => 'Acción, Ciencia ficción',
                'imagen' => 'https://image.tmdb.org/t/p/w500/br6krBFpaYmCSglLBWRuhui7tPc.jpg',
                'duracion' => 181,
                'clasificacion' => 'PG-13'
            ],
            [
                'titulo' => 'Joker',
                'descripcion' => 'La historia del origen del icónico villano de DC, mostrando su descenso a la locura.',
                'genero' => 'Crimen, Drama, Suspenso',
                'imagen' => 'https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg',
                'duracion' => 122,
                'clasificacion' => 'R'
            ],
            [
                'titulo' => 'Coco',
                'descripcion' => 'Un niño mexicano viaja al mundo de los muertos para descubrir su legado familiar y seguir su pasión por la música.',
                'genero' => 'Animación, Aventura, Familia',
                'imagen' => 'https://image.tmdb.org/t/p/w500/gGEsBPAijhVUFoiNpgZXqRVWJt2.jpg',
                'duracion' => 105,
                'clasificacion' => 'PG'
            ]
        ];

        foreach ($peliculas as $pelicula) {
            Pelicula::create($pelicula);
        }
    }
}
