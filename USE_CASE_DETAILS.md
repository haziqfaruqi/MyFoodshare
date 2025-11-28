# MyFoodshare Use Case Details

---

## Table 1: Create Food Listing

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-01 |
| **Use Case Name** | Create Food Listing |
| **Actor** | Restaurant Owner |
| **Description** | Post surplus food details with photos, quantity, and expiry time |
| **Preconditions** | - Restaurant user is logged in<br>- User has active restaurant profile<br>- User has "active" status |
| **Postconditions** | - Food listing created in database<br>- Status set to "pending_approval"<br>- Notification sent to admin for review |
| **Basic Flow** | 1. User clicks "Create Listing"<br>2. System displays food listing form<br>3. User selects food type from dropdown<br>4. User enters food name and quantity<br>5. User sets expiry time and pickup deadline<br>6. User uploads food photos<br>7. User enters pickup address<br>8. User submits form<br>9. System validates all fields<br>10. System saves listing to database<br>11. System triggers admin notification |
| **Alternative Flows** | **4a. Invalid quantity:** Show error message, prompt for valid input<br>**5a. Past expiry time:** Show error, must be future time<br>**8a. Missing required fields:** Highlight missing fields, prevent submission |
| **Exceptions** | - Photo upload failed (size/format error)<br>- Database connection error<br>- Form validation failed |
| **Priority** | High |
| **Frequency** | 5-10 times per restaurant per week |

---

## Table 2: Approve Match Request

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-02 |
| **Use Case Name** | Approve Match Request |
| **Actor** | Restaurant Owner |
| **Description** | Review and accept/reject NGO interest in food donations |
| **Preconditions** | - Restaurant user is logged in<br>- User has pending match requests<br>- Related food listing is approved |
| **Postconditions** | - Match status updated (approved/rejected)<br>- Notification sent to NGO<br>- Restaurant response time logged |
| **Basic Flow** | 1. User views dashboard with pending matches<br>2. User selects match to review<br>3. System displays NGO details and pickup notes<br>4. User reviews NGO information<br>5. User clicks "Approve" or "Reject"<br>6. System updates match status<br>7. System sends notification to NGO<br>8. System logs response time |
| **Alternative Flows** | **3a. Multiple matches:** System shows all interested NGOs with distance info<br>**6a. Partial approval:** User can suggest alternative pickup time |
| **Exceptions** | - NGO no longer exists (inactive/deleted)<br>- Related food listing expired<br>- System notification failed |
| **Priority** | High |
| **Frequency** | 3-5 times per restaurant per week |

---

## Table 3: Generate QR Code

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-03 |
| **Use Case Name** | Generate QR Code |
| **Actor** | Restaurant Owner |
| **Description** | Create unique verification code for secure pickup confirmation |
| **Preconditions** | - Restaurant user is logged in<br>- User has approved matches<br>- Match status is "confirmed" |
| **Postconditions** | - Unique QR code generated<br>- QR code stored in database<br>- QR code displayed to user<br>- Pickup verification record created |
| **Basic Flow** | 1. User views approved matches list<br>2. User selects confirmed match<br>3. System checks if QR already exists<br>4. System generates unique QR code (VRF-XXXXXXXX)<br>5. System saves QR code with match ID<br>6. System displays QR code to user<br>7. System provides download/share options<br>8. System logs QR generation activity |
| **Alternative Flows** | **3a. QR already exists:** System displays existing QR code<br>**4a. System error:** Regenerate with new code, log error |
| **Exceptions** | - QR generation failed<br>- Database save failed<br>- Match no longer confirmed |
| **Priority** | High |
| **Frequency** | Per confirmed pickup (variable) |

---

## Table 4: View Impact Dashboard

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-04 |
| **Use Case Name** | View Impact Dashboard |
| **Actor** | Restaurant Owner |
| **Description** | See statistics on meals donated and environmental impact |
| **Preconditions** | - Restaurant user is logged in<br>- User has donation history |
| **Postconditions** | - Dashboard data loaded<br>- Impact metrics displayed<br>- Charts rendered |
| **Basic Flow** | 1. User clicks "Impact Dashboard"<br>2. System fetches user's donation data<br>3. System calculates impact metrics<br>4. System displays total meals donated<br>5. System shows kg food rescued<br>6. System displays CO2 saved<br>7. System shows people helped<br>8. System renders trend charts |
| **Alternative Flows** | **2a. No donation history:** Display welcome message with quick start guide<br>**4a. Partial data:** Show available metrics, note incomplete data |
| **Exceptions** | - Database query timeout<br>- Chart rendering failed<br>- Impact calculation error |
| **Priority** | Medium |
| **Frequency** | Daily (active users) |

---

## Table 5: Browse Food Listings

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-05 |
| **Use Case Name** | Browse Food Listings |
| **Actor** | Recipient/NGO |
| **Description** | Search and filter available food donations by location and type |
| **Preconditions** | - NGO user is logged in<br>- User has active NGO profile<br>- User has "active" status |
| **Postconditions** | - Food listings loaded from database<br>- Map view rendered<br>- Filter options applied<br>- Results displayed |
| **Basic Flow** | 1. User opens food browse page<br>2. System displays map view<br>3. System shows nearby food listings<br>4. User applies filters (distance, food type, expiry)<br>5. System updates map with filtered results<br>6. User taps on listing for details<br>7. System displays full listing information<br>8. User can view photos and contact info |
| **Alternative Flows** | **3a. No listings nearby:** Show "No food available" message<br>**4a. No filters applied:** Show all approved listings within default radius<br>**6a. Slow connection:** Show loading indicator, cache results |
| **Exceptions** | - Map loading failed<br>- Filter criteria invalid<br>- Database connection error |
| **Priority** | High |
| **Frequency** | Multiple times per day (active NGOs) |

---

## Table 6: Express Interest

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-06 |
| **Use Case Name** | Express Interest |
| **Actor** | Recipient/NGO |
| **Description** | Show immediate interest in specific food donations |
| **Preconditions** | - NGO user is logged in<br>- User viewing food listing details<br>- Related listing is "approved" status |
| **Postconditions** | - Interest request created<br>- Status set to "interested"<br>- Notification sent to restaurant<br>- Restaurant response time tracking started |
| **Basic Flow** | 1. User views food listing details<br>2. User clicks "Express Interest"<br>3. System shows pickup time options<br>4. User selects preferred pickup time<br>5. User adds optional notes<br>6. User confirms interest<br>7. System validates interest request<br>8. System saves interest to database<br>9. System sends notification to restaurant<br>10. System logs request time |
| **Alternative Flows** | **4a. No time selection:** Use default "ASAP" option<br>**7a. Already interested:** Show error, prevent duplicate request<br>**9a. Restaurant offline:** Queue notification, retry later |
| **Exceptions** | - Already expressed interest<br>- Listing no longer available<br>- Restaurant not active<br>- Network error |
| **Priority** | High |
| **Frequency** | 5-15 times per NGO per day |

---

## Table 7: Scan QR Code

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-07 |
| **Use Case Name** | Scan QR Code |
| **Actor** | Recipient/NGO |
| **Description** | Verify pickup authenticity using unique verification code |
| **Preconditions** | - NGO user is logged in<br>- User at pickup location<br>- Has permission to scan (confirmed match) |
| **Postconditions** | - QR code scanned and validated<br>- Pickup verification started<br>- Restaurant notified of scan<br>- Real-time location tracking initiated |
| **Basic Flow** | 1. User opens app at pickup location<br>2. User taps "Scan QR Code"<br>3. System activates camera<br>4. User scans QR code from restaurant<br>5. System processes and validates QR<br>6. System confirms match validity<br>7. System displays pickup details<br>8. System sends scan confirmation to restaurant<br>9. System enables location tracking<br>10. User confirms scan completion |
| **Alternative Flows** | **5a. Invalid QR:** Show error, prompt for rescan<br>**6a. Expired match:** Show error, contact restaurant<br>**8a. No internet:** Store scan locally, sync when online<br>**10a. Manual entry:** Provide option to enter code manually |
| **Exceptions** | - QR code invalid/expired<br>- No camera access<br>- Permission denied<br>- Network connectivity issues |
| **Priority** | High |
| **Frequency** | Per pickup (variable) |

---

## Table 8: Confirm Pickup

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-08 |
| **Use Case Name** | Confirm Pickup |
| **Actor** | Recipient/NGO |
| **Description** | Finalize food collection with verification and feedback |
| **Preconditions** | - NGO user is logged in<br>- QR code successfully scanned<br>- At pickup location<br>- Has confirmed match |
| **Postconditions** | - Pickup verification completed<br>- Food status updated to "completed"<br>- Quality rating recorded<br>- Photo evidence uploaded<br>- Impact metrics updated |
| **Basic Flow** | 1. User views pickup confirmation screen<br>2. System displays scanned QR details<br>3. User confirms actual quantity received<br>4. User rates food quality (1-5 stars)<br>5. User uploads pickup photo<br>6. User adds optional notes<br>7. User clicks "Complete Pickup"<br>8. System validates all inputs<br>9. System saves pickup verification<br>10. System updates match status to "completed"<br>11. System updates impact metrics<br>12. System sends completion notification |
| **Alternative Flows** | **3a. Quantity different:** User can adjust quantity and explain difference<br>**5a. No photo uploaded:** Make photo optional but recommended<br>**9a. Validation failed:** Show specific errors, allow corrections |
| **Exceptions** | - Required fields missing<br>- Photo upload failed<br>- Network connectivity issues<br>- Match already completed |
| **Priority** | High |
| **Frequency** | Per successful pickup (variable) |

---

## Table 9: Manage Users

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-09 |
| **Use Case Name** | Manage Users |
| **Actor** | Admin |
| **Description** | Review and approve/reject restaurant and NGO applications |
| **Preconditions** | - Admin user is logged in<br>- User has admin privileges<br>- Pending user applications exist |
| **Postconditions** | - User application approved/rejected<br>- User status updated<br>- Notification sent to applicant<br>- Activity log created |
| **Basic Flow** | 1. Admin accesses user management dashboard<br>2. System displays pending applications list<br>3. Admin selects application to review<br>4. System displays user profile and documents<br>5. Admin reviews application details<br>6. Admin makes decision (approve/reject)<br>7. System updates user status<br>8. System sends notification to applicant<br>9. System logs admin action<br>10. System updates application count |
| **Alternative Flows** | **4a. Incomplete application:** Admin can request additional documents<br>**7a. Conditional approval:** Approve with specific conditions<br>**8a. Failed notification:** Log error, retry later |
| **Exceptions** | - No pending applications<br>- Application already processed<br>- Missing required information<br>- System notification failure |
| **Priority** | High |
| **Frequency** | Daily (as applications come in) |

---

## Table 10: Manage Food Listings

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-10 |
| **Use Case Name** | Manage Food Listings |
| **Actor** | Admin |
| **Description** | Verify and validate posted food donation details |
| **Preconditions** | - Admin user is logged in<br>- User has admin privileges<br>- Pending food listings exist |
| **Postconditions** | - Food listing approved/rejected<br>- Listing status updated<br>- Restaurant notified<br>- Activity log created |
| **Basic Flow** | 1. Admin accesses food management dashboard<br>2. System displays pending listings<br>3. Admin selects listing to review<br>4. System displays listing details and photos<br>5. Admin reviews food information<br>6. Admin checks food safety compliance<br>7. Admin makes decision (approve/reject)<br>8. System updates listing status<br>9. System sends notification to restaurant<br>10. System logs admin action |
| **Alternative Flows** | **5a. Suspicious content:** Admin can flag for further review<br>**7a. Conditional approval:** Approve with corrections required<br>**9a. Restaurant inactive:** Listing auto-rejected, restaurant notified |
| **Exceptions** | - No pending listings<br>- Invalid listing data<br>- Restaurant no longer active<br>- Photo verification failed |
| **Priority** | High |
| **Frequency** | Daily (as listings are posted) |

---

## Table 11: Monitor System

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-11 |
| **Use Case Name** | Monitor System |
| **Actor** | Admin |
| **Description** | Track overall platform usage and performance metrics |
| **Preconditions** | - Admin user is logged in<br>- User has admin privileges<br>- System is operational |
| **Postconditions** | - System metrics displayed<br>- Performance charts rendered<br>- Alert notifications shown<br>- Health status updated |
| **Basic Flow** | 1. Admin accesses system monitoring dashboard<br>2. System fetches real-time metrics<br>3. System displays active user count<br>4. System shows daily pickup statistics<br>5. System renders performance charts<br>6. System displays alert notifications<br>7. System shows server health status<br>8. System updates log file sizes<br>9. System displays recent system activities |
| **Alternative Flows** | **3a. High traffic:** System displays warning alert<br>**6a. No alerts:** Show "System healthy" message<br>**8a. Large logs:** Offer log cleanup option |
| **Exceptions** | - Database performance issues<br>- Server connectivity problems<br>- Metrics collection failed<br>- Alert notification failure |
| **Priority** | Medium |
| **Frequency** | Continuous (auto-refresh) |

---

## Table 12: Generate Reports

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-12 |
| **Use Case Name** | Generate Reports |
| **Actor** | Admin |
| **Description** | Create comprehensive analytics and system documentation |
| **Preconditions** | - Admin user is logged in<br>- User has admin privileges<br>- Sufficient data available for reporting |
| **Postconditions** | - Report generated successfully<br>- Report file created<br>- Report available for download<br>- Report generation logged |
| **Basic Flow** | 1. Admin accesses reports section<br>2. System displays report templates<br>3. Admin selects report type<br>4. Admin selects date range<br>5. Admin selects data filters<br>6. Admin clicks "Generate Report"<br>7. System processes selected data<br>8. System generates report file<br>9. System saves report to storage<br>10. System provides download link<br>11. System logs report generation |
| **Alternative Flows** | **4a. Custom range:** Admin can specify custom date range<br>**7a. Large dataset:** Show progress indicator, notify when complete<br>**10a. Multiple formats:** Offer PDF, Excel, CSV download options |
| **Exceptions** | - Insufficient data for report<br>- Report generation timeout<br>- File storage full<br>- Export format not supported |
| **Priority** | Medium |
| **Frequency** | Weekly/monthly (scheduled or on-demand) |

---

## Table 13: View Pickups

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-13 |
| **Use Case Name** | View Pickups |
| **Actor** | Volunteer |
| **Description** | Browse scheduled pickup opportunities in nearby areas |
| **Preconditions** | - Volunteer user is logged in<br>- User has active volunteer profile<br>- User location services enabled |
| **Postconditions** | - Pickup opportunities loaded<br>- Map view displayed<br>- Distance calculations shown<br>- Filter options available |
| **Basic Flow** | 1. Volunteer opens pickups page<br>2. System detects user location<br>3. System fetches nearby pickup opportunities<br>4. System displays map view with pickup locations<br>5. System shows pickup details (time, food type, restaurant)<br>6. System displays distance and estimated travel time<br>7. User can filter by pickup time and food type<br>8. System updates results based on filters |
| **Alternative Flows** | **3a. No nearby pickups:** Show "No pickups available" message<br>**5a. Multiple pickups:** Group by same restaurant for route efficiency<br>**7a. Offline mode:** Show cached pickups, update when online |
| **Exceptions** | - Location services disabled<br>- No internet connection<br>- Invalid pickup data<br>- Map loading failed |
| **Priority** | High |
| **Frequency** | Multiple times per day (active volunteers) |

---

## Table 14: Accept Pickup

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-14 |
| **Use Case Name** | Accept Pickup |
| **Actor** | Volunteer |
| **Description** | Confirm availability for specific food collection tasks |
| **Preconditions** | - Volunteer user is logged in<br>- Viewing pickup details<br>- Pickup is within acceptable distance<br>- Volunteer has availability |
| **Postconditions** | - Pickup assignment confirmed<br>- Volunteer status updated<br>- Restaurant notified<br>- Pickup status updated |
| **Basic Flow** | 1. Volunteer views pickup details<br>2. Volunteer reviews pickup information<br>3. Volunteer confirms pickup time<br>4. Volunteer clicks "Accept Pickup"<br>5. System validates assignment<br>6. System updates pickup status<br>7. System confirms assignment to NGO<br>8. System sends acceptance notification<br>9. System updates volunteer availability<br>10. System provides navigation options |
| **Alternative Flows** | **3a. Different time:** Volunteer can propose alternative pickup time<br>**6a. Already assigned:** Show error, pickup already claimed<br>**8a. Failed notification:** Log error, notify NGO via alternative method |
| **Exceptions** | - Pickup already accepted by another volunteer<br>- Volunteer unavailable<br>- Distance exceeds limits<br>- Restaurant cancelled pickup |
| **Priority** | High |
| **Frequency** | Variable (based on pickup availability) |

---

## Table 15: Navigate to Location

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-15 |
| **Use Case Name** | Navigate to Location |
| **Actor** | Volunteer |
| **Description** | Get directions to pickup destination using GPS |
| **Preconditions** | - Volunteer user is logged in<br>- Has accepted pickup assignment<br>- Location services enabled<br>- Has navigation app access |
| **Postconditions** | - Navigation initiated<br>- Route calculated<br>- Real-time traffic updates enabled<br>- ETA calculated |
| **Basic Flow** | 1. Volunteer views assigned pickup details<br>2. Volunteer clicks "Navigate"<br>3. System requests navigation permission<br>4. System calculates optimal route<br>5. System opens external navigation app<br>6. System passes destination coordinates<br>7. System calculates estimated travel time<br>8. System enables real-time location sharing<br>9. System updates pickup status to "en route" |
| **Alternative Flows** | **4a. Multiple routes:** System shows route options (fastest, shortest)<br>**5a. No navigation app:** System provides step-by-step directions<br>**8a. Opt-out:** Volunteer can disable location sharing |
| **Exceptions** | - Location services denied<br>- No navigation app installed<br>- Destination coordinates invalid<br>- Route calculation failed |
| **Priority** | High |
| **Frequency** | Per accepted pickup |

---

## Table 16: Collect Food

| Field | Description |
|-------|-------------|
| **Use Case ID** | UC-16 |
| **Use Case Name** | Collect Food |
| **Actor** | Volunteer |
| **Description** | Safely handle and transport donated food items |
| **Preconditions** | - Volunteer user is logged in<br>- At pickup location<br>- Has accepted pickup assignment<br>- Restaurant staff present |
| **Postconditions** | - Food collected successfully<br>- Photo evidence captured<br>- Quality assessment completed<br>- Pickup verification initiated<br>- Delivery status updated |
| **Basic Flow** | 1. Volunteer arrives at pickup location<br>2. Volunteer contacts restaurant staff<br>3. Volunteer receives food items<br>4. Volunteer inspects food condition<br>5. Volunteer takes collection photo<br>6. Volunteer notes any issues<br>7. Volunteer packs food safely<br>8. Volunteer updates pickup status to "collected"<br>9. System uploads photo evidence<br>10. System notifies NGO of collection<br>11. System provides delivery destination details |
| **Alternative Flows** | **4a. Food issues:** Volunteer can document problems and notify NGO<br>**5a. No camera:** Use device camera, ensure proper lighting<br>**8a. Partial collection:** Volunteer can collect partial amount, note discrepancy |
| **Exceptions** | - Restaurant not present<br>- Food condition unacceptable<br>- Photo capture failed<br>- Communication issues |
| **Priority** | High |
| **Frequency** | Per successful pickup |

---

**Note**: All use cases follow standard software engineering practices with proper error handling, logging, and user feedback mechanisms. Each use case is designed to support the core mission of reducing food waste through efficient surplus food redistribution.