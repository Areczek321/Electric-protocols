<?php

namespace Database\Seeders;

use App\Models\CircuitBreakerCatalog;
use App\Models\DifferentialBreakerCataloge;
use App\Models\InsulationCatalog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Alfred',
        //     'email' => 'anyszekalfred69@gmail.com',
        //     'password'=>'Kaweczyn149'
        // ]);
        // User::factory()->create([
        //     'name' => 'Wojtek',
        //     'email' => 'a@a.pl',
        // ]);
        $circuitBreakers = [
            // S 301 - Jednobiegunowe
            ['name' => 'S 301', 'characteristic' => 'B', 'rated_current' => 10, 'multiplicity_factor' => '5'],
            ['name' => 'S 301', 'characteristic' => 'B', 'rated_current' => 16, 'multiplicity_factor' => '5'],
            ['name' => 'S 301', 'characteristic' => 'B', 'rated_current' => 20, 'multiplicity_factor' => '5'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 6,  'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 10, 'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 16, 'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 20, 'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 25, 'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'D', 'rated_current' => 10, 'multiplicity_factor' => '20'],
            // ['name' => 'S 301', 'characteristic' => 'D', 'rated_current' => 16, 'multiplicity_factor' => '20'],
            // ['name' => 'S 301', 'characteristic' => 'D', 'rated_current' => 20, 'multiplicity_factor' => '20'],
            // ['name' => 'S 301', 'characteristic' => 'D', 'rated_current' => 25, 'multiplicity_factor' => '20'],

            // // S 302 - Dwubiegunowe (Charakterystyka B)
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 6,  'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 10, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 16, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 20, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 25, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 32, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 40, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 50, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 63, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'D', 'rated_current' => 10, 'multiplicity_factor' => '20'],
            // ['name' => 'S 302', 'characteristic' => 'D', 'rated_current' => 16, 'multiplicity_factor' => '20'],
            // ['name' => 'S 302', 'characteristic' => 'D', 'rated_current' => 20, 'multiplicity_factor' => '20'],
            // ['name' => 'S 302', 'characteristic' => 'D', 'rated_current' => 25, 'multiplicity_factor' => '20'],

            // // S 302 - Dwubiegunowe (Charakterystyka C)
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 6,  'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 10, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 16, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 20, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 25, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 32, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 40, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 50, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 63, 'multiplicity_factor' => '10'],
            // // S 303 - Trójbiegunowe (Charakterystyka B i C do obwodów siłowych)
            ['name' => 'S 303', 'characteristic' => 'B', 'rated_current' => 16, 'multiplicity_factor' => '5'],
            ['name' => 'S 303', 'characteristic' => 'B', 'rated_current' => 20, 'multiplicity_factor' => '5'],
            ['name' => 'S 303', 'characteristic' => 'B', 'rated_current' => 25, 'multiplicity_factor' => '5'],
            // ['name' => 'S 303', 'characteristic' => 'C', 'rated_current' => 16, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'C', 'rated_current' => 20, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'C', 'rated_current' => 25, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'C', 'rated_current' => 32, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 16, 'multiplicity_factor' => '20'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 20, 'multiplicity_factor' => '20'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 25, 'multiplicity_factor' => '20'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 32, 'multiplicity_factor' => '20'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 40, 'multiplicity_factor' => '20'],

            // FR - Rozłączniki izolacyjne (Główne wyłączniki prądu - nie mają krotności/charakterystyki wyrzutu)
            ['name' => 'FR 303', 'characteristic' => null, 'rated_current' => 40, 'multiplicity_factor' => null],
            ['name' => 'FR 303', 'characteristic' => null, 'rated_current' => 63, 'multiplicity_factor' => null],
            ['name' => 'FR 303', 'characteristic' => null, 'rated_current' => 100, 'multiplicity_factor' => null],
            ['name' => 'FR 304', 'characteristic' => null, 'rated_current' => 40, 'multiplicity_factor' => null],
            ['name' => 'FR 304', 'characteristic' => null, 'rated_current' => 100, 'multiplicity_factor' => null],
            ['name' => 'FR 304', 'characteristic' => null, 'rated_current' => 63, 'multiplicity_factor' => null],
            //misc
            
            ['name' => 'PSA-16A-3P', 'characteristic' => null, 'rated_current' => 16, 'multiplicity_factor' => null],
            ['name' => 'PSR-440', 'characteristic' => null, 'rated_current' => 40, 'multiplicity_factor' => null],
            ['name' => 'CZF-310', 'characteristic' => null, 'rated_current' => 10, 'multiplicity_factor' => null],

        ];
        $differentialBreakers = [
            // Różnicówki 4-biegunowe (Trójfazowe) - Legrand P 304
            ['name' => 'P 304',  'current' => 30, 'time' => '50', 'safe_voltage' => 50],

            // Różnicówki 2-biegunowe (Jednofazowe) - Legrand P 302
            ['name' => 'P 302',  'current' => 30, 'time' => '50', 'safe_voltage' => 50],

            // Konkretne modele z katalogu (np. Hager)
            ['name' => 'P 302', 'current' => 30, 'time' => '50', 'safe_voltage' => 50],
        ];
        $insulationCataloges = [
            [
                'insulation' => 'Polietylen',
                'resistance_allowed' => 1,
            ],

        ];

        foreach ($circuitBreakers as $circuitBreaker) {
            CircuitBreakerCatalog::firstOrCreate(
                [
                    'name' => $circuitBreaker['name'],
                    'characteristic' => $circuitBreaker['characteristic'],
                    'rated_current' => $circuitBreaker['rated_current'],
                ],
                [
                    'multiplicity_factor' => $circuitBreaker['multiplicity_factor'],
                ]
            );
        }
        foreach ($differentialBreakers as $differentialBreaker) {
            DifferentialBreakerCataloge::firstOrCreate(
                ['name' => $differentialBreaker['name']],
                [
                    'current' => $differentialBreaker['current'],
                    'time' => $differentialBreaker['time'],
                    'safe_voltage' => $differentialBreaker['safe_voltage'],
                ]
            );
        }
        foreach ($insulationCataloges as $insulationCataloge) {
            InsulationCatalog::firstOrCreate(
                ['insulation' => $insulationCataloge['insulation']],
                [
                    'resistance_allowed' => $insulationCataloge['resistance_allowed'],
                ]
            );
        }
        $breakersToDelete = [
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 6,  'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 10, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 16, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 20, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 25, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 32, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 40, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 50, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'B', 'rated_current' => 63, 'multiplicity_factor' => '5'],
            // ['name' => 'S 302', 'characteristic' => 'D', 'rated_current' => 10, 'multiplicity_factor' => '20'],
            // ['name' => 'S 302', 'characteristic' => 'D', 'rated_current' => 16, 'multiplicity_factor' => '20'],
            // ['name' => 'S 302', 'characteristic' => 'D', 'rated_current' => 20, 'multiplicity_factor' => '20'],
            // ['name' => 'S 302', 'characteristic' => 'D', 'rated_current' => 25, 'multiplicity_factor' => '20'],

            // // S 302 - Dwubiegunowe (Charakterystyka C)
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 6,  'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 10, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 16, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 20, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 25, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 32, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 40, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 50, 'multiplicity_factor' => '10'],
            // ['name' => 'S 302', 'characteristic' => 'C', 'rated_current' => 63, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'C', 'rated_current' => 16, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'C', 'rated_current' => 20, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'C', 'rated_current' => 25, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'C', 'rated_current' => 32, 'multiplicity_factor' => '10'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 16, 'multiplicity_factor' => '20'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 20, 'multiplicity_factor' => '20'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 25, 'multiplicity_factor' => '20'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 32, 'multiplicity_factor' => '20'],
            // ['name' => 'S 303', 'characteristic' => 'D', 'rated_current' => 40, 'multiplicity_factor' => '20'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 6,  'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 10, 'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 16, 'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 20, 'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'C', 'rated_current' => 25, 'multiplicity_factor' => '10'],
            // ['name' => 'S 301', 'characteristic' => 'D', 'rated_current' => 10, 'multiplicity_factor' => '20'],
            // ['name' => 'S 301', 'characteristic' => 'D', 'rated_current' => 16, 'multiplicity_factor' => '20'],
            // ['name' => 'S 301', 'characteristic' => 'D', 'rated_current' => 20, 'multiplicity_factor' => '20'],
            // ['name' => 'S 301', 'characteristic' => 'D', 'rated_current' => 25, 'multiplicity_factor' => '20'],
            
            // ['name' => 'FR 304', 'characteristic' => 'PSA-16A-3P', 'rated_current' => 16, 'multiplicity_factor' => null],
            // ['name' => 'FR 304', 'characteristic' => 'PSR-440', 'rated_current' => 40, 'multiplicity_factor' => null],
            
            ['name' => 'FR 303', 'characteristic' => null, 'rated_current' => 40, 'multiplicity_factor' => null],
            ['name' => 'FR 304', 'characteristic' => null, 'rated_current' => 40, 'multiplicity_factor' => null],
        ];

        foreach ($breakersToDelete as $breaker) {
            CircuitBreakerCatalog::query()
                ->where('name', $breaker['name'])
                ->where('characteristic', $breaker['characteristic'])
                ->where('rated_current', $breaker['rated_current'])
                ->delete();
        }

    }
}
