<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'isAuthenticated' => Auth::check()
    ]);
});

Route::get('/venue-listing', function () {
    return Inertia::render('VenueListing', [
        'isAuthenticated' => Auth::check()
    ]);
});

Route::get('/sport-category', function () {
    return Inertia::render('SportsCategory', [
        'isAuthenticated' => Auth::check()
    ]);
});

// Venue routes
Route::get('/venues', function () {
    return Inertia::render('VenueListing', [
        'auth' => [
            'user' => Auth::user()
        ]
    ]);
})->name('venues.index');

Route::get('/venues/{id}', function ($id) {
    // Mock data for venue detail - replace with actual database query later
    $venue = [
        'id' => $id,
        'name' => 'GOR Sahabat Sport Center',
        'rating' => 4.5,
        'address' => 'Jl. Raya Sport Center No. 123, Jakarta Selatan',
        'description' => 'Fasilitas olahraga modern dengan berbagai lapangan berkualitas tinggi. Dilengkapi dengan fasilitas pendukung lengkap untuk kenyamanan pengunjung.',
        'images' => [
            '/images/venues/venue-1.jpg',
            '/images/venues/venue-2.jpg',
            '/images/venues/venue-3.jpg'
        ],
        'amenities' => [
            'Parkir Luas',
            'Toilet Bersih',
            'Musholla',
            'Kantin',
            'Ruang Ganti'
        ],
        'sports' => ['Badminton', 'Basket', 'Futsal'],
        'fields' => [
            [
                'id' => 1,
                'name' => 'Lapangan Badminton 1',
                'type' => 'Badminton',
                'image' => '/images/venues/badminton-1.jpg',
                'price' => 100000,
                'equipment' => ['Net', 'Lighting'],
                'available' => true,
                'images' => [
                    '/images/venues/badminton-1-1.jpg',
                    '/images/venues/badminton-1-2.jpg',
                    '/images/venues/badminton-1-3.jpg'
                ],
                'description' => 'Lapangan badminton dengan lantai vinyl kualitas internasional'
            ],
            [
                'id' => 2,
                'name' => 'Lapangan Basket Indoor',
                'type' => 'Basket',
                'image' => '/images/venues/basket-1.jpg',
                'price' => 150000,
                'equipment' => ['Ring Standar', 'Lighting'],
                'available' => true,
                'images' => [
                    '/images/venues/basket-1-1.jpg',
                    '/images/venues/basket-1-2.jpg',
                    '/images/venues/basket-1-3.jpg'
                ],
                'description' => 'Lapangan basket indoor dengan lantai parquette'
            ],
            // Add more fields as needed
        ]
    ];

    return Inertia::render('VenueDetail', [
        'venue' => $venue,
        'auth' => [
            'user' => Auth::user()
        ]
    ]);
})->name('venues.show');

// Protected routes with dummy data
Route::middleware(['auth', 'verified'])->group(function () {
    // Cart page
    Route::get('/cart', function () {
        // Dummy cart data
        $cartItems = [
            [
                'id' => 1,
                'venue_name' => 'Lapangan Futsal Bintang',
                'venue_image' => '/images/venues/futsal-1.jpg',
                'date' => '2024-02-20',
                'start_time' => '18:00',
                'end_time' => '20:00',
                'price' => 250000,
            ],
            [
                'id' => 2,
                'venue_name' => 'GOR Badminton Sejahtera',
                'venue_image' => '/images/venues/badminton-1.jpg',
                'date' => '2024-02-21',
                'start_time' => '16:00',
                'end_time' => '18:00',
                'price' => 180000,
            ],
        ];

        return Inertia::render('User/MyCarts', [
            'cartItems' => $cartItems,
            'auth' => [
                'user' => Auth::user()
            ]
        ]);
    })->name('cart.index');

    // My Bookings page
    Route::get('/my-bookings', function () {
        // Dummy bookings data
        $bookings = [
            [
                'id' => 1,
                'venue_name' => 'Lapangan Futsal Bintang',
                'date' => '2024-02-15',
                'start_time' => '18:00',
                'end_time' => '20:00',
                'venue_location' => 'Jl. Ahmad Yani No. 123, Jakarta Selatan',
                'status' => 'active',
                'total_price' => 250000,
            ],
            [
                'id' => 2,
                'venue_name' => 'GOR Badminton Sejahtera',
                'date' => '2024-02-14',
                'start_time' => '16:00',
                'end_time' => '18:00',
                'venue_location' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'status' => 'completed',
                'total_price' => 180000,
            ],
            [
                'id' => 3,
                'venue_name' => 'Lapangan Basket Indoor Champion',
                'date' => '2024-02-13',
                'start_time' => '19:00',
                'end_time' => '21:00',
                'venue_location' => 'Jl. Gatot Subroto No. 78, Jakarta Selatan',
                'status' => 'cancelled',
                'total_price' => 300000,
            ],
        ];

        return Inertia::render('User/MyBookings', [
            'bookings' => $bookings,
            'auth' => [
                'user' => Auth::user()
            ]
        ]);
    })->name('user.bookings');

    // Checkout page
    Route::get('/checkout', function () {
        // Dummy checkout data
        $checkoutData = [
            'items' => [
                [
                    'id' => 1,
                    'venue_name' => 'Lapangan Futsal Bintang',
                    'date' => '2024-02-20',
                    'start_time' => '18:00',
                    'end_time' => '20:00',
                    'price' => 250000,
                ],
            ],
            'subtotal' => 250000,
            'service_fee' => 5000,
            'total' => 255000,
        ];

        return Inertia::render('User/Checkout', [
            'checkoutData' => $checkoutData,
            'auth' => [
                'user' => Auth::user()
            ]
        ]);
    })->name('checkout.index');

    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Profile & Settings routes
    Route::get('/profile', function () {
        return Inertia::render('User/Settings/Profile', [
            'auth' => [
                'user' => Auth::user()
            ]
        ]);
    })->name('profile.show');

    Route::get('/settings', function () {
        $tab = request()->query('tab', 'notifications');

        $components = [
            'notifications' => 'User/Settings/NotificationSettings',
            'security' => 'User/Settings/SecuritySettings',
            'payments' => 'User/Settings/PaymentSettings',
            'preferences' => 'User/Settings/PreferencesSettings',
        ];

        $component = $components[$tab] ?? $components['notifications'];

        // Add dummy data for preferences
        $data = [
            'auth' => [
                'user' => Auth::user()
            ],
            'currentTab' => $tab
        ];

        // Add preferences specific data when needed
        if ($tab === 'preferences') {
            $data['favoriteSports'] = [
                [
                    'id' => 1,
                    'name' => 'Futsal',
                    'active' => true
                ],
                [
                    'id' => 2,
                    'name' => 'Basket',
                    'active' => true
                ],
                [
                    'id' => 3,
                    'name' => 'Badminton',
                    'active' => false
                ],
                [
                    'id' => 4,
                    'name' => 'Voli',
                    'active' => false
                ]
            ];

            $data['favoriteVenues'] = [
                [
                    'id' => 1,
                    'name' => 'GOR Sahabat Futsal',
                    'location' => 'Jakarta Selatan',
                    'sport' => 'Futsal',
                    'rating' => 4.8,
                ],
                [
                    'id' => 2,
                    'name' => 'Basket Arena Champions',
                    'location' => 'Jakarta Pusat',
                    'sport' => 'Basket',
                    'rating' => 4.5,
                ],
                [
                    'id' => 3,
                    'name' => 'PB Sejahtera',
                    'location' => 'Jakarta Timur',
                    'sport' => 'Badminton',
                    'rating' => 4.9,
                ]
            ];
        }

        return Inertia::render($component, $data);
    })->name('settings.index');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dedicated transaction history page
    Route::get('/transactions', function () {
        return Inertia::render('User/TransactionHistory', [
            'transactions' => [/* paginated transactions */]
        ]);
    })->name('transactions.index');
});

Route::middleware(['auth'])->group(function () {
    // Profile & Settings routes
    Route::get('/profile', function () {
        return Inertia::render('User/Settings/Profile', [
            'auth' => [
                'user' => Auth::user()
            ]
        ]);
    })->name('profile.show');

    Route::get('/settings', function () {
        $tab = request()->query('tab', 'notifications');

        $components = [
            'notifications' => 'User/Settings/NotificationSettings',
            'security' => 'User/Settings/SecuritySettings',
            'payments' => 'User/Settings/PaymentSettings',
            'preferences' => 'User/Settings/PreferencesSettings/Index',
        ];

        $component = $components[$tab] ?? $components['notifications'];

        return Inertia::render($component, [
            'auth' => [
                'user' => Auth::user()
            ],
            'currentTab' => $tab
        ]);
    })->name('settings.index');

    Route::get('/my-bookings', function () {
        return Inertia::render('User/MyBookings');
    })->name('bookings.index');
});

require __DIR__.'/auth.php';
