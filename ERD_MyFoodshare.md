# MyFoodshare Corrected ERD - 8 Tables with Proper Relationships

## 🎯 Issue Identified & Fixed

**Problem**: `notifications` and `activity_logs` tables appeared to have no relationships, but they do - they track actions and events from all other tables.

**Solution**: Added proper relationships showing that notifications and activity_logs are created BY users FOR various events across the system.

---

## 🏗️ Corrected Entity Relationship Diagram (Mermaid)

```mermaid
erDiagram
    Users ||--o{ Food_Listings : creates
    Users ||--o{ Matches : expresses_interest
    Users ||--o{ Pickup_Verifications : completes
    Users ||--o{ Notifications : receives
    Users ||--o{ Activity_Logs : generates

    Food_Listings ||--o{ Matches : attracts
    Food_Listings ||--o{ Pickup_Verifications : completed_by
    Food_Listings ||--o{ Notifications : triggers
    Food_Listings ||--o{ Activity_Logs : tracked

    Matches ||--o{ Pickup_Verifications : results_in
    Matches ||--o{ Notifications : triggers
    Matches ||--o{ Activity_Logs : tracked

    Recipients ||--o{ Matches : represent
    Recipients ||--o{ Pickup_Verifications : perform
    Recipients ||--o{ Notifications : receive
    Recipients ||--o{ Activity_Logs : generate

    Pickup_Verifications ||--o{ Notifications : triggers
    Pickup_Verifications ||--o{ Activity_Logs : tracked

    Tracking ||--o{ Food_Listings : monitors
    Tracking ||--o{ Matches : tracks
    Tracking ||--o{ Pickup_Verifications : records
    Tracking ||--o{ Notifications : logs
    Tracking ||--o{ Activity_Logs : archives

    Users {
        id PK "INT, AI"
        name VARCHAR(255) "NOT NULL"
        email VARCHAR(255) "UNIQUE, NOT NULL"
        password_hash VARCHAR(255) "NOT NULL"
        phone VARCHAR(20) "NULL"
        role ENUM('restaurant', 'ngo', 'admin', 'volunteer') "NOT NULL"
        status ENUM('pending', 'active', 'suspended', 'rejected') "DEFAULT 'pending'"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        updated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    }

    Food_Listings {
        id PK "INT, AI"
        user_id FK "INT, NOT NULL" (Restaurant)
        food_type ENUM('rice', 'curry', 'bread', 'dessert', 'vegetable', 'meat', 'beverage', 'other') "NOT NULL"
        food_name VARCHAR(255) "NOT NULL"
        quantity VARCHAR(100) "NOT NULL" (e.g., "20 servings", "5kg")
        description TEXT "NULL"
        expiry_time DATETIME "NOT NULL"
        pickup_deadline DATETIME "NOT NULL"
        location_lat DECIMAL(10,8) "NOT NULL"
        location_lng DECIMAL(11,8) "NOT NULL"
        address TEXT "NOT NULL"
        photos JSON "NULL" (array of image URLs)
        status ENUM('pending', 'approved', 'matched', 'expired', 'cancelled') "DEFAULT 'pending'"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        updated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    }

    Matches {
        id PK "INT, AI"
        food_listing_id FK "INT, NOT NULL"
        recipient_id FK "INT, NOT NULL" (NGO/Recipient)
        restaurant_id FK "INT, NOT NULL" (Restaurant)
        status ENUM('interested', 'restaurant_approved', 'restaurant_rejected', 'confirmed', 'completed', 'cancelled') "DEFAULT 'interested'"
        pickup_time DATETIME "NULL"
        notes TEXT "NULL"
        distance_km DECIMAL(6,2) "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        updated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        UNIQUE(food_listing_id, recipient_id) "No duplicate interests"
    }

    Recipients {
        id PK "INT, AI"
        user_id FK "INT, UNIQUE, NOT NULL" (Linked to Users table)
        organization_name VARCHAR(255) "NULL"
        organization_address TEXT "NULL"
        contact_person VARCHAR(255) "NULL"
        registration_number VARCHAR(100) "NULL"
        verification_status ENUM('pending', 'verified', 'rejected') "DEFAULT 'pending'"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        updated_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    }

    Pickup_Verifications {
        id PK "INT, AI"
        match_id FK "INT, NOT NULL"
        food_listing_id FK "INT, NOT NULL"
        recipient_id FK "INT, NOT NULL" (NGO/Recipient)
        restaurant_id FK "INT, NOT NULL" (Restaurant)
        qr_code VARCHAR(12) "NOT NULL, UNIQUE" (format: VRF-XXXXXXXX)
        scan_time DATETIME "NOT NULL"
        actual_quantity VARCHAR(100) "NULL"
        quality_rating TINYINT "1-5, NULL"
        photo_evidence VARCHAR(500) "NULL"
        notes TEXT "NULL"
        volunteer_name VARCHAR(255) "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        UNIQUE(match_id) "One verification per match"
    }

    Notifications {
        id PK "INT, AI"
        user_id FK "INT, NOT NULL" (Recipient of notification)
        title VARCHAR(255) "NOT NULL"
        message TEXT "NOT NULL"
        type ENUM('new_listing', 'match_interest', 'match_approved', 'pickup_reminder', 'qr_generated', 'pickup_completed', 'system_update') "NOT NULL"
        related_id INT "NULL" (ID of related entity: food_listing, match, etc.)
        related_type ENUM('food_listing', 'match', 'pickup_verification') "NULL"
        is_read BOOLEAN "DEFAULT FALSE"
        sent_via ENUM('push', 'email', 'sms', 'whatsapp') "DEFAULT 'push'"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        read_at TIMESTAMP "NULL"
    }

    Activity_Logs {
        id PK "INT, AI"
        user_id FK "INT, NOT NULL" (User performing the action)
        action VARCHAR(100) "NOT NULL" (e.g., 'create', 'update', 'approve', 'reject', 'complete')
        entity_type ENUM('food_listing', 'match', 'pickup_verification', 'notification', 'user') "NOT NULL"
        entity_id INT "NOT NULL" (ID of the entity being acted upon)
        details JSON "NULL" (Additional context data)
        ip_address VARCHAR(45) "NULL"
        created_at TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        INDEX(user_id, created_at) "For user activity history"
        INDEX(entity_type, entity_id) "For entity tracking"
    }

    Tracking {
        id PK "INT, AI"
        entity_type ENUM('food_listing', 'match', 'pickup_verification', 'notification', 'activity_log') "NOT NULL"
        entity_id INT "NOT NULL" (ID of the entity being tracked)
        action_type ENUM('created', 'updated', 'deleted', 'viewed', 'exported') "NOT NULL"
        timestamp TIMESTAMP "DEFAULT CURRENT_TIMESTAMP"
        user_id FK "INT, NULL" (User who performed the action)
        ip_address VARCHAR(45) "NULL"
        user_agent TEXT "NULL"
        metadata JSON "NULL" (Additional tracking data)
        INDEX(entity_type, entity_id, timestamp) "For tracking queries"
        INDEX(user_id, timestamp) "For user activity tracking"
    }
```

---

## 🔗 Key Relationships Explained

### **Missing Relationships Added:**

#### **1. Notifications Table**
```mermaid
Users ||--o{ Notifications : "receives notifications"
Food_Listings ||--o{ Notifications : "triggers notifications when created/approved"
Matches ||--o{ Notifications : "triggers notifications when status changes"
Pickup_Verifications ||--o{ Notifications : "triggers notifications when completed"
```

#### **2. Activity_Logs Table**
```mermaid
Users ||--o{ Activity_Logs : "generates activity by performing actions"
Food_Listings ||--o{ Activity_Logs : "tracked when created/updated/deleted"
Matches ||--o{ Activity_Logs : "tracked when status changes"
Pickup_Verifications ||--o{ Activity_Logs : "tracked when created/completed"
Notifications ||--o{ Activity_Logs : "tracked when sent/read"
```

#### **3. Tracking Table (Your Additional Table)**
```mermaid
Tracking ||--o{ Food_Listings : "monitors all food listing activities"
Tracking ||--o{ Matches : "tracks all match status changes"
Tracking ||--o{ Pickup_Verifications : "records all pickup verification activities"
Tracking ||--o{ Notifications : "logs all notification activities"
Tracking ||--o{ Activity_Logs : "archives all system activities"
```

---

## 📊 Complete Relationship Matrix

| Table | Users | Food_Listings | Matches | Recipients | Pickup_Verifications | Notifications | Activity_Logs | Tracking |
|-------|-------|---------------|---------|------------|---------------------|---------------|---------------|----------|
| **Users** | - | Creates | Expresses Interest | Linked To | Completes | Receives | Generates | Performed By |
| **Food_Listings** | Created By | - | Attracts | - | Completed By | Triggers | Tracked | Monitored |
| **Matches** | Expresses Interest | Attracts | - | Represents | Results In | Triggers | Tracked | Tracked |
| **Recipients** | Linked To | - | Represents | - | Performs | Receives | Generates | Performed By |
| **Pickup_Verifications** | Completes | Completed By | Results In | Performs | - | Triggers | Tracked | Records |
| **Notifications** | Receives | Triggers | Triggers | Receives | Triggers | - | Tracked | Logged |
| **Activity_Logs** | Generates | Tracked | Tracked | Generates | Tracked | Tracked | - | Archived |
| **Tracking** | Performed By | Monitored | Tracked | Performed By | Records | Logged | Archived | - |

---

## 🎯 Business Logic Flow

### **Notification Triggers:**
1. **Food_Listings created** → New listing notifications to nearby NGOs
2. **Matches status changed** → Notifications to both restaurant and NGO
3. **Pickup_Verifications completed** → Confirmation notifications
4. **System updates** → General notifications to all users

### **Activity_Logs Tracking:**
1. **Users create/update** → Log user actions on entities
2. **Status changes** → Log all transitions (approved, rejected, completed)
3. **System events** → Log system-level activities
4. **Data access** → Log who accessed which data and when

### **Tracking Table Purpose:**
- **Comprehensive audit trail** - Every system action is tracked
- **Compliance monitoring** - For legal and regulatory requirements
- **Performance analytics** - Track system usage patterns
- **Security monitoring** - Monitor unusual activities

---

## 🔒 Foreign Key Relationships

### **Primary Relationships:**
- `Users → Food_Listings` (restaurant creates food)
- `Users → Matches` (restaurant/NGO express interest)
- `Matches → Pickup_Verifications` (confirmed match leads to pickup)
- `Users → Notifications` (user receives notifications)
- `Users → Activity_Logs` (user generates activity)
- `Tracking → All Tables` (comprehensive tracking)

### **Unique Constraints:**
- `Users.email` (unique email addresses)
- `Matches(food_listing_id, recipient_id)` (no duplicate interests)
- `Pickup_Verifications.match_id` (one verification per match)
- `Notifications(related_id, related_type)` (notification uniqueness)

---

## 🚀 Key Improvements

### **1. Added Missing Relationships**
- ✅ `Notifications` now properly connected to all trigger tables
- ✅ `Activity_Logs` now properly tracks all entity activities
- ✅ `Tracking` table monitors all system activities

### **2. Clearer Entity Roles**
- ✅ `Recipients` properly linked to `Users` table
- ✅ All status flows clearly defined
- ✅ Complete audit trail coverage

### **3. Enhanced Data Integrity**
- ✅ Comprehensive foreign key constraints
- ✅ Proper indexing for performance
- ✅ Complete tracking for compliance

---

This corrected ERD now has proper relationships for all 8 tables, ensuring that `notifications` and `activity_logs` are fully integrated into the system architecture!