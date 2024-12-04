# Database Schema Recommendation for SportVenue App

## Core Tables

### users
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(100) | User's full name |
| email | VARCHAR(255) | Unique email address |
| phone | VARCHAR(20) | Phone number |
| password | VARCHAR(255) | Hashed password |
| avatar_url | VARCHAR(255) | Profile photo URL |
| level | ENUM | Regular/Premium/VIP |
| status | ENUM | Active/Inactive/Suspended |
| email_verified_at | TIMESTAMP | Email verification date |
| remember_token | VARCHAR(100) | Remember me token |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### venues
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| owner_id | UUID | Foreign key to users |
| name | VARCHAR(255) | Venue name |
| slug | VARCHAR(255) | URL-friendly name |
| description | TEXT | Venue description |
| address | TEXT | Physical address |
| city | VARCHAR(100) | City name |
| province | VARCHAR(100) | Province name |
| latitude | DECIMAL(10,8) | Location latitude |
| longitude | DECIMAL(11,8) | Location longitude |
| rating | DECIMAL(3,2) | Average rating (1-5) |
| price_range | VARCHAR(50) | Price range indicator |
| is_featured | BOOLEAN | Featured status |
| status | ENUM | Active/Inactive/Maintenance |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### fields
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| venue_id | UUID | Foreign key to venues |
| name | VARCHAR(100) | Field name |
| sport_type | VARCHAR(50) | Sport type |
| description | TEXT | Field description |
| capacity | INTEGER | Maximum capacity |
| price_per_hour | DECIMAL(12,2) | Hourly rate |
| is_indoor | BOOLEAN | Indoor/Outdoor |
| status | ENUM | Available/Booked/Maintenance |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### bookings
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| booking_code | VARCHAR(20) | Unique booking reference |
| user_id | UUID | Foreign key to users |
| field_id | UUID | Foreign key to fields |
| date | DATE | Booking date |
| start_time | TIME | Start time |
| end_time | TIME | End time |
| duration | INTEGER | Duration in hours |
| total_amount | DECIMAL(12,2) | Total booking amount |
| admin_fee | DECIMAL(10,2) | Administrative fee |
| discount_amount | DECIMAL(10,2) | Discount amount |
| final_amount | DECIMAL(12,2) | Final payable amount |
| status | ENUM | Pending/Confirmed/Completed/Cancelled |
| payment_status | ENUM | Unpaid/Paid/Refunded |
| notes | TEXT | Booking notes |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

## Supporting Tables

### venue_images
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| venue_id | UUID | Foreign key to venues |
| image_url | VARCHAR(255) | Image URL |
| is_primary | BOOLEAN | Primary image flag |
| sort_order | INTEGER | Display order |
| created_at | TIMESTAMP | Upload date |

### venue_facilities
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| venue_id | UUID | Foreign key to venues |
| name | VARCHAR(100) | Facility name |
| icon | VARCHAR(50) | Icon identifier |
| created_at | TIMESTAMP | Creation date |

### venue_operating_hours
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| venue_id | UUID | Foreign key to venues |
| day_of_week | TINYINT | Day (1-7) |
| open_time | TIME | Opening time |
| close_time | TIME | Closing time |
| is_closed | BOOLEAN | Closed flag |

### reviews
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| venue_id | UUID | Foreign key to venues |
| booking_id | UUID | Foreign key to bookings |
| rating | TINYINT | Rating (1-5) |
| review | TEXT | Review content |
| images | JSON | Review images |
| status | ENUM | Published/Hidden |
| created_at | TIMESTAMP | Review date |

### promotions
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| code | VARCHAR(50) | Promo code |
| title | VARCHAR(255) | Promotion title |
| description | TEXT | Promotion details |
| discount_type | ENUM | Percentage/Fixed |
| discount_value | DECIMAL(10,2) | Discount amount |
| min_purchase | DECIMAL(10,2) | Minimum purchase |
| max_discount | DECIMAL(10,2) | Maximum discount |
| start_date | DATE | Valid from date |
| end_date | DATE | Valid until date |
| usage_limit | INTEGER | Total usage limit |
| used_count | INTEGER | Times used |
| status | ENUM | Active/Inactive |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### favorite_venues
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| venue_id | UUID | Foreign key to venues |
| created_at | TIMESTAMP | When favorited |

### notifications
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| type | VARCHAR(50) | Notification type |
| title | VARCHAR(255) | Notification title |
| content | TEXT | Notification content |
| data | JSON | Additional data |
| read_at | TIMESTAMP | When read |
| created_at | TIMESTAMP | Creation date |

## User Management & Settings

### user_settings
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| language | VARCHAR(10) | Preferred language |
| notification_email | BOOLEAN | Email notifications |
| notification_push | BOOLEAN | Push notifications |
| notification_sms | BOOLEAN | SMS notifications |
| dark_mode | BOOLEAN | Theme preference |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### user_sports_preferences
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| sport_type | VARCHAR(50) | Sport type |
| skill_level | ENUM | Beginner/Intermediate/Advanced |
| is_preferred | BOOLEAN | Preferred status |
| created_at | TIMESTAMP | Creation date |

### user_devices
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| device_token | VARCHAR(255) | Device push token |
| device_type | VARCHAR(50) | iOS/Android/Web |
| last_active | TIMESTAMP | Last activity |
| created_at | TIMESTAMP | Creation date |

## Payment & Transaction

### payments
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| booking_id | UUID | Foreign key to bookings |
| user_id | UUID | Foreign key to users |
| payment_method | VARCHAR(50) | Payment method used |
| amount | DECIMAL(12,2) | Payment amount |
| fee | DECIMAL(10,2) | Payment gateway fee |
| reference_id | VARCHAR(100) | Payment gateway reference |
| status | ENUM | Pending/Success/Failed/Refunded |
| payment_proof | VARCHAR(255) | Payment proof URL |
| paid_at | TIMESTAMP | Payment completion time |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### payment_methods
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(100) | Method name |
| code | VARCHAR(50) | Method code |
| icon | VARCHAR(255) | Method icon URL |
| description | TEXT | Method description |
| instructions | TEXT | Payment instructions |
| fee_type | ENUM | Fixed/Percentage |
| fee_amount | DECIMAL(10,2) | Fee amount |
| min_amount | DECIMAL(12,2) | Minimum payment |
| max_amount | DECIMAL(12,2) | Maximum payment |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

## Sports & Categories

### sports
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(100) | Sport name |
| slug | VARCHAR(100) | URL-friendly name |
| icon | VARCHAR(255) | Sport icon URL |
| description | TEXT | Sport description |
| is_active | BOOLEAN | Active status |
| sort_order | INTEGER | Display order |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### field_equipment
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| name | VARCHAR(100) | Equipment name |
| quantity | INTEGER | Available quantity |
| condition | ENUM | New/Good/Fair/Poor |
| notes | TEXT | Additional notes |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

## Analytics & Reporting

### user_activities
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| activity_type | VARCHAR(50) | Activity type |
| description | TEXT | Activity description |
| ip_address | VARCHAR(45) | User IP address |
| user_agent | TEXT | Browser/App info |
| created_at | TIMESTAMP | Activity date |

### venue_analytics
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| venue_id | UUID | Foreign key to venues |
| date | DATE | Analytics date |
| views | INTEGER | Page views |
| bookings | INTEGER | Total bookings |
| revenue | DECIMAL(12,2) | Daily revenue |
| avg_rating | DECIMAL(3,2) | Average rating |
| created_at | TIMESTAMP | Creation date |

### search_logs
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users (nullable) |
| query | VARCHAR(255) | Search query |
| filters | JSON | Applied filters |
| results_count | INTEGER | Number of results |
| created_at | TIMESTAMP | Search timestamp |

## Communication & Support

### support_tickets
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| ticket_number | VARCHAR(20) | Unique ticket number |
| subject | VARCHAR(255) | Ticket subject |
| category | VARCHAR(50) | Issue category |
| priority | ENUM | Low/Medium/High/Urgent |
| status | ENUM | Open/InProgress/Resolved/Closed |
| description | TEXT | Issue description |
| resolution | TEXT | Resolution notes |
| assigned_to | UUID | Staff assigned |
| resolved_at | TIMESTAMP | Resolution date |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### ticket_messages
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| ticket_id | UUID | Foreign key to tickets |
| sender_id | UUID | Foreign key to users |
| message | TEXT | Message content |
| attachments | JSON | Attached files |
| is_staff_reply | BOOLEAN | Staff message flag |
| created_at | TIMESTAMP | Message date |

## Content Management

### content_pages
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| title | VARCHAR(255) | Page title |
| slug | VARCHAR(255) | URL-friendly title |
| content | TEXT | Page content |
| meta_title | VARCHAR(255) | SEO title |
| meta_description | TEXT | SEO description |
| status | ENUM | Draft/Published/Archived |
| published_at | TIMESTAMP | Publish date |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### faqs
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| category | VARCHAR(50) | FAQ category |
| question | TEXT | Question text |
| answer | TEXT | Answer text |
| is_featured | BOOLEAN | Featured status |
| sort_order | INTEGER | Display order |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

## Scheduling & Availability

### venue_schedules
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| venue_id | UUID | Foreign key to venues |
| field_id | UUID | Foreign key to fields |
| date | DATE | Schedule date |
| is_holiday | BOOLEAN | Holiday flag |
| custom_hours | BOOLEAN | Custom hours flag |
| open_time | TIME | Custom opening time |
| close_time | TIME | Custom closing time |
| notes | TEXT | Schedule notes |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### blocked_slots
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| start_datetime | TIMESTAMP | Block start |
| end_datetime | TIMESTAMP | Block end |
| reason | VARCHAR(255) | Blocking reason |
| created_by | UUID | Staff who blocked |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

## Gamification & Rewards

### user_points
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| points | INTEGER | Current points |
| lifetime_points | INTEGER | Total earned points |
| level | INTEGER | User level |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### point_transactions
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| points | INTEGER | Points amount |
| type | ENUM | Earned/Redeemed/Expired |
| description | VARCHAR(255) | Transaction description |
| reference_id | UUID | Related entity ID |
| expires_at | TIMESTAMP | Points expiry date |
| created_at | TIMESTAMP | Transaction date |

### achievements
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| name | VARCHAR(100) | Achievement name |
| description | TEXT | Achievement description |
| icon | VARCHAR(255) | Achievement icon |
| points | INTEGER | Points awarded |
| requirements | JSON | Achievement criteria |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### user_achievements
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| user_id | UUID | Foreign key to users |
| achievement_id | UUID | Foreign key to achievements |
| achieved_at | TIMESTAMP | Completion date |
| created_at | TIMESTAMP | Creation date |

## Field Management

### field_images
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| image_url | VARCHAR(255) | Image URL |
| is_primary | BOOLEAN | Primary image flag |
| sort_order | INTEGER | Display order |
| alt_text | VARCHAR(255) | Image alt text |
| created_at | TIMESTAMP | Upload date |
| updated_at | TIMESTAMP | Last update date |

### field_availability
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| date | DATE | Availability date |
| time_slot | TIME | Time slot |
| is_available | BOOLEAN | Availability status |
| is_peak_hour | BOOLEAN | Peak hour flag |
| peak_hour_price | DECIMAL(12,2) | Peak hour pricing |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### field_maintenance
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| start_date | DATE | Maintenance start |
| end_date | DATE | Maintenance end |
| reason | TEXT | Maintenance reason |
| status | ENUM | Scheduled/InProgress/Completed |
| notes | TEXT | Additional notes |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### field_rules
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| title | VARCHAR(100) | Rule title |
| description | TEXT | Rule description |
| icon | VARCHAR(50) | Rule icon |
| sort_order | INTEGER | Display order |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### field_pricing_rules
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| name | VARCHAR(100) | Rule name |
| type | ENUM | Weekend/Holiday/Special |
| price_modifier | DECIMAL(5,2) | Price multiplier |
| start_date | DATE | Valid from date |
| end_date | DATE | Valid until date |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### field_cancellation_policies
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| hours_before | INTEGER | Hours before booking |
| refund_percentage | INTEGER | Refund percentage |
| description | TEXT | Policy description |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

### field_reviews
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| user_id | UUID | Foreign key to users |
| booking_id | UUID | Foreign key to bookings |
| rating | TINYINT | Rating (1-5) |
| review | TEXT | Review content |
| cleanliness_rating | TINYINT | Cleanliness (1-5) |
| facility_rating | TINYINT | Facilities (1-5) |
| service_rating | TINYINT | Service (1-5) |
| images | JSON | Review images |
| is_verified | BOOLEAN | Verified booking review |
| status | ENUM | Published/Hidden/Reported |
| created_at | TIMESTAMP | Review date |
| updated_at | TIMESTAMP | Last update date |

### field_time_slots
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| field_id | UUID | Foreign key to fields |
| day_of_week | TINYINT | Day (1-7) |
| start_time | TIME | Slot start time |
| end_time | TIME | Slot end time |
| is_available | BOOLEAN | Default availability |
| base_price | DECIMAL(12,2) | Regular price |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |
