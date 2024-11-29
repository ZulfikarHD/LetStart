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

// About Us route
Route::get('/about-us', function () {
    return Inertia::render('AboutUs', [
        'auth' => [
            'user' => Auth::user()
        ]
    ]);
})->name('about-us');

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
            '/images/venue/soccer.jpg',
            '/images/venue/basket.jpg',
            '/images/venue/badminton.jpg',
            '/images/venue/futsal.jpg'
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
                'image' => '/images/venue/badminton.jpg',
                'price' => 100000,
                'equipment' => ['Net', 'Lighting'],
                'available' => true,
                'images' => [
                    '/images/venue/badminton.jpg',
                    '/images/venue/badminton.jpg',
                    '/images/venue/badminton.jpg'
                ],
                'description' => 'Lapangan badminton dengan lantai vinyl kualitas internasional'
            ],
            [
                'id' => 2,
                'name' => 'Lapangan Basket Indoor',
                'type' => 'Basket',
                'image' => '/images/venue/basket.jpg',
                'price' => 150000,
                'equipment' => ['Ring Standar', 'Lighting'],
                'available' => true,
                'images' => [
                    '/images/venue/basket.jpg',
                    '/images/venue/basket.jpg',
                    '/images/venue/basket.jpg'
                ],
                'description' => 'Lapangan basket indoor dengan lantai parquette'
            ],
            [
                'id' => 3,
                'name' => 'Lapangan Futsal Indoor',
                'type' => 'Futsal',
                'image' => '/images/venue/futsal.jpg',
                'price' => 250000,
                'equipment' => ['Bola Futsal', 'Gawang Standar', 'Jaring Pengaman'],
                'available' => true,
                'images' => [
                    '/images/venue/futsal.jpg',
                    '/images/venue/futsal.jpg',
                    '/images/venue/futsal.jpg'
                ],
                'description' => 'Lapangan futsal indoor dengan rumput sintetis premium'
            ],
            [
                'id' => 4,
                'name' => 'Lapangan Sepak Bola A',
                'type' => 'Sepak Bola',
                'image' => '/images/venue/soccer.jpg',
                'price' => 300000,
                'equipment' => ['Bola', 'Rompi', 'Gawang Standar'],
                'available' => true,
                'images' => [
                    '/images/venue/soccer.jpg',
                    '/images/venue/soccer.jpg',
                    '/images/venue/soccer.jpg'
                ],
                'description' => 'Lapangan rumput sintetis standar FIFA dengan ukuran penuh'
            ],
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
        return Inertia::render('Checkout', [
            'auth' => [
                'user' => Auth::user()
            ],
            'paymentMethods' => [
                [
                    'id' => 'gopay',
                    'name' => 'GoPay',
                    'icon' => '/images/payment/gopay.png'
                ],
                [
                    'id' => 'ovo',
                    'name' => 'OVO',
                    'icon' => '/images/payment/ovo.png'
                ],
                [
                    'id' => 'dana',
                    'name' => 'DANA',
                    'icon' => '/images/payment/dana.png'
                ],
                [
                    'id' => 'bca',
                    'name' => 'Transfer Bank BCA',
                    'icon' => '/images/payment/bca.png'
                ]
            ],
            'adminFee' => 5000
        ]);
    })->name('checkout');

    // Process payment
    Route::post('/checkout/process', function () {
        $validated = request()->validate([
            'payment_method' => 'required|string',
            'items' => 'required|array',
            'total_amount' => 'required|numeric'
        ]);

        // Here you would typically:
        // 1. Create order record
        // 2. Process payment with payment gateway
        // 3. Clear cart after successful payment
        // 4. Create booking records

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diproses',
            'booking_id' => 'BOOK-' . time() // Generate proper booking ID in production
        ]);
    })->name('checkout.process');

    // Payment callback (for async payment notifications)
    Route::post('/payment/callback', function () {
        // Handle payment gateway callbacks
        // Verify payment status
        // Update order status
        // Send notification to user
    })->name('payment.callback');

    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Home');
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
    Route::get('/transaction-history', function () {
        return Inertia::render('User/TransactionHistory/Index', [
            'transactions' => [/* paginated transactions */]
        ]);
    })->name('transactions.index');

    // Add these middlewares for better security
    Route::middleware(['auth', 'verified', 'throttle:payment'])->group(function () {
        Route::post('/checkout/process', [CheckoutController::class, 'process'])
            ->middleware(['validate.cart', 'prevent.double.booking'])
            ->name('checkout.process');
    });
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

Route::get('/faq', function () {
    return Inertia::render('Support/Faq', [
        'faqs' => [
            // Your existing FAQ data structure
        ],
        'categories' => [
            // Categories data
        ],
        'auth' => [
            'user' => Auth::user()
        ]
    ]);
})->name('faq');

// Add contact route for the contact form
Route::get('/contact', function () {
    return Inertia::render('Support/Contact', [
        'isAuthenticated' => Auth::check()
    ]);
})->name('contact');

// Add route for FAQ feedback
Route::post('/faq/feedback', function () {
    // Validate and store feedback
    request()->validate([
        'faqId' => 'required|integer',
        'helpful' => 'required|boolean'
    ]);

    // Store feedback logic here

    return response()->json(['message' => 'Feedback recorded']);
})->middleware(['throttle:feedback']);

// Help Center routes - accessible to both guests and authenticated users
Route::get('/help', function () {
    $faqs = [
        [
            'id' => 1,
            'question' => 'Bagaimana cara membatalkan booking?',
            'answer' => 'Anda dapat membatalkan booking melalui halaman "Booking Saya" minimal 24 jam sebelum jadwal.',
            'helpful' => 0,
            'notHelpful' => 0
        ],
        [
            'id' => 2,
            'question' => 'Apa saja metode pembayaran yang tersedia?',
            'answer' => 'Kami menerima pembayaran melalui GoPay, OVO, DANA, dan transfer bank BCA.',
            'helpful' => 0,
            'notHelpful' => 0
        ],
        // ... more FAQs
    ];

    return Inertia::render('Support/Help', [
        'isAuthenticated' => Auth::check(),
        'user' => Auth::user(),
        'faqs' => $faqs,
        'popularArticles' => [
            [
                'id' => 1,
                'title' => 'Cara Melakukan Booking Venue',
                'category' => 'Booking',
                'url' => '/help/booking'
            ],
            [
                'id' => 2,
                'title' => 'Metode Pembayaran yang Tersedia',
                'category' => 'Pembayaran',
                'url' => '/help/payment'
            ],
            // ... more articles
        ]
    ]);
})->name('help');

// Help article detail - accessible to both guests and authenticated users
Route::get('/help/{slug}', function ($slug) {
    return Inertia::render('Support/HelpArticle', [
        'isAuthenticated' => Auth::check(),
        'user' => Auth::user(),
        'article' => [
            'title' => 'Cara Melakukan Booking Venue',
            'content' => 'Lorem ipsum...',
            'category' => 'Booking',
            'lastUpdated' => '2024-02-20',
            // ... more article data
        ]
    ]);
})->name('help.article');

// Help article feedback endpoint - add throttle middleware but allow guest access
Route::post('/help/feedback', function () {
    request()->validate([
        'articleId' => 'required|integer',
        'isHelpful' => 'required|boolean'
    ]);

    return response()->json(['message' => 'Terima kasih atas feedback Anda']);
})->middleware(['throttle:feedback']);

Route::get('/terms-and-conditions', function () {
    return Inertia::render('Support/TermAndCondition', [
        'isAuthenticated' => Auth::check(),
        'lastUpdated' => '20 Februari 2024'
    ]);
})->name('terms');

Route::get('/privacy-policy', function () {
    return Inertia::render('Support/TermAndPrivacy', [
        'isAuthenticated' => Auth::check(),
        'lastUpdated' => '20 Februari 2024',
        'privacyContent' => [
            'sections' => [
                [
                    'title' => 'Informasi yang Kami Kumpulkan',
                    'content' => [
                        'Data Pribadi (nama, email, nomor telepon)',
                        'Informasi Lokasi',
                        'Data Penggunaan Aplikasi',
                        'Informasi Pembayaran'
                    ]
                ],
                [
                    'title' => 'Penggunaan Informasi',
                    'content' => [
                        'Memproses pemesanan venue',
                        'Mengirim notifikasi terkait booking',
                        'Meningkatkan layanan kami',
                        'Keamanan dan pencegahan penipuan'
                    ]
                ],
                // Add more sections as needed
            ]
        ]
    ]);
})->name('privacy-policy');

require __DIR__.'/auth.php';
