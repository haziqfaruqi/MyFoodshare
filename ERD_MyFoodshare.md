# MyFoodshare Entity Relationship Diagram (ERD)

## 📊 Entity Overview

MyFoodshare consists of 8 core entities that manage the complete food donation ecosystem from restaurants to beneficiaries.

---

## 🏗️ Core Entities

### 1. **Users** - Central entity for all user types
```mermaid
erDiagram
    Users ||--o{ Food_Listings : creates
    Users ||--o{ Food_Matches : expresses_interest
    Users ||--o{ Pickup_Verifications : completes
    Users ||--o{ Notifications : receives
    Users ||--o{ Activity_Logs : generates
    Users ||--o{ User_Profiles : has

    Users {
        id PK "INT, AI"
        name VARCHAR(255) "NOT NULL"
        email VARCHAR(255) "UNIQUE, NOT NULL"
        password_hash VARCHAR(255) "NOT NULL"
        phone VARCHAR(20) "NULL"
        role ENUM('restaurant', 'ngo', 'admin') "NOT NULL"
        status ENUM('pending', 'active', 'suspended', 'rejected') "DEFAULT 'pending'"
        email_verified_at TIMESTAMP "NULL"
        last_login_at TIMESTAMP "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        updated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    }
```

### 2. **User_Profiles** - Extended user information
```mermaid
erDiagram
    Users ||--|| User_Profiles : 1-to-1

    User_Profiles {
        id PK "INT, AI"
        user_id FK "INT, UNIQUE, NOT NULL"
        organization_name VARCHAR(255) "NULL"
        organization_address TEXT "NULL"
        contact_person VARCHAR(255) "NULL"
        registration_number VARCHAR(100) "NULL"
        business_license_url VARCHAR(500) "NULL"
        tax_certificate_url VARCHAR(500) "NULL"
        profile_completed BOOLEAN "DEFAULT FALSE"
        verified_documents BOOLEAN "DEFAULT FALSE"
        verification_notes TEXT "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        updated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    }
```

### 3. **Food_Listings** - Available surplus food
```mermaid
erDiagram
    Users ||--o{ Food_Listings : creates
    Food_Listings ||--o{ Food_Matches : attracts
    Food_Listings ||--o{ Pickup_Verifications : completed_by

    Food_Listings {
        id PK "INT, AI"
        user_id FK "INT, NOT NULL"
        food_type ENUM('rice_dishes', 'curries', 'breads', 'desserts', 'vegetables', 'meats', 'beverages', 'other') "NOT NULL"
        food_name VARCHAR(255) "NOT NULL"
        quantity VARCHAR(100) "NOT NULL" (e.g., "20 servings", "5kg", "30 pieces")
        unit_price DECIMAL(10,2) "NULL" (for value tracking)
        description TEXT "NULL"
        expiry_time DATETIME "NOT NULL"
        pickup_deadline DATETIME "NOT NULL"
        location_lat DECIMAL(10,8) "NOT NULL"
        location_lng DECIMAL(11,8) "NOT NULL"
        address TEXT "NOT NULL"
        contact_person VARCHAR(255) "NULL"
        contact_phone VARCHAR(20) "NULL"
        photos JSON "NULL" (array of image URLs)
        dietary_info JSON "NULL" (halal, vegetarian, allergens)
        storage_requirements TEXT "NULL"
        status ENUM('pending_approval', 'approved', 'matched', 'expired', 'cancelled') "DEFAULT 'pending_approval'"
        approval_notes TEXT "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        updated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    }
```

### 4. **Food_Matches** - NGO interest and approval tracking
```mermaid
erDiagram
    Users ||--o{ Food_Matches : ngo_expresses
    Food_Listings ||--o{ Food_Matches : attracts
    Food_Matches ||--o{ Pickup_Verifications : results_in

    Food_Matches {
        id PK "INT, AI"
        food_listing_id FK "INT, NOT NULL"
        ngo_user_id FK "INT, NOT NULL"
        restaurant_user_id FK "INT, NOT NULL"
        status ENUM('interested', 'restaurant_approved', 'restaurant_rejected', 'ngo_confirmed', 'completed', 'cancelled') "DEFAULT 'interested'"
        pickup_time DATETIME "NULL"
        estimated_arrival_time DATETIME "NULL"
        pickup_notes TEXT "NULL"
        ngo_response_time_seconds INT "NULL"
        restaurant_response_time_seconds INT "NULL"
        distance_km DECIMAL(6,2) "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        updated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"

        UNIQUE(food_listing_id, ngo_user_id) "One NGO can't express interest twice"
    }
```

### 5. **Pickup_Verifications** - QR code based pickup completion
```mermaid
erDiagram
    Users ||--o{ Pickup_Verifications : ngo_completes
    Food_Matches ||--o{ Pickup_Verifications : confirms
    Food_Listings ||--o{ Pickup_Verifications : references

    Pickup_Verifications {
        id PK "INT, AI"
        food_match_id FK "INT, NOT NULL"
        food_listing_id FK "INT, NOT NULL"
        ngo_user_id FK "INT, NOT NULL"
        restaurant_user_id FK "INT, NOT NULL"
        qr_code VARCHAR(12) "NOT NULL, UNIQUE" (format: VRF-XXXXXXXX)
        scan_time DATETIME "NOT NULL"
        actual_quantity VARCHAR(100) "NULL"
        actual_quantity_change ENUM('same', 'increased', 'decreased') "DEFAULT 'same'"
        quality_rating TINYINT "1-5, NULL"
        photo_before VARCHAR(500) "NULL" (photo of food before pickup)
        photo_after VARCHAR(500) "NULL" (photo of collected food)
        notes TEXT "NULL"
        issues_reported TEXT "NULL" (JSON format for any problems)
        volunteer_name VARCHAR(255) "NULL"
        volunteer_contact VARCHAR(20) "NULL"
        vehicle_number VARCHAR(50) "NULL"
        temperature_checked BOOLEAN "DEFAULT FALSE"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"

        UNIQUE(food_match_id) "One verification per match"
    }
```

### 6. **Notifications** - Real-time communication system
```mermaid
erDiagram
    Users ||--o{ Notifications : receives
    Food_Matches ||--o{ Notifications : triggers
    Food_Listings ||--o{ Notifications : triggers
    Pickup_Verifications ||--o{ Notifications : triggers

    Notifications {
        id PK "INT, AI"
        user_id FK "INT, NOT NULL"
        type ENUM('new_listing', 'ngo_interest', 'match_approved', 'pickup_reminder', 'qr_generated', 'pickup_completed', 'review_reminder', 'system_update') "NOT NULL"
        title VARCHAR(255) "NOT NULL"
        message TEXT "NOT NULL"
        related_id INT "NULL" (ID of related entity)
        related_type ENUM('food_listing', 'food_match', 'pickup_verification') "NULL"
        is_read BOOLEAN "DEFAULT FALSE"
        sent_via ENUM('push', 'email', 'sms', 'whatsapp') "DEFAULT 'push'"
        email_sent_at TIMESTAMP "NULL"
        sms_sent_at TIMESTAMP "NULL"
        push_sent_at TIMESTAMP "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        read_at TIMESTAMP "NULL"
    }
```

### 7. **Activity_Logs** - Complete audit trail
```mermaid
erDiagram
    Users ||--o{ Activity_Logs : generates
    Food_Listings ||--o{ Activity_Logs : tracked
    Food_Matches ||--o{ Activity_Logs : tracked
    Pickup_Verifications ||--o{ Activity_Logs : tracked

    Activity_Logs {
        id PK "INT, AI"
        user_id FK "INT, NOT NULL"
        action VARCHAR(100) "NOT NULL" (create, update, delete, approve, reject, complete)
        entity_type ENUM('user', 'food_listing', 'food_match', 'pickup_verification', 'notification') "NOT NULL"
        entity_id INT "NOT NULL"
        details JSON "NULL" (additional context data)
        ip_address VARCHAR(45) "NULL"
        user_agent TEXT "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"

        INDEX(user_id, created_at) "For user activity history"
        INDEX(entity_type, entity_id) "For entity tracking"
    }
```

### 8. **Impact_Reports** - Grant-ready analytics
```mermaid
erDiagram
    Food_Listings ||--o{ Impact_Reports : generates_data
    Food_Matches ||--o{ Impact_Reports : generates_data
    Pickup_Verifications ||--o{ Impact_Reports : generates_data

    Impact_Reports {
        id PK "INT, AI"
        report_type ENUM('daily', 'weekly', 'monthly', 'quarterly', 'yearly', 'custom') "NOT NULL"
        period_start DATE "NOT NULL"
        period_end DATE "NOT NULL"
        total_listings INT "DEFAULT 0"
        total_pickups INT "DEFAULT 0"
        total_meals_rescued INT "DEFAULT 0"
        total_kg_food_rescued DECIMAL(10,2) "DEFAULT 0.00"
        total_people_helped INT "DEFAULT 0"
        total_families_helped INT "DEFAULT 0"
        environmental_co2_saved DECIMAL(10,2) "DEFAULT 0.00"
        total_money_saved DECIMAL(10,2) "DEFAULT 0.00"
        restaurant_participants INT "DEFAULT 0"
        ngo_participants INT "DEFAULT 0"
        volunteer_participants INT "DEFAULT 0"
        top_restaurants JSON "NULL" (array of top contributors)
        top_ngos JSON "NULL" (array of top recipients)
        geographic_distribution JSON "NULL" (area breakdown)
        generated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        generated_by INT "NULL" (admin user who generated)
        report_file_url VARCHAR(500) "NULL" (PDF/Excel download)

        UNIQUE(report_type, period_start, period_end) "No duplicate reports for same period"
    }
```

---

## 🔗 Entity Relationships Summary

### **Primary Relationships**
```
Users → Food_Listings : 1:N (Restaurant creates multiple listings)
Users → Food_Matches : 1:N (NGO expresses interest in multiple listings)
Food_Listings → Food_Matches : 1:N (Listing attracts multiple NGO interests)
Food_Matches → Pickup_Verifications : 1:1 (Match leads to one pickup verification)
Users → Notifications : 1:N (User receives multiple notifications)
Users → Activity_Logs : 1:N (User generates multiple activity logs)
```

### **Foreign Key Constraints**
- **CASCADE DELETE**: When user is deleted, their related entities are deleted
- **RESTRICT DELETE**: Food listings can't be deleted if pickups completed
- **SET NULL**: Notifications retain history even if user deleted

---

## 📈 Key Business Logic

### **Status Flow Patterns**
```
User Status: pending → active → (suspended → active) / rejected
Food Listing Status: pending_approval → approved → matched → (expired/cancelled/confirmed)
Food Match Status: interested → restaurant_approved → ngo_confirmed → completed
```

### **Data Validation Rules**
- **Email uniqueness**: No duplicate emails across all user types
- **QR code uniqueness**: Each pickup verification has unique QR code
- **Geospatial validation**: Coordinates must be valid lat/lng
- **Time validation**: Pickup time must be after creation time, before expiry
- **Quantity validation**: Actual pickup quantity can't exceed available quantity

---

## 🔒 Security & Privacy Considerations

### **Data Access Control**
- Users can only see their own notifications and activity logs
- Admins have full access to all data
- Restaurants see only their listings and related matches
- NGOs see only their matches and pickups

### **PII Protection**
- Phone numbers masked in notifications
- User data anonymized in impact reports
- Activity logs audit all data access

---

## 🚀 Performance Optimizations

### **Database Indexes**
```sql
-- Critical indexes for performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_food_listings_location ON food_listings(location_lat, location_lng);
CREATE INDEX idx_food_listings_status ON food_listings(status, expiry_time);
CREATE INDEX idx_food_matches_status ON food_matches(status, created_at);
CREATE INDEX idx_notifications_user_read ON notifications(user_id, is_read);
CREATE INDEX idx_activity_logs_user_time ON activity_logs(user_id, created_at);
```

### **Query Optimization**
- **Geospatial queries**: Use MySQL spatial indexing for distance calculations
- **Real-time updates**: Pusher integration for live notifications
- **Pagination**: Large datasets paginated (activity logs, notifications)
- **Caching**: Redis for user sessions and frequently accessed data

---

## 📋 Future Extensions

### **Planned Entities**
- **Beneficiaries** - Track individual/family recipients
- **Volunteers** - Separate from NGOs for pickup assistance
- **Donations** - Track individual meal contributions
- **Partners** - Corporate and foundation partners
- **Reviews** - Two-way reviews between restaurants and NGOs

### **Enhanced Features**
- **Inventory Management** - Restaurant inventory tracking
- **Route Optimization** - AI-powered pickup routing
- **Predictive Analytics** - Forecast food surplus patterns
- **Multi-location Support** - Chain restaurants and multiple NGO centers

---

## 🎯 Data Migration Strategy

### **Import/Export Format**
```json
// Sample JSON structure for data migration
{
  "users": [
    {
      "name": "Marcus Tan",
      "email": "marcus@example.com",
      "role": "restaurant",
      "status": "active"
    }
  ],
  "food_listings": [
    {
      "user_id": 1,
      "food_type": "rice_dishes",
      "quantity": "20 servings",
      "expiry_time": "2025-01-15T18:00:00",
      "location": {"lat": 3.1390, "lng": 101.6869}
    }
  ]
}
```

---

**ERD Version**: 1.0
**Last Updated**: 2025-01-XX
**Created For**: MyFoodshare System Development

---

This ERD provides a comprehensive foundation for building the MyFoodshare platform with proper data relationships, constraints, and scalability considerations.