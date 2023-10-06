<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'nama' => 'Ditowner',
            'username' => 'dito',
            'password' => bcrypt('12345678'),
            'role' => 'owner',
        ]);
        User::create([
            'nama' => 'Faryawan',
            'username' => 'farhan',
            'password' => bcrypt('12345678'),
            'role' => 'karyawan',
        ]);
        // 79 Produk
        $daftar_produk = array(
            array('nama' => 'Combo Cheese Bread', 'harga' => 10000, 'stok' => rand(0, 100)),
            array('nama' => 'Sobek Terang Bulan', 'harga' => 25000, 'stok' => rand(0, 100)),
            array('nama' => 'Sobek Cobay (Coklat+Abon)','harga' => 22500,'stok' => rand(0, 100)),
            array('nama' => 'Long Choco Bread', 'harga' => 8500, 'stok' => rand(0, 100)),
            array('nama' => 'Chiken Pandan Bread', 'harga' => 6000, 'stok' => rand(0, 100)),
            array('nama' => 'Banana Marble', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Sausage Sosis Bread', 'harga' => 9000, 'stok' => rand(0, 100)),
            array('nama' => 'Butter Sugar Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Sobek Pandan Flower', 'harga' => 30000, 'stok' => rand(0, 100)),
            array('nama' => 'Green Tea Oreo', 'harga' => 7500, 'stok' => rand(0, 100)),
            array('nama' => 'Pie Brownies', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Muffin Mini Coklat', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Proll Tape', 'harga' => 30000, 'stok' => rand(0, 100)),
            array('nama' => 'Pizza Potong Kotak 4', 'harga' => 12000, 'stok' => rand(0, 100)),
            array('nama' => 'Spider Buns Bread', 'harga' => 8500, 'stok' => rand(0, 100)),
            array('nama' => 'Tapal Kuda Buns Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Sos Bon Bread', 'harga' => 5500, 'stok' => rand(0, 100)),
            array('nama' => 'Sos Spicy Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Klasik Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Sakura Spicy Bread', 'harga' => 13000, 'stok' => rand(0, 100)),
            array('nama' => 'Bluder', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Pizza Segitiga', 'harga' => 7500, 'stok' => rand(0, 100)),
            array('nama' => 'Duet Flower Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Buns Pandan Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Kepang Bread', 'harga' => 25000, 'stok' => rand(0, 100)),
            array('nama' => 'Babka Martabak Bread', 'harga' => 19000, 'stok' => rand(0, 100)),
            array('nama' => 'Krumpul Mini Ori', 'harga' => 10000, 'stok' => rand(0, 100)),
            array('nama' => 'Stripe Bread', 'harga' => 8000, 'stok' => rand(0, 100)),
            array('nama' => 'Tiger Roll Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Sobek Triple Bread', 'harga' => 13000, 'stok' => rand(0, 100)),
            array('nama' => 'Roll Sos Bon Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Jasuke Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Mozarella Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Flower Cupcake', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Choco Bread', 'harga' => 5500, 'stok' => rand(0, 100)),
            array('nama' => 'Jamaica Bread', 'harga' => 6000, 'stok' => rand(0, 100)),
            array('nama' => 'Melted Beef Bread', 'harga' => 7500, 'stok' => rand(0, 100)),
            array('nama' => 'Banut Bread (Banana Nutella)', 'harga' => 6000, 'stok' => rand(0, 100)),
            array('nama' => 'Red Velvet Bread', 'harga' => 5500, 'stok' => rand(0, 100)),
            array('nama' => 'Mocca Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Sobek 4 Rasa', 'harga' => 33000, 'stok' => rand(0, 100)),
            array('nama' => 'Sobek Premium', 'harga' => 35000, 'stok' => rand(0, 100)),
            array('nama' => 'Wassant Paijo Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Pisang Blester Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Sweet Roll Kismis Almond', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Unique Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Kacang Berry Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Kaya Crumble Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Floss Roll Utuh', 'harga' => 38000, 'stok' => rand(0, 100)),
            array('nama' => 'Floss Roll Potong', 'harga' => 45000, 'stok' => rand(0, 100)),
            array('nama' => 'Sejoli Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Nacip Bread', 'harga' => 5500, 'stok' => rand(0, 100)),
            array('nama' => 'Klepon Klapa Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Paijo Love Bread', 'harga' => 5500, 'stok' => rand(0, 100)),
            array('nama' => 'Cheese Blueberry Bread', 'harga' => 7500, 'stok' => rand(0, 100)),
            array('nama' => 'Nucomaltime Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Twist Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Roti Tawar Gandum', 'harga' => 24000, 'stok' => rand(0, 100)),
            array('nama' => 'Roti Tawar Premi', 'harga' => 27000, 'stok' => rand(0, 100)),
            array('nama' => 'Semir Choco Bread', 'harga' => 6000, 'stok' => rand(0, 100)),
            array('nama' => 'Donat Kentang', 'harga' => 4500, 'stok' => rand(0, 100)),
            array('nama' => 'Beef Triple Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Roll Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Abon Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Sarikaya Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Roll Milk Bread', 'harga' => 6500, 'stok' => rand(0, 100)),
            array('nama' => 'Choco Almond Bread', 'harga' => 4500, 'stok' => rand(0, 100)),
            array('nama' => 'Chocolate Banana Bread', 'harga' => 4000, 'stok' => rand(0, 100)),
            array('nama' => 'Blueberry Bread', 'harga' => 4000, 'stok' => rand(0, 100)),
            array('nama' => 'Pizza Mini Bread', 'harga' => 4500, 'stok' => rand(0, 100)),
            array('nama' => 'Pandan Vanila Bread', 'harga' => 4500, 'stok' => rand(0, 100)),
            array('nama' => 'Menul Bread', 'harga' => 10000, 'stok' => rand(0, 100)),
            array('nama' => 'Sos Bread', 'harga' => 4500, 'stok' => rand(0, 100)),
            array('nama' => 'Garlic Cheese Bread', 'harga' => 5500, 'stok' => rand(0, 100)),
            array('nama' => 'Cappucino Bread', 'harga' => 7000, 'stok' => rand(0, 100)),
            array('nama' => 'Black Nutella Bread', 'harga' => 5000, 'stok' => rand(0, 100)),
            array('nama' => 'Jumbo Bread', 'harga' => 12000, 'stok' => rand(0, 100)),
            array('nama' => 'Chocolava Bread', 'harga' => 4500, 'stok' => rand(0, 100)),
            array('nama' => 'Fla Vanilla Bread ', 'harga' => 5000, 'stok' => rand(0, 100)),
        );
        foreach ($daftar_produk as $data) {
            $nama = strtolower(str_replace([' ', '(', ')', '+'], ['-', '', '', '-'], $data['nama']));
            Produk::create([
                "nama_produk" => $data['nama'],
                "stok_produk" => $data['stok'],
                "harga_produk" => $data['harga'],
                "gambar_produk" => $nama.'.jpg',
            ]);
        }
    }
}
