<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Library Admin',
            'username' => 'admin',
            'email' => 'admin@library.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Library Office, Main Building',
        ]);

        // Create sample regular users
        \App\Models\User::create([
            'name' => 'Takeshi Yamamoto',
            'username' => 'takeshi',
            'email' => 'takeshi@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567891',
            'address' => 'Tokyo, Japan',
        ]);

        \App\Models\User::create([
            'name' => 'Yuki Tanaka',
            'username' => 'yuki',
            'email' => 'yuki@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567892',
            'address' => 'Osaka, Japan',
        ]);

        // Create sample light novels
        \App\Models\Buku::create([
            'title' => 'Sword Art Online: Aincrad',
            'author' => 'Reki Kawahara',
            'isbn' => '9784048869502',
            'type' => 'light_novel',
            'description' => 'Players are trapped in a virtual MMORPG where death in the game means death in real life.',
            'publisher' => 'ASCII Media Works',
            'publication_date' => '2009-04-10',
            'total_copies' => 3,
            'available_copies' => 3,
            'price' => 75000,
        ]);

        \App\Models\Buku::create([
            'title' => 'Overlord: The Undead King',
            'author' => 'Kugane Maruyama',
            'isbn' => '9784047306417',
            'type' => 'light_novel',
            'description' => 'A player gets stuck in a game as his character, an overlord skeleton.',
            'publisher' => 'Enterbrain',
            'publication_date' => '2012-07-30',
            'total_copies' => 2,
            'available_copies' => 2,
            'price' => 80000,
        ]);

        \App\Models\Buku::create([
            'title' => 'Re:Zero - Starting Life in Another World',
            'author' => 'Tappei Nagatsuki',
            'isbn' => '9784040684505',
            'type' => 'light_novel',
            'description' => 'Subaru finds himself in a fantasy world with the power to return by death.',
            'publisher' => 'MF Bunko J',
            'publication_date' => '2014-01-23',
            'total_copies' => 4,
            'available_copies' => 4,
            'price' => 72000,
        ]);

        \App\Models\Buku::create([
            'title' => 'Konosuba: God\'s Blessing on This Wonderful World!',
            'author' => 'Natsume Akatsuki',
            'isbn' => '9784048689816',
            'type' => 'light_novel',
            'description' => 'A comedy about a boy who dies and is reincarnated in a fantasy world.',
            'publisher' => 'Kadokawa Sneaker Bunko',
            'publication_date' => '2013-10-01',
            'total_copies' => 2,
            'available_copies' => 2,
            'price' => 68000,
        ]);

        // Create sample manga
        \App\Models\Buku::create([
            'title' => 'Attack on Titan Volume 1',
            'author' => 'Hajime Isayama',
            'isbn' => '9784063844472',
            'type' => 'manga',
            'description' => 'Humanity fights for survival against giant humanoid Titans.',
            'publisher' => 'Kodansha',
            'publication_date' => '2010-03-17',
            'total_copies' => 5,
            'available_copies' => 5,
            'price' => 45000,
        ]);

        \App\Models\Buku::create([
            'title' => 'Death Note Volume 1',
            'author' => 'Tsugumi Ohba',
            'isbn' => '9784088736815',
            'type' => 'manga',
            'description' => 'A high school student finds a supernatural notebook that can kill people.',
            'publisher' => 'Shueisha',
            'publication_date' => '2004-04-02',
            'total_copies' => 3,
            'available_copies' => 3,
            'price' => 42000,
        ]);

        \App\Models\Buku::create([
            'title' => 'Demon Slayer Volume 1',
            'author' => 'Koyoharu Gotouge',
            'isbn' => '9784088808710',
            'type' => 'manga',
            'description' => 'A young boy becomes a demon slayer to save his sister.',
            'publisher' => 'Shueisha',
            'publication_date' => '2016-06-03',
            'total_copies' => 4,
            'available_copies' => 4,
            'price' => 48000,
        ]);

        \App\Models\Buku::create([
            'title' => 'Your Name Volume 1',
            'author' => 'Makoto Shinkai',
            'isbn' => '9784040686837',
            'type' => 'manga',
            'description' => 'Two teenagers share a profound, magical connection.',
            'publisher' => 'Kadokawa',
            'publication_date' => '2016-08-26',
            'total_copies' => 2,
            'available_copies' => 2,
            'price' => 52000,
        ]);

        \App\Models\Buku::create([
            'title' => 'Jujutsu Kaisen Volume 1',
            'author' => 'Gege Akutami',
            'isbn' => '9784088815725',
            'type' => 'manga',
            'description' => 'A high school student joins a secret organization of Jujutsu Sorcerers.',
            'publisher' => 'Shueisha',
            'publication_date' => '2018-07-04',
            'total_copies' => 3,
            'available_copies' => 3,
            'price' => 46000,
        ]);

        \App\Models\Buku::create([
            'title' => 'One Piece Volume 1',
            'author' => 'Eiichiro Oda',
            'isbn' => '9784088725093',
            'type' => 'manga',
            'description' => 'A young pirate\'s journey to find the ultimate treasure.',
            'publisher' => 'Shueisha',
            'publication_date' => '1997-12-24',
            'total_copies' => 6,
            'available_copies' => 6,
            'price' => 43000,
        ]);
    }
}
