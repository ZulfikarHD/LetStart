# Sports Category Documentation

## Overview
The Sports Category page displays a list of sports categories available for venue booking. It provides a grid/map view with filtering, searching, and sorting capabilities.

## Features
- Grid/Map view toggle
- Search functionality with debounced input
- Filtering by venue count, rating, and facilities  
- Sorting by popularity, rating, and venue count
- Infinite scroll pagination
- Favorite/bookmark categories
- Detailed category information with expandable sections
- Mobile responsive design
- Dark mode support

## Component Structure
- Hero section with search and quick filters
- Category grid/list view
- Filter dialog using Headless UI
- Loading skeletons
- Map view (placeholder)

## Data Flow
- Initial categories loaded on mount with pagination
- Search filters applied reactively
- Sort and filter state managed locally
- Navigation handled through Inertia router

## Recommended Database Schema

### sports_categories
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| name | varchar(100) | Category name (e.g. Futsal, Badminton) |
| image_url | varchar(255) | Category image path |
| description | text | Category description |
| venue_count | int | Number of venues in category |
| rating | decimal(2,1) | Average rating (1.0-5.0) |
| review_count | int | Number of reviews |
| is_active | boolean | Category status |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### category_facilities 
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| category_id | bigint | Foreign key to sports_categories |
| name | varchar(100) | Facility name |
| icon | varchar(50) | Icon identifier |

### category_locations
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| category_id | bigint | Foreign key to sports_categories |
| location | varchar(100) | Location name |
| is_popular | boolean | Popular location flag |
| coordinates | point | Geographical coordinates |

### category_operating_hours
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| category_id | bigint | Foreign key to sports_categories |
| day_of_week | tinyint | Day (1-7) |
| open_time | time | Opening time |
| close_time | time | Closing time |

### user_favorite_categories
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| category_id | bigint | Foreign key to sports_categories |
| created_at | timestamp | When favorited |

## Current Data Source
Currently using dummy data generated in the component:
- Basic category info hardcoded (Futsal, Badminton, Basket, Tenis)
- Random venue counts and ratings
- Simulated loading delays
- Placeholder images from /images/sport-type/
- Mock facilities and operating hours

## Recommendations
1. Implement proper backend API endpoints for:
   - Paginated category listing
   - Search and filtering
   - Favorite toggling
   - Category details

2. Add caching for:
   - Popular categories
   - Category images
   - Location data

3. Consider adding:
   - Category analytics (popular times, booking trends)
   - User preferences
   - Location-based sorting
   - Real-time venue availability

4. Security considerations:
   - Rate limiting for search/filter requests
   - Image validation and secure storage
   - User authorization for favorites
   - Input sanitization
