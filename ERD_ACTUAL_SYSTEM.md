# MyFoodshare - Actual Database ERD

Based on analysis of the real Laravel application, this is the accurate Entity Relationship Diagram that matches the actual implementation.

## 🔄 **CRITICAL CORRECTIONS**
- **No separate `recipients` table** - All recipient data is embedded in `users` table
- **No separate `donors` table** - All donor data is embedded in `users` table
- **Role-based approach** - Users have roles ('donor', 'recipient', 'admin')
- **Real foreign key relationships** - All relationships match actual database constraints

---

## 📊 **Entity Relationship Diagram**

```mermaid
erDiagram
    USERS {
        bigint id PK
        varchar name
        varchar email UK
        varchar password
        enum role
        varchar phone
        text address
        varchar restaurant_name
        varchar organization_name
        varchar business_license
        varchar ngo_registration
        enum status
        decimal latitude
        decimal longitude
        datetime created_at
        datetime updated_at
    }

    FOOD_LISTINGS {
        bigint id PK
        bigint user_id FK
        varchar food_name
        text description
        varchar category
        int quantity
        varchar unit
        date expiry_date
        time expiry_time
        varchar pickup_location
        varchar pickup_address
        enum status
        enum approval_status
        datetime created_at
        datetime updated_at
    }

    MATCHES {
        bigint id PK
        bigint food_listing_id FK
        bigint recipient_id FK
        enum status
        decimal distance
        datetime matched_at
        datetime pickup_scheduled_at
        datetime completed_at
        varchar qr_code UK
        text notes
        datetime created_at
        datetime updated_at
    }

    PICKUP_VERIFICATIONS {
        bigint id PK
        bigint food_match_id FK
        bigint food_listing_id FK
        bigint recipient_id FK
        bigint donor_id FK
        varchar verification_code UK
        varchar qr_code_scanned
        datetime scanned_at
        json pickup_details
        enum verification_status
        json photo_evidence
        boolean quality_confirmed
        int quality_rating
        datetime pickup_completed_at
        datetime created_at
        datetime updated_at
    }

    TRACKING {
        bigint id PK
        bigint match_id FK
        enum status
        text notes
        json location_data
        datetime status_changed_at
        datetime created_at
        datetime updated_at
    }

    ACTIVITY_LOGS {
        bigint id PK
        varchar log_name
        text description
        varchar subject_type
        bigint subject_id
        varchar causer_type
        bigint causer_id
        json properties
        json old_values
        json new_values
        datetime created_at
    }

    NOTIFICATIONS {
        uuid id PK
        varchar type
        varchar notifiable_type
        bigint notifiable_id
        text data
        datetime read_at
        datetime created_at
        datetime updated_at
    }

    %% RELATIONSHIPS
    USERS ||--o{ FOOD_LISTINGS : "creates"
    USERS ||--o{ MATCHES : "recipient_in"
    FOOD_LISTINGS ||--o{ MATCHES : "matched_to"
    USERS }o--|| USERS : "approved_by"

    MATCHES ||--o{ PICKUP_VERIFICATIONS : "generates"
    FOOD_LISTINGS ||--o{ PICKUP_VERIFICATIONS : "has"
    USERS }o--|| PICKUP_VERIFICATIONS : "recipient_in"
    USERS }o--|| PICKUP_VERIFICATIONS : "donor_in"

    MATCHES ||--o{ TRACKING : "has_tracking"

    USERS }o--|| ACTIVITY_LOGS : "performs"
    FOOD_LISTINGS }o--|| ACTIVITY_LOGS : "subject"
    MATCHES }o--|| ACTIVITY_LOGS : "subject"
    PICKUP_VERIFICATIONS }o--|| ACTIVITY_LOGS : "subject"

    USERS }o--|| NOTIFICATIONS : "receives"
    FOOD_LISTINGS }o--|| NOTIFICATIONS : "triggers"
    MATCHES }o--|| NOTIFICATIONS : "triggers"
    PICKUP_VERIFICATIONS }o--|| NOTIFICATIONS : "triggers"
```

---

## 🔍 **Key Findings & Corrections**

### **1. User Model - Role-Based Architecture**
- **Single `users` table** handles all roles (donor, recipient, admin)
- **Recipient fields embedded**: `organization_name`, `contact_person`, `ngo_registration`
- **Donor fields embedded**: `restaurant_name`, `business_license`, `cuisine_type`
- **No separate `recipients` or `donors` tables** in actual implementation

### **2. Food Match Relationships**
- **FoodMatch → Restaurant**: References through `foodListing.user_id`
- **FoodMatch → Recipient**: Direct reference to `users.id` (role='recipient')
- **No `restaurant_id` field** in matches table

### **3. Pickup Verification Structure**
- **Multiple foreign keys**: Links to match, listing, recipient, and donor
- **Donor references**: Through `foodListing.user_id` or direct `donor_id`
- **Verification workflow**: QR scanning → completion → quality assessment

### **4. Activity & Notification Systems**
- **Comprehensive audit trail**: All entity activities logged
- **Real-time notifications**: Laravel's built-in notification system
- **Polymorphic relationships**: Activity logs can track any entity type

---

## 🗃️ **Actual Database Schema**

### **Users Table (Multi-Role)**
```sql
CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role ENUM('donor', 'recipient', 'admin') DEFAULT 'donor',
    -- Donor specific fields
    restaurant_name VARCHAR(255),
    business_license VARCHAR(255),
    cuisine_type VARCHAR(255),
    -- Recipient specific fields
    organization_name VARCHAR(255),
    ngo_registration VARCHAR(255),
    -- Common fields
    status ENUM('pending', 'active', 'suspended', 'rejected') DEFAULT 'pending',
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    created_at DATETIME,
    updated_at DATETIME
);
```

### **Foreign Key Constraints**
- `food_listings.user_id` → `users.id`
- `matches.food_listing_id` → `food_listings.id`
- `matches.recipient_id` → `users.id`
- `pickup_verifications.food_match_id` → `matches.id`
- `tracking.match_id` → `matches.id`

### **Missing Tables (From Initial Design)**
- ❌ **No separate `recipients` table** - All embedded in `users`
- ❌ **No separate `donors` table** - All embedded in `users`
- ❌ **No `restaurant_id` field** in matches table

---

## 📋 **Relationship Summary**

| Entity | Relationships | Cardinality |
|--------|---------------|-------------|
| **Users** | Creates food listings | 1:N |
| **Users** | Receives as recipient | 1:N (matches) |
| **Users** | Performs actions | 1:N (activity logs) |
| **Users** | Receives notifications | 1:N (notifications) |
| **Food Listings** | Matched to recipients | 1:N (matches) |
| **Matches** | Has tracking updates | 1:N (tracking) |
| **Matches** | Generates verifications | 1:1 (pickup_verification) |
| **Pickup Verifications** | Photo evidence | 1:N (photos) |
| **All Entities** | Generate activity logs | N:M (via logs) |
| **All Entities** | Trigger notifications | N:M (via notifications) |

---

## 🚨 **Data Integrity Notes**

1. **Cascade Deletes**: All foreign keys use `ON DELETE CASCADE`
2. **Unique Constraints**: `email`, `qr_code`, `verification_code`
3. **Status Enums**: Multiple status fields for workflow tracking
4. **JSON Fields**: Flexible data storage for addresses, photos, etc.
5. **Geolocation**: Latitude/longitude for pickup location mapping

---

## 🔄 **Workflow Overview**

```
User (Donor) → Food Listing → Match → Tracking → Pickup Verification
User (Recipient) ← Match ← Food Listing ← User (Donor)
User (Admin) → Approval workflows → All entities
```

This ERD accurately reflects the real Laravel application structure as of the current implementation.