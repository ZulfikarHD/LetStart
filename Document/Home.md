# SportVenue Home Page Documentation

## Overview
The home page serves as the main landing page for the SportVenue application, providing venue search, featured venues, promotions, and app download information. The page has two views - one for authenticated users and one for guests.

## Components

### Hero Section
- Search bar for venue lookup
- Background pattern with overlay
- Main heading and subheading

### Stats Section 
Displays 3 key metrics:
- Total venues (500+)
- Service availability (24/7) 
- Payment security (100%)

### Featured Venues Section
Displays popular sports venues in a grid layout with:
- Venue image
- Sport type tag
- Venue name
- Rating
- Location
- Price per hour
- Booking button

### Promotions Section
Shows special offers including:
- Promo title
- Description
- Valid until date
- Discount percentage
- Call-to-action button

### How It Works Section
3-step guide explaining the booking process:
1. Search for venue
2. Select and book
3. Play and enjoy

### Mobile App Section
Promotes the mobile application with:
- App preview image
- Download buttons for App Store and Play Store

## Required Database Tables & Fields

### venues
| Field | Type | Description |
|-------|------|-------------|
| id | INT | Primary key |
| name | VARCHAR(255) | Venue name |
| location | VARCHAR(255) | Full address |
| sport_type | VARCHAR(100) | Type of sport |
| rating | DECIMAL(2,1) | Average rating (0-5) |
| price_per_hour | DECIMAL(10,2) | Hourly rate |
| image_url | VARCHAR(255) | Venue photo URL |
| description | TEXT | Full description |
| is_featured | BOOLEAN | Featured status |
| status | ENUM | Active/Inactive |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update |

### promotions
| Field | Type | Description |
|-------|------|-------------|
| id | INT | Primary key |
| title | VARCHAR(255) | Promo title |
| description | TEXT | Promo details |
| discount_percentage | INT | Discount amount |
| valid_until | DATE | Expiry date |
| promo_code | VARCHAR(50) | Unique code |
| status | ENUM | Active/Inactive |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update |

### venue_ratings
| Field | Type | Description |
|-------|------|-------------|
| id | INT | Primary key |
| venue_id | INT | Foreign key to venues |
| user_id | INT | Foreign key to users |
| rating | INT | Rating value (1-5) |
| review | TEXT | Review comment |
| created_at | TIMESTAMP | Rating date |

### venue_images 
| Field | Type | Description |
|-------|------|-------------|
| id | INT | Primary key |
| venue_id | INT | Foreign key to venues |
| image_url | VARCHAR(255) | Image URL |
| is_primary | BOOLEAN | Primary image flag |
| created_at | TIMESTAMP | Upload date |

### venue_operating_hours
| Field | Type | Description |
|-------|------|-------------|
| id | INT | Primary key |
| venue_id | INT | Foreign key to venues |
| day | ENUM | Day of week |
| open_time | TIME | Opening time |
| close_time | TIME | Closing time |

## Key Features & Functionality

1. **Search Functionality**
   - Real-time venue search
   - Filter by sport type, location
   - Auto-complete suggestions

2. **Venue Display**
   - Grid layout with hover effects
   - Image galleries
   - Rating system
   - Location mapping

3. **Responsive Design**
   - Mobile-first approach
   - Dark mode support
   - Smooth animations

4. **Promotion System**
   - Time-limited offers
   - Discount calculations
   - Promo code redemption

## Security Considerations

1. Input validation for search queries
2. Image upload restrictions
3. Rate limiting for API calls
4. User authentication for bookings
5. Secure payment processing
6. Data encryption for sensitive information

## Performance Optimization

1. Image optimization and lazy loading
2. Caching for static content
3. Pagination for venue listings
4. Efficient database indexing
5. API response optimization

## Future Enhancements

1. Advanced search filters
2. Social sharing integration
3. Virtual tour features
4. Real-time availability
5. Loyalty program integration
6. Review moderation system
