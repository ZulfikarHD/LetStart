# Profile Page Documentation

## Overview
The Profile page displays and manages user profile information, including personal details, achievements, booking history, and preferred sports.

## Components
- Profile Header with Avatar
- Personal Information Form 
- Recent Bookings List
- Preferred Sports Section
- Achievements Section

## Data Structure Recommendations

### users
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(100) | User's full name |
| email | VARCHAR(255) | User's email address |
| phone | VARCHAR(20) | Phone number |
| address | TEXT | Full address |
| avatar_url | VARCHAR(255) | Profile photo URL |
| member_since | DATE | Registration date |
| level | VARCHAR(50) | Member level (Gold/Silver/etc) |
| rating | DECIMAL(3,2) | Average rating (1-5) |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### user_sports
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| sport_name | VARCHAR(50) | Sport name |
| is_preferred | BOOLEAN | Preferred status |
| created_at | TIMESTAMP | Record creation date |

### user_achievements 
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| title | VARCHAR(100) | Achievement title |
| description | TEXT | Achievement description |
| icon | VARCHAR(50) | Icon identifier |
| achieved_at | TIMESTAMP | Achievement date |
| created_at | TIMESTAMP | Record creation date |

### user_stats
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| total_bookings | INTEGER | Total bookings count |
| favorite_venues | INTEGER | Favorited venues count |
| monthly_bookings | INTEGER | Bookings in current month |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

## Current Data Location
The current implementation uses:
- Dummy data in `user` ref for profile info
- Local state for photo preview
- Local state for edit mode
- Hardcoded achievements
- Static preferred sports list

## Recommendations
1. Move all data to proper database tables
2. Implement proper file upload for avatar
3. Add real-time stats calculation
4. Implement proper achievement system
5. Add booking stats analytics
6. Implement member level progression
7. Add profile completion percentage

## Security Considerations
1. Validate file uploads
2. Sanitize user inputs
3. Implement proper authorization
4. Add rate limiting for updates
5. Validate stats calculations
6. Add audit logging
7. Implement proper error handling

## Performance Optimization
1. Optimize avatar loading/resizing
2. Cache user stats
3. Implement lazy loading
4. Add proper loading states
5. Optimize achievement checks
6. Cache static content
7. Add request debouncing

## UI/UX Improvements
1. Add profile completion guide
2. Improve achievement animations
3. Add stats visualizations
4. Improve photo upload UX
5. Add profile sharing feature
6. Implement achievement notifications
7. Add profile verification badge

