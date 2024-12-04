# VenueDetail Documentation

## Overview
The VenueDetail component displays detailed information about a sports venue, including images, facilities, fields/courts available for booking, and booking functionality.

## Features
- Image gallery with thumbnails and navigation
- Venue information display (name, rating, address, etc)
- Field/court listing and filtering
- Booking system with date and time slot selection
- Cart integration
- Mobile responsive design

## Props
- `auth` (Object): Authentication state containing user info
- `venue` (Object): Venue details and related data

## Key Functionality
- Image gallery navigation and transitions
- Field filtering by sport type
- Time slot selection and booking management
- Cart and checkout integration
- Responsive layout and dark mode support

## Data Structure Recommendations

### Venue Table
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(255) | Venue name |
| description | TEXT | Venue description |
| address | TEXT | Physical address |
| rating | DECIMAL(2,1) | Average rating (0-5) |
| sports | JSON | Array of available sports |
| images | JSON | Array of image URLs |
| operating_hours | JSON | Operating hours by day |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### Field Table
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| venue_id | UUID | Foreign key to venue |
| name | VARCHAR(255) | Field name |
| type | VARCHAR(100) | Sport type |
| description | TEXT | Field description |
| price | DECIMAL(10,2) | Price per hour |
| images | JSON | Array of field images |
| equipment | JSON | Available equipment |
| available | BOOLEAN | Availability status |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### Booking Table
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to user |
| field_id | UUID | Foreign key to field |
| date | DATE | Booking date |
| time_slots | JSON | Array of booked time slots |
| total_price | DECIMAL(10,2) | Total booking price |
| status | VARCHAR(50) | Booking status |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

## Security Considerations
1. Implement proper authentication for booking actions
2. Validate date and time slot selections
3. Verify price calculations on server-side
4. Sanitize user inputs
5. Implement rate limiting for booking requests

## Performance Optimization Tips
1. Lazy load images
2. Implement caching for venue and field data
3. Optimize database queries
4. Use pagination for large datasets
5. Minimize unnecessary re-renders

## UI/UX Recommendations
1. Add loading states for async operations
2. Implement error handling with user feedback
3. Add confirmation dialogs for important actions
4. Ensure proper form validation
5. Maintain consistent styling with design system

## Future Enhancements
1. Add review/rating system
2. Implement real-time availability updates
3. Add payment gateway integration
4. Include booking history
5. Add favorite venues feature
