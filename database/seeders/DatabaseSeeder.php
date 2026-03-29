<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Intervention;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === UTILIZATORI ===
        $admin = User::create([
            'name' => 'Admin Platform',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        $client = User::create([
            'name' => 'Vlad Popescu',
            'email' => 'vlad@test.com',
            'password' => Hash::make('123456'),
            'role' => 'client',
        ]);

        $client2 = User::create([
            'name' => 'Maria Ionescu',
            'email' => 'maria@test.com',
            'password' => Hash::make('123456'),
            'role' => 'client',
        ]);

        $serviceUser1 = User::create([
            'name' => 'AutoPro Focșani',
            'email' => 'autopro@test.com',
            'password' => Hash::make('123456'),
            'role' => 'service',
        ]);

        $serviceUser2 = User::create([
            'name' => 'Speedy Auto',
            'email' => 'speedy@test.com',
            'password' => Hash::make('123456'),
            'role' => 'service',
        ]);

        $serviceUser3 = User::create([
            'name' => 'Midas Auto',
            'email' => 'midas@test.com',
            'password' => Hash::make('123456'),
            'role' => 'service',
        ]);

        // === SERVICE-URI ===
        $autoPro = Service::create([
            'user_id' => $serviceUser1->id,
            'name' => 'AutoPro Focșani',
            'address' => 'Str. Republicii 45',
            'city' => 'Focșani',
            'lat' => 45.698,
            'lng' => 27.181,
            'rating' => 4.8,
            'phone' => '0722 123 456',
            'description' => 'Service auto autorizat cu experiență de peste 15 ani. Specializați în mărci germane.',
        ]);

        $speedy = Service::create([
            'user_id' => $serviceUser2->id,
            'name' => 'Speedy Auto București',
            'address' => 'Bd. Iuliu Maniu 120',
            'city' => 'București',
            'lat' => 44.426,
            'lng' => 26.102,
            'rating' => 4.6,
            'phone' => '0733 456 789',
            'description' => 'Reparații rapide și de calitate. Diagnoză computerizată gratuită.',
        ]);

        $midas = Service::create([
            'user_id' => $serviceUser3->id,
            'name' => 'Midas Auto Ploiești',
            'address' => 'Str. Gheorghe Doja 78',
            'city' => 'Ploiești',
            'lat' => 44.944,
            'lng' => 25.789,
            'rating' => 4.5,
            'phone' => '0744 789 012',
            'description' => 'Service multimarcă cu piese originale și garanție extinsă.',
        ]);

        // === MAȘINI ===
        $bmw = Car::create([
            'user_id' => $client->id,
            'brand' => 'BMW',
            'model' => '420d F31',
            'year' => 2016,
            'plate' => 'BV-12-ABC',
            'km_current' => 180000,
        ]);

        $golf = Car::create([
            'user_id' => $client->id,
            'brand' => 'Volkswagen',
            'model' => 'Golf 5 1.9TDI',
            'year' => 2007,
            'plate' => 'B-123-ABC',
            'km_current' => 245000,
        ]);

        $dacia = Car::create([
            'user_id' => $client2->id,
            'brand' => 'Dacia',
            'model' => 'Logan 1.5 dCi',
            'year' => 2019,
            'plate' => 'IF-99-XYZ',
            'km_current' => 95000,
        ]);

        // === INTERVENȚII BMW ===
        $i1 = Intervention::create([
            'car_id' => $bmw->id,
            'service_id' => $autoPro->id,
            'status' => 'completed',
            'description' => 'Schimb ulei motor + filtru ulei + filtru aer',
            'type' => 'ulei',
            'estimated_hours' => 1.5,
            'final_cost' => 450.00,
            'km_at_intervention' => 160000,
            'scheduled_at' => '2024-03-15',
            'completed_at' => '2024-03-15',
        ]);

        $i2 = Intervention::create([
            'car_id' => $bmw->id,
            'service_id' => $autoPro->id,
            'status' => 'completed',
            'description' => 'Revizie completă - filtre, plăcuțe frână față, lichid frână',
            'type' => 'revizie',
            'estimated_hours' => 4.0,
            'final_cost' => 1200.00,
            'km_at_intervention' => 165000,
            'scheduled_at' => '2024-06-20',
            'completed_at' => '2024-06-21',
        ]);

        $i3 = Intervention::create([
            'car_id' => $bmw->id,
            'service_id' => $autoPro->id,
            'status' => 'completed',
            'description' => 'Schimb plăcuțe frână spate + discuri',
            'type' => 'frane',
            'estimated_hours' => 2.5,
            'final_cost' => 850.00,
            'km_at_intervention' => 172000,
            'scheduled_at' => '2024-10-10',
            'completed_at' => '2024-10-10',
        ]);

        $i4 = Intervention::create([
            'car_id' => $bmw->id,
            'service_id' => $autoPro->id,
            'status' => 'in_progress',
            'description' => 'Schimb ulei + diagnosticare erori motor',
            'type' => 'ulei',
            'estimated_hours' => 2.0,
            'final_cost' => null,
            'km_at_intervention' => 180000,
            'scheduled_at' => '2025-01-15',
            'completed_at' => null,
        ]);

        // === INTERVENȚII GOLF ===
        $i5 = Intervention::create([
            'car_id' => $golf->id,
            'service_id' => $speedy->id,
            'status' => 'completed',
            'description' => 'Schimb distribuție + pompă apă',
            'type' => 'revizie',
            'estimated_hours' => 6.0,
            'final_cost' => 1800.00,
            'km_at_intervention' => 230000,
            'scheduled_at' => '2024-05-10',
            'completed_at' => '2024-05-11',
        ]);

        $i6 = Intervention::create([
            'car_id' => $golf->id,
            'service_id' => $speedy->id,
            'status' => 'completed',
            'description' => 'Schimb ulei motor + filtru habitaclu',
            'type' => 'ulei',
            'estimated_hours' => 1.0,
            'final_cost' => 380.00,
            'km_at_intervention' => 240000,
            'scheduled_at' => '2024-09-05',
            'completed_at' => '2024-09-05',
        ]);

        // === INTERVENȚII DACIA ===
        Intervention::create([
            'car_id' => $dacia->id,
            'service_id' => $midas->id,
            'status' => 'completed',
            'description' => 'Revizie anuală - schimb ulei, filtre, verificare generală',
            'type' => 'revizie',
            'estimated_hours' => 2.0,
            'final_cost' => 550.00,
            'km_at_intervention' => 85000,
            'scheduled_at' => '2024-07-20',
            'completed_at' => '2024-07-20',
        ]);

        // === FACTURI ===
        Invoice::create([
            'intervention_id' => $i1->id,
            'number' => 'FA-2024-001',
            'total' => 450.00,
            'issued_at' => '2024-03-15',
        ]);

        Invoice::create([
            'intervention_id' => $i2->id,
            'number' => 'FA-2024-002',
            'total' => 1200.00,
            'issued_at' => '2024-06-21',
        ]);

        Invoice::create([
            'intervention_id' => $i3->id,
            'number' => 'FA-2024-003',
            'total' => 850.00,
            'issued_at' => '2024-10-10',
        ]);

        Invoice::create([
            'intervention_id' => $i5->id,
            'number' => 'FA-2024-004',
            'total' => 1800.00,
            'issued_at' => '2024-05-11',
        ]);

        Invoice::create([
            'intervention_id' => $i6->id,
            'number' => 'FA-2024-005',
            'total' => 380.00,
            'issued_at' => '2024-09-05',
        ]);
    }
}
