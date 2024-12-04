erDiagram
    users ||--o{ bookings : makes
    users ||--o{ reviews : writes
    users ||--o{ user_favorites : has
    venues ||--o{ bookings : receives
    venues ||--o{ venue_facilities : has
    venues ||--o{ venue_images : has
    venues ||--o{ reviews : receives
    venues ||--o{ venue_schedules : has
    bookings ||--o{ transactions : has

    users {
        uuid id PK
        string name
        string email
        string phone
        string address
        string avatar_url
        date member_since
        string level
        decimal rating
        string password_hash
        boolean two_factor_enabled
        string two_factor_secret
        timestamp password_updated_at
        string account_status
        string preferred_language
        timestamp last_booking_date
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    venues {
        uuid id PK
        string name
        string description
        string address
        point location
        decimal rating
        string sport_type
        int price_per_hour
        string rules
        json operating_hours
        int capacity
        boolean is_active
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    bookings {
        uuid id PK
        uuid user_id FK
        uuid venue_id FK
        datetime start_time
        datetime end_time
        string status
        decimal total_price
        int guest_count
        string notes
        timestamp created_at
        timestamp updated_at
        timestamp cancelled_at
    }

    transactions {
        uuid id PK
        uuid booking_id FK
        string payment_method
        decimal amount
        string status
        string payment_proof
        timestamp paid_at
        timestamp created_at
        timestamp updated_at
    }

    reviews {
        uuid id PK
        uuid user_id FK
        uuid venue_id FK
        int rating
        text comment
        timestamp created_at
        timestamp updated_at
    }

    venue_facilities {
        uuid id PK
        uuid venue_id FK
        string name
        string description
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    venue_images {
        uuid id PK
        uuid venue_id FK
        string url
        boolean is_primary
        int order
        timestamp created_at
        timestamp updated_at
    }

    venue_schedules {
        uuid id PK
        uuid venue_id FK
        string day_of_week
        time open_time
        time close_time
        boolean is_closed
        timestamp created_at
        timestamp updated_at
    }

    user_favorites {
        uuid id PK
        uuid user_id FK
        uuid venue_id FK
        timestamp created_at
        timestamp updated_at
    }
