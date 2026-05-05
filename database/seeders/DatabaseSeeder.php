<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'       => 'Admin User',
            'email'      => 'admin@library.com',
            'role'       => 'admin',
            'password'   => Hash::make('password'),
        ]);

        // Staff
        User::create([
            'name'       => 'Staff User',
            'email'      => 'staff@library.com',
            'role'       => 'staff',
            'password'   => Hash::make('password'),
        ]);
        
        
        // Sample User
        User::create([
            'name'       => 'John Doe',
            'email'      => 'user@library.com',
            'student_id' => 'STU-2024-001',
            'role'       => 'user',
            'password'   => Hash::make('password'),
        ]);

        // Sample Books
        $books = [
            ['title' => 'Introduction to Algorithms', 'author' => 'Thomas H. Cormen', 'isbn' => '978-0262033848', 'category' => 'Computer Science', 'total_copies' => 5, 'available_copies' => 5, 'publisher' => 'MIT Press', 'published_year' => 2009],
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'isbn' => '978-0132350884', 'category' => 'Software Engineering', 'total_copies' => 3, 'available_copies' => 3, 'publisher' => 'Prentice Hall', 'published_year' => 2008],
            ['title' => 'The Pragmatic Programmer', 'author' => 'David Thomas', 'isbn' => '978-0201616224', 'category' => 'Software Engineering', 'total_copies' => 4, 'available_copies' => 4, 'publisher' => 'Addison-Wesley', 'published_year' => 1999],
            ['title' => 'Design Patterns', 'author' => 'Gang of Four', 'isbn' => '978-0201633610', 'category' => 'Software Engineering', 'total_copies' => 2, 'available_copies' => 2, 'publisher' => 'Addison-Wesley', 'published_year' => 1994],
            ['title' => 'Laravel: Up & Running', 'author' => 'Matt Stauffer', 'isbn' => '978-1491936085', 'category' => 'Web Development', 'total_copies' => 6, 'available_copies' => 6, 'publisher' => "O'Reilly", 'published_year' => 2019],
            ['title' => 'PHP & MySQL Web Development', 'author' => 'Luke Welling', 'isbn' => '978-0672329104', 'category' => 'Web Development', 'total_copies' => 3, 'available_copies' => 3, 'publisher' => 'Sams', 'published_year' => 2008],
            ['title' => 'Database System Concepts', 'author' => 'Abraham Silberschatz', 'isbn' => '978-0073523323', 'category' => 'Database', 'total_copies' => 4, 'available_copies' => 4, 'publisher' => 'McGraw-Hill', 'published_year' => 2010],
            ['title' => 'Operating System Concepts', 'author' => 'Abraham Silberschatz', 'isbn' => '978-1118063330', 'category' => 'Operating Systems', 'total_copies' => 3, 'available_copies' => 3, 'publisher' => 'Wiley', 'published_year' => 2012],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}