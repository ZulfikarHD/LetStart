# Checkout Documentation

## Overview
The Checkout page handles the payment process for venue bookings, featuring a multi-step checkout flow, order summary, and payment method selection.

## Features
- 3-step checkout progress indicator
- Cart item management
- Multiple payment method options
- Order summary with pricing breakdown
- Real-time total calculation
- Mobile responsive design
- Success confirmation dialog

## Key Components
- Progress stepper (Cart → Payment → Complete)
- Order details list with item removal
- Payment method selector with radio buttons
- Sticky order summary sidebar
- Loading states and animations
- Confirmation dialogs

## Data Structure Recommendations

### orders
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| total_amount | DECIMAL(12,2) | Total order amount |
| admin_fee | DECIMAL(10,2) | Administrative fee |
| payment_method | VARCHAR(50) | Selected payment method |
| status | VARCHAR(20) | Order status (pending/paid/failed) |
| payment_status | VARCHAR(20) | Payment status |
| created_at | TIMESTAMP | Order creation time |
| updated_at | TIMESTAMP | Last update time |

### order_items
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| order_id | UUID | Foreign key to orders |
| venue_id | UUID | Foreign key to venues |
| field_id | UUID | Foreign key to fields |
| date | DATE | Booking date |
| time_slots | JSON | Array of booked time slots |
| price | DECIMAL(10,2) | Item price |
| created_at | TIMESTAMP | Creation timestamp |

### payment_methods
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(100) | Payment method name |
| code | VARCHAR(50) | Unique identifier |
| icon_url | VARCHAR(255) | Icon image URL |
| processing_time | VARCHAR(50) | Estimated processing time |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Creation timestamp |

### payment_transactions
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| order_id | UUID | Foreign key to orders |
| amount | DECIMAL(12,2) | Transaction amount |
| payment_method | VARCHAR(50) | Payment method used |
| status | VARCHAR(20) | Transaction status |
| reference_id | VARCHAR(100) | Payment gateway reference |
| created_at | TIMESTAMP | Transaction time |

## Current Implementation
- Uses Vuex store for cart management
- Dummy payment methods data
- Static admin fee
- Mock payment processing
- Local storage for cart persistence

## Security Considerations
1. Implement payment data encryption
2. Add CSRF protection
3. Validate payment amounts server-side
4. Rate limit checkout attempts
5. Secure storage of payment details
6. Implement session timeouts
7. Add order amount validation

## Recommendations
1. Add payment gateway integration
2. Implement real-time stock checking
3. Add email notifications
4. Include order tracking
5. Add payment retry mechanism
6. Implement order expiration
7. Add booking conflict detection

## Performance Optimization
1. Optimize cart calculations
2. Cache payment method data
3. Implement lazy loading
4. Add request debouncing
5. Optimize API calls
