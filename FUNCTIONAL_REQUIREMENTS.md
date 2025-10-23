# MyFoodshare - Functional Requirements

## Table of Contents
1. [User Management](#1-user-management)
2. [Authentication & Authorization](#2-authentication--authorization)
3. [Food Listing Management](#3-food-listing-management)
4. [Matching System](#4-matching-system)
5. [Pickup & Verification](#5-pickup--verification)
6. [Notification System](#6-notification-system)
7. [Dashboard & Analytics](#7-dashboard--analytics)
8. [Admin Management](#8-admin-management)
9. [Profile Management](#9-profile-management)
10. [Reporting & Logging](#10-reporting--logging)

---

## 1. User Management

### FR-UM-001: User Registration for Restaurants
**Description**: The system shall allow restaurants/food businesses to register by providing restaurant-specific information.

**Details**:
- **Input Fields**: restaurant_name, business_license, cuisine_type, phone, address, email, password, latitude, longitude
- **Process**: User submits registration form → Account created with status='pending' → Awaits admin approval
- **Output**: User account created in database with role='restaurant' and status='pending'
- **Route**: GET/POST /register/donor
- **Validation**: Email must be unique, all required fields must be filled, GPS coordinates optional

---

### FR-UM-002: User Registration for Recipients/NGOs
**Description**: The system shall allow NGOs and charitable organizations to register by providing organization-specific information.

**Details**:
- **Input Fields**: organization_name, ngo_registration, contact_person, recipient_capacity, phone, address, email, password, latitude, longitude
- **Process**: User submits registration form → Account created with status='pending' → Awaits admin approval
- **Output**: User account created in database with role='recipient' and status='pending'
- **Route**: GET/POST /register/recipient
- **Validation**: Email must be unique, NGO registration number required, all required fields must be filled

---

### FR-UM-003: User Account Approval by Admin
**Description**: The system shall allow administrators to approve or reject pending user registrations.

**Details**:
- **Input**: User ID, approval decision (approve/reject), optional admin_notes
- **Process**: Admin reviews application → Approves or rejects → Status updated → Email notification sent
- **Output**:
  - If approved: status='active', approved_at=timestamp, approved_by=admin_id, email sent
  - If rejected: status='rejected', admin_notes saved, rejection email sent
- **Route**: PATCH /admin/pending-approvals/{user}/approve or /reject
- **Business Rule**: Only users with status='active' can login to the system

---

### FR-UM-004: User Status Management
**Description**: The system shall allow administrators to change user account status (active, suspended, rejected).

**Details**:
- **Input**: User ID, new status
- **Process**: Admin selects user → Changes status → System updates database
- **Output**: User status updated, access permissions adjusted
- **Route**: PATCH /admin/users/{user}/status
- **Business Rule**: Suspended users cannot access the system, status changes are logged in activity_logs

---

## 2. Authentication & Authorization

### FR-AUTH-001: User Login
**Description**: The system shall authenticate users using email and password credentials.

**Details**:
- **Input**: email, password
- **Process**: User enters credentials → System validates → Role-based redirect
- **Output**:
  - Restaurant: Redirect to /restaurant/dashboard
  - Recipient: Redirect to /recipient/dashboard
  - Admin: Redirect to /admin/dashboard
- **Route**: POST /login
- **Validation**: Email must exist, password must match hashed value, account status must be 'active'
- **Security**: Passwords stored using bcrypt hashing

---

### FR-AUTH-002: User Logout
**Description**: The system shall allow authenticated users to log out and terminate their session.

**Details**:
- **Input**: Authenticated user session
- **Process**: User clicks logout → Session destroyed → Redirect to login
- **Output**: Session terminated, user redirected to home/login page
- **Route**: POST /logout

---

### FR-AUTH-003: Role-Based Access Control
**Description**: The system shall enforce role-based access control using middleware.

**Details**:
- **Roles**: admin, restaurant_owner, recipient
- **Middleware**:
  - `Admin`: Checks isAdmin() && isActive()
  - `RestaurantOwner`: Checks isRestaurantOwner() && isActive()
  - `Recipient`: Checks isRecipient() && isActive()
- **Process**: User accesses protected route → Middleware checks role and status → Grant/deny access
- **Output**: Access granted if authorized, 403 Forbidden if unauthorized

---

### FR-AUTH-004: Session Management
**Description**: The system shall maintain user sessions and verify authentication status on each request.

**Details**:
- **Process**: User logs in → Session created → Session validated on each request
- **Security**: CSRF token validation on all form submissions
- **Timeout**: Session expires after inactivity period (configurable)

---

## 3. Food Listing Management

### FR-FL-001: Create Food Listing
**Description**: The system shall allow restaurants to create food donation listings with comprehensive details.

**Details**:
- **Input Fields**:
  - food_name, description, category
  - quantity, unit (kg, servings, etc.)
  - expiry_date, expiry_time
  - pickup_location, pickup_address, latitude, longitude
  - special_instructions, dietary_info (JSON)
  - images (up to 5 photos)
- **Process**: Restaurant fills form → Submits → Listing created with approval_status='pending_approval'
- **Output**: FoodListing record created, ActivityLog entry created
- **Route**: GET/POST /restaurant/listings/create
- **Validation**: All required fields must be filled, expiry_date must be in future, images must be valid format

---

### FR-FL-002: Edit Food Listing
**Description**: The system shall allow restaurants to edit their food listings before approval or if rejected.

**Details**:
- **Input**: Listing ID, updated fields
- **Process**: Restaurant updates fields → Submits → Listing updated in database
- **Output**: FoodListing record updated, timestamps updated
- **Route**: GET/PUT /restaurant/listings/{id}/edit
- **Business Rule**: Can only edit own listings, cannot edit approved listings with active matches

---

### FR-FL-003: Delete Food Listing
**Description**: The system shall allow restaurants to delete their food listings if no active matches exist.

**Details**:
- **Input**: Listing ID
- **Process**: Restaurant clicks delete → Confirmation prompt → Listing deleted
- **Output**: FoodListing record soft-deleted or removed
- **Route**: DELETE /restaurant/listings/{id}
- **Business Rule**: Cannot delete listings with active/scheduled matches

---

### FR-FL-004: View Food Listings (Restaurant)
**Description**: The system shall allow restaurants to view all their food listings with status indicators.

**Details**:
- **Input**: Restaurant user ID (from session)
- **Process**: System queries food_listings where user_id=restaurant_id
- **Output**: List of listings with status (pending_approval, approved, rejected, expired)
- **Route**: GET /restaurant/listings
- **Display**: Paginated list with filters (status, date range)

---

### FR-FL-005: Admin Approval of Food Listings
**Description**: The system shall require admin approval before food listings become visible to recipients.

**Details**:
- **Input**: Listing ID, approval decision, optional rejection reason
- **Process**:
  1. Admin reviews listing at /admin/listing-approvals
  2. Approves or rejects
  3. If approved: approval_status='approved', approved_at=timestamp, approved_by=admin_id
  4. If approved: FoodMatchingService auto-matches with nearby recipients (5km)
  5. Notifications sent to matched recipients
- **Output**: Listing status updated, matches created if approved, notifications sent
- **Route**: PATCH /admin/listing-approvals/{listing}/approve or /reject
- **Business Rule**: Only approved listings visible to recipients

---

### FR-FL-006: Bulk Approve Food Listings
**Description**: The system shall allow admins to approve multiple food listings simultaneously.

**Details**:
- **Input**: Array of listing IDs
- **Process**: Admin selects multiple listings → Clicks bulk approve → All approved
- **Output**: Multiple listings approved, matches created for each, notifications sent
- **Route**: POST /admin/listing-approvals/bulk-approve

---

### FR-FL-007: Listing Expiry Management
**Description**: The system shall automatically handle expired food listings based on expiry_date and expiry_time.

**Details**:
- **Process**: System checks expiry_date/time → Marks as expired if past
- **Output**: Listings no longer visible to recipients, status updated
- **Business Rule**: Expired listings cannot be matched, existing matches may continue

---

## 4. Matching System

### FR-MS-001: GPS-Based Auto-Matching
**Description**: The system shall automatically create matches between approved food listings and nearby recipients using GPS coordinates.

**Details**:
- **Trigger**: Admin approves food listing
- **Process**:
  1. FoodMatchingService.createMatches() called
  2. System queries recipients where status='active' and role='recipient'
  3. For each recipient with GPS coordinates: Calculate distance using Haversine formula
  4. If distance ≤ 5km: Create FoodMatch record with status='pending'
  5. Calculate and store distance in match record
- **Output**: FoodMatch records created, NewFoodMatchNotification sent to recipients
- **Algorithm**: Haversine formula with Earth radius = 6371 km
- **Fallback**: If listing has no GPS, show to all recipients

---

### FR-MS-002: Distance Calculation
**Description**: The system shall calculate geographic distance between donors and recipients using the Haversine formula.

**Details**:
- **Input**: lat1, lon1 (donor), lat2, lon2 (recipient)
- **Formula**:
  ```
  a = sin²(Δφ/2) + cos(φ1) × cos(φ2) × sin²(Δλ/2)
  c = 2 × atan2(√a, √(1−a))
  distance = R × c (where R = 6371 km)
  ```
- **Output**: Distance in kilometers (decimal)
- **Service**: FoodMatchingService::calculateDistance()

---

### FR-MS-003: Manual Interest Expression
**Description**: The system shall allow recipients to manually express interest in food listings, creating a match.

**Details**:
- **Input**: Listing ID, recipient ID (from session)
- **Process**:
  1. Recipient browses /recipient/browse
  2. Clicks "Express Interest" on listing
  3. System creates/updates FoodMatch record with status='pending'
  4. Distance calculated and stored
  5. InterestExpressedNotification sent to restaurant
- **Output**: FoodMatch record created, notification sent
- **Route**: POST /recipient/browse/{listing}/interest
- **Business Rule**: Can only express interest once per listing

---

### FR-MS-004: Match Approval by Restaurant
**Description**: The system shall allow restaurants to approve or reject recipient interest in their food listings.

**Details**:
- **Input**: Match ID, decision (approve/reject)
- **Process**:
  1. Restaurant views pending matches at /restaurant/matches
  2. Reviews recipient details
  3. Approves or rejects
  4. If approved: status='approved', approved_at=timestamp
  5. If approved: Triggers pickup scheduling
- **Output**: Match status updated, notification sent to recipient
- **Route**:
  - PATCH /restaurant/listings/{id}/matches/{match}/approve
  - PATCH /restaurant/listings/{id}/matches/{match}/reject

---

### FR-MS-005: Match Cancellation by Recipient
**Description**: The system shall allow recipients to cancel unscheduled matches.

**Details**:
- **Input**: Match ID
- **Process**: Recipient clicks cancel → Match status='cancelled'
- **Output**: Match cancelled, restaurant notified
- **Route**: PATCH /recipient/matches/{match}/cancel
- **Business Rule**: Can only cancel if status='pending', cannot cancel scheduled/completed matches

---

## 5. Pickup & Verification

### FR-PV-001: Pickup Scheduling
**Description**: The system shall allow restaurants to schedule pickup times for approved matches.

**Details**:
- **Input**: Match ID, pickup_scheduled_at (date and time)
- **Process**:
  1. Restaurant schedules pickup at /restaurant/listings/{id}/matches/{match}/schedule
  2. Match status='scheduled', pickup_scheduled_at set
  3. PickupVerification record created with verification_code (format: VRF-XXXXXXXX)
  4. PickupScheduledNotification sent to recipient
- **Output**: Match scheduled, PickupVerification created, notification sent
- **Route**: PATCH /restaurant/listings/{id}/matches/{match}/schedule

---

### FR-PV-002: QR Code Generation
**Description**: The system shall generate unique QR codes for pickup verification.

**Details**:
- **Input**: Listing ID
- **Process**:
  1. Restaurant clicks "Generate QR Code"
  2. System generates QR code containing URL: /pickup/verify/{CODE}
  3. QR code data stored as JSON in qr_code_data field
- **Output**: QR code image displayed/downloadable, qr_code_data saved
- **Route**: POST /api/restaurant/listings/{listing}/generate-qr
- **Library**: SimpleSoftwareIO QR Code generation

---

### FR-PV-003: QR Code Scanning
**Description**: The system shall allow recipients to scan QR codes using device camera for pickup verification.

**Details**:
- **Input**: QR code image (via camera)
- **Process**:
  1. Recipient accesses /pickup/scanner
  2. Camera permission requested
  3. QR code scanned using HTML5 Camera API
  4. Code extracted from QR
  5. Redirects to /pickup/verify/{CODE}
- **Output**: Verification page loaded
- **Route**: GET /pickup/scanner
- **Requirement**: HTTPS connection, camera permission granted

---

### FR-PV-004: Manual Pickup Verification
**Description**: The system shall allow recipients to verify pickup using verification code without scanning QR.

**Details**:
- **Input**: verification_code (VRF-XXXXXXXX)
- **Process**:
  1. Recipient enters code at /pickup/verify/{CODE}
  2. System validates code
  3. If valid: Shows pickup details and completion form
  4. Updates qr_code_scanned=true, scanned_at=timestamp, verification_status='verified'
  5. Optional: Captures location_data JSON
- **Output**: Verification confirmed, pickup details displayed
- **Route**: GET/POST /pickup/verify/{code}, POST /api/pickup/scan/{code}

---

### FR-PV-005: Pickup Completion
**Description**: The system shall allow recipients to complete pickup by submitting completion details.

**Details**:
- **Input**:
  - verification_code
  - quality_rating (1-5 stars, required)
  - quality_confirmed (boolean)
  - photo_evidence (JSON array, optional)
  - recipient_notes (text, optional)
  - quantity_received
- **Process**:
  1. Recipient fills completion form
  2. Submits form
  3. PickupVerification updated: pickup_completed_at=timestamp, all fields saved
  4. FoodMatch status='completed', completed_at=timestamp
  5. ActivityLog created with pickup_completed event
  6. PickupCompletedNotification sent to restaurant
  7. Impact metrics updated (meals provided, waste reduced)
- **Output**: Pickup completed, notifications sent, metrics updated
- **Route**: POST /api/pickup/complete/{code}, PATCH /recipient/matches/{match}/complete
- **Validation**: quality_rating is required (1-5)

---

### FR-PV-006: Photo Evidence Upload
**Description**: The system shall allow recipients to upload photo evidence during pickup completion.

**Details**:
- **Input**: Image files (JPEG, PNG)
- **Process**: Images uploaded → Stored in storage → Paths saved as JSON array in photo_evidence
- **Output**: photo_evidence JSON array saved
- **Storage**: Stored in public/storage or S3
- **Validation**: Maximum 5 photos, max file size, valid image formats

---

### FR-PV-007: Quality Rating System
**Description**: The system shall capture and store quality ratings from recipients for completed pickups.

**Details**:
- **Input**: quality_rating (1-5 integer), quality_confirmed (boolean), quality_issues (text, optional)
- **Process**: Recipient provides rating → Stored in PickupVerification
- **Output**: quality_rating, quality_confirmed, quality_issues saved
- **Display**: Visible to restaurant and admin, used for analytics

---

## 6. Notification System

### FR-NS-001: In-App Notifications
**Description**: The system shall send in-app notifications for key events and store them in the database.

**Details**:
- **Notification Types**:
  - InterestExpressedNotification: To restaurant when recipient shows interest
  - NewFoodMatchNotification: To recipient when listing available nearby
  - PickupConfirmedNotification: To recipient when restaurant approves match
  - PickupScheduledNotification: To recipient with pickup time and code
  - PickupCompletedNotification: To restaurant with quality rating
- **Storage**: notifications table with polymorphic notifiable relationship
- **Fields**: type, notifiable_type, notifiable_id, data (JSON), read_at
- **Display**: Notification bell icon with unread count, notification history page

---

### FR-NS-002: Real-time Notifications via Pusher
**Description**: The system shall broadcast real-time notifications using Pusher WebSockets.

**Details**:
- **Technology**: Pusher + Laravel Echo
- **Channels**:
  - private-user-{id}: General user channel
  - private-restaurant-{id}: Restaurant-specific
  - private-recipient-{id}: Recipient-specific
- **Events**:
  - MatchStatusUpdated: Match state changes
  - QrCodeScanned: Real-time QR scanning notification
  - PickupCompleted: Final pickup completion
- **Process**: Event occurs → Broadcast to channel → Frontend listener updates UI
- **Configuration**: Pusher credentials in .env (app_id, app_key, app_secret, cluster)

---

### FR-NS-003: Email Notifications
**Description**: The system shall send email notifications for critical events.

**Details**:
- **Email Types**:
  - User registration approval: Account activated
  - User registration rejection: Account rejected with reason
- **Technology**: Laravel Mail
- **Process**: Event occurs → Email job queued → Email sent asynchronously

---

### FR-NS-004: View Notification History
**Description**: The system shall allow users to view their notification history with pagination.

**Details**:
- **Input**: User ID (from session)
- **Process**: System queries notifications where notifiable_id=user_id
- **Output**: Paginated list of notifications with read/unread status
- **Route**: GET /notifications
- **Display**: Grouped by date, unread highlighted

---

### FR-NS-005: Mark Notifications as Read
**Description**: The system shall allow users to mark notifications as read individually or in bulk.

**Details**:
- **Input**: Notification ID (or "all")
- **Process**: Update read_at=timestamp for specified notification(s)
- **Output**: Notification(s) marked as read, unread count updated
- **Route**:
  - POST /notifications/{id}/read
  - POST /notifications/mark-all-read

---

### FR-NS-006: Delete Notifications
**Description**: The system shall allow users to delete individual notifications from their history.

**Details**:
- **Input**: Notification ID
- **Process**: Notification deleted from database
- **Output**: Notification removed from history
- **Route**: DELETE /notifications/{id}

---

### FR-NS-007: Unread Notification Count
**Description**: The system shall provide real-time unread notification count for display in UI.

**Details**:
- **Input**: User ID (from session)
- **Process**: Count notifications where notifiable_id=user_id AND read_at IS NULL
- **Output**: Integer count
- **Route**: GET /notifications/unread-count
- **Display**: Badge on notification bell icon

---

## 7. Dashboard & Analytics

### FR-DA-001: Restaurant Dashboard
**Description**: The system shall provide restaurants with a comprehensive dashboard showing their donation statistics.

**Details**:
- **Metrics Displayed**:
  - Total listings created
  - Active listings count
  - Total matches (all statuses)
  - Completed donations count
  - Meals provided (calculated from activity_logs)
  - Food waste reduced in kg
  - Monthly trends (last 6 months)
  - Recent activity timeline
  - Pending matches awaiting approval
- **Route**: GET /restaurant/dashboard
- **Data Source**: FoodListing, FoodMatch, ActivityLog tables

---

### FR-DA-002: Recipient Dashboard
**Description**: The system shall provide recipients with a dashboard showing received donations and nearby listings.

**Details**:
- **Metrics Displayed**:
  - Total matches
  - Pending matches count
  - Approved/scheduled matches
  - Completed pickups count
  - Meals received
  - Money saved estimate
  - Monthly pickup trends
  - Nearby listings (6 most relevant within 5km)
  - Recent matches
  - Category preferences
- **Route**: GET /recipient/dashboard
- **Data Source**: FoodMatch, FoodListing, ActivityLog tables

---

### FR-DA-003: Admin Dashboard
**Description**: The system shall provide admins with platform-wide statistics and management tools.

**Details**:
- **Metrics Displayed**:
  - Total users (by role: restaurants, recipients, admins)
  - Pending approvals count (users and listings)
  - Active listings count
  - Total matches created
  - Completed pickups count
  - Success rate percentage
  - Monthly trends (donations, matches, new users)
  - Recent pending approvals
  - Real-time activity feed
  - Geographic distribution map
- **Route**: GET /admin/dashboard
- **Data Source**: All tables, aggregated statistics

---

### FR-DA-004: Impact Metrics Calculation
**Description**: The system shall calculate and display environmental impact metrics from completed pickups.

**Details**:
- **Metrics**:
  - Total meals provided: SUM(estimated_meals) from activity_logs where log_name='pickup' and description='pickup_completed'
  - Food waste reduced: SUM(estimated_weight_kg) from activity_logs
  - Money saved estimate: meals × average_meal_cost
- **Service**: ActivityLog::getImpactStats(), calculateMealsProvided(), calculateFoodWasteReduced()
- **Display**: Dashboard cards with trend charts

---

### FR-DA-005: Monthly Trends Analytics
**Description**: The system shall provide time-series analytics showing monthly trends.

**Details**:
- **Metrics Tracked**:
  - Listings created per month
  - Matches created per month
  - Pickups completed per month
  - New users registered per month
  - Category breakdown
- **Time Range**: Last 6-12 months
- **Visualization**: Line charts using Recharts library
- **Route**: GET /restaurant/dashboard, /recipient/dashboard, /admin/analytics

---

## 8. Admin Management

### FR-AM-001: View Pending User Approvals
**Description**: The system shall allow admins to view all pending user registrations requiring approval.

**Details**:
- **Input**: None
- **Process**: Query users where status='pending'
- **Output**: List of pending users with application details
- **Route**: GET /admin/pending-approvals
- **Display**: Table with user details, registration date, role

---

### FR-AM-002: View All Users
**Description**: The system shall allow admins to view, search, and filter all registered users.

**Details**:
- **Filters**: Role (restaurant/recipient/admin), Status (pending/active/suspended/rejected), search by name/email
- **Output**: Paginated list of users with stats
- **Route**: GET /admin/users
- **Actions Available**: View details, update status, delete user

---

### FR-AM-003: View Pending Listing Approvals
**Description**: The system shall allow admins to view all food listings awaiting approval.

**Details**:
- **Input**: None
- **Process**: Query food_listings where approval_status='pending_approval'
- **Output**: List of pending listings with details and photos
- **Route**: GET /admin/listing-approvals
- **Display**: Grid/list view with listing details, restaurant info

---

### FR-AM-004: Monitor Active Listings
**Description**: The system shall allow admins to monitor all active food listings for compliance.

**Details**:
- **Input**: Optional filters (date range, category, restaurant)
- **Process**: Query food_listings where approval_status='approved'
- **Output**: List of active listings
- **Route**: GET /admin/active-listings
- **Actions Available**: View details, deactivate, mark as expired

---

### FR-AM-005: View Pickup Verifications
**Description**: The system shall allow admins to monitor all pickup verifications and handle disputes.

**Details**:
- **Input**: Optional filters (status, date range)
- **Process**: Query pickup_verifications with all statuses
- **Output**: List of verifications with status indicators
- **Route**: GET /admin/pickup-verifications
- **Display**:
  - Pending: Scheduled but not scanned
  - Verified: QR scanned, awaiting completion
  - Completed: Pickup completed with rating
  - Disputed: quality_confirmed=false

---

### FR-AM-006: Handle Quality Disputes
**Description**: The system shall allow admins to review and resolve quality disputes from pickups.

**Details**:
- **Input**: Verification ID, resolution notes
- **Process**: Admin reviews dispute → Adds resolution → Updates status
- **Output**: Resolution saved, parties notified
- **Route**:
  - GET /admin/pickup-verifications/{verification}
  - POST /admin/pickup-verifications/{verification}/resolve

---

### FR-AM-007: System Analytics Dashboard
**Description**: The system shall provide admins with comprehensive platform analytics and reporting.

**Details**:
- **Metrics**:
  - Platform-wide statistics (users, listings, matches, success rate)
  - Monthly trends (6-12 months)
  - Food waste reduction impact
  - Geographic distribution (activity by region)
  - Category breakdown
  - User growth trends
- **Route**: GET /admin/analytics
- **Visualization**: Charts, graphs, maps using Recharts

---

### FR-AM-008: Deactivate Listings
**Description**: The system shall allow admins to manually deactivate or expire listings.

**Details**:
- **Input**: Listing ID, reason (optional)
- **Process**: Admin clicks deactivate → Listing removed from active pool
- **Output**: Listing deactivated, no longer visible to recipients
- **Route**:
  - PATCH /admin/active-listings/{listing}/deactivate
  - PATCH /admin/active-listings/{listing}/expire

---

## 9. Profile Management

### FR-PM-001: View Restaurant Profile
**Description**: The system shall allow restaurants to view their profile information.

**Details**:
- **Data Displayed**: restaurant_name, business_license, cuisine_type, phone, address, email, GPS coordinates
- **Route**: GET /restaurant/profile

---

### FR-PM-002: Edit Restaurant Profile
**Description**: The system shall allow restaurants to update their profile information.

**Details**:
- **Input**: Updated profile fields
- **Process**: Restaurant updates fields → Submits → Database updated
- **Output**: Profile updated
- **Route**: GET/PUT /restaurant/profile/edit
- **Validation**: Email must remain unique, business_license format validated

---

### FR-PM-003: View Recipient Profile
**Description**: The system shall allow recipients to view their organization profile.

**Details**:
- **Data Displayed**: organization_name, ngo_registration, contact_person, recipient_capacity, phone, address, email, GPS coordinates
- **Route**: GET /recipient/profile

---

### FR-PM-004: Edit Recipient Profile
**Description**: The system shall allow recipients to update their profile information.

**Details**:
- **Input**: Updated profile fields
- **Process**: Recipient updates fields → Submits → Database updated
- **Output**: Profile updated
- **Route**: GET/PUT /recipient/profile/edit
- **Validation**: Email must remain unique, NGO registration format validated

---

## 10. Reporting & Logging

### FR-RL-001: Activity Logging
**Description**: The system shall automatically log all major actions for audit trail and analytics.

**Details**:
- **Log Types** (log_name):
  - donation: Food listing creation, matches
  - pickup: Pickup completion events
  - admin: Admin actions (approvals, rejections)
- **Fields Logged**:
  - subject_type, subject_id (polymorphic - FoodListing, FoodMatch, User)
  - causer_type, causer_id (polymorphic - User who performed action)
  - description (event name)
  - properties (JSON - event-specific data)
  - old_values, new_values (for updates)
  - batch_uuid (for grouping related actions)
  - created_at timestamp
- **Service**: ActivityLog model methods: logFoodDonation(), logPickupActivity(), logAdminAction()

---

### FR-RL-002: View Activity Logs
**Description**: The system shall allow restaurants and admins to view activity logs.

**Details**:
- **Input**: User ID (restaurants see own logs, admins see all)
- **Process**: Query activity_logs with filters
- **Output**: Chronological list of activities
- **Route**: Accessed via dashboard or dedicated logs page
- **Display**: Timeline view with event details

---

### FR-RL-003: Donation Reports
**Description**: The system shall allow restaurants to generate detailed donation history reports.

**Details**:
- **Input**: Date range, filters (status, category)
- **Process**: Query food_listings and food_matches with filters
- **Output**: Report showing donations, matches, completions, impact metrics
- **Route**: GET /restaurant/reports
- **Export**: CSV/PDF export capability

---

### FR-RL-004: Recipient History
**Description**: The system shall allow recipients to view their complete donation receipt history.

**Details**:
- **Input**: Recipient ID (from session)
- **Process**: Query food_matches where recipient_id=user_id
- **Output**: List of all matches with statuses, dates, quality ratings
- **Route**: GET /recipient/matches (filtered by completed)
- **Display**: Table with food details, pickup dates, ratings given

---

### FR-RL-005: Browse Food Listings (Recipient)
**Description**: The system shall allow recipients to browse available food listings filtered by distance.

**Details**:
- **Input**: Recipient GPS coordinates, optional filters (category, keyword, radius)
- **Process**:
  1. FoodMatchingService.getMatchesForRecipient() called
  2. Query approved food_listings
  3. Calculate distance for each using Haversine formula
  4. Filter by radius (default 5km)
  5. Sort by distance ascending
- **Output**: List of listings with distance, sorted by proximity
- **Route**: GET /recipient/browse
- **Display**: Grid/list view with food details, distance, expiry time
- **Fallback**: If no GPS coordinates, show all approved listings

---

### FR-RL-006: Map View for Food Listings
**Description**: The system shall allow recipients to view food listings plotted on an interactive map.

**Details**:
- **Input**: Recipient GPS coordinates, map viewport
- **Process**:
  1. Query approved food_listings with GPS coordinates
  2. Calculate distances
  3. Plot on map using markers
- **Output**: Interactive map with listing markers
- **Route**: GET /recipient/browse/map
- **Technology**: Google Maps API or Leaflet.js
- **Interaction**: Click marker to view listing details

---

### FR-RL-007: Track Donation Progress
**Description**: The system shall allow restaurants to track the progress of active donations from creation to completion.

**Details**:
- **Input**: Restaurant ID (from session)
- **Process**: Query food_listings and food_matches with status tracking
- **Output**: Visual progress tracker showing listing → approval → match → pickup stages
- **Route**: GET /restaurant/track-donations
- **Display**: Kanban-style board or timeline view with status indicators

---

## Summary Statistics

**Total Functional Requirements**: 68

**Breakdown by Category**:
- User Management: 4
- Authentication & Authorization: 4
- Food Listing Management: 7
- Matching System: 5
- Pickup & Verification: 7
- Notification System: 7
- Dashboard & Analytics: 5
- Admin Management: 8
- Profile Management: 4
- Reporting & Logging: 7

**Technology Stack Referenced**:
- Backend: Laravel 10.10, PHP 8.1+, MySQL
- Frontend: React 18.2, TypeScript, Vite, Tailwind CSS
- Real-time: Pusher, Laravel Echo
- QR Code: SimpleSoftwareIO
- Authentication: Laravel Sanctum
- Charts: Recharts
- Icons: Lucide React

**Key Algorithms**:
- Haversine formula for GPS distance calculation
- Auto-matching algorithm with 5km radius
- Impact metrics calculation from activity logs
