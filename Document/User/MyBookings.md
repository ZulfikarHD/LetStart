# MyBookings Page Documentation

## Overview
The MyBookings page allows users to view and manage their sports venue bookings. It provides features like filtering, searching, sorting, and detailed booking information.

## Features
- View bookings in list or calendar view
- Filter bookings by status (Active/Completed/Cancelled)
- Search bookings by venue name or location
- Sort bookings by date or price
- View detailed booking information with QR code
- Share and download booking details
- Mobile responsive with swipe actions
- Recent searches history
- Empty state handling
- Loading states and animations

## Components Used
- AuthenticatedLayout
- CountdownTimer 
- AnimatedDialog
- Headless UI (Menu, Popover, Dialog)
- Lucide Icons

## Data Structure Recommendations

### Bookings Table
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| user_id | UUID | Reference to users table |
| venue_id | UUID | Reference to venues table |
| booking_code | VARCHAR(20) | Unique booking reference code |
| date | DATE | Booking date |
| start_time | TIME | Start time |
| end_time | TIME | End time |
| total_price | DECIMAL(10,2) | Total booking amount |
| status | ENUM | Active/Completed/Cancelled |
| payment_status | ENUM | Pending/Paid/Refunded |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### Venues Table
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| name | VARCHAR(100) | Venue name |
| location | TEXT | Full address |
| type | VARCHAR(50) | Sport type (Futsal/Badminton/etc) |
| price_per_hour | DECIMAL(10,2) | Hourly rate |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### Recent Searches Table
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| user_id | UUID | Reference to users table |
| search_query | VARCHAR(255) | Search text |
| created_at | TIMESTAMP | Record creation date |

## Current Data Implementation
The current implementation uses dummy data defined in the `dummyBookings` array. For production:

1. Replace dummy data with API calls to fetch real booking data
2. Implement proper database tables as per structure above
3. Add pagination for large datasets
4. Add caching for frequently accessed data
5. Implement real-time updates for booking status changes

## Security Considerations
1. Implement user authentication and authorization
2. Validate booking ownership before showing details
3. Sanitize search inputs
4. Rate limit API requests
5. Encrypt sensitive booking information
6. Implement secure QR code generation
7. Add CSRF protection for forms

## Performance Recommendations
1. Implement lazy loading for booking history
2. Cache static content and images
3. Optimize database queries with proper indexing
4. Use debouncing for search functionality
5. Implement virtual scrolling for large lists
6. Optimize images and assets
7. Use CDN for static resources

## UI/UX Improvements
1. Add booking confirmation emails
2. Implement push notifications for booking reminders
3. Add export functionality (PDF/CSV)
4. Improve empty states with helpful actions
5. Add venue photos in booking details
6. Implement rating/review system after completion
7. Add booking modification functionality
8. Improve accessibility features
