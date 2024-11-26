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

// Venue Detail
Route::get('/venues/{id}', function ($id) {
    return Inertia::render('VenueDetail', [
        'id' => $id,
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
        // Get the tab from query parameter, default to 'notifications'
        $tab = request()->query('tab', 'notifications');

        // Map tab names to their components
        $components = [
            'notifications' => 'User/Settings/NotificationSettings',
            'security' => 'User/Settings/SecuritySettings',
            'payments' => 'User/Settings/PaymentSettings',
        ];

        // Get the component name based on tab, fallback to notifications
        $component = $components[$tab] ?? $components['notifications'];

        return Inertia::render($component, [
            'auth' => [
                'user' => Auth::user()
            ],
            'currentTab' => $tab
        ]);
    })->name('settings.index');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
        // Get the tab from query parameter, default to 'notifications'
        $tab = request()->query('tab', 'notifications');

        // Map tab names to their components
        $components = [
            'notifications' => 'User/Settings/NotificationSettings',
            'security' => 'User/Settings/SecuritySettings',
            'payments' => 'User/Settings/PaymentSettings',
        ];

        // Get the component name based on tab, fallback to notifications
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
