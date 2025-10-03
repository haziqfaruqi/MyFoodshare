# MyFoodshare Use Case Diagram

## System Use Case Diagram

```mermaid
graph TB
    subgraph System["MyFoodshare System"]
        subgraph Authentication["Authentication & Authorization"]
            UC1[Register Account]
            UC2[Login]
            UC3[Logout]
            UC4[Reset Password]
        end

        subgraph RestaurantUseCases["Restaurant/Donor Use Cases"]
            UC5[Create Food Listing]
            UC6[Edit Food Listing]
            UC7[Delete Food Listing]
            UC8[View My Listings]
            UC9[Review Match Requests]
            UC10[Approve Match]
            UC11[Reject Match]
            UC12[Schedule Pickup Time]
            UC13[View Pickup Verifications]
            UC14[Generate QR Code]
            UC15[View Impact Statistics]
            UC16[View Donation Reports]
            UC17[Track Donation Progress]
            UC18[Manage Profile]
        end

        subgraph RecipientUseCases["Recipient/NGO Use Cases"]
            UC19[Browse Food Listings]
            UC20[Search Food Listings]
            UC21[Filter by Category]
            UC22[Filter by Location]
            UC23[View Listing Details]
            UC24[Express Interest]
            UC25[View My Matches]
            UC26[View Pickup Schedule]
            UC27[Scan QR Code]
            UC28[Verify Pickup]
            UC29[Complete Pickup]
            UC30[Rate Food Quality]
            UC31[View Received Donations]
            UC32[Manage Profile]
        end

        subgraph AdminUseCases["Admin Use Cases"]
            UC33[Approve User Registration]
            UC34[Reject User Registration]
            UC35[View All Users]
            UC36[Manage Users]
            UC37[View All Listings]
            UC38[Monitor Food Matches]
            UC39[View Pickup Verifications]
            UC40[Handle Disputed Pickups]
            UC41[View System Analytics]
            UC42[View Monthly Trends]
            UC43[View Geographic Distribution]
            UC44[Generate Reports]
            UC45[Manage System Settings]
        end

        subgraph NotificationUseCases["Notification Use Cases"]
            UC46[Receive Email Notifications]
            UC47[Receive In-App Notifications]
            UC48[Receive Push Notifications]
            UC49[View Notification History]
        end

        subgraph ReportingUseCases["Reporting & Analytics"]
            UC50[View Dashboard Statistics]
            UC51[Export Data]
            UC52[View Activity Logs]
            UC53[Track Environmental Impact]
        end
    end

    %% Actors
    Guest[Guest User]
    Restaurant[Restaurant/Donor]
    Recipient[Recipient/NGO]
    Admin[Administrator]
    System_Auto[System Automated]

    %% Guest Relationships
    Guest --> UC1
    Guest --> UC2

    %% Restaurant Relationships
    Restaurant --> UC2
    Restaurant --> UC3
    Restaurant --> UC4
    Restaurant --> UC5
    Restaurant --> UC6
    Restaurant --> UC7
    Restaurant --> UC8
    Restaurant --> UC9
    Restaurant --> UC10
    Restaurant --> UC11
    Restaurant --> UC12
    Restaurant --> UC13
    Restaurant --> UC14
    Restaurant --> UC15
    Restaurant --> UC16
    Restaurant --> UC17
    Restaurant --> UC18
    Restaurant --> UC47
    Restaurant --> UC48
    Restaurant --> UC49
    Restaurant --> UC50
    Restaurant --> UC52

    %% Recipient Relationships
    Recipient --> UC2
    Recipient --> UC3
    Recipient --> UC4
    Recipient --> UC19
    Recipient --> UC20
    Recipient --> UC21
    Recipient --> UC22
    Recipient --> UC23
    Recipient --> UC24
    Recipient --> UC25
    Recipient --> UC26
    Recipient --> UC27
    Recipient --> UC28
    Recipient --> UC29
    Recipient --> UC30
    Recipient --> UC31
    Recipient --> UC32
    Recipient --> UC47
    Recipient --> UC48
    Recipient --> UC49
    Recipient --> UC50

    %% Admin Relationships
    Admin --> UC2
    Admin --> UC3
    Admin --> UC33
    Admin --> UC34
    Admin --> UC35
    Admin --> UC36
    Admin --> UC37
    Admin --> UC38
    Admin --> UC39
    Admin --> UC40
    Admin --> UC41
    Admin --> UC42
    Admin --> UC43
    Admin --> UC44
    Admin --> UC45
    Admin --> UC47
    Admin --> UC49
    Admin --> UC52

    %% System Automated
    System_Auto --> UC46
    System_Auto --> UC47
    System_Auto --> UC48
    System_Auto --> UC53

    %% Use Case Dependencies and Includes
    UC5 -.->|includes| UC52
    UC10 -.->|includes| UC12
    UC10 -.->|triggers| UC47
    UC24 -.->|triggers| UC47
    UC29 -.->|includes| UC30
    UC29 -.->|includes| UC52
    UC33 -.->|triggers| UC46
    UC34 -.->|triggers| UC46

    classDef actorClass fill:#e1f5ff,stroke:#01579b,stroke-width:2px
    classDef useCaseClass fill:#fff9c4,stroke:#f57f17,stroke-width:2px
    classDef systemClass fill:#f3e5f5,stroke:#4a148c,stroke-width:2px

    class Guest,Restaurant,Recipient,Admin,System_Auto actorClass
    class UC1,UC2,UC3,UC4,UC5,UC6,UC7,UC8,UC9,UC10,UC11,UC12,UC13,UC14,UC15,UC16,UC17,UC18,UC19,UC20,UC21,UC22,UC23,UC24,UC25,UC26,UC27,UC28,UC29,UC30,UC31,UC32,UC33,UC34,UC35,UC36,UC37,UC38,UC39,UC40,UC41,UC42,UC43,UC44,UC45,UC46,UC47,UC48,UC49,UC50,UC51,UC52,UC53 useCaseClass
```

## Detailed Use Case Descriptions

### 1. Authentication & Authorization

| Use Case ID | Use Case Name | Actor | Description |
|------------|---------------|-------|-------------|
| UC1 | Register Account | Guest User | User registers as Restaurant/Donor or Recipient/NGO, providing required details |
| UC2 | Login | All Users | User authenticates with email and password |
| UC3 | Logout | All Authenticated Users | User logs out of the system |
| UC4 | Reset Password | All Users | User requests password reset via email |

### 2. Restaurant/Donor Use Cases

| Use Case ID | Use Case Name | Actor | Description |
|------------|---------------|-------|-------------|
| UC5 | Create Food Listing | Restaurant | Create new food donation listing with details |
| UC6 | Edit Food Listing | Restaurant | Modify existing food listing |
| UC7 | Delete Food Listing | Restaurant | Remove food listing from system |
| UC8 | View My Listings | Restaurant | View all owned food listings |
| UC9 | Review Match Requests | Restaurant | View recipient interest in listings |
| UC10 | Approve Match | Restaurant | Accept recipient's interest and approve match |
| UC11 | Reject Match | Restaurant | Decline recipient's interest |
| UC12 | Schedule Pickup Time | Restaurant | Set pickup date and time for approved match |
| UC13 | View Pickup Verifications | Restaurant | Monitor pickup verification status |
| UC14 | Generate QR Code | Restaurant | Create QR code for pickup verification |
| UC15 | View Impact Statistics | Restaurant | View meals provided, waste reduced |
| UC16 | View Donation Reports | Restaurant | Access detailed donation history reports |
| UC17 | Track Donation Progress | Restaurant | Monitor status of active donations |
| UC18 | Manage Profile | Restaurant | Update restaurant information |

### 3. Recipient/NGO Use Cases

| Use Case ID | Use Case Name | Actor | Description |
|------------|---------------|-------|-------------|
| UC19 | Browse Food Listings | Recipient | View all available food listings |
| UC20 | Search Food Listings | Recipient | Search listings by keyword |
| UC21 | Filter by Category | Recipient | Filter listings by food category |
| UC22 | Filter by Location | Recipient | Filter listings by pickup location |
| UC23 | View Listing Details | Recipient | See complete food listing information |
| UC24 | Express Interest | Recipient | Show interest in a food listing |
| UC25 | View My Matches | Recipient | View all matched food donations |
| UC26 | View Pickup Schedule | Recipient | Check scheduled pickup times |
| UC27 | Scan QR Code | Recipient | Scan QR code at pickup location |
| UC28 | Verify Pickup | Recipient | Verify pickup with verification code |
| UC29 | Complete Pickup | Recipient | Confirm food received |
| UC30 | Rate Food Quality | Recipient | Provide quality rating (1-5 stars) |
| UC31 | View Received Donations | Recipient | View history of received donations |
| UC32 | Manage Profile | Recipient | Update organization information |

### 4. Admin Use Cases

| Use Case ID | Use Case Name | Actor | Description |
|------------|---------------|-------|-------------|
| UC33 | Approve User Registration | Admin | Approve pending user registrations |
| UC34 | Reject User Registration | Admin | Reject pending user registrations |
| UC35 | View All Users | Admin | View complete user list |
| UC36 | Manage Users | Admin | Edit, activate, deactivate users |
| UC37 | View All Listings | Admin | Monitor all food listings |
| UC38 | Monitor Food Matches | Admin | Track all food matches |
| UC39 | View Pickup Verifications | Admin | Monitor pickup verification status |
| UC40 | Handle Disputed Pickups | Admin | Resolve quality disputes |
| UC41 | View System Analytics | Admin | View platform-wide statistics |
| UC42 | View Monthly Trends | Admin | Analyze monthly system trends |
| UC43 | View Geographic Distribution | Admin | See regional activity distribution |
| UC44 | Generate Reports | Admin | Create system reports |
| UC45 | Manage System Settings | Admin | Configure system parameters |

### 5. Notification Use Cases

| Use Case ID | Use Case Name | Actor | Description |
|------------|---------------|-------|-------------|
| UC46 | Receive Email Notifications | All Users | Get email notifications for events |
| UC47 | Receive In-App Notifications | All Authenticated Users | View in-app notifications |
| UC48 | Receive Push Notifications | Restaurant, Recipient | Get mobile push notifications |
| UC49 | View Notification History | All Authenticated Users | Access past notifications |

### 6. Reporting & Analytics

| Use Case ID | Use Case Name | Actor | Description |
|------------|---------------|-------|-------------|
| UC50 | View Dashboard Statistics | Restaurant, Recipient, Admin | View role-specific statistics |
| UC51 | Export Data | Admin | Export system data to files |
| UC52 | View Activity Logs | Restaurant, Admin | View system activity logs |
| UC53 | Track Environmental Impact | System | Calculate and display environmental metrics |

## Use Case Relationships

### Include Relationships
- **Create Food Listing** includes **View Activity Logs** (automatically logs creation)
- **Approve Match** includes **Schedule Pickup Time** (must schedule after approval)
- **Complete Pickup** includes **Rate Food Quality** (rating is part of completion)

### Extend Relationships
- **Browse Food Listings** extends to **Search Food Listings**
- **Browse Food Listings** extends to **Filter by Category**
- **Browse Food Listings** extends to **Filter by Location**

### Triggers (Notifications)
- **Approve Match** triggers **Receive In-App Notifications** (notifies recipient)
- **Express Interest** triggers **Receive In-App Notifications** (notifies restaurant)
- **Approve User Registration** triggers **Receive Email Notifications**
- **Reject User Registration** triggers **Receive Email Notifications**

## Actor Descriptions

| Actor | Description | Responsibilities |
|-------|-------------|------------------|
| **Guest User** | Unauthenticated visitor | Can register and login only |
| **Restaurant/Donor** | Food provider (restaurants, cafes, food businesses) | Create and manage food listings, approve matches, manage pickups |
| **Recipient/NGO** | Food recipient (NGOs, charities, community organizations) | Browse listings, express interest, complete pickups |
| **Administrator** | System admin | Manage users, monitor system, resolve disputes, view analytics |
| **System Automated** | Automated processes | Send notifications, calculate statistics, log activities |

## Business Rules

1. **User Registration**: All new users require admin approval before accessing the system
2. **Food Listing**: Only active restaurants can create food listings
3. **Match Approval**: Only the restaurant owner can approve/reject match requests
4. **Pickup Verification**: Requires valid verification code (VRF-XXXXXXXX format)
5. **Quality Rating**: Required when completing pickup (1-5 stars)
6. **Listing Expiry**: Food listings automatically expire after expiry date/time
7. **Match Limit**: Recipients can only express interest once per listing
8. **Pickup Completion**: Only recipients who verified pickup can complete it

## System Boundaries

**Included in System:**
- User management and authentication
- Food listing management
- Matching and scheduling
- Pickup verification with QR codes
- Notifications (email, in-app, push)
- Analytics and reporting
- Activity logging

**Excluded from System:**
- Payment processing (all donations are free)
- Food delivery services (pickup only)
- Inventory management for restaurants
- Tax/accounting features
- Social media integration
- Mobile apps (uses responsive web)
