-- Check for food listings without restaurant_profile_id
SELECT COUNT(*) as null_count
FROM food_listings
WHERE restaurant_profile_id IS NULL;

-- Get a user's restaurant profile
SELECT * FROM restaurant_profiles WHERE user_id = 1 LIMIT 1;

-- Update any food listings that need restaurant_profile_id
UPDATE food_listings
SET restaurant_profile_id = r.id
FROM restaurant_profiles r
WHERE food_listings.created_by = 1
AND food_listings.restaurant_profile_id IS NULL
AND r.user_id = 1;