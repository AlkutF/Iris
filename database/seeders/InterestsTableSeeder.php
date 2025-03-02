<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('interests')->insert([
            // Intereses para Desarrollo de Software
            ['name' => 'Programación'],
            ['name' => 'Desarrollo de aplicaciones móviles'],
            ['name' => 'Desarrollo web'],
            ['name' => 'Bases de datos'],
            ['name' => 'Inteligencia Artificial'],
            ['name' => 'Ciberseguridad'],
            ['name' => 'Algoritmos'],

            // Intereses para Diseño Gráfico
            ['name' => 'Diseño digital'],
            ['name' => 'Adobe Photoshop'],
            ['name' => 'Ilustración'],
            ['name' => 'Animación 2D/3D'],
            ['name' => 'Diseño UX/UI'],
            ['name' => 'Fotografía digital'],
            ['name' => 'Diseño de logotipos'],

            // Intereses para Entrenamiento Deportivo
            ['name' => 'Preparación física'],
            ['name' => 'Nutrición deportiva'],
            ['name' => 'Psicología deportiva'],
            ['name' => 'Yoga'],
            ['name' => 'Crossfit'],
            ['name' => 'Running'],
            ['name' => 'Pilates'],

            // Intereses para Educación Inicial
            ['name' => 'Métodos de enseñanza'],
            ['name' => 'Psicopedagogía'],
            ['name' => 'Juegos educativos'],
            ['name' => 'Pedagogía Montessori'],
            ['name' => 'Educación inclusiva'],
            ['name' => 'Desarrollo infantil'],
            ['name' => 'Enseñanza de idiomas'],

            // Intereses para Mecánica Automotriz
            ['name' => 'Mantenimiento de vehículos'],
            ['name' => 'Reparación de automóviles'],
            ['name' => 'Diseño de motores'],
            ['name' => 'Tecnología automotriz'],
            ['name' => 'Mecánica de precisión'],
            ['name' => 'Seguridad automotriz'],
            ['name' => 'Electricidad automotriz'],

            // Intereses para Educación Básica
            ['name' => 'Pedagogía'],
            ['name' => 'Métodos de enseñanza primaria'],
            ['name' => 'Psicología infantil'],
            ['name' => 'Actividades didácticas'],
            ['name' => 'Planificación educativa'],
            ['name' => 'Aprendizaje en línea'],
            ['name' => 'Evaluación educativa'],

            // Intereses para Electrónica
            ['name' => 'Circuitos electrónicos'],
            ['name' => 'Diseño de PCB'],
            ['name' => 'Robótica'],
            ['name' => 'Energía renovable'],
            ['name' => 'Sensores'],
            ['name' => 'Automatización industrial'],
            ['name' => 'IoT (Internet de las Cosas)'],

            // Intereses para Gastronomía
            ['name' => 'Cocina internacional'],
            ['name' => 'Repostería'],
            ['name' => 'Cocina vegana'],
            ['name' => 'Técnicas culinarias'],
            ['name' => 'Seguridad alimentaria'],
            ['name' => 'Cocina saludable'],
            ['name' => 'Enología'],

            // Intereses para Redes & Telecomunicaciones
            ['name' => 'Redes informáticas'],
            ['name' => 'Seguridad en redes'],
            ['name' => 'Comunicaciones inalámbricas'],
            ['name' => 'Hardware de redes'],
            ['name' => 'Protocolos de comunicación'],
            ['name' => 'Redes sociales'],
            ['name' => '5G y telecomunicaciones'],

            // Intereses para Contabilidad y Asesoría Tributaria
            ['name' => 'Finanzas'],
            ['name' => 'Contabilidad financiera'],
            ['name' => 'Auditoría'],
            ['name' => 'Impuestos'],
            ['name' => 'Gestión empresarial'],
            ['name' => 'Planificación fiscal'],
            ['name' => 'Análisis financiero'],

            // Intereses para Educación Inclusiva
            ['name' => 'Inclusión educativa'],
            ['name' => 'Accesibilidad'],
            ['name' => 'Enseñanza diferenciada'],
            ['name' => 'Estrategias de inclusión'],
            ['name' => 'Discapacidad intelectual'],
            ['name' => 'Apoyo educativo'],
            ['name' => 'Políticas de inclusión'],

            // Intereses para Marketing & Comercio Electrónico
            ['name' => 'Estrategias de marketing'],
            ['name' => 'Publicidad digital'],
            ['name' => 'SEO (Optimización en buscadores)'],
            ['name' => 'Marketing en redes sociales'],
            ['name' => 'Comercio electrónico'],
            ['name' => 'Branding'],
            ['name' => 'Análisis de mercado'],

            // Intereses para Talento Humano
            ['name' => 'Gestión de recursos humanos'],
            ['name' => 'Psicología organizacional'],
            ['name' => 'Capacitación y desarrollo'],
            ['name' => 'Gestión del cambio'],
            ['name' => 'Evaluación de desempeño'],
            ['name' => 'Clima organizacional'],
            ['name' => 'Relaciones laborales'],
        ]);
    }
}
