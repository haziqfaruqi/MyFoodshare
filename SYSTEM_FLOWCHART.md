# MyFoodshare System Flowchart

## 1. User Registration & Authentication Flow

```mermaid
flowchart TD
    A[User Visits Site] --> B{Has Account?}
    B -->|No| C[Register]
    B -->|Yes| D[Login]

    C --> E{Select Role}
    E -->|Restaurant/Donor| F[Fill Restaurant Details<br/>- Name<br/>- Location<br/>- Contact]
    E -->|NGO/Recipient| G[Fill Organization Details<br/>- Organization Name<br/>- Registration Number<br/>- Address]
    E -->|Admin| H[Admin Registration<br/>Requires Super Admin]

    F --> I[Submit Registration]
    G --> I
    H --> I

    I --> J[Status: Pending Approval]
    J --> K[Admin Reviews Application]

    K --> L{Approved?}
    L -->|Yes| M[Account Activated<br/>Email Notification]
    L -->|No| N[Account Rejected<br/>Email Notification]

    M --> D
    D --> O{User Role?}
    O -->|Restaurant| P[Restaurant Dashboard]
    O -->|Recipient| Q[Recipient Dashboard]
    O -->|Admin| R[Admin Dashboard]
```

## 2. Restaurant/Donor Food Listing Flow

```mermaid
flowchart TD
    A[Restaurant Dashboard] --> B[Create Food Listing]
    B --> C[Fill Listing Details]

    C --> D[Food Details<br/>- Food Name<br/>- Category<br/>- Quantity & Unit<br/>- Expiry Date/Time]
    D --> E[Pickup Information<br/>- Pickup Location<br/>- Pickup Address<br/>- Special Instructions]
    E --> F[Dietary Information<br/>- Allergens<br/>- Storage Requirements<br/>- Preparation Date]

    F --> G[Submit Listing]
    G --> H[Activity Log Created<br/>- Estimated Meals<br/>- Estimated Weight]

    H --> I[Listing Status: Active]
    I --> J[Visible to Recipients]

    J --> K{Match Interest?}
    K -->|No Interest| L[Listing Expires]
    K -->|Recipient Shows Interest| M[New Match Created<br/>Status: Pending]

    M --> N[Restaurant Notified]
    N --> O{Restaurant Decision}

    O -->|Approve| P[Match Status: Approved]
    O -->|Reject| Q[Match Status: Rejected]

    P --> R[Schedule Pickup Time]
    R --> S[Recipient Notified<br/>Pickup Scheduled]
    S --> T[Pickup Verification Created<br/>Verification Code Generated]

    T --> U[Generate QR Code<br/>For Pickup]
    U --> V[Wait for Pickup]

    L --> W[Activity Log: Listing Expired]
    Q --> X[Notify Recipient<br/>Match Rejected]
```

## 3. Recipient Food Discovery & Pickup Flow

```mermaid
flowchart TD
    A[Recipient Dashboard] --> B[Browse Food Listings]

    B --> C{View Options}
    C -->|All Listings| D[View All Active Listings]
    C -->|Search/Filter| E[Filter by<br/>- Category<br/>- Location<br/>- Dietary Info]

    D --> F[View Listing Details]
    E --> F

    F --> G{Interested?}
    G -->|No| B
    G -->|Yes| H[Express Interest]

    H --> I[Create Food Match<br/>Status: Pending]
    I --> J[Restaurant Notified]
    J --> K[Wait for Restaurant Response]

    K --> L{Restaurant Response}
    L -->|Rejected| M[Match Rejected<br/>Browse Other Listings]
    L -->|Approved| N[Match Approved<br/>Pickup Scheduled Notification]

    N --> O[View Pickup Details<br/>- Pickup Time<br/>- Location<br/>- Verification Code]

    O --> P[Go to Pickup Location]
    P --> Q{Verification Method}

    Q -->|Scan QR Code| R[Access /pickup/scanner<br/>or Scan via QR]
    Q -->|Manual Code Entry| S[Access /pickup/verify/CODE]

    R --> T[Enter Verification Code Manually]
    S --> T

    T --> U[Click 'Verify Without Scanning'<br/>Skip Camera]
    U --> V[Pickup Verified<br/>Status: Verified]

    V --> W[Receive Food]
    W --> X[Complete Pickup Form<br/>- Quality Rating 1-5<br/>- Quality Confirmation<br/>- Notes Optional]

    X --> Y[Submit Completion]
    Y --> Z[Match Status: Completed]
    Z --> AA[Activity Log Created]
    AA --> AB[Restaurant Notified<br/>Pickup Completed]

    M --> B
```

## 4. Admin Management Flow

```mermaid
flowchart TD
    A[Admin Dashboard] --> B{Management Tasks}

    B -->|User Management| C[View All Users]
    C --> D{User Status}
    D -->|Pending| E[Review Application<br/>- Check Details<br/>- Verify Documents]
    D -->|Active| F[View User Details]
    D -->|Rejected| G[View Rejected Users]

    E --> H{Approve/Reject}
    H -->|Approve| I[Activate User Account<br/>Send Email]
    H -->|Reject| J[Reject User<br/>Send Email]

    I --> K[User Can Login]
    J --> L[User Cannot Access]

    B -->|Listings Management| M[View All Food Listings]
    M --> N[Monitor Listings<br/>- Active<br/>- Expired<br/>- Completed]

    B -->|Matches Management| O[View All Matches]
    O --> P[Monitor Pickups<br/>- Pending<br/>- Scheduled<br/>- Completed]

    B -->|Verification Management| Q[View Pickup Verifications]
    Q --> R{Verification Status}
    R -->|Pending| S[Monitor Scheduled Pickups]
    R -->|Verified| T[QR Code Scanned]
    R -->|Completed| U[Pickup Completed<br/>View Rating]
    R -->|Disputed| V[Handle Quality Issues]

    B -->|Analytics| W[View System Analytics<br/>- Total Users<br/>- Total Listings<br/>- Total Matches<br/>- Success Rate]
    W --> X[View Monthly Trends<br/>- Listings<br/>- Matches<br/>- New Users]
    X --> Y[Geographic Distribution]
```

## 5. Pickup Verification Process Flow

```mermaid
flowchart TD
    A[Match Approved & Scheduled] --> B[System Creates<br/>PickupVerification Record]
    B --> C[Generate Unique Code<br/>Format: VRF-XXXXXXXX]

    C --> D{Restaurant Action}
    D -->|View Match Details| E[See Verification Code]
    D -->|Generate QR| F[Generate QR Code<br/>Contains Verification URL]

    E --> G[Share Code with Recipient]
    F --> H[Show/Print QR Code<br/>For Recipient to Scan]

    G --> I[Recipient at Pickup Location]
    H --> I

    I --> J{Access Method}
    J -->|From Phone| K[Access /pickup/scanner]
    J -->|From Link/QR| L[Access /pickup/verify/CODE]

    K --> M{Verification Method}
    M -->|Camera Available + HTTPS| N[Scan QR Code]
    M -->|No Camera or HTTP| O[Enter Code Manually]

    N --> P[Redirect to /pickup/verify/CODE]
    O --> P
    L --> P

    P --> Q[Verification Page Loaded<br/>Show Pickup Details]
    Q --> R[Click 'Verify Without Scanning'<br/>or 'Scan QR Code']

    R --> S[API Call: POST /api/pickup/scan/CODE]
    S --> T[Backend Validates Code]

    T --> U{Valid Code?}
    U -->|No| V[Error: Invalid Code]
    U -->|Yes| W[Update Verification<br/>Status: Verified<br/>Scanned At: NOW]

    W --> X[Broadcast Event:<br/>QrCodeScanned]
    X --> Y[Restaurant Receives Notification]

    W --> Z[Show Completion Form<br/>- Quality Rating Stars<br/>- Quality Checkbox<br/>- Notes Textarea]

    Z --> AA[Recipient Rates & Confirms]
    AA --> AB[Submit: POST /api/pickup/complete/CODE]

    AB --> AC[Update Verification<br/>Status: Completed<br/>Quality Rating Saved]
    AC --> AD[Update FoodMatch<br/>Status: Completed]

    AD --> AE[Broadcast Event:<br/>PickupCompleted]
    AE --> AF[Restaurant Receives Notification<br/>With Rating]

    AD --> AG[Activity Log Created<br/>Pickup Completed Event]
    AG --> AH[Update Impact Stats<br/>- Completed Pickups +1<br/>- Meals Provided +X]

    V --> AI[User Can Retry<br/>or Contact Support]
```

## 6. Real-time Notification Flow

```mermaid
flowchart TD
    A[System Event Occurs] --> B{Event Type}

    B -->|Recipient Shows Interest| C[InterestExpressedNotification]
    C --> D[Notify Restaurant/Donor]
    D --> E[Database Notification Created]
    E --> F[Broadcast: MatchStatusUpdated Event]

    B -->|Restaurant Schedules Pickup| G[PickupScheduledNotification]
    G --> H[Notify Recipient]
    H --> I[Database Notification Created]
    I --> J[Broadcast: MatchStatusUpdated Event]

    B -->|Restaurant Approves Match| K[PickupConfirmedNotification]
    K --> H

    B -->|QR Code Scanned| L[Broadcast: QrCodeScanned Event]
    L --> M[Restaurant Channel:<br/>private-restaurant-userid]
    M --> N[Restaurant Dashboard<br/>Shows Real-time Update]

    B -->|Pickup Completed| O[PickupCompletedNotification]
    O --> P[Notify Restaurant]
    P --> Q[Broadcast: PickupCompleted Event]
    Q --> M

    F --> R[User Channels:<br/>private-user-userid]
    J --> R
    R --> S[User Receives Notification<br/>- In-app Alert<br/>- Optional: FCM Push]
```

## 7. Activity Logging & Stats Flow

```mermaid
flowchart TD
    A[System Action] --> B{Action Type}

    B -->|Food Listing Created| C[ActivityLog::logFoodDonation<br/>Event: created]
    C --> D[Store Properties:<br/>- estimated_meals<br/>- estimated_weight_kg<br/>- category, quantity, unit]

    B -->|Pickup Completed| E[ActivityLog::logPickupActivity<br/>Event: pickup_completed]
    E --> F[Store Properties:<br/>- completed_at<br/>- recipient info]

    B -->|Admin Action| G[ActivityLog::logAdminAction<br/>Event: user_approved/rejected]

    D --> H[Activity Logs Table<br/>- log_name: donation<br/>- description<br/>- subject: FoodListing<br/>- causer: User]

    F --> I[Activity Logs Table<br/>- log_name: pickup<br/>- description<br/>- subject: FoodMatch<br/>- causer: User]

    H --> J[Calculate Impact Stats]
    I --> J

    J --> K[Dashboard Displays:<br/>- Total Donations<br/>- Completed Pickups<br/>- Meals Provided<br/>- Food Waste Reduced]

    K --> L{User Role}
    L -->|Restaurant| M[Restaurant Dashboard<br/>Own Stats Only]
    L -->|Recipient| N[Recipient Dashboard<br/>Own Stats Only]
    L -->|Admin| O[Admin Dashboard<br/>System-wide Stats]
```

## 8. Data Flow Summary

```mermaid
flowchart LR
    A[User] -->|Registers| B[Users Table]
    B -->|Creates| C[Food Listings Table]
    C -->|Matched With| D[Food Matches Table]
    D -->|Verified Via| E[Pickup Verifications Table]

    B -->|Logged In| F[Activity Logs Table]
    C -->|Logged In| F
    D -->|Logged In| F
    E -->|Logged In| F

    F -->|Calculates| G[Impact Statistics]

    E -->|Sends| H[Notifications Table]
    D -->|Sends| H

    H -->|Broadcasts| I[Real-time Events<br/>Pusher/WebSockets]

    I -->|Updates| J[User Dashboards]
    G -->|Displays On| J
```

## Key System Components

### Tables
- **users**: Restaurant owners, NGO recipients, admins
- **food_listings**: Available food donations
- **food_matches**: Interest/matches between listings and recipients
- **pickup_verifications**: QR code verification records
- **activity_logs**: System activity tracking
- **notifications**: User notifications

### Main Routes
- `/restaurant/*`: Restaurant/donor dashboard and management
- `/recipient/*`: Recipient/NGO dashboard and browsing
- `/admin/*`: Admin management panel
- `/pickup/scanner`: QR code scanner page
- `/pickup/verify/{code}`: Verification and completion page
- `/api/pickup/*`: Pickup verification APIs

### Real-time Events
- `MatchStatusUpdated`: When match status changes
- `QrCodeScanned`: When recipient scans QR code
- `PickupCompleted`: When pickup is completed

### Notifications
- `InterestExpressedNotification`: To donor when recipient shows interest
- `PickupScheduledNotification`: To recipient when pickup is scheduled
- `PickupConfirmedNotification`: To recipient when match is approved
