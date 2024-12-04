# Preferences Settings Documentation

## Overview
The Preferences Settings page allows users to manage their favorite sports and venues. It includes features for toggling preferred sports and managing favorite venues with ratings and locations.

## Components
- Sports Preferences Section
- Favorite Venues Section
- Add Venue Modal
- Delete Confirmation Dialog

## Data Structure Recommendations

### user_sports_preferences
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| sport_id | UUID | Foreign key to sports |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### sports
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(100) | Sport name |
| icon | VARCHAR(50) | Icon identifier |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### favorite_venues
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| venue_id | UUID | Foreign key to venues |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### venues
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(255) | Venue name |
| location | VARCHAR(255) | Venue location |
| rating | DECIMAL(2,1) | Average rating (1-5) |
| sport_id | UUID | Foreign key to sports |
| image_url | VARCHAR(255) | Venue image URL |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

## Current Data Location
The current implementation uses:
- Dummy data in `sports` ref for sports preferences
- Dummy data in `venues` ref for favorite venues
- Local state for UI controls and modals
- No persistence layer implemented yet

## Recommendations
1. Move data to proper database tables
2. Implement proper venue image handling
3. Add venue rating system
4. Add venue search/filter
5. Implement sports categories
6. Add venue recommendations
7. Implement venue bookmarking

## Security Considerations
1. Validate user ownership
2. Sanitize venue data
3. Validate image uploads
4. Rate limit preference changes
5. Add audit logging
6. Implement proper authorization
7. Add change notifications

## Performance Optimization
1. Cache user preferences
2. Implement lazy loading
3. Optimize venue queries
4. Add proper loading states
5. Cache static content
6. Add request debouncing
7. Implement proper error handling

## UI/UX Improvements
1. Add venue search
2. Improve sports icons
3. Add venue categories
4. Implement drag-and-drop reordering
5. Add venue details preview
6. Improve mobile layout
7. Add preference sync
8. Implement quick actions

## Related Components
- AddVenueModal.vue
- AnimatedDialog.vue
- AuthenticatedLayout.vue

## Dependencies
- Vue 3 Composition API
- Lucide Vue Icons
- Headless UI
- Tailwind CSS
