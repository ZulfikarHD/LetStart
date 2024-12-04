# My Carts Documentation

## Overview
The My Carts page displays the user's selected venue bookings, allowing them to manage their cart items, edit time slots, apply promo codes, and proceed to checkout.

## Key Features
- Cart item management (view, edit, remove)
- Time slot selection and editing
- Promo code application
- Order summary with pricing breakdown
- Mobile responsive design
- Smooth animations and transitions
- Dark mode support

## Components
- Cart item list with venue details
- Time slot editor modal
- Confirmation dialog for item removal
- Order summary sidebar
- Promo code input
- Checkout button

## Data Structure Recommendations

### cart_items
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| venue_id | UUID | Foreign key to venues |
| field_id | UUID | Foreign key to fields |
| date | DATE | Booking date |
| start_time | TIME | Start time |
| end_time | TIME | End time |
| price | DECIMAL(10,2) | Total price |
| is_available | BOOLEAN | Availability status |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### time_slots
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| venue_id | UUID | Foreign key to venues |
| field_id | UUID | Foreign key to fields |
| start_time | TIME | Slot start time |
| end_time | TIME | Slot end time |
| price | DECIMAL(10,2) | Slot price |
| is_available | BOOLEAN | Availability status |
| day_of_week | TINYINT | Day of week (0-6) |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### promo_codes
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| code | VARCHAR(50) | Promo code |
| discount_percentage | DECIMAL(5,2) | Discount percentage |
| start_date | DATE | Validity start date |
| end_date | DATE | Validity end date |
| is_active | BOOLEAN | Active status |
| min_purchase | DECIMAL(10,2) | Minimum purchase amount |
| max_discount | DECIMAL(10,2) | Maximum discount amount |
| usage_limit | INTEGER | Total usage limit |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### service_fees
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(100) | Fee name |
| amount | DECIMAL(10,2) | Fee amount |
| percentage | DECIMAL(5,2) | Fee percentage |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

## Current Data Location
The current implementation uses:
- Props for initial cart items data
- Ref for managing cart state
- Computed properties for calculations
- Static data for time slots and service fee
- Dummy data for promo codes

## Recommendations
1. Move cart data to Vuex/Pinia store
2. Implement real-time availability checking
3. Add cart item expiration
4. Implement proper promo validation
5. Add cart total validation
6. Implement proper time slot management
7. Add booking conflict detection

## Security Considerations
1. Validate all prices server-side
2. Implement CSRF protection
3. Add rate limiting for cart operations
4. Validate time slot availability
5. Secure promo code validation
6. Add cart session management
7. Implement proper error handling

## Performance Optimization
1. Implement cart data caching
2. Optimize time slot calculations
3. Add request debouncing
4. Implement lazy loading
5. Optimize API calls
6. Add proper loading states
7. Implement proper error states
