-- Fix the foreign key constraint issue

-- First, let's check what's in the tables
SELECT COUNT(*) as food_listings_count FROM food_listings;
SELECT COUNT(*) as restaurant_profiles_count FROM restaurant_profiles;

-- Check for food listings without restaurant_profile_id
SELECT COUNT(*) as null_fk_count FROM food_listings WHERE restaurant_profile_id IS NULL;

-- Get all food listings and their corresponding restaurant profiles
SELECT fl.id as listing_id, fl.created_by, fl.restaurant_profile_id,
       rp.id as profile_id, rp.user_id, rp.restaurant_name
FROM food_listings fl
LEFT JOIN restaurant_profiles rp ON fl.restaurant_profile_id = rp.id
LIMIT 10;

-- Fix any null restaurant_profile_id by updating them to match the created_by user
UPDATE food_listings fl
JOIN restaurant_profiles rp ON fl.created_by = rp.user_id
SET fl.restaurant_profile_id = rp.id
WHERE fl.restaurant_profile_id IS NULL
AND fl.created_by = rp.user_id;