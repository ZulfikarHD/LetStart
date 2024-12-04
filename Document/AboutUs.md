# About Us Page Documentation

## Overview
The About Us page provides information about MainNow's company profile, team members, features, testimonials, statistics, and contact form.

## Components
- Hero Section
- Mission Section  
- Team Section
- Stats Section
- Testimonials Section
- FAQ Section
- Contact Form Section

## Data Structure Recommendations

### Team Members
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| name | VARCHAR(100) | Team member name |
| role | VARCHAR(100) | Job position/role |
| image_url | VARCHAR(255) | Profile photo URL |
| description | TEXT | Brief bio description |
| linkedin_url | VARCHAR(255) | LinkedIn profile URL |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### Features
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| title | VARCHAR(100) | Feature name |
| description | TEXT | Feature description |
| icon | VARCHAR(50) | Icon identifier |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### Statistics
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| label | VARCHAR(100) | Statistic label |
| value | INTEGER | Current value |
| target | INTEGER | Target value |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### Testimonials
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| name | VARCHAR(100) | Customer name |
| role | VARCHAR(100) | Customer role/occupation |
| image_url | VARCHAR(255) | Profile photo URL |
| content | TEXT | Testimonial content |
| rating | TINYINT | Rating (1-5) |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### FAQs
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| question | VARCHAR(255) | FAQ question |
| answer | TEXT | FAQ answer |
| order | INTEGER | Display order |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### Contact Messages
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| name | VARCHAR(100) | Sender name |
| email | VARCHAR(255) | Sender email |
| message | TEXT | Message content |
| status | ENUM | Status (new/read/replied) |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

### Company Info
| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Unique identifier |
| address | TEXT | Office address |
| email | VARCHAR(255) | Contact email |
| phone | VARCHAR(50) | Contact phone |
| social_media | JSON | Social media links |
| created_at | TIMESTAMP | Record creation date |
| updated_at | TIMESTAMP | Record last update date |

## Current Data Location
The current implementation uses hardcoded data in the Vue component:

- Team members data is defined in `teamMembers` array
- Features data is defined in `features` array  
- Stats data is defined in `stats` ref
- Testimonials data is defined in `testimonials` array
- FAQs data is defined in `faqs` ref
- Contact form data is handled by `form` ref

## Recommendations
1. Move all hardcoded data to database tables as per structure above
2. Create API endpoints to fetch data
3. Implement caching for static content
4. Add admin panel for content management
5. Implement image upload functionality for team/testimonial photos
6. Add validation for contact form submissions
7. Implement email notifications for new contact messages
