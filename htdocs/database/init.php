<?php
class DatabaseInitializer {
    private $pdo;
    private $dbPath = 'restaurant.db';
    public function __construct() {
        $this->connect();
        $this->initializeDatabase();
    }
    private function connect() {
        try {
            $this->pdo = new PDO("sqlite:{$this->dbPath}");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("PRAGMA foreign_keys = ON");
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    public function initializeDatabase() {
        $sqlSchema = file_get_contents('schema.sql');
        
        try {
            $this->pdo->exec($sqlSchema);
            $this->seedInitialData();
            echo "Database initialized successfully with demo data!";
        } catch (PDOException $e) {
            die("Database initialization failed: " . $e->getMessage());
        }
    }
    private function seedInitialData() {
        // Seed Translations
        $translations = [
            // App translations
            ['app_name', 'en-US', 'Luna Dine'],
            ['app_name', 'bn-BD', 'লুনা ডাইন'],
            
            // Category translations
            ['category_appetizers', 'en-US', 'Appetizers'],
            ['category_appetizers', 'bn-BD', 'অ্যাপিটাইজার'],
            ['category_main_course', 'en-US', 'Main Course'],
            ['category_main_course', 'bn-BD', 'মূল খাবার'],
            ['category_beverages', 'en-US', 'Beverages'],
            ['category_beverages', 'bn-BD', 'পানীয়'],
            ['category_desserts', 'en-US', 'Desserts'],
            ['category_desserts', 'bn-BD', 'মিষ্টি'],
            
            // Fast Food Category translations
            ['category_burgers', 'en-US', 'Burgers'],
            ['category_burgers', 'bn-BD', 'বার্গার'],
            ['category_sandwiches', 'en-US', 'Sandwiches'],
            ['category_sandwiches', 'bn-BD', 'স্যান্ডউইচ'],
            ['category_fries', 'en-US', 'Fries & Sides'],
            ['category_fries', 'bn-BD', 'ফ্রাই ও সাইড ডিশ'],
            ['category_fast_food_desserts', 'en-US', 'Desserts'],
            ['category_fast_food_desserts', 'bn-BD', 'মিষ্টি'],
            ['category_hot_dogs', 'en-US', 'Hot Dogs'],
            ['category_hot_dogs', 'bn-BD', 'হট ডগ'],
            ['category_wraps', 'en-US', 'Wraps'],
            ['category_wraps', 'bn-BD', 'র‌্যাপ'],
            
            // Desi Food Category translations
            ['category_desi_rice', 'en-US', 'Rice Dishes'],
            ['category_desi_rice', 'bn-BD', 'ভাত ভিত্তিক খাবার'],
            ['category_desi_curry', 'en-US', 'Curries'],
            ['category_desi_curry', 'bn-BD', 'ঝোল'],
            ['category_desi_kabab', 'en-US', 'Kebabs & Grills'],
            ['category_desi_kabab', 'bn-BD', 'কাবাব ও গ্রিল'],
            ['category_desi_bread', 'en-US', 'Breads'],
            ['category_desi_bread', 'bn-BD', 'রুটি'],
            ['category_desi_desserts', 'en-US', 'Bengali Sweets'],
            ['category_desi_desserts', 'bn-BD', 'বাংলাদেশী মিষ্টি'],
            ['category_desi_starters', 'en-US', 'Starters'],
            ['category_desi_starters', 'bn-BD', 'স্টার্টার'],
            
            // Chinese Food Category translations
            ['category_chinese_rice', 'en-US', 'Fried Rice'],
            ['category_chinese_rice', 'bn-BD', 'ভাজা ভাত'],
            ['category_chinese_noodles', 'en-US', 'Noodles'],
            ['category_chinese_noodles', 'bn-BD', 'নুডলস'],
            ['category_chinese_soup', 'en-US', 'Soups'],
            ['category_chinese_soup', 'bn-BD', 'স্যুপ'],
            ['category_chinese_starters', 'en-US', 'Appetizers'],
            ['category_chinese_starters', 'bn-BD', 'অ্যাপিটাইজার'],
            ['category_chinese_main', 'en-US', 'Main Dishes'],
            ['category_chinese_main', 'bn-BD', 'মূল খাবার'],
            ['category_chinese_desserts', 'en-US', 'Desserts'],
            ['category_chinese_desserts', 'bn-BD', 'মিষ্টি'],
            
            // Indian Food Category translations
            ['category_indian_bread', 'en-US', 'Breads'],
            ['category_indian_bread', 'bn-BD', 'রুটি'],
            ['category_indian_curry', 'en-US', 'Indian Curries'],
            ['category_indian_curry', 'bn-BD', 'ভারতীয় কারী'],
            ['category_indian_tandoor', 'en-US', 'Tandoori Special'],
            ['category_indian_tandoor', 'bn-BD', 'তন্দুরি স্পেশাল'],
            ['category_indian_rice', 'en-US', 'Rice Dishes'],
            ['category_indian_rice', 'bn-BD', 'ভাত ভিত্তিক খাবার'],
            ['category_indian_starters', 'en-US', 'Starters'],
            ['category_indian_starters', 'bn-BD', 'স্টার্টার'],
            ['category_indian_desserts', 'en-US', 'Indian Sweets'],
            ['category_indian_desserts', 'bn-BD', 'ভারতীয় মিষ্টি'],
            
            // Italian Category translations
            ['category_pizza', 'en-US', 'Pizza'],
            ['category_pizza', 'bn-BD', 'পিৎজা'],
            ['category_pasta', 'en-US', 'Pasta'],
            ['category_pasta', 'bn-BD', 'পাস্তা'],
            ['category_salad', 'en-US', 'Salads'],
            ['category_salad', 'bn-BD', 'সালাদ'],
            ['category_italian_starters', 'en-US', 'Antipasti'],
            ['category_italian_starters', 'bn-BD', 'অ্যান্টিপাস্টি'],
            ['category_italian_desserts', 'en-US', 'Dolci'],
            ['category_italian_desserts', 'bn-BD', 'ডলচি'],
            ['category_italian_main', 'en-US', 'Main Courses'],
            ['category_italian_main', 'bn-BD', 'মূল খাবার'],
            
            // Japanese Category translations
            ['category_sushi', 'en-US', 'Sushi & Sashimi'],
            ['category_sushi', 'bn-BD', 'সুশি ও সাশিমি'],
            ['category_ramen', 'en-US', 'Ramen & Noodles'],
            ['category_ramen', 'bn-BD', 'রামেন ও নুডলস'],
            ['category_tepanyaki', 'en-US', 'Teppanyaki & Tempura'],
            ['category_tepanyaki', 'bn-BD', 'তেপ্পানিয়াকি ও টেম্পুরা'],
            ['category_japanese_starters', 'en-US', 'Appetizers'],
            ['category_japanese_starters', 'bn-BD', 'অ্যাপিটাইজার'],
            ['category_japanese_bento', 'en-US', 'Bento Boxes'],
            ['category_japanese_bento', 'bn-BD', 'বেন্টো বক্স'],
            ['category_japanese_desserts', 'en-US', 'Desserts'],
            ['category_japanese_desserts', 'bn-BD', 'মিষ্টি'],
            
            // Dessert Category translations
            ['category_bengali_sweets', 'en-US', 'Bengali Sweets'],
            ['category_bengali_sweets', 'bn-BD', 'বাংলাদেশী মিষ্টি'],
            ['category_ice_cream', 'en-US', 'Ice Cream'],
            ['category_ice_cream', 'bn-BD', 'আইসক্রিম'],
            ['category_cakes', 'en-US', 'Cakes & Pastries'],
            ['category_cakes', 'bn-BD', 'কেক ও পেস্ট্রি'],
            
            // Item translations - Fast Food
            ['item_beef_burger', 'en-US', 'Classic Beef Burger'],
            ['item_beef_burger', 'bn-BD', 'ক্লাসিক বিফ বার্গার'],
            ['item_beef_burger_desc', 'en-US', 'Juicy beef patty with fresh vegetables and sauce'],
            ['item_beef_burger_desc', 'bn-BD', 'রসালো বিফ প্যাটি তাজা সবজি এবং সস দিয়ে'],
            
            ['item_chicken_burger', 'en-US', 'Crispy Chicken Burger'],
            ['item_chicken_burger', 'bn-BD', 'ক্রিস্পি চিকেন বার্গার'],
            ['item_chicken_burger_desc', 'en-US', 'Crispy fried chicken fillet with mayo and lettuce'],
            ['item_chicken_burger_desc', 'bn-BD', 'ক্রিস্পি ভাজা চিকেন ফিলে মেয়োনেজ এবং লেটুস দিয়ে'],
            
            ['item_french_fries', 'en-US', 'French Fries'],
            ['item_french_fries', 'bn-BD', 'ফ্রেঞ্চ ফ্রাই'],
            ['item_french_fries_desc', 'en-US', 'Crispy golden potato fries with salt'],
            ['item_french_fries_desc', 'bn-BD', 'লবণ মাখানো সোনালী আলুর কুরকুরে ফ্রাই'],
            
            ['item_chicken_sandwich', 'en-US', 'Grilled Chicken Sandwich'],
            ['item_chicken_sandwich', 'bn-BD', 'গ্রিলড চিকেন স্যান্ডউইচ'],
            ['item_chicken_sandwich_desc', 'en-US', 'Grilled chicken breast with vegetables and sauce'],
            ['item_chicken_sandwich_desc', 'bn-BD', 'গ্রিলড চিকেন ব্রেস্ট সবজি এবং সস দিয়ে'],
            
            // Additional Fast Food Items
            ['item_cheese_burger', 'en-US', 'Double Cheese Burger'],
            ['item_cheese_burger', 'bn-BD', 'ডাবল চিজ বার্গার'],
            ['item_cheese_burger_desc', 'en-US', 'Double beef patty with melted cheese and special sauce'],
            ['item_cheese_burger_desc', 'bn-BD', 'গলিত পনির এবং বিশেষ সস সহ ডাবল বিফ প্যাটি'],
            
            ['item_fish_burger', 'en-US', 'Fish Fillet Burger'],
            ['item_fish_burger', 'bn-BD', 'ফিশ ফিলে বার্গার'],
            ['item_fish_burger_desc', 'en-US', 'Crispy fish fillet with tartar sauce and lettuce'],
            ['item_fish_burger_desc', 'bn-BD', 'টার্টার সস এবং লেটুস সহ কুরকুরে মাছের ফিলে'],
            
            ['item_veggie_burger', 'en-US', 'Veggie Delight Burger'],
            ['item_veggie_burger', 'bn-BD', 'ভেজি ডিলাইট বার্গার'],
            ['item_veggie_burger_desc', 'en-US', 'Plant-based patty with fresh vegetables and special sauce'],
            ['item_veggie_burger_desc', 'bn-BD', 'তাজা সবজি এবং বিশেষ সস সহ উদ্ভিজ্জ-ভিত্তিক প্যাটি'],
            
            ['item_club_sandwich', 'en-US', 'Club Sandwich'],
            ['item_club_sandwich', 'bn-BD', 'ক্লাব স্যান্ডউইচ'],
            ['item_club_sandwich_desc', 'en-US', 'Triple-decker sandwich with chicken, bacon, lettuce, tomato and mayo'],
            ['item_club_sandwich_desc', 'bn-BD', 'চিকেন, বেকন, লেটুস, টমেটো এবং মেয়োনেজ সহ ট্রিপল-ডেকার স্যান্ডউইচ'],
            
            ['item_tuna_sandwich', 'en-US', 'Tuna Melt Sandwich'],
            ['item_tuna_sandwich', 'bn-BD', 'টুনা মেল্ট স্যান্ডউইচ'],
            ['item_tuna_sandwich_desc', 'en-US', 'Tuna salad with melted cheese on toasted bread'],
            ['item_tuna_sandwich_desc', 'bn-BD', 'টোস্টেড রুটিতে গলিত পনির সহ টুনা সালাদ'],
            
            ['item_crispy_chicken', 'en-US', 'Crispy Chicken Wings'],
            ['item_crispy_chicken', 'bn-BD', 'ক্রিস্পি চিকেন উইংস'],
            ['item_crispy_chicken_desc', 'en-US', 'Crispy fried chicken wings with your choice of sauce'],
            ['item_crispy_chicken_desc', 'bn-BD', 'আপনার পছন্দের সস সহ কুরকুরে ভাজা চিকেন উইংস'],
            
            ['item_onion_rings', 'en-US', 'Onion Rings'],
            ['item_onion_rings', 'bn-BD', 'পেঁয়াজ রিংস'],
            ['item_onion_rings_desc', 'en-US', 'Crispy battered onion rings served with dipping sauce'],
            ['item_onion_rings_desc', 'bn-BD', 'ডিপিং সস সহ পরিবেশিত কুরকুরে ব্যাটারড পেঁয়াজ রিংস'],
            
            ['item_mozzarella_sticks', 'en-US', 'Mozzarella Sticks'],
            ['item_mozzarella_sticks', 'bn-BD', 'মোজারেলা স্টিকস'],
            ['item_mozzarella_sticks_desc', 'en-US', 'Fried mozzarella cheese sticks with marinara sauce'],
            ['item_mozzarella_sticks_desc', 'bn-BD', 'মেরিনারা সস সহ ভাজা মোজারেলা পনিরের স্টিকস'],
            
            ['item_hot_dog', 'en-US', 'Classic Hot Dog'],
            ['item_hot_dog', 'bn-BD', 'ক্লাসিক হট ডগ'],
            ['item_hot_dog_desc', 'en-US', 'Beef frankfurter in a soft bun with mustard and ketchup'],
            ['item_hot_dog_desc', 'bn-BD', 'সফট বানে সরিষা এবং কেচাপ সহ বিফ ফ্র্যাঙ্কফুর্টার'],
            
            ['item_chili_dog', 'en-US', 'Chili Cheese Dog'],
            ['item_chili_dog', 'bn-BD', 'চিলি চিজ ডগ'],
            ['item_chili_dog_desc', 'en-US', 'Hot dog topped with chili con carne and melted cheese'],
            ['item_chili_dog_desc', 'bn-BD', 'চিলি কন কার্নে এবং গলিত পনির দিয়ে টপড হট ডগ'],
            
            ['item_chicken_wrap', 'en-US', 'Chicken Caesar Wrap'],
            ['item_chicken_wrap', 'bn-BD', 'চিকেন সিজার র‌্যাপ'],
            ['item_chicken_wrap_desc', 'en-US', 'Grilled chicken with Caesar dressing in a tortilla wrap'],
            ['item_chicken_wrap_desc', 'bn-BD', 'টর্টিলা র‌্যাপে সিজার ড্রেসিং সহ গ্রিলড চিকেন'],
            
            ['item_veggie_wrap', 'en-US', 'Garden Veggie Wrap'],
            ['item_veggie_wrap', 'bn-BD', 'গার্ডেন ভেজি র‌্যাপ'],
            ['item_veggie_wrap_desc', 'en-US', 'Fresh vegetables with hummus in a whole wheat wrap'],
            ['item_veggie_wrap_desc', 'bn-BD', 'গোটা গমের র‌্যাপে হুম্মাস সহ তাজা সবজি'],
            
            ['item_chocolate_shake', 'en-US', 'Chocolate Milkshake'],
            ['item_chocolate_shake', 'bn-BD', 'চকোলেট মিল্কশেক'],
            ['item_chocolate_shake_desc', 'en-US', 'Creamy chocolate milkshake topped with whipped cream'],
            ['item_chocolate_shake_desc', 'bn-BD', 'হুইপড ক্রিম দিয়ে টপড ক্রিমি চকোলেট মিল্কশেক'],
            
            ['item_vanilla_shake', 'en-US', 'Vanilla Milkshake'],
            ['item_vanilla_shake', 'bn-BD', 'ভ্যানিলা মিল্কশেক'],
            ['item_vanilla_shake_desc', 'en-US', 'Classic vanilla milkshake with a cherry on top'],
            ['item_vanilla_shake_desc', 'bn-BD', 'উপরে একটি চেরি সহ ক্লাসিক ভ্যানিলা মিল্কশেক'],
            
            // Item translations - Desi Food
            ['item_biryani', 'en-US', 'Kacchi Biryani'],
            ['item_biryani', 'bn-BD', 'কাচ্চি বিরিয়ানি'],
            ['item_biryani_desc', 'en-US', 'Fragrant basmati rice with tender mutton and spices'],
            ['item_biryani_desc', 'bn-BD', 'নরম খাসির মাংস এবং মসলা দিয়ে সুগন্ধি বাসমতি চাল'],
            
            ['item_bhuna_khichuri', 'en-US', 'Bhuna Khichuri'],
            ['item_bhuna_khichuri', 'bn-BD', 'ভুনা খিচুড়ি'],
            ['item_bhuna_khichuri_desc', 'en-US', 'Spiced rice and lentil dish with beef'],
            ['item_bhuna_khichuri_desc', 'bn-BD', 'মসলাদার চাল এবং ডাল দিয়ে গরুর মাংস সহকারে'],
            
            ['item_seekh_kabab', 'en-US', 'Seekh Kebab'],
            ['item_seekh_kabab', 'bn-BD', 'সীখ কাবাব'],
            ['item_seekh_kabab_desc', 'en-US', 'Minced meat kebabs grilled with spices'],
            ['item_seekh_kabab_desc', 'bn-BD', 'মসলা দিয়ে গ্রিল করা কিমা মাংসের কাবাব'],
            
            ['item_chicken_rezala', 'en-US', 'Chicken Rezala'],
            ['item_chicken_rezala', 'bn-BD', 'চিকেন রেজালা'],
            ['item_chicken_rezala_desc', 'en-US', 'Mildly spiced chicken curry in yogurt gravy'],
            ['item_chicken_rezala_desc', 'bn-BD', 'টক দই দিয়ে মৃদু মসলায় রান্না করা চিকেন কারী'],
            
            // Additional Desi Food Items
            ['item_morog_polao', 'en-US', 'Chicken Polao'],
            ['item_morog_polao', 'bn-BD', 'মোরগ পোলাও'],
            ['item_morog_polao_desc', 'en-US', 'Fragrant rice cooked with chicken and aromatic spices'],
            ['item_morog_polao_desc', 'bn-BD', 'চিকেন এবং সুগন্ধি মসলা দিয়ে রান্না করা সুগন্ধি চাল'],
            
            ['item_tehari', 'en-US', 'Beef Tehari'],
            ['item_tehari', 'bn-BD', 'গরুর মাংসের তেহারি'],
            ['item_tehari_desc', 'en-US', 'Spiced rice dish with tender beef pieces'],
            ['item_tehari_desc', 'bn-BD', 'নরম গরুর মাংসের টুকরা সহ মসলাদার চালের খাবার'],
            
            ['item_kacchi_gosht', 'en-US', 'Kacchi Gosht'],
            ['item_kacchi_gosht', 'bn-BD', 'কাচ্চি গোশত'],
            ['item_kacchi_gosht_desc', 'en-US', 'Slow-cooked tender meat with aromatic spices'],
            ['item_kacchi_gosht_desc', 'bn-BD', 'সুগন্ধি মসলা দিয়ে ধীরে রান্না করা নরম মাংস'],
            
            ['item_chicken_korma', 'en-US', 'Chicken Korma'],
            ['item_chicken_korma', 'bn-BD', 'চিকেন কোরমা'],
            ['item_chicken_korma_desc', 'en-US', 'Mild and creamy chicken curry with nuts'],
            ['item_chicken_korma_desc', 'bn-BD', 'বাদাম সহ মৃদু এবং ক্রিমি চিকেন কারী'],
            
            ['item_beef_bhuna', 'en-US', 'Beef Bhuna'],
            ['item_beef_bhuna', 'bn-BD', 'গরুর মাংসের ভুনা'],
            ['item_beef_bhuna_desc', 'en-US', 'Spiced beef cooked until tender with thick gravy'],
            ['item_beef_bhuna_desc', 'bn-BD', 'ঘন ঝোলে নরম না হওয়া পর্যন্ত মসলা দিয়ে রান্না করা গরুর মাংস'],
            
            ['item_mutton_curry', 'en-US', 'Mutton Curry'],
            ['item_mutton_curry', 'bn-BD', 'খাসির মাংসের কারী'],
            ['item_mutton_curry_desc', 'en-US', 'Tender mutton pieces in a rich spicy gravy'],
            ['item_mutton_curry_desc', 'bn-BD', 'সমৃদ্ধ ঝাল ঝোলে নরম খাসির মাংসের টুকরা'],
            
            ['item_chapli_kabab', 'en-US', 'Chapli Kebab'],
            ['item_chapli_kabab', 'bn-BD', 'চাপলি কাবাব'],
            ['item_chapli_kabab_desc', 'en-US', 'Spiced minced meat patties fried to perfection'],
            ['item_chapli_kabab_desc', 'bn-BD', 'নিখুঁতভাবে ভাজা মসলাযুক্ত কিমা মাংসের প্যাটি'],
            
            ['item_shami_kabab', 'en-US', 'Shami Kebab'],
            ['item_shami_kabab', 'bn-BD', 'শামি কাবাব'],
            ['item_shami_kabab_desc', 'en-US', 'Minced meat and lentil patties with spices'],
            ['item_shami_kabab_desc', 'bn-BD', 'মসলা সহ কিমা মাংস এবং ডালের প্যাটি'],
            
            ['item_paratha', 'en-US', 'Paratha'],
            ['item_paratha', 'bn-BD', 'পরোটা'],
            ['item_paratha_desc', 'en-US', 'Flaky layered flatbread, perfect with curries'],
            ['item_paratha_desc', 'bn-BD', 'ফ্লেকি স্তরযুক্ত ফ্ল্যাটব্রেড, কারীর সাথে নিখুঁত'],
            
            ['item_luchi', 'en-US', 'Luchi'],
            ['item_luchi', 'bn-BD', 'লুচি'],
            ['item_luchi_desc', 'en-US', 'Light and fluffy deep-fried bread'],
            ['item_luchi_desc', 'bn-BD', 'হালকা এবং ফুলে যাওয়া ডুবো তেলে ভাজা রুটি'],
            
            ['item_ruti', 'en-US', 'Ruti'],
            ['item_ruti', 'bn-BD', 'রুটি'],
            ['item_ruti_desc', 'en-US', 'Traditional whole wheat flatbread'],
            ['item_ruti_desc', 'bn-BD', 'ঐতিহ্যবাহী গোটা গমের ফ্ল্যাটব্রেড'],
            
            ['item_rasmalai', 'en-US', 'Rasmalai'],
            ['item_rasmalai', 'bn-BD', 'রসমালাই'],
            ['item_rasmalai_desc', 'en-US', 'Soft cottage cheese dumplings in sweetened milk'],
            ['item_rasmalai_desc', 'bn-BD', 'মিষ্টি দুধে ছানার পিঠা'],
            
            ['item_mishti_doi', 'en-US', 'Mishti Doi'],
            ['item_mishti_doi', 'bn-BD', 'মিষ্টি দই'],
            ['item_mishti_doi_desc', 'en-US', 'Sweetened yogurt with caramelized flavor'],
            ['item_mishti_doi_desc', 'bn-BD', 'ক্যারামেলাইজড স্বাদের মিষ্টি দই'],
            
            ['item_chomchom', 'en-US', 'Chomchom'],
            ['item_chomchom', 'bn-BD', 'চমচম'],
            ['item_chomchom_desc', 'en-US', 'Elongated sweet cottage cheese dessert'],
            ['item_chomchom_desc', 'bn-BD', 'লম্বা মিষ্টি ছানার মিষ্টি'],
            
            ['item_shondesh', 'en-US', 'Shondesh'],
            ['item_shondesh', 'bn-BD', 'সন্দেশ'],
            ['item_shondesh_desc', 'en-US', 'Traditional Bengali sweet made from cottage cheese'],
            ['item_shondesh_desc', 'bn-BD', 'ছানা থেকে তৈরি ঐতিহ্যবাহী বাঙালি মিষ্টি'],
            
            ['item_beguni', 'en-US', 'Beguni'],
            ['item_beguni', 'bn-BD', 'বেগুনি'],
            ['item_beguni_desc', 'en-US', 'Sliced eggplant coated in batter and deep-fried'],
            ['item_beguni_desc', 'bn-BD', 'ব্যাটারে ডুবিয়ে ডুবো তেলে ভাজা কাটা বেগুন'],
            
            ['item_chop', 'en-US', 'Aloor Chop'],
            ['item_chop', 'bn-BD', 'আলুর চপ'],
            ['item_chop_desc', 'en-US', 'Spiced potato mixture coated in batter and fried'],
            ['item_chop_desc', 'bn-BD', 'ব্যাটারে ডুবিয়ে ভাজা মসলাযুক্ত আলুর মিশ্রণ'],
            
            ['item_singara', 'en-US', 'Singara'],
            ['item_singara', 'bn-BD', 'সিঙাড়া'],
            ['item_singara_desc', 'en-US', 'Triangular pastry filled with spiced potatoes and peas'],
            ['item_singara_desc', 'bn-BD', 'মসলাযুক্ত আলু এবং মটরশুঁটি দিয়ে পূর্ণ ত্রিকোণাকার পেস্ট্রি'],
            
            ['item_fuchka', 'en-US', 'Fuchka'],
            ['item_fuchka', 'bn-BD', 'ফুচকা'],
            ['item_fuchka_desc', 'en-US', 'Crispy hollow spheres filled with spicy tamarind water'],
            ['item_fuchka_desc', 'bn-BD', 'ঝাল তেতুলের পানি দিয়ে পূর্ণ কুরকুরে ফাঁপা গোলক'],
            
            // Item translations - Chinese Food
            ['item_chicken_fried_rice', 'en-US', 'Chicken Fried Rice'],
            ['item_chicken_fried_rice', 'bn-BD', 'চিকেন ফ্রাইড রাইস'],
            ['item_chicken_fried_rice_desc', 'en-US', 'Wok-fried rice with chicken pieces and vegetables'],
            ['item_chicken_fried_rice_desc', 'bn-BD', 'চিকেন এবং সবজি দিয়ে ওকে ভাজা ভাত'],
            
            ['item_chowmein', 'en-US', 'Chicken Chowmein'],
            ['item_chowmein', 'bn-BD', 'চিকেন চাওমিন'],
            ['item_chowmein_desc', 'en-US', 'Stir-fried noodles with chicken and vegetables'],
            ['item_chowmein_desc', 'bn-BD', 'চিকেন এবং সবজি দিয়ে ভাজা নুডলস'],
            
            ['item_hot_and_sour_soup', 'en-US', 'Hot and Sour Soup'],
            ['item_hot_and_sour_soup', 'bn-BD', 'হট অ্যান্ড সাওয়ার স্যুপ'],
            ['item_hot_and_sour_soup_desc', 'en-US', 'Spicy and tangy soup with vegetables and chicken'],
            ['item_hot_and_sour_soup_desc', 'bn-BD', 'ঝাল এবং টক স্বাদের সবজি এবং চিকেন দিয়ে তৈরি স্যুপ'],
            
            ['item_chili_chicken', 'en-US', 'Chili Chicken'],
            ['item_chili_chicken', 'bn-BD', 'চিলি চিকেন'],
            ['item_chili_chicken_desc', 'en-US', 'Spicy chicken stir-fried with capsicum and onions'],
            ['item_chili_chicken_desc', 'bn-BD', 'কাঁচা মরিচ এবং পেঁয়াজ দিয়ে ঝাল চিকেন ভাজা'],
            
            // Additional Chinese Food Items
            ['item_veg_fried_rice', 'en-US', 'Vegetable Fried Rice'],
            ['item_veg_fried_rice', 'bn-BD', 'ভেজিটেবল ফ্রাইড রাইস'],
            ['item_veg_fried_rice_desc', 'en-US', 'Wok-fried rice with mixed vegetables'],
            ['item_veg_fried_rice_desc', 'bn-BD', 'মিশ্র সবজি দিয়ে ওকে ভাজা ভাত'],
            
            ['item_szechuan_rice', 'en-US', 'Szechuan Fried Rice'],
            ['item_szechuan_rice', 'bn-BD', 'সিচুয়ান ফ্রাইড রাইস'],
            ['item_szechuan_rice_desc', 'en-US', 'Spicy Szechuan style fried rice with chili and garlic'],
            ['item_szechuan_rice_desc', 'bn-BD', 'মরিচ এবং রসুন দিয়ে ঝাল সিচুয়ান স্টাইল ফ্রাইড রাইস'],
            
            ['item_egg_fried_rice', 'en-US', 'Egg Fried Rice'],
            ['item_egg_fried_rice', 'bn-BD', 'ডিম ভাজা ভাত'],
            ['item_egg_fried_rice_desc', 'en-US', 'Simple fried rice with scrambled eggs and vegetables'],
            ['item_egg_fried_rice_desc', 'bn-BD', 'ডিম ভাজা এবং সবজি দিয়ে সাধারণ ভাজা ভাত'],
            
            ['item_hakka_noodles', 'en-US', 'Hakka Noodles'],
            ['item_hakka_noodles', 'bn-BD', 'হাক্কা নুডলস'],
            ['item_hakka_noodles_desc', 'en-US', 'Stir-fried noodles with vegetables and soy sauce'],
            ['item_hakka_noodles_desc', 'bn-BD', 'সবজি এবং সয়া সস দিয়ে ভাজা নুডলস'],
            
            ['item_singapore_noodles', 'en-US', 'Singapore Noodles'],
            ['item_singapore_noodles', 'bn-BD', 'সিঙ্গাপুর নুডলস'],
            ['item_singapore_noodles_desc', 'en-US', 'Thin rice noodles with curry powder and vegetables'],
            ['item_singapore_noodles_desc', 'bn-BD', 'কারি পাউডার এবং সবজি দিয়ে পাতলা চালের নুডলস'],
            
            ['item_schezwan_noodles', 'en-US', 'Schezwan Noodles'],
            ['item_schezwan_noodles', 'bn-BD', 'সেজওয়ান নুডলস'],
            ['item_schezwan_noodles_desc', 'en-US', 'Spicy noodles with Schezwan sauce and vegetables'],
            ['item_schezwan_noodles_desc', 'bn-BD', 'সেজওয়ান সস এবং সবজি দিয়ে ঝাল নুডলস'],
            
            ['item_wanton_soup', 'en-US', 'Wonton Soup'],
            ['item_wanton_soup', 'bn-BD', 'ওয়াংটন স্যুপ'],
            ['item_wanton_soup_desc', 'en-US', 'Clear broth with dumplings filled with meat and vegetables'],
            ['item_wanton_soup_desc', 'bn-BD', 'মাংস এবং সবজি দিয়ে পূর্ণ ডাম্পলিং সহ পরিষ্কার স্টক'],
            
            ['item_sweetcorn_soup', 'en-US', 'Sweet Corn Soup'],
            ['item_sweetcorn_soup', 'bn-BD', 'মিষ্টি ভুট্টার স্যুপ'],
            ['item_sweetcorn_soup_desc', 'en-US', 'Creamy soup with sweet corn and chicken'],
            ['item_sweetcorn_soup_desc', 'bn-BD', 'মিষ্টি ভুট্টা এবং চিকেন দিয়ে ক্রিমি স্যুপ'],
            
            ['item_tomato_soup', 'en-US', 'Tomato Soup'],
            ['item_tomato_soup', 'bn-BD', 'টমেটো স্যুপ'],
            ['item_tomato_soup_desc', 'en-US', 'Rich and tangy tomato soup with herbs'],
            ['item_tomato_soup_desc', 'bn-BD', 'ভেষজ সহ সমৃদ্ধ এবং টক টমেটো স্যুপ'],
            
            ['item_spring_rolls', 'en-US', 'Spring Rolls'],
            ['item_spring_rolls', 'bn-BD', 'স্প্রিং রোল'],
            ['item_spring_rolls_desc', 'en-US', 'Crispy rolls filled with vegetables and served with sweet chili sauce'],
            ['item_spring_rolls_desc', 'bn-BD', 'সবজি দিয়ে পূর্ণ কুরকুরে রোল, মিষ্টি মরিচের সস সহ পরিবেশিত'],
            
            ['item_dumplings', 'en-US', 'Steamed Dumplings'],
            ['item_dumplings', 'bn-BD', 'ভাপায় রান্না করা ডাম্পলিংস'],
            ['item_dumplings_desc', 'en-US', 'Delicate dumplings filled with meat and vegetables'],
            ['item_dumplings_desc', 'bn-BD', 'মাংস এবং সবজি দিয়ে পূর্ণ কোমল ডাম্পলিংস'],
            
            ['item_crispy_chili_chicken', 'en-US', 'Crispy Chilli Chicken'],
            ['item_crispy_chili_chicken', 'bn-BD', 'ক্রিস্পি চিলি চিকেন'],
            ['item_crispy_chili_chicken_desc', 'en-US', 'Crispy fried chicken pieces in spicy chili sauce'],
            ['item_crispy_chili_chicken_desc', 'bn-BD', 'ঝাল মরিচের সসে কুরকুরে ভাজা চিকেনের টুকরা'],
            
            ['item_garlic_chicken', 'en-US', 'Garlic Chicken'],
            ['item_garlic_chicken', 'bn-BD', 'রসুন চিকেন'],
            ['item_garlic_chicken_desc', 'en-US', 'Chicken stir-fried with garlic and chili'],
            ['item_garlic_chicken_desc', 'bn-BD', 'রসুন এবং মরিচ দিয়ে ভাজা চিকেন'],
            
            ['item_manchurian', 'en-US', 'Gobi Manchurian'],
            ['item_manchurian', 'bn-BD', 'গোবি মাঞ্চুরিয়ান'],
            ['item_manchurian_desc', 'en-US', 'Cauliflower florets in spicy Manchurian sauce'],
            ['item_manchurian_desc', 'bn-BD', 'ঝাল মাঞ্চুরিয়ান সসে ফুলকপি'],
            
            ['item_paneer_chilli', 'en-US', 'Chilli Paneer'],
            ['item_paneer_chilli', 'bn-BD', 'চিলি পনির'],
            ['item_paneer_chilli_desc', 'en-US', 'Cottage cheese cubes in spicy chili sauce'],
            ['item_paneer_chilli_desc', 'bn-BD', 'ঝাল মরিচের সসে পনিরের কিউব'],
            
            ['item_fried_ice_cream', 'en-US', 'Fried Ice Cream'],
            ['item_fried_ice_cream', 'bn-BD', 'ভাজা আইসক্রিম'],
            ['item_fried_ice_cream_desc', 'en-US', 'Ice cream coated in batter and quickly fried, served warm'],
            ['item_fried_ice_cream_desc', 'bn-BD', 'ব্যাটারে আবৃত আইসক্রিম দ্রুত ভাজা, উষ্ণ পরিবেশিত'],
            
            ['item_darsaan', 'en-US', 'Darsaan'],
            ['item_darsaan', 'bn-BD', 'দরসান'],
            ['item_darsaan_desc', 'en-US', 'Honey-glazed fried noodles with ice cream'],
            ['item_darsaan_desc', 'bn-BD', 'মধু-গ্লেজড ভাজা নুডলস আইসক্রিম সহ'],
            
            // Item translations - Indian Food
            ['item_butter_chicken', 'en-US', 'Butter Chicken'],
            ['item_butter_chicken', 'bn-BD', 'বাটার চিকেন'],
            ['item_butter_chicken_desc', 'en-US', 'Tender chicken in creamy tomato sauce'],
            ['item_butter_chicken_desc', 'bn-BD', 'ক্রিমি টমেটো সসে নরম চিকেন'],
            
            ['item_naan', 'en-US', 'Butter Naan'],
            ['item_naan', 'bn-BD', 'বাটার নান'],
            ['item_naan_desc', 'en-US', 'Soft flatbread brushed with butter'],
            ['item_naan_desc', 'bn-BD', 'মাখন মাখানো নরম ফ্ল্যাটব্রেড'],
            
            ['item_tandoori_chicken', 'en-US', 'Tandoori Chicken'],
            ['item_tandoori_chicken', 'bn-BD', 'তন্দুরি চিকেন'],
            ['item_tandoori_chicken_desc', 'en-US', 'Chicken marinated in yogurt and spices, cooked in tandoor'],
            ['item_tandoori_chicken_desc', 'bn-BD', 'দই এবং মসলায় মেরিনেট করা চিকেন, তন্দুরে রান্না'],
            
            ['item_palak_paneer', 'en-US', 'Palak Paneer'],
            ['item_palak_paneer', 'bn-BD', 'পালাক পনির'],
            ['item_palak_paneer_desc', 'en-US', 'Spinach curry with cottage cheese cubes'],
            ['item_palak_paneer_desc', 'bn-BD', 'পালং শাক এবং পনির দিয়ে তৈরি কারী'],
            
            // Additional Indian Food Items
            ['item_chicken_tikka_masala', 'en-US', 'Chicken Tikka Masala'],
            ['item_chicken_tikka_masala', 'bn-BD', 'চিকেন টিক্কা মসলা'],
            ['item_chicken_tikka_masala_desc', 'en-US', 'Grilled chicken pieces in creamy tomato and cashew sauce'],
            ['item_chicken_tikka_masala_desc', 'bn-BD', 'ক্রিমি টমেটো এবং কাজু সসে গ্রিলড চিকেনের টুকরা'],
            
            ['item_rogan_josh', 'en-US', 'Rogan Josh'],
            ['item_rogan_josh', 'bn-BD', 'রোগন জোশ'],
            ['item_rogan_josh_desc', 'en-US', 'Aromatic lamb curry cooked with Kashmiri spices'],
            ['item_rogan_josh_desc', 'bn-BD', 'কাশ্মীরি মসলা দিয়ে রান্না করা সুগন্ধি ভেড়ার মাংসের কারী'],
            
            ['item_dal_makhani', 'en-US', 'Dal Makhani'],
            ['item_dal_makhani', 'bn-BD', 'দাল মাখানি'],
            ['item_dal_makhani_desc', 'en-US', 'Creamy lentil dish slow-cooked with butter and cream'],
            ['item_dal_makhani_desc', 'bn-BD', 'মাখন এবং ক্রিম দিয়ে ধীরে রান্না করা ক্রিমি ডালের খাবার'],
            
            ['item_chana_masala', 'en-US', 'Chana Masala'],
            ['item_chana_masala', 'bn-BD', 'ছানা মসলা'],
            ['item_chana_masala_desc', 'en-US', 'Spiced chickpea curry with onions and tomatoes'],
            ['item_chana_masala_desc', 'bn-BD', 'পেঁয়াজ এবং টমেটো সহ মসলাযুক্ত ছোলার কারী'],
            
            ['item_malai_kofta', 'en-US', 'Malai Kofta'],
            ['item_malai_kofta', 'bn-BD', 'মালাই কোফতা'],
            ['item_malai_kofta_desc', 'en-US', 'Vegetable dumplings in creamy cashew sauce'],
            ['item_malai_kofta_desc', 'bn-BD', 'ক্রিমি কাজু সসে সবজির কোফতা'],
            
            ['item_aloo_gobi', 'en-US', 'Aloo Gobi'],
            ['item_aloo_gobi', 'bn-BD', 'আলু গোবি'],
            ['item_aloo_gobi_desc', 'en-US', 'Potato and cauliflower curry with turmeric and spices'],
            ['item_aloo_gobi_desc', 'bn-BD', 'হলুদ এবং মসলা দিয়ে আলু এবং ফুলকপির কারী'],
            
            ['item_baingan_bharta', 'en-US', 'Baingan Bharta'],
            ['item_baingan_bharta', 'bn-BD', 'বেগুন ভর্তা'],
            ['item_baingan_bharta_desc', 'en-US', 'Smoked eggplant mashed with tomatoes and spices'],
            ['item_baingan_bharta_desc', 'bn-BD', 'ধূমপান করা বেগুন টমেটো এবং মসলা দিয়ে মেশানো'],
            
            ['item_tandoori_roti', 'en-US', 'Tandoori Roti'],
            ['item_tandoori_roti', 'bn-BD', 'তন্দুরি রুটি'],
            ['item_tandoori_roti_desc', 'en-US', 'Whole wheat flatbread baked in tandoor'],
            ['item_tandoori_roti_desc', 'bn-BD', 'তন্দুরে বেক করা গোটা গমের ফ্ল্যাটব্রেড'],
            
            ['item_garlic_naan', 'en-US', 'Garlic Naan'],
            ['item_garlic_naan', 'bn-BD', 'রসুন নান'],
            ['item_garlic_naan_desc', 'en-US', 'Soft naan bread topped with garlic and butter'],
            ['item_garlic_naan_desc', 'bn-BD', 'রসুন এবং মাখন দিয়ে টপড নরম নান রুটি'],
            
            ['item_lachha_paratha', 'en-US', 'Lachha Paratha'],
            ['item_lachha_paratha', 'bn-BD', 'লাচ্ছা পরোটা'],
            ['item_lachha_paratha_desc', 'en-US', 'Multi-layered flaky paratha with crispy layers'],
            ['item_lachha_paratha_desc', 'bn-BD', 'কুরকুরে স্তর সহ বহু-স্তরযুক্ত ফ্লেকি পরোটা'],
            
            ['item_pulao', 'en-US', 'Vegetable Pulao'],
            ['item_pulao', 'bn-BD', 'ভেজিটেবল পোলাও'],
            ['item_pulao_desc', 'en-US', 'Fragrant basmati rice cooked with mixed vegetables'],
            ['item_pulao_desc', 'bn-BD', 'মিশ্র সবজি দিয়ে রান্না করা সুগন্ধি বাসমতি চাল'],
            
            ['item_jeera_rice', 'en-US', 'Jeera Rice'],
            ['item_jeera_rice', 'bn-BD', 'জিরা ভাত'],
            ['item_jeera_rice_desc', 'en-US', 'Basmati rice flavored with cumin seeds'],
            ['item_jeera_rice_desc', 'bn-BD', 'জিরা বীজ দিয়ে স্বাদযুক্ত বাসমতি চাল'],
            
            ['item_biryani_rice', 'en-US', 'Hyderabadi Biryani'],
            ['item_biryani_rice', 'bn-BD', 'হায়দ্রাবাদী বিরিয়ানি'],
            ['item_biryani_rice_desc', 'en-US', 'Fragrant rice layered with marinated meat and spices'],
            ['item_biryani_rice_desc', 'bn-BD', 'মেরিনেট করা মাংস এবং মসলা দিয়ে স্তরযুক্ত সুগন্ধি চাল'],
            
            ['item_samosa', 'en-US', 'Samosa'],
            ['item_samosa', 'bn-BD', 'সমোসা'],
            ['item_samosa_desc', 'en-US', 'Crispy triangular pastry filled with spiced potatoes and peas'],
            ['item_samosa_desc', 'bn-BD', 'মসলাযুক্ত আলু এবং মটরশুঁটি দিয়ে পূর্ণ কুরকুরে ত্রিকোণাকার পেস্ট্রি'],
            
            ['item_pakora', 'en-US', 'Vegetable Pakora'],
            ['item_pakora', 'bn-BD', 'ভেজিটেবল পাকোড়া'],
            ['item_pakora_desc', 'en-US', 'Mixed vegetables coated in gram flour batter and deep-fried'],
            ['item_pakora_desc', 'bn-BD', 'বেসন ব্যাটারে আবৃত মিশ্র সবজি ডুবো তেলে ভাজা'],
            
            ['item_papad', 'en-US', 'Papad'],
            ['item_papad', 'bn-BD', 'পাপড়'],
            ['item_papad_desc', 'en-US', 'Thin and crispy lentil wafers, roasted or fried'],
            ['item_papad_desc', 'bn-BD', 'পাতলা এবং কুরকুরে ডালের ওয়েফার, ভাজা বা রোস্টেড'],
            
            ['item_gulab_jamun', 'en-US', 'Gulab Jamun'],
            ['item_gulab_jamun', 'bn-BD', 'গোলাপ জামুন'],
            ['item_gulab_jamun_desc', 'en-US', 'Soft milk solids dumplings soaked in rose-flavored sugar syrup'],
            ['item_gulab_jamun_desc', 'bn-BD', 'গোলাপ-স্বাদযুক্ত চিনির সিরায় ভেজানো নরম দুধের কঠিন পদার্থের ডাম্পলিং'],
            
            ['item_kheer', 'en-US', 'Rice Kheer'],
            ['item_kheer', 'bn-BD', 'চালের ক্ষীর'],
            ['item_kheer_desc', 'en-US', 'Creamy rice pudding with nuts and cardamom'],
            ['item_kheer_desc', 'bn-BD', 'বাদাম এবং এলাচ সহ ক্রিমি চালের পুডিং'],
            
            ['item_jalebi', 'en-US', 'Jalebi'],
            ['item_jalebi', 'bn-BD', 'জিলাপি'],
            ['item_jalebi_desc', 'en-US', 'Crispy pretzel-shaped sweets soaked in sugar syrup'],
            ['item_jalebi_desc', 'bn-BD', 'চিনির সিরায় ভেজানো কুরকুরে প্রেটজেল-আকৃতির মিষ্টি'],
            
            // Item translations - Italian
            ['item_margherita_pizza', 'en-US', 'Margherita Pizza'],
            ['item_margherita_pizza', 'bn-BD', 'মার্গারিটা পিৎজা'],
            ['item_margherita_pizza_desc', 'en-US', 'Classic pizza with tomato sauce, mozzarella and basil'],
            ['item_margherita_pizza_desc', 'bn-BD', 'টমেটো সস, মোজারেলা এবং তুলসী পাতা দিয়ে ক্লাসিক পিৎজা'],
            
            ['item_spaghetti_pasta', 'en-US', 'Spaghetti Bolognese'],
            ['item_spaghetti_pasta', 'bn-BD', 'স্প্যাগেটি বোলোনিজ'],
            ['item_spaghetti_pasta_desc', 'en-US', 'Spaghetti pasta with meat sauce'],
            ['item_spaghetti_pasta_desc', 'bn-BD', 'মাংসের সস দিয়ে স্প্যাগেটি পাস্তা'],
            
            ['item_caesar_salad', 'en-US', 'Caesar Salad'],
            ['item_caesar_salad', 'bn-BD', 'সিজার সালাদ'],
            ['item_caesar_salad_desc', 'en-US', 'Romaine lettuce with croutons, parmesan and Caesar dressing'],
            ['item_caesar_salad_desc', 'bn-BD', 'ক্রুটন, পারমেসান এবং সিজার ড্রেসিং সহ রোমাইন লেটুস'],
            
            ['item_garlic_bread', 'en-US', 'Garlic Bread'],
            ['item_garlic_bread', 'bn-BD', 'রসুন রুটি'],
            ['item_garlic_bread_desc', 'en-US', 'Toasted bread with garlic butter and herbs'],
            ['item_garlic_bread_desc', 'bn-BD', 'রসুন মাখানো মাখন এবং গার্নিশ দিয়ে টোস্টেড রুটি'],
            
            // Additional Italian Items
            ['item_pepperoni_pizza', 'en-US', 'Pepperoni Pizza'],
            ['item_pepperoni_pizza', 'bn-BD', 'পেপেরোনি পিৎজা'],
            ['item_pepperoni_pizza_desc', 'en-US', 'Classic pizza topped with pepperoni slices and mozzarella cheese'],
            ['item_pepperoni_pizza_desc', 'bn-BD', 'পেপেরোনি স্লাইস এবং মোজারেলা পনির দিয়ে টপড ক্লাসিক পিৎজা'],
            
            ['item_hawaiian_pizza', 'en-US', 'Hawaiian Pizza'],
            ['item_hawaiian_pizza', 'bn-BD', 'হাওয়াইয়ান পিৎজা'],
            ['item_hawaiian_pizza_desc', 'en-US', 'Pizza topped with ham, pineapple, and mozzarella cheese'],
            ['item_hawaiian_pizza_desc', 'bn-BD', 'হ্যাম, আনারস, এবং মোজারেলা পনির দিয়ে টপড পিৎজা'],
            
            ['item_veggie_pizza', 'en-US', 'Vegetarian Pizza'],
            ['item_veggie_pizza', 'bn-BD', 'ভেজিটেরিয়ান পিৎজা'],
            ['item_veggie_pizza_desc', 'en-US', 'Pizza topped with bell peppers, mushrooms, onions, and olives'],
            ['item_veggie_pizza_desc', 'bn-BD', 'বেল পেপার, মাশরুম, পেঁয়াজ, এবং জলপাই দিয়ে টপড পিৎজা'],
            
            ['item_bbq_chicken_pizza', 'en-US', 'BBQ Chicken Pizza'],
            ['item_bbq_chicken_pizza', 'bn-BD', 'বিবিকিউ চিকেন পিৎজা'],
            ['item_bbq_chicken_pizza_desc', 'en-US', 'Pizza with BBQ sauce, grilled chicken, red onions, and cilantro'],
            ['item_bbq_chicken_pizza_desc', 'bn-BD', 'বিবিকিউ সস, গ্রিলড চিকেন, লাল পেঁয়াজ, এবং ধনিয়া সহ পিৎজা'],
            
            ['item_fettuccine_alfredo', 'en-US', 'Fettuccine Alfredo'],
            ['item_fettuccine_alfredo', 'bn-BD', 'ফেটুচিনি আলফ্রেডো'],
            ['item_fettuccine_alfredo_desc', 'en-US', 'Fettuccine pasta in creamy Parmesan sauce'],
            ['item_fettuccine_alfredo_desc', 'bn-BD', 'ক্রিমি পারমেসান সসে ফেটুচিনি পাস্তা'],
            
            ['item_penne_arrabbiata', 'en-US', 'Penne Arrabbiata'],
            ['item_penne_arrabbiata', 'bn-BD', 'পেনে আরাবিয়াতা'],
            ['item_penne_arrabbiata_desc', 'en-US', 'Penne pasta in spicy tomato sauce with garlic and herbs'],
            ['item_penne_arrabbiata_desc', 'bn-BD', 'রসুন এবং ভেষজ সহ ঝাল টমেটো সসে পেনে পাস্তা'],
            
            ['item_lasagna', 'en-US', 'Lasagna'],
            ['item_lasagna', 'bn-BD', 'লাসানিয়া'],
            ['item_lasagna_desc', 'en-US', 'Layers of pasta with meat sauce, béchamel, and cheese'],
            ['item_lasagna_desc', 'bn-BD', 'মাংসের সস, বেচামেল এবং পনির সহ পাস্তার স্তর'],
            
            ['item_ravioli', 'en-US', 'Cheese Ravioli'],
            ['item_ravioli', 'bn-BD', 'চিজ রাভিওলি'],
            ['item_ravioli_desc', 'en-US', 'Pasta pillows filled with cheese and served in tomato sauce'],
            ['item_ravioli_desc', 'bn-BD', 'পনির দিয়ে পূর্ণ পাস্তা পিলো, টমেটো সসে পরিবেশিত'],
            
            ['item_greek_salad', 'en-US', 'Greek Salad'],
            ['item_greek_salad', 'bn-BD', 'গ্রীক সালাদ'],
            ['item_greek_salad_desc', 'en-US', 'Fresh vegetables with feta cheese, olives, and Greek dressing'],
            ['item_greek_salad_desc', 'bn-BD', 'ফেটা পনির, জলপাই এবং গ্রীক ড্রেসিং সহ তাজা সবজি'],
            
            ['item_caprese_salad', 'en-US', 'Caprese Salad'],
            ['item_caprese_salad', 'bn-BD', 'কাপ্রেসে সালাদ'],
            ['item_caprese_salad_desc', 'en-US', 'Fresh mozzarella, tomatoes, and basil with balsamic glaze'],
            ['item_caprese_salad_desc', 'bn-BD', 'তাজা মোজারেলা, টমেটো, এবং বালসামিক গ্লেজ সহ তুলসী'],
            
            ['item_antonio_salad', 'en-US', 'Antonio Salad'],
            ['item_antonio_salad', 'bn-BD', 'অ্যান্টোনিও সালাদ'],
            ['item_antonio_salad_desc', 'en-US', 'Mixed greens with grilled chicken, avocado, and Caesar dressing'],
            ['item_antonio_salad_desc', 'bn-BD', 'গ্রিলড চিকেন, আভোকাডো, এবং সিজার ড্রেসিং সহ মিশ্র সবুজ'],
            
            ['item_bruschetta', 'en-US', 'Bruschetta'],
            ['item_bruschetta', 'bn-BD', 'ব্রুসকেটা'],
            ['item_bruschetta_desc', 'en-US', 'Toasted bread topped with tomatoes, garlic, and fresh basil'],
            ['item_bruschetta_desc', 'bn-BD', 'টমেটো, রসুন, এবং তাজা তুলসী দিয়ে টপড টোস্টেড রুটি'],
            
            ['item_calamari', 'en-US', 'Calamari'],
            ['item_calamari', 'bn-BD', 'ক্যালামারি'],
            ['item_calamari_desc', 'en-US', 'Crispy fried squid rings served with marinara sauce'],
            ['item_calamari_desc', 'bn-BD', 'মেরিনারা সস সহ পরিবেশিত কুরকুরে ভাজা স্কুইড রিংস'],
            
            ['item_arancini', 'en-US', 'Arancini'],
            ['item_arancini', 'bn-BD', 'আরানচিনি'],
            ['item_arancini_desc', 'en-US', 'Crispy risotto balls filled with cheese and deep-fried'],
            ['item_arancini_desc', 'bn-BD', 'পনির দিয়ে পূর্ণ কুরকুরে রিসোট্টো বল ডুবো তেলে ভাজা'],
            
            ['item_tiramisu', 'en-US', 'Tiramisu'],
            ['item_tiramisu', 'bn-BD', 'টিরামিসু'],
            ['item_tiramisu_desc', 'en-US', 'Classic Italian dessert with coffee-soaked ladyfingers and mascarpone'],
            ['item_tiramisu_desc', 'bn-BD', 'কফি-সোকড লেডিফিঙ্গার এবং মাসকারপোন সহ ক্লাসিক ইতালীয় ডেজার্ট'],
            
            ['item_panna_cotta', 'en-US', 'Panna Cotta'],
            ['item_panna_cotta', 'bn-BD', 'পান্না কোটা'],
            ['item_panna_cotta_desc', 'en-US', 'Silky smooth vanilla pudding topped with berry sauce'],
            ['item_panna_cotta_desc', 'bn-BD', 'বেরি সস দিয়ে টপড রেশমী মসৃণ ভ্যানিলা পুডিং'],
            
            ['item_cannoli', 'en-US', 'Cannoli'],
            ['item_cannoli', 'bn-BD', 'কানোলি'],
            ['item_cannoli_desc', 'en-US', 'Crispy pastry shells filled with sweet ricotta cheese'],
            ['item_cannoli_desc', 'bn-BD', 'মিষ্টি রিকোটা পনির দিয়ে পূর্ণ কুরকুরে পেস্ট্রি শেল'],
            
            ['item_gelato', 'en-US', 'Gelato'],
            ['item_gelato', 'bn-BD', 'জেলাতো'],
            ['item_gelato_desc', 'en-US', 'Italian ice cream with intense flavor and creamy texture'],
            ['item_gelato_desc', 'bn-BD', 'তীব্র স্বাদ এবং ক্রিমি টেক্সচার সহ ইতালীয় আইসক্রিম'],
            
            ['item_chicken_marsala', 'en-US', 'Chicken Marsala'],
            ['item_chicken_marsala', 'bn-BD', 'চিকেন মারসালা'],
            ['item_chicken_marsala_desc', 'en-US', 'Chicken cutlets in a rich Marsala wine and mushroom sauce'],
            ['item_chicken_marsala_desc', 'bn-BD', 'সমৃদ্ধ মারসালা ওয়াইন এবং মাশরুম সসে চিকেন কাটলেট'],
            
            ['item_veal_scaloppine', 'en-US', 'Veal Scaloppine'],
            ['item_veal_scaloppine', 'bn-BD', 'ভিল স্কালোপিন'],
            ['item_veal_scaloppine_desc', 'en-US', 'Thin veal cutlets sautéed in lemon and white wine sauce'],
            ['item_veal_scaloppine_desc', 'bn-BD', 'লেবু এবং সাদা ওয়াইন সসে সটেড পাতলা গরুর মাংসের কাটলেট'],
            
            // Item translations - Japanese Food
            ['item_california_roll', 'en-US', 'California Roll'],
            ['item_california_roll', 'bn-BD', 'ক্যালিফোর্নিয়া রোল'],
            ['item_california_roll_desc', 'en-US', 'Sushi roll with crab, avocado and cucumber'],
            ['item_california_roll_desc', 'bn-BD', 'কাঁকড়া, আভোকাডো এবং কাকুম্বার দিয়ে তৈরি সুশি রোল'],
            
            ['item_chicken_ramen', 'en-US', 'Chicken Ramen'],
            ['item_chicken_ramen', 'bn-BD', 'চিকেন রামেন'],
            ['item_chicken_ramen_desc', 'en-US', 'Noodle soup with chicken, vegetables and miso broth'],
            ['item_chicken_ramen_desc', 'bn-BD', 'চিকেন, সবজি এবং মিসো স্টক দিয়ে তৈরি নুডলস স্যুপ'],
            
            ['item_tempura', 'en-US', 'Vegetable Tempura'],
            ['item_tempura', 'bn-BD', 'সবজি টেম্পুরা'],
            ['item_tempura_desc', 'en-US', 'Lightly battered and fried vegetables'],
            ['item_tempura_desc', 'bn-BD', 'হালকা ব্যাটারে ডুবিয়ে ভাজা সবজি'],
            
            ['item_tepanyaki_chicken', 'en-US', 'Teppanyaki Chicken'],
            ['item_tepanyaki_chicken', 'bn-BD', 'তেপ্পানিয়াকি চিকেন'],
            ['item_tepanyaki_chicken_desc', 'en-US', 'Grilled chicken with vegetables on iron griddle'],
            ['item_tepanyaki_chicken_desc', 'bn-BD', 'লোহার গ্রিডলে সবজি সহকারে গ্রিল করা চিকেন'],
            
            // Additional Japanese Food Items
            ['item_salmon_roll', 'en-US', 'Salmon Roll'],
            ['item_salmon_roll', 'bn-BD', 'স্যামন রোল'],
            ['item_salmon_roll_desc', 'en-US', 'Fresh salmon with avocado and cucumber in nori'],
            ['item_salmon_roll_desc', 'bn-BD', 'নোরিতে আভোকাডো এবং কাকুম্বার সহ তাজা স্যামন'],
            
            ['item_tuna_roll', 'en-US', 'Tuna Roll'],
            ['item_tuna_roll', 'bn-BD', 'টুনা রোল'],
            ['item_tuna_roll_desc', 'en-US', 'Fresh tuna with spicy mayo and cucumber'],
            ['item_tuna_roll_desc', 'bn-BD', 'ঝাল মেয়োনেজ এবং কাকুম্বার সহ তাজা টুনা'],
            
            ['item_rainbow_roll', 'en-US', 'Rainbow Roll'],
            ['item_rainbow_roll', 'bn-BD', 'রেইনবো রোল'],
            ['item_rainbow_roll_desc', 'en-US', 'California roll topped with assorted sashimi'],
            ['item_rainbow_roll_desc', 'bn-BD', 'বিভিন্ন সাশিমি দিয়ে টপড ক্যালিফোর্নিয়া রোল'],
            
            ['item_dragon_roll', 'en-US', 'Dragon Roll'],
            ['item_dragon_roll', 'bn-BD', 'ড্রাগন রোল'],
            ['item_dragon_roll_desc', 'en-US', 'Eel and cucumber roll topped with avocado'],
            ['item_dragon_roll_desc', 'bn-BD', 'আভোকাডো দিয়ে টপড ইল এবং কাকুম্বার রোল'],
            
            ['item_sashimi_platter', 'en-US', 'Sashimi Platter'],
            ['item_sashimi_platter', 'bn-BD', 'সাশিমি প্ল্যাটার'],
            ['item_sashimi_platter_desc', 'en-US', 'Assorted fresh sashimi with wasabi and pickled ginger'],
            ['item_sashimi_platter_desc', 'bn-BD', 'ওয়াসাবি এবং আচার আদা সহ বিভিন্ন তাজা সাশিমি'],
            
            ['item_edamame', 'en-US', 'Edamame'],
            ['item_edamame', 'bn-BD', 'এডামেম'],
            ['item_edamame_desc', 'en-US', 'Steamed young soybeans with sea salt'],
            ['item_edamame_desc', 'bn-BD', 'সমুদ্রের লবণ সহ ভাপায় রান্না করা তরুণ সয়াবিন'],
            
            ['item_miso_soup', 'en-US', 'Miso Soup'],
            ['item_miso_soup', 'bn-BD', 'মিসো স্যুপ'],
            ['item_miso_soup_desc', 'en-US', 'Traditional soup with tofu, seaweed and green onions'],
            ['item_miso_soup_desc', 'bn-BD', 'টোফু, সামুদ্রিক শৈবাল এবং সবুজ পেঁয়াজ সহ ঐতিহ্যবাহী স্যুপ'],
            
            ['item_tonkotsu_ramen', 'en-US', 'Tonkotsu Ramen'],
            ['item_tonkotsu_ramen', 'bn-BD', 'টনকোটসু রামেন'],
            ['item_tonkotsu_ramen_desc', 'en-US', 'Rich pork bone broth with ramen noodles, chashu pork and egg'],
            ['item_tonkotsu_ramen_desc', 'bn-BD', 'রামেন নুডলস, চাশু পোর্ক এবং ডিম সহ সমৃদ্ধ শূকরের হাড়ের স্টক'],
            
            ['item_udon_noodles', 'en-US', 'Beef Udon'],
            ['item_udon_noodles', 'bn-BD', 'বিফ উডন'],
            ['item_udon_noodles_desc', 'en-US', 'Thick wheat noodles in savory broth with beef and vegetables'],
            ['item_udon_noodles_desc', 'bn-BD', 'গরুর মাংস এবং সবজি সহ সুস্বাদু স্টকে পুরু গমের নুডলস'],
            
            ['item_yakisoba', 'en-US', 'Yakisoba'],
            ['item_yakisoba', 'bn-BD', 'ইয়াকিসোবা'],
            ['item_yakisoba_desc', 'en-US', 'Stir-fried noodles with pork and vegetables in sweet sauce'],
            ['item_yakisoba_desc', 'bn-BD', 'মিষ্টি সসে শূকরের মাংস এবং সবজি দিয়ে ভাজা নুডলস'],
            
            ['item_shrimp_tempura', 'en-US', 'Shrimp Tempura'],
            ['item_shrimp_tempura', 'bn-BD', 'চিংড়ি টেম্পুরা'],
            ['item_shrimp_tempura_desc', 'en-US', 'Lightly battered and fried shrimp with dipping sauce'],
            ['item_shrimp_tempura_desc', 'bn-BD', 'ডিপিং সস সহ হালকা ব্যাটারে ডুবিয়ে ভাজা চিংড়ি'],
            
            ['item_chicken_katsu', 'en-US', 'Chicken Katsu'],
            ['item_chicken_katsu', 'bn-BD', 'চিকেন কাটসু'],
            ['item_chicken_katsu_desc', 'en-US', 'Breaded and fried chicken cutlet served with tonkatsu sauce'],
            ['item_chicken_katsu_desc', 'bn-BD', 'টনকাটসু সস সহ পরিবেশিত ব্রেডেড এবং ভাজা চিকেন কাটলেট'],
            
            ['item_beef_teriyaki', 'en-US', 'Beef Teriyaki'],
            ['item_beef_teriyaki', 'bn-BD', 'বিফ টেরিয়াকি'],
            ['item_beef_teriyaki_desc', 'en-US', 'Grilled beef glazed with sweet teriyaki sauce'],
            ['item_beef_teriyaki_desc', 'bn-BD', 'মিষ্টি টেরিয়াকি সস দিয়ে গ্লেজড গ্রিলড বিফ'],
            
            ['item_salmon_teriyaki', 'en-US', 'Salmon Teriyaki'],
            ['item_salmon_teriyaki', 'bn-BD', 'স্যামন টেরিয়াকি'],
            ['item_salmon_teriyaki_desc', 'en-US', 'Grilled salmon glazed with teriyaki sauce'],
            ['item_salmon_teriyaki_desc', 'bn-BD', 'টেরিয়াকি সস দিয়ে গ্লেজড গ্রিলড স্যামন'],
            
            ['item_tofu_steak', 'en-US', 'Tofu Steak'],
            ['item_tofu_steak', 'bn-BD', 'টোফু স্টেক'],
            ['item_tofu_steak_desc', 'en-US', 'Pan-fried tofu steak with ginger sauce'],
            ['item_tofu_steak_desc', 'bn-BD', 'আদা সস সহ প্যান-ফ্রাইড টোফু স্টেক'],
            
            ['item_chicken_bento', 'en-US', 'Chicken Bento'],
            ['item_chicken_bento', 'bn-BD', 'চিকেন বেন্টো'],
            ['item_chicken_bento_desc', 'en-US', 'Complete meal with grilled chicken, rice, vegetables and miso soup'],
            ['item_chicken_bento_desc', 'bn-BD', 'গ্রিলড চিকেন, ভাত, সবজি এবং মিসো স্যুপ সহ সম্পূর্ণ খাবার'],
            
            ['item_salmon_bento', 'en-US', 'Salmon Bento'],
            ['item_salmon_bento', 'bn-BD', 'স্যামন বেন্টো'],
            ['item_salmon_bento_desc', 'en-US', 'Complete meal with grilled salmon, rice, vegetables and miso soup'],
            ['item_salmon_bento_desc', 'bn-BD', 'গ্রিলড স্যামন, ভাত, সবজি এবং মিসো স্যুপ সহ সম্পূর্ণ খাবার'],
            
            ['item_vegetable_bento', 'en-US', 'Vegetable Bento'],
            ['item_vegetable_bento', 'bn-BD', 'ভেজিটেবল বেন্টো'],
            ['item_vegetable_bento_desc', 'en-US', 'Complete meal with assorted vegetables, tofu, rice and miso soup'],
            ['item_vegetable_bento_desc', 'bn-BD', 'বিভিন্ন সবজি, টোফু, ভাত এবং মিসো স্যুপ সহ সম্পূর্ণ খাবার'],
            
            ['item_mochi_ice_cream', 'en-US', 'Mochi Ice Cream'],
            ['item_mochi_ice_cream', 'bn-BD', 'মোচি আইসক্রিম'],
            ['item_mochi_ice_cream_desc', 'en-US', 'Sweet rice cake filled with ice cream'],
            ['item_mochi_ice_cream_desc', 'bn-BD', 'আইসক্রিম দিয়ে পূর্ণ মিষ্টি চালের কেক'],
            
            ['item_dorayaki', 'en-US', 'Dorayaki'],
            ['item_dorayaki', 'bn-BD', 'ডোরায়াকি'],
            ['item_dorayaki_desc', 'en-US', 'Sweet pancake sandwich with red bean paste filling'],
            ['item_dorayaki_desc', 'bn-BD', 'লাল মটর পেস্ট দিয়ে পূর্ণ মিষ্টি প্যানকেক স্যান্ডউইচ'],
            
            ['item_matcha_cake', 'en-US', 'Matcha Cake'],
            ['item_matcha_cake', 'bn-BD', 'মাচা কেক'],
            ['item_matcha_cake_desc', 'en-US', 'Green tea flavored sponge cake with red bean filling'],
            ['item_matcha_cake_desc', 'bn-BD', 'লাল মটর পূরণ সহ সবুজ চা স্বাদযুক্ত স্পঞ্জ কেক'],
            
            // Beverage translations
            ['item_coke', 'en-US', 'Coca-Cola'],
            ['item_coke', 'bn-BD', 'কোকা-কোলা'],
            ['item_coke_desc', 'en-US', 'Refreshing carbonated soft drink'],
            ['item_coke_desc', 'bn-BD', 'সতেজ কার্বনেটেড সফট ড্রিংক'],
            
            ['item_mango_lassi', 'en-US', 'Mango Lassi'],
            ['item_mango_lassi', 'bn-BD', 'আমের লস্যি'],
            ['item_mango_lassi_desc', 'en-US', 'Sweet mango yogurt drink'],
            ['item_mango_lassi_desc', 'bn-BD', 'মিষ্টি আমের দই পানীয়'],
            
            ['item_tea', 'en-US', 'Deshi Tea'],
            ['item_tea', 'bn-BD', 'দেশি চা'],
            ['item_tea_desc', 'en-US', 'Traditional Bangladeshi milk tea'],
            ['item_tea_desc', 'bn-BD', 'ঐতিহ্যবাহী বাংলাদেশী দুধ চা'],
            
            ['item_borhani', 'en-US', 'Borhani'],
            ['item_borhani', 'bn-BD', 'বোরহানী'],
            ['item_borhani_desc', 'en-US', 'Spicy yogurt drink with mint'],
            ['item_borhani_desc', 'bn-BD', 'পুদিনা দিয়ে ঝাল দই পানীয়'],
            
            // Branch translations
            ['branch_fast_food_name', 'en-US', 'Luna Dine Fast Food'],
            ['branch_fast_food_name', 'bn-BD', 'লুনা ডাইন ফাস্ট ফুড'],
            
            ['branch_desi_name', 'en-US', 'Luna Dine Desi Food'],
            ['branch_desi_name', 'bn-BD', 'লুনা ডাইন দেশি খাবার'],
            
            ['branch_chinese_name', 'en-US', 'Luna Dine Chinese Food'],
            ['branch_chinese_name', 'bn-BD', 'লুনা ডাইন চাইনিজ খাবার'],
            
            ['branch_indian_name', 'en-US', 'Luna Dine Indian Food'],
            ['branch_indian_name', 'bn-BD', 'লুনা ডাইন ভারতীয় খাবার'],
            
            ['branch_italian_name', 'en-US', 'Luna Dine Italian Food'],
            ['branch_italian_name', 'bn-BD', 'লুনা ডাইন ইতালিয়ান খাবার'],
            
            ['branch_japanese_name', 'en-US', 'Luna Dine Japanese Food'],
            ['branch_japanese_name', 'bn-BD', 'লুনা ডাইন জাপানি খাবার'],
            
            // Promotion translations
            ['promo_weekend_discount', 'en-US', 'Weekend Special Discount'],
            ['promo_weekend_discount', 'bn-BD', 'সাপ্তাহিক বিশেষ ছাড়'],
            ['promo_weekend_discount_desc', 'en-US', '15% off on all items during weekends'],
            ['promo_weekend_discount_desc', 'bn-BD', 'সাপ্তাহিক ছুটিতে সমস্ত আইটেমে 15% ছাড়'],
            
            ['promo_first_order', 'en-US', 'First Order Discount'],
            ['promo_first_order', 'bn-BD', 'প্রথম অর্ডার ছাড়'],
            ['promo_first_order_desc', 'en-US', 'Get 100 Taka off on your first order'],
            ['promo_first_order_desc', 'bn-BD', 'আপনার প্রথম অর্ডারে 100 টাকা ছাড় পান'],
            
            // Banner translations
            ['banner_new_menu', 'en-US', 'Try Our New Menu'],
            ['banner_new_menu', 'bn-BD', 'আমাদের নতুন মেনু চেষ্টা করুন'],
            ['banner_new_menu_desc', 'en-US', 'Exciting new dishes now available'],
            ['banner_new_menu_desc', 'bn-BD', 'এখন উত্তেজনাপূর্ণ নতুন খাবার পাওয়া যাচ্ছে'],
            
            ['banner_weekend_special', 'en-US', 'Weekend Special'],
            ['banner_weekend_special', 'bn-BD', 'সাপ্তাহিক বিশেষ'],
            ['banner_weekend_special_desc', 'en-US', 'Special dishes every weekend'],
            ['banner_weekend_special_desc', 'bn-BD', 'প্রতি সপ্তাহান্তে বিশেষ খাবার'],
            
            ['banner_new_branch', 'en-US', 'New Branch Opening'],
            ['banner_new_branch', 'bn-BD', 'নতুন শাখা উদ্বোধন'],
            ['banner_new_branch_desc', 'en-US', 'Visit our newly opened branch'],
            ['banner_new_branch_desc', 'bn-BD', 'আমাদের নতুন উদ্বোধনকৃত শাখায় ভিজিট করুন'],
            
            ['banner_delivery_offer', 'en-US', 'Free Delivery Offer'],
            ['banner_delivery_offer', 'bn-BD', 'ফ্রি ডেলিভারি অফার'],
            ['banner_delivery_offer_desc', 'en-US', 'Free delivery on orders above 500 Taka'],
            ['banner_delivery_offer_desc', 'bn-BD', '500 টাকার উপরে অর্ডারে ফ্রি ডেলিভারি'],
        ];
        $stmt = $this->pdo->prepare("INSERT INTO Translations (translation_key, language_code, translation_text) VALUES (?, ?, ?)");
        foreach ($translations as $translation) {
            $stmt->execute($translation);
        }
        // Seed Roles
        $roles = [
            ['Super Admin', '["*"]', 'Full system access', 10],
            ['Owner', '["manage_branches", "view_all_reports", "manage_users"]', 'Business owner', 9],
            ['Branch Manager', '["edit_own_branch_menu", "view_branch_reports", "manage_branch_staff"]', 'Branch operations manager', 8],
            ['Chef', '["view_orders", "update_order_status", "manage_inventory"]', 'Kitchen staff', 5],
            ['Cashier', '["process_payments", "view_orders", "manage_tables"]', 'Payment processor', 4],
            ['Waiter', '["view_orders", "create_orders", "update_order_status"]', 'Service staff', 3]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO Roles (role_name, permissions, description, hierarchy_level) VALUES (?, ?, ?, ?)");
        foreach ($roles as $role) {
            $stmt->execute($role);
        }
        // Seed Menu Categories
        $categories = [
            // Fast Food Categories
            ['category_burgers', 'category_burgers_desc', 1, '/assets/images/categories/burgers.jpg', null, true],
            ['category_sandwiches', 'category_sandwiches_desc', 2, '/assets/images/categories/sandwiches.jpg', null, true],
            ['category_fries', 'category_fries_desc', 3, '/assets/images/categories/fries.jpg', null, true],
            ['category_hot_dogs', 'category_hot_dogs_desc', 4, '/assets/images/categories/hot-dogs.jpg', null, true],
            ['category_wraps', 'category_wraps_desc', 5, '/assets/images/categories/wraps.jpg', null, true],
            ['category_fast_food_desserts', 'category_fast_food_desserts_desc', 6, '/assets/images/categories/fastfood-desserts.jpg', null, true],
            ['category_beverages', 'category_beverages_desc', 7, '/assets/images/categories/beverages.jpg', null, true],
            
            // Desi Food Categories
            ['category_desi_rice', 'category_desi_rice_desc', 1, '/assets/images/categories/desi-rice.jpg', null, true],
            ['category_desi_curry', 'category_desi_curry_desc', 2, '/assets/images/categories/desi-curry.jpg', null, true],
            ['category_desi_kabab', 'category_desi_kabab_desc', 3, '/assets/images/categories/desi-kabab.jpg', null, true],
            ['category_desi_bread', 'category_desi_bread_desc', 4, '/assets/images/categories/desi-bread.jpg', null, true],
            ['category_desi_starters', 'category_desi_starters_desc', 5, '/assets/images/categories/desi-starters.jpg', null, true],
            ['category_desi_desserts', 'category_desi_desserts_desc', 6, '/assets/images/categories/desi-desserts.jpg', null, true],
            ['category_beverages', 'category_beverages_desc', 7, '/assets/images/categories/beverages.jpg', null, true],
            
            // Chinese Food Categories
            ['category_chinese_rice', 'category_chinese_rice_desc', 1, '/assets/images/categories/chinese-rice.jpg', null, true],
            ['category_chinese_noodles', 'category_chinese_noodles_desc', 2, '/assets/images/categories/chinese-noodles.jpg', null, true],
            ['category_chinese_soup', 'category_chinese_soup_desc', 3, '/assets/images/categories/chinese-soup.jpg', null, true],
            ['category_chinese_starters', 'category_chinese_starters_desc', 4, '/assets/images/categories/chinese-starters.jpg', null, true],
            ['category_chinese_main', 'category_chinese_main_desc', 5, '/assets/images/categories/chinese-main.jpg', null, true],
            ['category_chinese_desserts', 'category_chinese_desserts_desc', 6, '/assets/images/categories/chinese-desserts.jpg', null, true],
            ['category_beverages', 'category_beverages_desc', 7, '/assets/images/categories/beverages.jpg', null, true],
            
            // Indian Food Categories
            ['category_indian_bread', 'category_indian_bread_desc', 1, '/assets/images/categories/indian-bread.jpg', null, true],
            ['category_indian_curry', 'category_indian_curry_desc', 2, '/assets/images/categories/indian-curry.jpg', null, true],
            ['category_indian_tandoor', 'category_indian_tandoor_desc', 3, '/assets/images/categories/indian-tandoor.jpg', null, true],
            ['category_indian_rice', 'category_indian_rice_desc', 4, '/assets/images/categories/indian-rice.jpg', null, true],
            ['category_indian_starters', 'category_indian_starters_desc', 5, '/assets/images/categories/indian-starters.jpg', null, true],
            ['category_indian_desserts', 'category_indian_desserts_desc', 6, '/assets/images/categories/indian-desserts.jpg', null, true],
            ['category_beverages', 'category_beverages_desc', 7, '/assets/images/categories/beverages.jpg', null, true],
            
            // Italian Categories
            ['category_pizza', 'category_pizza_desc', 1, '/assets/images/categories/pizza.jpg', null, true],
            ['category_pasta', 'category_pasta_desc', 2, '/assets/images/categories/pasta.jpg', null, true],
            ['category_salad', 'category_salad_desc', 3, '/assets/images/categories/salad.jpg', null, true],
            ['category_italian_starters', 'category_italian_starters_desc', 4, '/assets/images/categories/italian-starters.jpg', null, true],
            ['category_italian_main', 'category_italian_main_desc', 5, '/assets/images/categories/italian-main.jpg', null, true],
            ['category_italian_desserts', 'category_italian_desserts_desc', 6, '/assets/images/categories/italian-desserts.jpg', null, true],
            ['category_beverages', 'category_beverages_desc', 7, '/assets/images/categories/beverages.jpg', null, true],
            
            // Japanese Categories
            ['category_sushi', 'category_sushi_desc', 1, '/assets/images/categories/sushi.jpg', null, true],
            ['category_ramen', 'category_ramen_desc', 2, '/assets/images/categories/ramen.jpg', null, true],
            ['category_tepanyaki', 'category_tepanyaki_desc', 3, '/assets/images/categories/tepanyaki.jpg', null, true],
            ['category_japanese_starters', 'category_japanese_starters_desc', 4, '/assets/images/categories/japanese-starters.jpg', null, true],
            ['category_japanese_bento', 'category_japanese_bento_desc', 5, '/assets/images/categories/japanese-bento.jpg', null, true],
            ['category_japanese_desserts', 'category_japanese_desserts_desc', 6, '/assets/images/categories/japanese-desserts.jpg', null, true],
            ['category_beverages', 'category_beverages_desc', 7, '/assets/images/categories/beverages.jpg', null, true]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO MenuCategories (name_translation_key, description_translation_key, display_order, image_url, parent_category_id, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $categoryIds = [];
        foreach ($categories as $category) {
            $stmt->execute($category);
            $categoryIds[] = $this->pdo->lastInsertId();
        }
        // Seed Menu Items
        $menuItems = [
            // Fast Food Items
            [$categoryIds[0], 'FF-BURGER-001', 'item_beef_burger', 'item_beef_burger_desc', '/assets/images/items/beef-burger.jpg', '["Beef", "Fast Food"]', '["Gluten"]', 15, '["Halal"]', true],
            [$categoryIds[0], 'FF-BURGER-002', 'item_chicken_burger', 'item_chicken_burger_desc', '/assets/images/items/chicken-burger.jpg', '["Chicken", "Fast Food"]', '["Gluten"]', 12, '["Halal"]', true],
            [$categoryIds[0], 'FF-BURGER-003', 'item_cheese_burger', 'item_cheese_burger_desc', '/assets/images/items/cheese-burger.jpg', '["Beef", "Cheese"]', '["Gluten", "Dairy"]', 20, '["Halal"]', true],
            [$categoryIds[0], 'FF-BURGER-004', 'item_fish_burger', 'item_fish_burger_desc', '/assets/images/items/fish-burger.jpg', '["Fish", "Fast Food"]', '["Gluten", "Seafood"]', 18, null, true],
            [$categoryIds[0], 'FF-BURGER-005', 'item_veggie_burger', 'item_veggie_burger_desc', '/assets/images/items/veggie-burger.jpg', '["Vegetarian", "Plant-based"]', '["Gluten"]', 16, '["Vegetarian"]', true],
            
            [$categoryIds[1], 'FF-SANDWICH-001', 'item_chicken_sandwich', 'item_chicken_sandwich_desc', '/assets/images/items/chicken-sandwich.jpg', '["Chicken", "Grilled"]', '["Gluten"]', 10, '["Halal"]', true],
            [$categoryIds[1], 'FF-SANDWICH-002', 'item_club_sandwich', 'item_club_sandwich_desc', '/assets/images/items/club-sandwich.jpg', '["Chicken", "Bacon"]', '["Gluten"]', 15, null, true],
            [$categoryIds[1], 'FF-SANDWICH-003', 'item_tuna_sandwich', 'item_tuna_sandwich_desc', '/assets/images/items/tuna-sandwich.jpg', '["Fish", "Sandwich"]', '["Gluten", "Seafood"]', 14, null, true],
            
            [$categoryIds[2], 'FF-FRIES-001', 'item_french_fries', 'item_french_fries_desc', '/assets/images/items/french-fries.jpg', '["Vegetarian", "Fast Food"]', null, 5, '["Vegetarian"]', true],
            [$categoryIds[2], 'FF-FRIES-002', 'item_crispy_chicken', 'item_crispy_chicken_desc', '/assets/images/items/crispy-chicken.jpg', '["Chicken", "Spicy"]', '["Gluten"]', 12, '["Halal"]', true],
            [$categoryIds[2], 'FF-FRIES-003', 'item_onion_rings', 'item_onion_rings_desc', '/assets/images/items/onion-rings.jpg', '["Vegetarian", "Appetizer"]', '["Gluten"]', 8, '["Vegetarian"]', true],
            [$categoryIds[2], 'FF-FRIES-004', 'item_mozzarella_sticks', 'item_mozzarella_sticks_desc', '/assets/images/items/mozzarella-sticks.jpg', '["Vegetarian", "Cheese"]', '["Gluten", "Dairy"]', 10, '["Vegetarian"]', true],
            
            [$categoryIds[3], 'FF-HOTDOG-001', 'item_hot_dog', 'item_hot_dog_desc', '/assets/images/items/hot-dog.jpg', '["Beef", "Fast Food"]', '["Gluten"]', 8, '["Halal"]', true],
            [$categoryIds[3], 'FF-HOTDOG-002', 'item_chili_dog', 'item_chili_dog_desc', '/assets/images/items/chili-dog.jpg', '["Beef", "Spicy"]', '["Gluten"]', 12, '["Halal"]', true],
            
            [$categoryIds[4], 'FF-WRAP-001', 'item_chicken_wrap', 'item_chicken_wrap_desc', '/assets/images/items/chicken-wrap.jpg', '["Chicken", "Wrap"]', '["Gluten"]', 14, '["Halal"]', true],
            [$categoryIds[4], 'FF-WRAP-002', 'item_veggie_wrap', 'item_veggie_wrap_desc', '/assets/images/items/veggie-wrap.jpg', '["Vegetarian", "Healthy"]', '["Gluten"]', 12, '["Vegetarian"]', true],
            
            [$categoryIds[5], 'FF-DESSERT-001', 'item_chocolate_shake', 'item_chocolate_shake_desc', '/assets/images/items/chocolate-shake.jpg', '["Dessert", "Chocolate"]', '["Dairy"]', 8, '["Vegetarian"]', true],
            [$categoryIds[5], 'FF-DESSERT-002', 'item_vanilla_shake', 'item_vanilla_shake_desc', '/assets/images/items/vanilla-shake.jpg', '["Dessert", "Vanilla"]', '["Dairy"]', 8, '["Vegetarian"]', true],
            
            // Desi Food Items
            [$categoryIds[7], 'DS-RICE-001', 'item_biryani', 'item_biryani_desc', '/assets/images/items/biryani.jpg', '["Mutton", "Rice Dish"]', '["Nuts"]', 30, '["Halal"]', true],
            [$categoryIds[7], 'DS-RICE-002', 'item_morog_polao', 'item_morog_polao_desc', '/assets/images/items/morog-polao.jpg', '["Chicken", "Rice Dish"]', '["Nuts"]', 28, '["Halal"]', true],
            [$categoryIds[7], 'DS-RICE-003', 'item_tehari', 'item_tehari_desc', '/assets/images/items/tehari.jpg', '["Beef", "Rice Dish"]', '["Gluten"]', 25, '["Halal"]', true],
            [$categoryIds[7], 'DS-RICE-004', 'item_kacchi_gosht', 'item_kacchi_gosht_desc', '/assets/images/items/kacchi-gosht.jpg', '["Beef", "Traditional"]', '["Gluten"]', 32, '["Halal"]', true],
            
            [$categoryIds[8], 'DS-CURRY-001', 'item_bhuna_khichuri', 'item_bhuna_khichuri_desc', '/assets/images/items/bhuna-khichuri.jpg', '["Beef", "Rice Dish"]', '["Gluten"]', 25, '["Halal"]', true],
            [$categoryIds[8], 'DS-CURRY-002', 'item_chicken_rezala', 'item_chicken_rezala_desc', '/assets/images/items/chicken-rezala.jpg', '["Chicken", "Mild"]', '["Dairy"]', 15, '["Halal"]', true],
            [$categoryIds[8], 'DS-CURRY-003', 'item_chicken_korma', 'item_chicken_korma_desc', '/assets/images/items/chicken-korma.jpg', '["Chicken", "Creamy"]', '["Dairy", "Nuts"]', 18, '["Halal"]', true],
            [$categoryIds[8], 'DS-CURRY-004', 'item_beef_bhuna', 'item_beef_bhuna_desc', '/assets/images/items/beef-bhuna.jpg', '["Beef", "Spicy"]', '["Gluten"]', 20, '["Halal"]', true],
            [$categoryIds[8], 'DS-CURRY-005', 'item_mutton_curry', 'item_mutton_curry_desc', '/assets/images/items/mutton-curry.jpg', '["Mutton", "Spicy"]', null, 24, '["Halal"]', true],
            
            [$categoryIds[9], 'DS-KABAB-001', 'item_seekh_kabab', 'item_seekh_kabab_desc', '/assets/images/items/seekh-kabab.jpg', '["Beef", "Grilled"]', null, 20, '["Halal"]', true],
            [$categoryIds[9], 'DS-KABAB-002', 'item_chapli_kabab', 'item_chapli_kabab_desc', '/assets/images/items/chapli-kabab.jpg', '["Beef", "Spicy"]', null, 18, '["Halal"]', true],
            [$categoryIds[9], 'DS-KABAB-003', 'item_shami_kabab', 'item_shami_kabab_desc', '/assets/images/items/shami-kabab.jpg', '["Beef", "Lentil"]', '["Gluten"]', 16, '["Halal"]', true],
            
            [$categoryIds[10], 'DS-BREAD-001', 'item_paratha', 'item_paratha_desc', '/assets/images/items/paratha.jpg', '["Bread", "Vegetarian"]', '["Gluten"]', 8, '["Vegetarian"]', true],
            [$categoryIds[10], 'DS-BREAD-002', 'item_luchi', 'item_luchi_desc', '/assets/images/items/luchi.jpg', '["Bread", "Vegetarian"]', '["Gluten"]', 6, '["Vegetarian"]', true],
            [$categoryIds[10], 'DS-BREAD-003', 'item_ruti', 'item_ruti_desc', '/assets/images/items/ruti.jpg', '["Bread", "Whole Wheat"]', '["Gluten"]', 5, '["Vegetarian"]', true],
            
            [$categoryIds[11], 'DS-STARTER-001', 'item_beguni', 'item_beguni_desc', '/assets/images/items/beguni.jpg', '["Vegetarian", "Appetizer"]', '["Gluten"]', 6, '["Vegetarian"]', true],
            [$categoryIds[11], 'DS-STARTER-002', 'item_chop', 'item_chop_desc', '/assets/images/items/chop.jpg', '["Vegetarian", "Potato"]', '["Gluten"]', 8, '["Vegetarian"]', true],
            [$categoryIds[11], 'DS-STARTER-003', 'item_singara', 'item_singara_desc', '/assets/images/items/singara.jpg', '["Vegetarian", "Snack"]', '["Gluten"]', 10, '["Vegetarian"]', true],
            [$categoryIds[11], 'DS-STARTER-004', 'item_fuchka', 'item_fuchka_desc', '/assets/images/items/fuchka.jpg', '["Vegetarian", "Street Food"]', null, 12, '["Vegetarian"]', true],
            
            [$categoryIds[12], 'DS-DESSERT-001', 'item_rasmalai', 'item_rasmalai_desc', '/assets/images/items/rasmalai.jpg', '["Dessert", "Dairy"]', '["Dairy"]', 15, '["Vegetarian"]', true],
            [$categoryIds[12], 'DS-DESSERT-002', 'item_mishti_doi', 'item_mishti_doi_desc', '/assets/images/items/mishti-doi.jpg', '["Dessert", "Yogurt"]', '["Dairy"]', 12, '["Vegetarian"]', true],
            [$categoryIds[12], 'DS-DESSERT-003', 'item_chomchom', 'item_chomchom_desc', '/assets/images/items/chomchom.jpg', '["Dessert", "Sweet"]', '["Dairy"]', 18, '["Vegetarian"]', true],
            [$categoryIds[12], 'DS-DESSERT-004', 'item_shondesh', 'item_shondesh_desc', '/assets/images/items/shondesh.jpg', '["Dessert", "Traditional"]', '["Dairy"]', 14, '["Vegetarian"]', true],
            
            // Chinese Food Items
            [$categoryIds[14], 'CN-RICE-001', 'item_chicken_fried_rice', 'item_chicken_fried_rice_desc', '/assets/images/items/chicken-fried-rice.jpg', '["Chicken", "Rice Dish"]', null, 15, '["Halal"]', true],
            [$categoryIds[14], 'CN-RICE-002', 'item_veg_fried_rice', 'item_veg_fried_rice_desc', '/assets/images/items/veg-fried-rice.jpg', '["Vegetarian", "Rice Dish"]', null, 12, '["Vegetarian"]', true],
            [$categoryIds[14], 'CN-RICE-003', 'item_szechuan_rice', 'item_szechuan_rice_desc', '/assets/images/items/szechuan-rice.jpg', '["Spicy", "Rice Dish"]', null, 16, '["Vegetarian"]', true],
            [$categoryIds[14], 'CN-RICE-004', 'item_egg_fried_rice', 'item_egg_fried_rice_desc', '/assets/images/items/egg-fried-rice.jpg', '["Egg", "Rice Dish"]', null, 13, null, true],
            
            [$categoryIds[15], 'CN-NOODLES-001', 'item_chowmein', 'item_chowmein_desc', '/assets/images/items/chowmein.jpg', '["Chicken", "Noodles"]', '["Gluten"]', 15, '["Halal"]', true],
            [$categoryIds[15], 'CN-NOODLES-002', 'item_hakka_noodles', 'item_hakka_noodles_desc', '/assets/images/items/hakka-noodles.jpg', '["Vegetarian", "Noodles"]', '["Gluten"]', 14, '["Vegetarian"]', true],
            [$categoryIds[15], 'CN-NOODLES-003', 'item_singapore_noodles', 'item_singapore_noodles_desc', '/assets/images/items/singapore-noodles.jpg', '["Vegetarian", "Noodles"]', '["Gluten"]', 16, '["Vegetarian"]', true],
            [$categoryIds[15], 'CN-NOODLES-004', 'item_schezwan_noodles', 'item_schezwan_noodles_desc', '/assets/images/items/schezwan-noodles.jpg', '["Spicy", "Noodles"]', '["Gluten"]', 17, '["Vegetarian"]', true],
            
            [$categoryIds[16], 'CN-SOUP-001', 'item_hot_and_sour_soup', 'item_hot_and_sour_soup_desc', '/assets/images/items/hot-sour-soup.jpg', '["Chicken", "Spicy"]', null, 10, '["Halal"]', true],
            [$categoryIds[16], 'CN-SOUP-002', 'item_wanton_soup', 'item_wanton_soup_desc', '/assets/images/items/wanton-soup.jpg', '["Pork", "Soup"]', '["Gluten"]', 12, null, true],
            [$categoryIds[16], 'CN-SOUP-003', 'item_sweetcorn_soup', 'item_sweetcorn_soup_desc', '/assets/images/items/sweetcorn-soup.jpg', '["Chicken", "Creamy"]', '["Dairy"]', 11, '["Halal"]', true],
            [$categoryIds[16], 'CN-SOUP-004', 'item_tomato_soup', 'item_tomato_soup_desc', '/assets/images/items/tomato-soup.jpg', '["Vegetarian", "Soup"]', null, 9, '["Vegetarian"]', true],
            
            [$categoryIds[17], 'CN-STARTER-001', 'item_spring_rolls', 'item_spring_rolls_desc', '/assets/images/items/spring-rolls.jpg', '["Vegetarian", "Appetizer"]', '["Gluten"]', 10, '["Vegetarian"]', true],
            [$categoryIds[17], 'CN-STARTER-002', 'item_dumplings', 'item_dumplings_desc', '/assets/images/items/dumplings.jpg', '["Pork", "Appetizer"]', '["Gluten"]', 14, null, true],
            
            [$categoryIds[18], 'CN-MAIN-001', 'item_chili_chicken', 'item_chili_chicken_desc', '/assets/images/items/chili-chicken.jpg', '["Chicken", "Spicy"]', null, 15, '["Halal"]', true],
            [$categoryIds[18], 'CN-MAIN-002', 'item_crispy_chili_chicken', 'item_crispy_chili_chicken_desc', '/assets/images/items/crispy-chicken.jpg', '["Chicken", "Spicy"]', '["Gluten"]', 16, '["Halal"]', true],
            [$categoryIds[18], 'CN-MAIN-003', 'item_garlic_chicken', 'item_garlic_chicken_desc', '/assets/images/items/garlic-chicken.jpg', '["Chicken", "Garlic"]', null, 14, '["Halal"]', true],
            [$categoryIds[18], 'CN-MAIN-004', 'item_manchurian', 'item_manchurian_desc', '/assets/images/items/manchurian.jpg', '["Vegetarian", "Cauliflower"]', null, 12, '["Vegetarian"]', true],
            [$categoryIds[18], 'CN-MAIN-005', 'item_paneer_chilli', 'item_paneer_chilli_desc', '/assets/images/items/paneer-chilli.jpg', '["Vegetarian", "Paneer"]', '["Dairy"]', 13, '["Vegetarian"]', true],
            
            [$categoryIds[19], 'CN-DESSERT-001', 'item_fried_ice_cream', 'item_fried_ice_cream_desc', '/assets/images/items/fried-ice-cream.jpg', '["Dessert", "Ice Cream"]', '["Dairy"]', 16, '["Vegetarian"]', true],
            [$categoryIds[19], 'CN-DESSERT-002', 'item_darsaan', 'item_darsaan_desc', '/assets/images/items/darsaan.jpg', '["Dessert", "Sweet"]', '["Gluten", "Dairy"]', 14, '["Vegetarian"]', true],
            
            // Indian Food Items
            [$categoryIds[21], 'IN-BREAD-001', 'item_naan', 'item_naan_desc', '/assets/images/items/naan.jpg', '["Vegetarian", "Bread"]', '["Gluten"]', 10, '["Vegetarian"]', true],
            [$categoryIds[21], 'IN-BREAD-002', 'item_tandoori_roti', 'item_tandoori_roti_desc', '/assets/images/items/tandoori-roti.jpg', '["Vegetarian", "Whole Wheat"]', '["Gluten"]', 8, '["Vegetarian"]', true],
            [$categoryIds[21], 'IN-BREAD-003', 'item_garlic_naan', 'item_garlic_naan_desc', '/assets/images/items/garlic-naan.jpg', '["Vegetarian", "Garlic"]', '["Gluten"]', 12, '["Vegetarian"]', true],
            [$categoryIds[21], 'IN-BREAD-004', 'item_lachha_paratha', 'item_lachha_paratha_desc', '/assets/images/items/lachha-paratha.jpg', '["Vegetarian", "Layered"]', '["Gluten"]', 14, '["Vegetarian"]', true],
            
            [$categoryIds[22], 'IN-CURRY-001', 'item_butter_chicken', 'item_butter_chicken_desc', '/assets/images/items/butter-chicken.jpg', '["Chicken", "Creamy"]', '["Dairy"]', 20, '["Halal"]', true],
            [$categoryIds[22], 'IN-CURRY-002', 'item_chicken_tikka_masala', 'item_chicken_tikka_masala_desc', '/assets/images/items/chicken-tikka-masala.jpg', '["Chicken", "Creamy"]', '["Dairy", "Nuts"]', 22, '["Halal"]', true],
            [$categoryIds[22], 'IN-CURRY-003', 'item_rogan_josh', 'item_rogan_josh_desc', '/assets/images/items/rogan-josh.jpg', '["Lamb", "Aromatic"]', null, 26, '["Halal"]', true],
            [$categoryIds[22], 'IN-CURRY-004', 'item_dal_makhani', 'item_dal_makhani_desc', '/assets/images/items/dal-makhani.jpg', '["Vegetarian", "Lentils"]', '["Dairy"]', 16, '["Vegetarian"]', true],
            [$categoryIds[22], 'IN-CURRY-005', 'item_chana_masala', 'item_chana_masala_desc', '/assets/images/items/chana-masala.jpg', '["Vegetarian", "Chickpeas"]', null, 14, '["Vegetarian"]', true],
            [$categoryIds[22], 'IN-CURRY-006', 'item_malai_kofta', 'item_malai_kofta_desc', '/assets/images/items/malai-kofta.jpg', '["Vegetarian", "Creamy"]', '["Dairy", "Nuts"]', 18, '["Vegetarian"]', true],
            [$categoryIds[22], 'IN-CURRY-007', 'item_aloo_gobi', 'item_aloo_gobi_desc', '/assets/images/items/aloo-gobi.jpg', '["Vegetarian", "Potato"]', null, 12, '["Vegetarian"]', true],
            [$categoryIds[22], 'IN-CURRY-008', 'item_baingan_bharta', 'item_baingan_bharta_desc', '/assets/images/items/baingan-bharta.jpg', '["Vegetarian", "Eggplant"]', null, 14, '["Vegetarian"]', true],
            [$categoryIds[22], 'IN-CURRY-009', 'item_palak_paneer', 'item_palak_paneer_desc', '/assets/images/items/palak-paneer.jpg', '["Vegetarian", "Spinach"]', '["Dairy"]', 18, '["Vegetarian"]', true],
            
            [$categoryIds[23], 'IN-TANDOOR-001', 'item_tandoori_chicken', 'item_tandoori_chicken_desc', '/assets/images/items/tandoori-chicken.jpg', '["Chicken", "Grilled"]', '["Dairy"]', 25, '["Halal"]', true],
            
            [$categoryIds[24], 'IN-RICE-001', 'item_pulao', 'item_pulao_desc', '/assets/images/items/pulao.jpg', '["Vegetarian", "Rice"]', null, 14, '["Vegetarian"]', true],
            [$categoryIds[24], 'IN-RICE-002', 'item_jeera_rice', 'item_jeera_rice_desc', '/assets/images/items/jeera-rice.jpg', '["Vegetarian", "Cumin"]', null, 12, '["Vegetarian"]', true],
            [$categoryIds[24], 'IN-RICE-003', 'item_biryani_rice', 'item_biryani_rice_desc', '/assets/images/items/biryani-rice.jpg', '["Meat", "Rice"]', '["Nuts"]', 28, '["Halal"]', true],
            
            [$categoryIds[25], 'IN-STARTER-001', 'item_samosa', 'item_samosa_desc', '/assets/images/items/samosa.jpg', '["Vegetarian", "Potato"]', '["Gluten"]', 10, '["Vegetarian"]', true],
            [$categoryIds[25], 'IN-STARTER-002', 'item_pakora', 'item_pakora_desc', '/assets/images/items/pakora.jpg', '["Vegetarian", "Gram Flour"]', null, 12, '["Vegetarian"]', true],
            [$categoryIds[25], 'IN-STARTER-003', 'item_papad', 'item_papad_desc', '/assets/images/items/papad.jpg', '["Vegetarian", "Lentil"]', null, 6, '["Vegetarian"]', true],
            
            [$categoryIds[26], 'IN-DESSERT-001', 'item_gulab_jamun', 'item_gulab_jamun_desc', '/assets/images/items/gulab-jamun.jpg', '["Dessert", "Dairy"]', '["Dairy"]', 16, '["Vegetarian"]', true],
            [$categoryIds[26], 'IN-DESSERT-002', 'item_kheer', 'item_kheer_desc', '/assets/images/items/kheer.jpg', '["Dessert", "Rice"]', '["Dairy", "Nuts"]', 14, '["Vegetarian"]', true],
            [$categoryIds[26], 'IN-DESSERT-003', 'item_jalebi', 'item_jalebi_desc', '/assets/images/items/jalebi.jpg', '["Dessert", "Sweet"]', '["Gluten"]', 12, '["Vegetarian"]', true],
            
            // Italian Items
            [$categoryIds[28], 'IT-PIZZA-001', 'item_margherita_pizza', 'item_margherita_pizza_desc', '/assets/images/items/margherita-pizza.jpg', '["Vegetarian", "Cheese"]', '["Gluten"]', 20, '["Vegetarian"]', true],
            [$categoryIds[28], 'IT-PIZZA-002', 'item_pepperoni_pizza', 'item_pepperoni_pizza_desc', '/assets/images/items/pepperoni-pizza.jpg', '["Pepperoni", "Meat"]', '["Gluten"]', 24, null, true],
            [$categoryIds[28], 'IT-PIZZA-003', 'item_hawaiian_pizza', 'item_hawaiian_pizza_desc', '/assets/images/items/hawaiian-pizza.jpg', '["Ham", "Pineapple"]', '["Gluten"]', 22, null, true],
            [$categoryIds[28], 'IT-PIZZA-004', 'item_veggie_pizza', 'item_veggie_pizza_desc', '/assets/images/items/veggie-pizza.jpg', '["Vegetarian", "Vegetables"]', '["Gluten"]', 22, '["Vegetarian"]', true],
            [$categoryIds[28], 'IT-PIZZA-005', 'item_bbq_chicken_pizza', 'item_bbq_chicken_pizza_desc', '/assets/images/items/bbq-chicken-pizza.jpg', '["Chicken", "BBQ"]', '["Gluten"]', 26, '["Halal"]', true],
            
            [$categoryIds[29], 'IT-PASTA-001', 'item_spaghetti_pasta', 'item_spaghetti_pasta_desc', '/assets/images/items/spaghetti-bolognese.jpg', '["Beef", "Pasta"]', '["Gluten"]', 20, '["Halal"]', true],
            [$categoryIds[29], 'IT-PASTA-002', 'item_fettuccine_alfredo', 'item_fettuccine_alfredo_desc', '/assets/images/items/fettuccine-alfredo.jpg', '["Vegetarian", "Creamy"]', '["Gluten", "Dairy"]', 18, '["Vegetarian"]', true],
            [$categoryIds[29], 'IT-PASTA-003', 'item_penne_arrabbiata', 'item_penne_arrabbiata_desc', '/assets/images/items/penne-arrabbiata.jpg', '["Vegetarian", "Spicy"]', '["Gluten"]', 16, '["Vegetarian"]', true],
            [$categoryIds[29], 'IT-PASTA-004', 'item_lasagna', 'item_lasagna_desc', '/assets/images/items/lasagna.jpg', '["Beef", "Cheese"]', '["Gluten", "Dairy"]', 24, '["Halal"]', true],
            [$categoryIds[29], 'IT-PASTA-005', 'item_ravioli', 'item_ravioli_desc', '/assets/images/items/ravioli.jpg', '["Vegetarian", "Cheese"]', '["Gluten", "Dairy"]', 20, '["Vegetarian"]', true],
            
            [$categoryIds[30], 'IT-SALAD-001', 'item_caesar_salad', 'item_caesar_salad_desc', '/assets/images/items/caesar-salad.jpg', '["Vegetarian", "Healthy"]', null, 10, '["Vegetarian"]', true],
            [$categoryIds[30], 'IT-SALAD-002', 'item_greek_salad', 'item_greek_salad_desc', '/assets/images/items/greek-salad.jpg', '["Vegetarian", "Feta"]', '["Dairy"]', 12, '["Vegetarian"]', true],
            [$categoryIds[30], 'IT-SALAD-003', 'item_caprese_salad', 'item_caprese_salad_desc', '/assets/images/items/caprese-salad.jpg', '["Vegetarian", "Mozzarella"]', '["Dairy"]', 14, '["Vegetarian"]', true],
            [$categoryIds[30], 'IT-SALAD-004', 'item_antonio_salad', 'item_antonio_salad_desc', '/assets/images/items/antonio-salad.jpg', '["Chicken", "Healthy"]', null, 16, '["Halal"]', true],
            
            [$categoryIds[31], 'IT-STARTER-001', 'item_bruschetta', 'item_bruschetta_desc', '/assets/images/items/bruschetta.jpg', '["Vegetarian", "Tomato"]', '["Gluten"]', 10, '["Vegetarian"]', true],
            [$categoryIds[31], 'IT-STARTER-002', 'item_calamari', 'item_calamari_desc', '/assets/images/items/calamari.jpg', '["Seafood", "Appetizer"]', '["Gluten", "Seafood"]', 14, null, true],
            [$categoryIds[31], 'IT-STARTER-003', 'item_arancini', 'item_arancini_desc', '/assets/images/items/arancini.jpg', '["Vegetarian", "Rice"]', '["Gluten", "Dairy"]', 12, '["Vegetarian"]', true],
            
            [$categoryIds[32], 'IT-MAIN-001', 'item_chicken_marsala', 'item_chicken_marsala_desc', '/assets/images/items/chicken-marsala.jpg', '["Chicken", "Mushroom"]', '["Dairy"]', 24, '["Halal"]', true],
            [$categoryIds[32], 'IT-MAIN-002', 'item_veal_scaloppine', 'item_veal_scaloppine_desc', '/assets/images/items/veal-scaloppine.jpg', '["Veal", "Lemon"]', '["Gluten", "Dairy"]', 26, null, true],
            
            [$categoryIds[33], 'IT-DESSERT-001', 'item_tiramisu', 'item_tiramisu_desc', '/assets/images/items/tiramisu.jpg', '["Dessert", "Coffee"]', '["Dairy"]', 18, '["Vegetarian"]', true],
            [$categoryIds[33], 'IT-DESSERT-002', 'item_panna_cotta', 'item_panna_cotta_desc', '/assets/images/items/panna-cotta.jpg', '["Dessert", "Vanilla"]', '["Dairy"]', 16, '["Vegetarian"]', true],
            [$categoryIds[33], 'IT-DESSERT-003', 'item_cannoli', 'item_cannoli_desc', '/assets/images/items/cannoli.jpg', '["Dessert", "Ricotta"]', '["Gluten", "Dairy"]', 16, '["Vegetarian"]', true],
            [$categoryIds[33], 'IT-DESSERT-004', 'item_gelato', 'item_gelato_desc', '/assets/images/items/gelato.jpg', '["Dessert", "Ice Cream"]', '["Dairy"]', 14, '["Vegetarian"]', true],
            
            // Japanese Items
            [$categoryIds[35], 'JP-SUSHI-001', 'item_california_roll', 'item_california_roll_desc', '/assets/images/items/california-roll.jpg', '["Seafood", "Rice"]', '["Gluten"]', 25, null, true],
            [$categoryIds[35], 'JP-SUSHI-002', 'item_salmon_roll', 'item_salmon_roll_desc', '/assets/images/items/salmon-roll.jpg', '["Seafood", "Salmon"]', '["Gluten"]', 28, null, true],
            [$categoryIds[35], 'JP-SUSHI-003', 'item_tuna_roll', 'item_tuna_roll_desc', '/assets/images/items/tuna-roll.jpg', '["Seafood", "Tuna"]', '["Gluten"]', 26, null, true],
            [$categoryIds[35], 'JP-SUSHI-004', 'item_rainbow_roll', 'item_rainbow_roll_desc', '/assets/images/items/rainbow-roll.jpg', '["Seafood", "Assorted"]', '["Gluten"]', 32, null, true],
            [$categoryIds[35], 'JP-SUSHI-005', 'item_dragon_roll', 'item_dragon_roll_desc', '/assets/images/items/dragon-roll.jpg', '["Seafood", "Eel"]', '["Gluten"]', 30, null, true],
            [$categoryIds[35], 'JP-SUSHI-006', 'item_sashimi_platter', 'item_sashimi_platter_desc', '/assets/images/items/sashimi-platter.jpg', '["Seafood", "Raw"]', null, 35, null, true],
            
            [$categoryIds[36], 'JP-RAMEN-001', 'item_chicken_ramen', 'item_chicken_ramen_desc', '/assets/images/items/chicken-ramen.jpg', '["Chicken", "Noodles"]', '["Gluten"]', 22, '["Halal"]', true],
            [$categoryIds[36], 'JP-RAMEN-002', 'item_tonkotsu_ramen', 'item_tonkotsu_ramen_desc', '/assets/images/items/tonkotsu-ramen.jpg', '["Pork", "Noodles"]', '["Gluten"]', 24, null, true],
            [$categoryIds[36], 'JP-RAMEN-003', 'item_udon_noodles', 'item_udon_noodles_desc', '/assets/images/items/udon-noodles.jpg', '["Beef", "Noodles"]', '["Gluten"]', 20, '["Halal"]', true],
            [$categoryIds[36], 'JP-RAMEN-004', 'item_yakisoba', 'item_yakisoba_desc', '/assets/images/items/yakisoba.jpg', '["Pork", "Noodles"]', '["Gluten"]', 18, null, true],
            
            [$categoryIds[37], 'JP-TEPPAN-001', 'item_tempura', 'item_tempura_desc', '/assets/images/items/tempura.jpg', '["Vegetarian", "Fried"]', '["Gluten"]', 18, '["Vegetarian"]', true],
            [$categoryIds[37], 'JP-TEPPAN-002', 'item_shrimp_tempura', 'item_shrimp_tempura_desc', '/assets/images/items/shrimp-tempura.jpg', '["Seafood", "Shrimp"]', '["Gluten", "Seafood"]', 22, null, true],
            [$categoryIds[37], 'JP-TEPPAN-003', 'item_chicken_katsu', 'item_chicken_katsu_desc', '/assets/images/items/chicken-katsu.jpg', '["Chicken", "Fried"]', '["Gluten"]', 20, '["Halal"]', true],
            [$categoryIds[37], 'JP-TEPPAN-004', 'item_tepanyaki_chicken', 'item_tepanyaki_chicken_desc', '/assets/images/items/tepanyaki-chicken.jpg', '["Chicken", "Grilled"]', null, 24, '["Halal"]', true],
            [$categoryIds[37], 'JP-TEPPAN-005', 'item_beef_teriyaki', 'item_beef_teriyaki_desc', '/assets/images/items/beef-teriyaki.jpg', '["Beef", "Teriyaki"]', null, 26, '["Halal"]', true],
            [$categoryIds[37], 'JP-TEPPAN-006', 'item_salmon_teriyaki', 'item_salmon_teriyaki_desc', '/assets/images/items/salmon-teriyaki.jpg', '["Seafood", "Salmon"]', null, 28, null, true],
            [$categoryIds[37], 'JP-TEPPAN-007', 'item_tofu_steak', 'item_tofu_steak_desc', '/assets/images/items/tofu-steak.jpg', '["Vegetarian", "Tofu"]', null, 16, '["Vegetarian"]', true],
            
            [$categoryIds[38], 'JP-STARTER-001', 'item_edamame', 'item_edamame_desc', '/assets/images/items/edamame.jpg', '["Vegetarian", "Soybeans"]', null, 8, '["Vegetarian"]', true],
            [$categoryIds[38], 'JP-STARTER-003', 'item_miso_soup', 'item_miso_soup_desc', '/assets/images/items/miso-soup.jpg', '["Vegetarian", "Soup"]', null, 10, '["Vegetarian"]', true],
            
            [$categoryIds[39], 'JP-BENTO-001', 'item_chicken_bento', 'item_chicken_bento_desc', '/assets/images/items/chicken-bento.jpg', '["Chicken", "Complete Meal"]', null, 28, '["Halal"]', true],
            [$categoryIds[39], 'JP-BENTO-002', 'item_salmon_bento', 'item_salmon_bento_desc', '/assets/images/items/salmon-bento.jpg', '["Seafood", "Complete Meal"]', null, 32, null, true],
            [$categoryIds[39], 'JP-BENTO-003', 'item_vegetable_bento', 'item_vegetable_bento_desc', '/assets/images/items/vegetable-bento.jpg', '["Vegetarian", "Complete Meal"]', null, 24, '["Vegetarian"]', true],
            
            [$categoryIds[40], 'JP-DESSERT-001', 'item_mochi_ice_cream', 'item_mochi_ice_cream_desc', '/assets/images/items/mochi-ice-cream.jpg', '["Dessert", "Rice Cake"]', '["Dairy"]', 14, '["Vegetarian"]', true],
            [$categoryIds[40], 'JP-DESSERT-002', 'item_dorayaki', 'item_dorayaki_desc', '/assets/images/items/dorayaki.jpg', '["Dessert", "Red Bean"]', '["Gluten"]', 12, '["Vegetarian"]', true],
            [$categoryIds[40], 'JP-DESSERT-003', 'item_matcha_cake', 'item_matcha_cake_desc', '/assets/images/items/matcha-cake.jpg', '["Dessert", "Green Tea"]', '["Dairy"]', 16, '["Vegetarian"]', true],
            
            // Beverage Items
            [$categoryIds[6], 'BV-COKE-001', 'item_coke', 'item_coke_desc', '/assets/images/items/coke.jpg', '["Cold", "Soft Drink"]', null, 2, null, true],
            [$categoryIds[13], 'BV-LASSI-001', 'item_mango_lassi', 'item_mango_lassi_desc', '/assets/images/items/mango-lassi.jpg', '["Cold", "Dairy"]', '["Dairy"]', 3, '["Vegetarian"]', true],
            [$categoryIds[20], 'BV-TEA-001', 'item_tea', 'item_tea_desc', '/assets/images/items/deshi-tea.jpg', '["Hot", "Tea"]', '["Dairy"]', 3, '["Vegetarian"]', true],
            [$categoryIds[27], 'BV-BORHANI-001', 'item_borhani', 'item_borhani_desc', '/assets/images/items/borhani.jpg', '["Cold", "Yogurt"]', '["Dairy"]', 3, '["Vegetarian"]', true],
            [$categoryIds[34], 'BV-COKE-002', 'item_coke', 'item_coke_desc', '/assets/images/items/coke.jpg', '["Cold", "Soft Drink"]', null, 2, null, true],
            [$categoryIds[41], 'BV-COKE-003', 'item_coke', 'item_coke_desc', '/assets/images/items/coke.jpg', '["Cold", "Soft Drink"]', null, 2, null, true]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO MenuItems_Global (category_id, sku, name_translation_key, description_translation_key, image_url, tags, allergen_information, preparation_time_minutes, dietary_restrictions, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $itemIds = [];
        foreach ($menuItems as $item) {
            $stmt->execute($item);
            $itemIds[] = $this->pdo->lastInsertId();
        }
        // Seed Branches
        $branches = [
            ['FastFood_01', 'House 15, Road 7, Dhanmondi, Dhaka', 23.746466, 90.376015, 'Asia/Dhaka', 'fastfood@restaurant.com', true],
            ['Desi_01', 'Plot 8, Block C, Gulshan-1, Dhaka', 23.781034, 90.414415, 'Asia/Dhaka', 'desi@restaurant.com', true],
            ['Chinese_01', 'Shop 205, Bashundhara City, Dhaka', 23.746466, 90.376015, 'Asia/Dhaka', 'chinese@restaurant.com', true],
            ['Indian_01', 'House 42, Road 11, Banani, Dhaka', 23.781034, 90.414415, 'Asia/Dhaka', 'indian@restaurant.com', true],
            ['Italian_01', 'Level 5, Jamuna Future Park, Dhaka', 23.746466, 90.376015, 'Asia/Dhaka', 'italian@restaurant.com', true],
            ['Japanese_01', 'House 7, Road 27, Old DOHS, Dhaka', 23.781034, 90.414415, 'Asia/Dhaka', 'japanese@restaurant.com', true]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO Branches (internal_name, address, latitude, longitude, timezone, contact_email, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $branchIds = [];
        foreach ($branches as $branch) {
            $stmt->execute($branch);
            $branchIds[] = $this->pdo->lastInsertId();
        }
        // Seed Branch Settings
        $branchSettings = [
            // Fast Food Branch
            [
                $branchIds[0], 
                'branch_fast_food_name', 
                '/assets/images/logos/fastfood-logo.jpg', 
                '/assets/images/covers/fastfood-cover.jpg',
                '+8801234567890', 
                15.00, 
                10.00, 
                '{"type": "fixed", "amount": 50.00}', 
                '{"monday": "10:00-23:00", "tuesday": "10:00-23:00", "wednesday": "10:00-23:00", "thursday": "10:00-23:00", "friday": "10:00-23:00", "saturday": "10:00-00:00", "sunday": "10:00-23:00"}',
                200.00,
                5.0
            ],
            // Desi Food Branch
            [
                $branchIds[1], 
                'branch_desi_name', 
                '/assets/images/logos/desi-logo.jpg', 
                '/assets/images/covers/desi-cover.jpg',
                '+8801987654321', 
                15.00, 
                10.00, 
                '{"type": "distance", "base_amount": 40.00, "per_km": 10.00}', 
                '{"monday": "11:00-23:00", "tuesday": "11:00-23:00", "wednesday": "11:00-23:00", "thursday": "11:00-23:00", "friday": "11:00-23:00", "saturday": "11:00-00:00", "sunday": "11:00-23:00"}',
                300.00,
                7.0
            ],
            // Chinese Food Branch
            [
                $branchIds[2], 
                'branch_chinese_name', 
                '/assets/images/logos/chinese-logo.jpg', 
                '/assets/images/covers/chinese-cover.jpg',
                '+8801122334455', 
                15.00, 
                10.00, 
                '{"type": "fixed", "amount": 60.00}', 
                '{"monday": "11:00-23:00", "tuesday": "11:00-23:00", "wednesday": "11:00-23:00", "thursday": "11:00-23:00", "friday": "11:00-23:00", "saturday": "11:00-00:00", "sunday": "11:00-23:00"}',
                250.00,
                5.0
            ],
            // Indian Food Branch
            [
                $branchIds[3], 
                'branch_indian_name', 
                '/assets/images/logos/indian-logo.jpg', 
                '/assets/images/covers/indian-cover.jpg',
                '+8801555666777', 
                15.00, 
                10.00, 
                '{"type": "distance", "base_amount": 40.00, "per_km": 10.00}', 
                '{"monday": "08:00-22:00", "tuesday": "08:00-22:00", "wednesday": "08:00-22:00", "thursday": "08:00-22:00", "friday": "08:00-22:00", "saturday": "08:00-23:00", "sunday": "08:00-22:00"}',
                200.00,
                4.0
            ],
            // Italian Branch
            [
                $branchIds[4], 
                'branch_italian_name', 
                '/assets/images/logos/italian-logo.jpg', 
                '/assets/images/covers/italian-cover.jpg',
                '+8801777888999', 
                15.00, 
                10.00, 
                '{"type": "fixed", "amount": 70.00}', 
                '{"monday": "12:00-23:00", "tuesday": "12:00-23:00", "wednesday": "12:00-23:00", "thursday": "12:00-23:00", "friday": "12:00-23:00", "saturday": "12:00-00:00", "sunday": "12:00-23:00"}',
                400.00,
                5.0
            ],
            // Japanese Branch
            [
                $branchIds[5], 
                'branch_japanese_name', 
                '/assets/images/logos/japanese-logo.jpg', 
                '/assets/images/covers/japanese-cover.jpg',
                '+8801888999000', 
                15.00, 
                10.00, 
                '{"type": "distance", "base_amount": 40.00, "per_km": 10.00}', 
                '{"monday": "10:00-23:00", "tuesday": "10:00-23:00", "wednesday": "10:00-23:00", "thursday": "10:00-23:00", "friday": "10:00-23:00", "saturday": "10:00-00:00", "sunday": "10:00-23:00"}',
                350.00,
                6.0
            ]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO BranchSettings (branch_id, display_name_translation_key, logo_url, cover_photo_url, phone_number, vat_percentage, service_charge_percentage, delivery_fee_config, operating_hours, minimum_order_amount, delivery_radius_km) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($branchSettings as $settings) {
            $stmt->execute($settings);
        }
        // Seed Tables
        $tables = [];
        foreach ($branchIds as $branchId) {
            for ($i = 1; $i <= 10; $i++) {
                $tableType = ($i <= 5) ? 'table' : (($i <= 8) ? 'booth' : 'outdoor');
                $capacity = ($i <= 5) ? 4 : (($i <= 8) ? 6 : 8);
                $tables[] = [
                    $branchId,
                    "Table $i",
                    "branch{$branchId}_table{$i}",
                    $capacity,
                    $tableType,
                    true, // is_active
                    ($tableType === 'outdoor') ? 'Garden area' : 'Main hall'
                ];
            }
        }
        $stmt = $this->pdo->prepare("INSERT INTO Tables (branch_id, table_identifier, qr_code_hash, capacity, table_type, is_active, location_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($tables as $table) {
            $stmt->execute($table);
        }
        // Seed Users
        $users = [
            // Super Admin
            [1, null, 'Admin User', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@restaurant.com', 'en-US', true],
            // Branch Managers
            [3, $branchIds[0], 'Fast Food Manager', 'fastfood_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager.fastfood@restaurant.com', 'en-US', true],
            [3, $branchIds[1], 'Desi Manager', 'desi_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager.desi@restaurant.com', 'en-US', true],
            [3, $branchIds[2], 'Chinese Manager', 'chinese_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager.chinese@restaurant.com', 'en-US', true],
            [3, $branchIds[3], 'Indian Manager', 'indian_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager.indian@restaurant.com', 'en-US', true],
            [3, $branchIds[4], 'Italian Manager', 'italian_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager.italian@restaurant.com', 'en-US', true],
            [3, $branchIds[5], 'Japanese Manager', 'japanese_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager.japanese@restaurant.com', 'en-US', true],
            // Chefs
            [4, $branchIds[0], 'Fast Food Chef', 'fastfood_chef', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chef.fastfood@restaurant.com', 'en-US', true],
            [4, $branchIds[1], 'Desi Chef', 'desi_chef', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chef.desi@restaurant.com', 'en-US', true],
            [4, $branchIds[2], 'Chinese Chef', 'chinese_chef', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chef.chinese@restaurant.com', 'en-US', true],
            [4, $branchIds[3], 'Indian Chef', 'indian_chef', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chef.indian@restaurant.com', 'en-US', true],
            [4, $branchIds[4], 'Italian Chef', 'italian_chef', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chef.italian@restaurant.com', 'en-US', true],
            [4, $branchIds[5], 'Japanese Chef', 'japanese_chef', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chef.japanese@restaurant.com', 'en-US', true],
            // Cashiers
            [5, $branchIds[0], 'Fast Food Cashier', 'fastfood_cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier.fastfood@restaurant.com', 'en-US', true],
            [5, $branchIds[1], 'Desi Cashier', 'desi_cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier.desi@restaurant.com', 'en-US', true],
            [5, $branchIds[2], 'Chinese Cashier', 'chinese_cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier.chinese@restaurant.com', 'en-US', true],
            [5, $branchIds[3], 'Indian Cashier', 'indian_cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier.indian@restaurant.com', 'en-US', true],
            [5, $branchIds[4], 'Italian Cashier', 'italian_cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier.italian@restaurant.com', 'en-US', true],
            [5, $branchIds[5], 'Japanese Cashier', 'japanese_cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier.japanese@restaurant.com', 'en-US', true],
            // Waiters
            [6, $branchIds[0], 'Fast Food Waiter 1', 'fastfood_waiter1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter1.fastfood@restaurant.com', 'en-US', true],
            [6, $branchIds[0], 'Fast Food Waiter 2', 'fastfood_waiter2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter2.fastfood@restaurant.com', 'en-US', true],
            [6, $branchIds[1], 'Desi Waiter 1', 'desi_waiter1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter1.desi@restaurant.com', 'en-US', true],
            [6, $branchIds[1], 'Desi Waiter 2', 'desi_waiter2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter2.desi@restaurant.com', 'en-US', true],
            [6, $branchIds[2], 'Chinese Waiter 1', 'chinese_waiter1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter1.chinese@restaurant.com', 'en-US', true],
            [6, $branchIds[2], 'Chinese Waiter 2', 'chinese_waiter2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter2.chinese@restaurant.com', 'en-US', true],
            [6, $branchIds[3], 'Indian Waiter 1', 'indian_waiter1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter1.indian@restaurant.com', 'en-US', true],
            [6, $branchIds[3], 'Indian Waiter 2', 'indian_waiter2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter2.indian@restaurant.com', 'en-US', true],
            [6, $branchIds[4], 'Italian Waiter 1', 'italian_waiter1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter1.italian@restaurant.com', 'en-US', true],
            [6, $branchIds[4], 'Italian Waiter 2', 'italian_waiter2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter2.italian@restaurant.com', 'en-US', true],
            [6, $branchIds[5], 'Japanese Waiter 1', 'japanese_waiter1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter1.japanese@restaurant.com', 'en-US', true],
            [6, $branchIds[5], 'Japanese Waiter 2', 'japanese_waiter2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter2.japanese@restaurant.com', 'en-US', true]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO Users (role_id, branch_id, full_name, username, password_hash, email, preferred_language, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $userIds = [];
        foreach ($users as $user) {
            $stmt->execute($user);
            $userIds[] = $this->pdo->lastInsertId();
        }
        // Seed Branch Menu
        $branchMenuItems = [];
        
        // Define customization options for each menu item
        $customizationOptions = [
            // Beef Burger (Fast Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "cheese_group",
                        "name" => [
                            "en-US" => "Cheese",
                            "bn-BD" => "পনির"
                        ],
                        "type" => "checkbox",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "cheddar",
                                "name" => [
                                    "en-US" => "Cheddar Cheese",
                                    "bn-BD" => "চেডার পনির"
                                ],
                                "price_adjustment" => 20
                            ],
                            [
                                "id" => "swiss",
                                "name" => [
                                    "en-US" => "Swiss Cheese",
                                    "bn-BD" => "সুইস পনির"
                                ],
                                "price_adjustment" => 20
                            ]
                        ]
                    ],
                    [
                        "id" => "extras_group",
                        "name" => [
                            "en-US" => "Extras",
                            "bn-BD" => "অতিরিক্ত"
                        ],
                        "type" => "checkbox",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "bacon",
                                "name" => [
                                    "en-US" => "Bacon",
                                    "bn-BD" => "বেকন"
                                ],
                                "price_adjustment" => 30
                            ],
                            [
                                "id" => "extra_patty",
                                "name" => [
                                    "en-US" => "Extra Patty",
                                    "bn-BD" => "অতিরিক্ত প্যাটি"
                                ],
                                "price_adjustment" => 50
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Chicken Burger (Fast Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "spice_level_group",
                        "name" => [
                            "en-US" => "Spice Level",
                            "bn-BD" => "ঝাল"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "mild",
                                "name" => [
                                    "en-US" => "Mild",
                                    "bn-BD" => "ঝালহীন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি ঝাল"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "hot",
                                "name" => [
                                    "en-US" => "Hot",
                                    "bn-BD" => "খুব ঝাল"
                                ],
                                "price_adjustment" => 0
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Chicken Sandwich (Fast Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "sauce_group",
                        "name" => [
                            "en-US" => "Sauce",
                            "bn-BD" => "সস"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "mayo",
                                "name" => [
                                    "en-US" => "Mayonnaise",
                                    "bn-BD" => "মেয়োনেজ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "garlic_mayo",
                                "name" => [
                                    "en-US" => "Garlic Mayo",
                                    "bn-BD" => "রসুন মেয়োনেজ"
                                ],
                                "price_adjustment" => 5
                            ],
                            [
                                "id" => "chili_sauce",
                                "name" => [
                                    "en-US" => "Chili Sauce",
                                    "bn-BD" => "মরিচের সস"
                                ],
                                "price_adjustment" => 5
                            ]
                        ]
                    ]
                ]
            ]),
            
            // French Fries (Fast Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "size_group",
                        "name" => [
                            "en-US" => "Size",
                            "bn-BD" => "আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "small",
                                "name" => [
                                    "en-US" => "Small",
                                    "bn-BD" => "ছোট"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি"
                                ],
                                "price_adjustment" => 10
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 20
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Kacchi Biryani (Desi Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "single",
                                "name" => [
                                    "en-US" => "Single",
                                    "bn-BD" => "এক জনের"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "double",
                                "name" => [
                                    "en-US" => "Double",
                                    "bn-BD" => "দুই জনের"
                                ],
                                "price_adjustment" => 100
                            ]
                        ]
                    ],
                    [
                        "id" => "extras_group",
                        "name" => [
                            "en-US" => "Extras",
                            "bn-BD" => "অতিরিক্ত"
                        ],
                        "type" => "checkbox",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "extra_meat",
                                "name" => [
                                    "en-US" => "Extra Meat",
                                    "bn-BD" => "অতিরিক্ত মাংস"
                                ],
                                "price_adjustment" => 80
                            ],
                            [
                                "id" => "borhani",
                                "name" => [
                                    "en-US" => "Borhani",
                                    "bn-BD" => "বোরহানী"
                                ],
                                "price_adjustment" => 30
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Bhuna Khichuri (Desi Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "spice_level_group",
                        "name" => [
                            "en-US" => "Spice Level",
                            "bn-BD" => "ঝাল"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "mild",
                                "name" => [
                                    "en-US" => "Mild",
                                    "bn-BD" => "ঝালহীন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি ঝাল"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "hot",
                                "name" => [
                                    "en-US" => "Hot",
                                    "bn-BD" => "খুব ঝাল"
                                ],
                                "price_adjustment" => 0
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Seekh Kebab (Desi Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "two_pieces",
                                "name" => [
                                    "en-US" => "2 Pieces",
                                    "bn-BD" => "২ পিস"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "four_pieces",
                                "name" => [
                                    "en-US" => "4 Pieces",
                                    "bn-BD" => "৪ পিস"
                                ],
                                "price_adjustment" => 40
                            ],
                            [
                                "id" => "six_pieces",
                                "name" => [
                                    "en-US" => "6 Pieces",
                                    "bn-BD" => "৬ পিস"
                                ],
                                "price_adjustment" => 80
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Chicken Rezala (Desi Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "regular",
                                "name" => [
                                    "en-US" => "Regular",
                                    "bn-BD" => "সাধারণ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 40
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Chicken Fried Rice (Chinese Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "protein_group",
                        "name" => [
                            "en-US" => "Protein",
                            "bn-BD" => "প্রোটিন"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "chicken",
                                "name" => [
                                    "en-US" => "Chicken",
                                    "bn-BD" => "চিকেন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "beef",
                                "name" => [
                                    "en-US" => "Beef",
                                    "bn-BD" => "বিফ"
                                ],
                                "price_adjustment" => 20
                            ],
                            [
                                "id" => "shrimp",
                                "name" => [
                                    "en-US" => "Shrimp",
                                    "bn-BD" => "চিংড়ি"
                                ],
                                "price_adjustment" => 30
                            ]
                        ]
                    ],
                    [
                        "id" => "spice_level_group",
                        "name" => [
                            "en-US" => "Spice Level",
                            "bn-BD" => "ঝাল"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "mild",
                                "name" => [
                                    "en-US" => "Mild",
                                    "bn-BD" => "ঝালহীন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি ঝাল"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "hot",
                                "name" => [
                                    "en-US" => "Hot",
                                    "bn-BD" => "খুব ঝাল"
                                ],
                                "price_adjustment" => 0
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Chicken Chowmein (Chinese Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "spice_level_group",
                        "name" => [
                            "en-US" => "Spice Level",
                            "bn-BD" => "ঝাল"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "mild",
                                "name" => [
                                    "en-US" => "Mild",
                                    "bn-BD" => "ঝালহীন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি ঝাল"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "hot",
                                "name" => [
                                    "en-US" => "Hot",
                                    "bn-BD" => "খুব ঝাল"
                                ],
                                "price_adjustment" => 0
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Hot and Sour Soup (Chinese Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "small",
                                "name" => [
                                    "en-US" => "Small",
                                    "bn-BD" => "ছোট"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 20
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Chili Chicken (Chinese Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "spice_level_group",
                        "name" => [
                            "en-US" => "Spice Level",
                            "bn-BD" => "ঝাল"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "mild",
                                "name" => [
                                    "en-US" => "Mild",
                                    "bn-BD" => "ঝালহীন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি ঝাল"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "hot",
                                "name" => [
                                    "en-US" => "Hot",
                                    "bn-BD" => "খুব ঝাল"
                                ],
                                "price_adjustment" => 0
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Butter Chicken (Indian Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "regular",
                                "name" => [
                                    "en-US" => "Regular",
                                    "bn-BD" => "সাধারণ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 50
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Butter Naan (Indian Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "one_piece",
                                "name" => [
                                    "en-US" => "1 Piece",
                                    "bn-BD" => "১ পিস"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "two_pieces",
                                "name" => [
                                    "en-US" => "2 Pieces",
                                    "bn-BD" => "২ পিস"
                                ],
                                "price_adjustment" => 10
                            ],
                            [
                                "id" => "four_pieces",
                                "name" => [
                                    "en-US" => "4 Pieces",
                                    "bn-BD" => "৪ পিস"
                                ],
                                "price_adjustment" => 20
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Tandoori Chicken (Indian Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "quarter",
                                "name" => [
                                    "en-US" => "Quarter Chicken",
                                    "bn-BD" => "কোয়ার্টার চিকেন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "half",
                                "name" => [
                                    "en-US" => "Half Chicken",
                                    "bn-BD" => "হাফ চিকেন"
                                ],
                                "price_adjustment" => 50
                            ],
                            [
                                "id" => "full",
                                "name" => [
                                    "en-US" => "Full Chicken",
                                    "bn-BD" => "ফুল চিকেন"
                                ],
                                "price_adjustment" => 100
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Palak Paneer (Indian Food)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "regular",
                                "name" => [
                                    "en-US" => "Regular",
                                    "bn-BD" => "সাধারণ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 30
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Margherita Pizza (Italian)
            json_encode([
                "groups" => [
                    [
                        "id" => "size_group",
                        "name" => [
                            "en-US" => "Size",
                            "bn-BD" => "আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "small",
                                "name" => [
                                    "en-US" => "Small (8\")",
                                    "bn-BD" => "ছোট (৮ ইঞ্চি)"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium (10\")",
                                    "bn-BD" => "মাঝারি (১০ ইঞ্চি)"
                                ],
                                "price_adjustment" => 50
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large (12\")",
                                    "bn-BD" => "বড় (১২ ইঞ্চি)"
                                ],
                                "price_adjustment" => 100
                            ]
                        ]
                    ],
                    [
                        "id" => "crust_group",
                        "name" => [
                            "en-US" => "Crust Type",
                            "bn-BD" => "ক্রাস্টের ধরন"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "thin",
                                "name" => [
                                    "en-US" => "Thin Crust",
                                    "bn-BD" => "পাতলা ক্রাস্ট"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "thick",
                                "name" => [
                                    "en-US" => "Thick Crust",
                                    "bn-BD" => "মোটা ক্রাস্ট"
                                ],
                                "price_adjustment" => 20
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Spaghetti Bolognese (Italian)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "regular",
                                "name" => [
                                    "en-US" => "Regular",
                                    "bn-BD" => "সাধারণ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 40
                            ]
                        ]
                    ],
                    [
                        "id" => "cheese_group",
                        "name" => [
                            "en-US" => "Extra Cheese",
                            "bn-BD" => "অতিরিক্ত পনির"
                        ],
                        "type" => "checkbox",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "parmesan",
                                "name" => [
                                    "en-US" => "Parmesan",
                                    "bn-BD" => "পারমেসান"
                                ],
                                "price_adjustment" => 20
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Caesar Salad (Italian)
            json_encode([
                "groups" => [
                    [
                        "id" => "protein_group",
                        "name" => [
                            "en-US" => "Add Protein",
                            "bn-BD" => "প্রোটিন যোগ করুন"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "none",
                                "name" => [
                                    "en-US" => "No Protein",
                                    "bn-BD" => "কোন প্রোটিন না"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "chicken",
                                "name" => [
                                    "en-US" => "Grilled Chicken",
                                    "bn-BD" => "গ্রিলড চিকেন"
                                ],
                                "price_adjustment" => 50
                            ],
                            [
                                "id" => "shrimp",
                                "name" => [
                                    "en-US" => "Shrimp",
                                    "bn-BD" => "চিংড়ি"
                                ],
                                "price_adjustment" => 70
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Garlic Bread (Italian)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "half",
                                "name" => [
                                    "en-US" => "Half Loaf",
                                    "bn-BD" => "অর্ধেক রুটি"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "full",
                                "name" => [
                                    "en-US" => "Full Loaf",
                                    "bn-BD" => "পূর্ণ রুটি"
                                ],
                                "price_adjustment" => 15
                            ]
                        ]
                    ]
                ]
            ]),
            
            // California Roll (Japanese)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "four_pieces",
                                "name" => [
                                    "en-US" => "4 Pieces",
                                    "bn-BD" => "৪ পিস"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "eight_pieces",
                                "name" => [
                                    "en-US" => "8 Pieces",
                                    "bn-BD" => "৮ পিস"
                                ],
                                "price_adjustment" => 25
                            ],
                            [
                                "id" => "twelve_pieces",
                                "name" => [
                                    "en-US" => "12 Pieces",
                                    "bn-BD" => "১২ পিস"
                                ],
                                "price_adjustment" => 50
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Chicken Ramen (Japanese)
            json_encode([
                "groups" => [
                    [
                        "id" => "spice_level_group",
                        "name" => [
                            "en-US" => "Spice Level",
                            "bn-BD" => "ঝাল"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "mild",
                                "name" => [
                                    "en-US" => "Mild",
                                    "bn-BD" => "ঝালহীন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি ঝাল"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "hot",
                                "name" => [
                                    "en-US" => "Hot",
                                    "bn-BD" => "খুব ঝাল"
                                ],
                                "price_adjustment" => 0
                            ]
                        ]
                    ],
                    [
                        "id" => "toppings_group",
                        "name" => [
                            "en-US" => "Extra Toppings",
                            "bn-BD" => "অতিরিক্ত টপিংস"
                        ],
                        "type" => "checkbox",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "egg",
                                "name" => [
                                    "en-US" => "Soft Boiled Egg",
                                    "bn-BD" => "সফট বয়েলড ডিম"
                                ],
                                "price_adjustment" => 15
                            ],
                            [
                                "id" => "extra_noodles",
                                "name" => [
                                    "en-US" => "Extra Noodles",
                                    "bn-BD" => "অতিরিক্ত নুডলস"
                                ],
                                "price_adjustment" => 20
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Vegetable Tempura (Japanese)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "small",
                                "name" => [
                                    "en-US" => "Small",
                                    "bn-BD" => "ছোট"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি"
                                ],
                                "price_adjustment" => 15
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 30
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Teppanyaki Chicken (Japanese)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Serving Size",
                            "bn-BD" => "পরিবেশনের আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "regular",
                                "name" => [
                                    "en-US" => "Regular",
                                    "bn-BD" => "সাধারণ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 40
                            ]
                        ]
                    ],
                    [
                        "id" => "sauce_group",
                        "name" => [
                            "en-US" => "Sauce",
                            "bn-BD" => "সস"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "teriyaki",
                                "name" => [
                                    "en-US" => "Teriyaki",
                                    "bn-BD" => "টেরিয়াকি"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "garlic",
                                "name" => [
                                    "en-US" => "Garlic Soy",
                                    "bn-BD" => "রসুন সয়া"
                                ],
                                "price_adjustment" => 5
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Coca-Cola (Beverage)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Size",
                            "bn-BD" => "আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "small",
                                "name" => [
                                    "en-US" => "Small (250ml)",
                                    "bn-BD" => "ছোট (২৫০মিলি)"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium (330ml)",
                                    "bn-BD" => "মাঝারি (৩৩০মিলি)"
                                ],
                                "price_adjustment" => 5
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large (500ml)",
                                    "bn-BD" => "বড় (৫০০মিলি)"
                                ],
                                "price_adjustment" => 10
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Mango Lassi (Beverage)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Size",
                            "bn-BD" => "আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "regular",
                                "name" => [
                                    "en-US" => "Regular (300ml)",
                                    "bn-BD" => "সাধারণ (৩০০মিলি)"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large (450ml)",
                                    "bn-BD" => "বড় (৪৫০মিলি)"
                                ],
                                "price_adjustment" => 15
                            ]
                        ]
                    ],
                    [
                        "id" => "sweetness_group",
                        "name" => [
                            "en-US" => "Sweetness",
                            "bn-BD" => "মিষ্টতা"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "less_sweet",
                                "name" => [
                                    "en-US" => "Less Sweet",
                                    "bn-BD" => "কম মিষ্টি"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "regular_sweet",
                                "name" => [
                                    "en-US" => "Regular Sweet",
                                    "bn-BD" => "সাধারণ মিষ্টি"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "extra_sweet",
                                "name" => [
                                    "en-US" => "Extra Sweet",
                                    "bn-BD" => "অতিরিক্ত মিষ্টি"
                                ],
                                "price_adjustment" => 0
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Deshi Tea (Beverage)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Size",
                            "bn-BD" => "আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "small",
                                "name" => [
                                    "en-US" => "Small",
                                    "bn-BD" => "ছোট"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি"
                                ],
                                "price_adjustment" => 5
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large",
                                    "bn-BD" => "বড়"
                                ],
                                "price_adjustment" => 10
                            ]
                        ]
                    ],
                    [
                        "id" => "milk_group",
                        "name" => [
                            "en-US" => "Milk",
                            "bn-BD" => "দুধ"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "less_milk",
                                "name" => [
                                    "en-US" => "Less Milk",
                                    "bn-BD" => "কম দুধ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "regular_milk",
                                "name" => [
                                    "en-US" => "Regular Milk",
                                    "bn-BD" => "সাধারণ দুধ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "extra_milk",
                                "name" => [
                                    "en-US" => "Extra Milk",
                                    "bn-BD" => "অতিরিক্ত দুধ"
                                ],
                                "price_adjustment" => 5
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Borhani (Beverage)
            json_encode([
                "groups" => [
                    [
                        "id" => "serving_size_group",
                        "name" => [
                            "en-US" => "Size",
                            "bn-BD" => "আকার"
                        ],
                        "type" => "radio",
                        "required" => true,
                        "options" => [
                            [
                                "id" => "small",
                                "name" => [
                                    "en-US" => "Small (250ml)",
                                    "bn-BD" => "ছোট (২৫০মিলি)"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium (350ml)",
                                    "bn-BD" => "মাঝারি (৩৫০মিলি)"
                                ],
                                "price_adjustment" => 10
                            ],
                            [
                                "id" => "large",
                                "name" => [
                                    "en-US" => "Large (500ml)",
                                    "bn-BD" => "বড় (৫০০মিলি)"
                                ],
                                "price_adjustment" => 20
                            ]
                        ]
                    ],
                    [
                        "id" => "spice_level_group",
                        "name" => [
                            "en-US" => "Spice Level",
                            "bn-BD" => "ঝাল"
                        ],
                        "type" => "radio",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "mild",
                                "name" => [
                                    "en-US" => "Mild",
                                    "bn-BD" => "ঝালহীন"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "medium",
                                "name" => [
                                    "en-US" => "Medium",
                                    "bn-BD" => "মাঝারি ঝাল"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "hot",
                                "name" => [
                                    "en-US" => "Hot",
                                    "bn-BD" => "খুব ঝাল"
                                ],
                                "price_adjustment" => 0
                            ]
                        ]
                    ]
                ]
            ])
        ];
        
        // Fast Food Branch Menu
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[0],
            180,
            true,
            true,
            null,
            null,
            null,
            1,
            -1,
            $customizationOptions[0]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[1],
            150,
            true,
            true,
            null,
            null,
            null,
            2,
            -1,
            $customizationOptions[1]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[2],
            200,
            true,
            false,
            null,
            null,
            null,
            3,
            -1,
            $customizationOptions[0]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[3],
            180,
            true,
            false,
            null,
            null,
            null,
            4,
            -1,
            $customizationOptions[1]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[4],
            160,
            true,
            false,
            null,
            null,
            null,
            5,
            -1,
            $customizationOptions[1]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[5],
            120,
            true,
            false,
            null,
            null,
            null,
            6,
            -1,
            $customizationOptions[2]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[6],
            150,
            true,
            false,
            null,
            null,
            null,
            7,
            -1,
            $customizationOptions[2]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[7],
            140,
            true,
            false,
            null,
            null,
            null,
            8,
            -1,
            $customizationOptions[2]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[8],
            60,
            true,
            false,
            null,
            null,
            null,
            9,
            -1,
            $customizationOptions[3]
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[9],
            120,
            true,
            false,
            null,
            null,
            null,
            10,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[10],
            80,
            true,
            false,
            null,
            null,
            null,
            11,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[11],
            100,
            true,
            false,
            null,
            null,
            null,
            12,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[12],
            80,
            true,
            false,
            null,
            null,
            null,
            13,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[13],
            120,
            true,
            false,
            null,
            null,
            null,
            14,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[14],
            140,
            true,
            false,
            null,
            null,
            null,
            15,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[15],
            80,
            true,
            false,
            null,
            null,
            null,
            16,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[16],
            80,
            true,
            false,
            null,
            null,
            null,
            17,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[0],
            $itemIds[17],
            40,
            true,
            false,
            null,
            null,
            null,
            18,
            -1,
            $customizationOptions[20]
        ];
        
        // Desi Food Branch Menu
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[18],
            350,
            true,
            true,
            null,
            null,
            null,
            1,
            -1,
            $customizationOptions[4]
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[19],
            280,
            true,
            true,
            null,
            null,
            null,
            2,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[20],
            250,
            true,
            false,
            null,
            null,
            null,
            3,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[21],
            320,
            true,
            false,
            null,
            null,
            null,
            4,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[22],
            280,
            true,
            false,
            null,
            null,
            null,
            5,
            -1,
            $customizationOptions[5]
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[23],
            220,
            true,
            false,
            null,
            null,
            null,
            6,
            -1,
            $customizationOptions[7]
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[24],
            180,
            true,
            false,
            null,
            null,
            null,
            7,
            -1,
            $customizationOptions[7]
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[25],
            200,
            true,
            false,
            null,
            null,
            null,
            8,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[26],
            240,
            true,
            false,
            null,
            null,
            null,
            9,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[27],
            220,
            true,
            false,
            null,
            null,
            null,
            10,
            -1,
            $customizationOptions[6]
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[28],
            180,
            true,
            false,
            null,
            null,
            null,
            11,
            -1,
            $customizationOptions[6]
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[29],
            160,
            true,
            false,
            null,
            null,
            null,
            12,
            -1,
            $customizationOptions[6]
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[30],
            80,
            true,
            false,
            null,
            null,
            null,
            13,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[31],
            60,
            true,
            false,
            null,
            null,
            null,
            14,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[32],
            50,
            true,
            false,
            null,
            null,
            null,
            15,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[33],
            120,
            true,
            false,
            null,
            null,
            null,
            16,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[34],
            150,
            true,
            false,
            null,
            null,
            null,
            17,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[35],
            180,
            true,
            false,
            null,
            null,
            null,
            18,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[36],
            140,
            true,
            false,
            null,
            null,
            null,
            19,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[1],
            $itemIds[37],
            30,
            true,
            false,
            null,
            null,
            null,
            20,
            -1,
            $customizationOptions[23]
        ];
        
        // Chinese Food Branch Menu
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[38],
            180,
            true,
            true,
            null,
            null,
            null,
            1,
            -1,
            $customizationOptions[8]
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[39],
            120,
            true,
            false,
            null,
            null,
            null,
            2,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[40],
            160,
            true,
            false,
            null,
            null,
            null,
            3,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[41],
            130,
            true,
            false,
            null,
            null,
            null,
            4,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[42],
            150,
            true,
            false,
            null,
            null,
            null,
            5,
            -1,
            $customizationOptions[9]
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[43],
            140,
            true,
            false,
            null,
            null,
            null,
            6,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[44],
            160,
            true,
            false,
            null,
            null,
            null,
            7,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[45],
            170,
            true,
            false,
            null,
            null,
            null,
            8,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[46],
            120,
            true,
            false,
            null,
            null,
            null,
            9,
            -1,
            $customizationOptions[10]
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[47],
            120,
            true,
            false,
            null,
            null,
            null,
            10,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[48],
            110,
            true,
            false,
            null,
            null,
            null,
            11,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[49],
            90,
            true,
            false,
            null,
            null,
            null,
            12,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[50],
            100,
            true,
            false,
            null,
            null,
            null,
            13,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[51],
            140,
            true,
            false,
            null,
            null,
            null,
            14,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[52],
            150,
            true,
            false,
            null,
            null,
            null,
            15,
            -1,
            $customizationOptions[11]
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[53],
            160,
            true,
            false,
            null,
            null,
            null,
            16,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[54],
            140,
            true,
            false,
            null,
            null,
            null,
            17,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[55],
            120,
            true,
            false,
            null,
            null,
            null,
            18,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[56],
            130,
            true,
            false,
            null,
            null,
            null,
            19,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[57],
            160,
            true,
            false,
            null,
            null,
            null,
            20,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[58],
            140,
            true,
            false,
            null,
            null,
            null,
            21,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[2],
            $itemIds[59],
            40,
            true,
            false,
            null,
            null,
            null,
            22,
            -1,
            $customizationOptions[20]
        ];
        
        // Indian Food Branch Menu
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[60],
            100,
            true,
            true,
            null,
            null,
            null,
            1,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[61],
            80,
            true,
            false,
            null,
            null,
            null,
            2,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[62],
            120,
            true,
            false,
            null,
            null,
            null,
            3,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[63],
            140,
            true,
            false,
            null,
            null,
            null,
            4,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[64],
            220,
            true,
            true,
            null,
            null,
            null,
            5,
            -1,
            $customizationOptions[12]
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[65],
            240,
            true,
            false,
            null,
            null,
            null,
            6,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[66],
            260,
            true,
            false,
            null,
            null,
            null,
            7,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[67],
            160,
            true,
            false,
            null,
            null,
            null,
            8,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[68],
            140,
            true,
            false,
            null,
            null,
            null,
            9,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[69],
            180,
            true,
            false,
            null,
            null,
            null,
            10,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[70],
            120,
            true,
            false,
            null,
            null,
            null,
            11,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[71],
            140,
            true,
            false,
            null,
            null,
            null,
            12,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[72],
            120,
            true,
            false,
            null,
            null,
            null,
            13,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[73],
            140,
            true,
            false,
            null,
            null,
            null,
            14,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[74],
            180,
            true,
            false,
            null,
            null,
            null,
            15,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[75],
            250,
            true,
            false,
            null,
            null,
            null,
            16,
            -1,
            $customizationOptions[14]
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[76],
            140,
            true,
            false,
            null,
            null,
            null,
            17,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[77],
            120,
            true,
            false,
            null,
            null,
            null,
            18,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[78],
            280,
            true,
            false,
            null,
            null,
            null,
            19,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[79],
            100,
            true,
            false,
            null,
            null,
            null,
            20,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[80],
            120,
            true,
            false,
            null,
            null,
            null,
            21,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[81],
            60,
            true,
            false,
            null,
            null,
            null,
            22,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[82],
            120,
            true,
            false,
            null,
            null,
            null,
            23,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[83],
            60,
            true,
            false,
            null,
            null,
            null,
            24,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[84],
            160,
            true,
            false,
            null,
            null,
            null,
            25,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[85],
            140,
            true,
            false,
            null,
            null,
            null,
            26,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[86],
            120,
            true,
            false,
            null,
            null,
            null,
            27,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[3],
            $itemIds[87],
            30,
            true,
            false,
            null,
            null,
            null,
            28,
            -1,
            $customizationOptions[21]
        ];
        
        // Italian Branch Menu
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[88],
            300,
            true,
            true,
            null,
            null,
            null,
            1,
            -1,
            $customizationOptions[16]
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[89],
            340,
            true,
            false,
            null,
            null,
            null,
            2,
            -1,
            $customizationOptions[16]
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[90],
            320,
            true,
            false,
            null,
            null,
            null,
            3,
            -1,
            $customizationOptions[16]
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[91],
            320,
            true,
            false,
            null,
            null,
            null,
            4,
            -1,
            $customizationOptions[16]
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[92],
            360,
            true,
            false,
            null,
            null,
            null,
            5,
            -1,
            $customizationOptions[16]
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[93],
            280,
            true,
            true,
            null,
            null,
            null,
            6,
            -1,
            $customizationOptions[17]
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[94],
            260,
            true,
            false,
            null,
            null,
            null,
            7,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[95],
            240,
            true,
            false,
            null,
            null,
            null,
            8,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[96],
            320,
            true,
            false,
            null,
            null,
            null,
            9,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[97],
            300,
            true,
            false,
            null,
            null,
            null,
            10,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[98],
            180,
            true,
            false,
            null,
            null,
            null,
            11,
            -1,
            $customizationOptions[18]
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[99],
            200,
            true,
            false,
            null,
            null,
            null,
            12,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[100],
            220,
            true,
            false,
            null,
            null,
            null,
            13,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[101],
            240,
            true,
            false,
            null,
            null,
            null,
            14,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[102],
            100,
            true,
            false,
            null,
            null,
            null,
            15,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[103],
            140,
            true,
            false,
            null,
            null,
            null,
            16,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[104],
            120,
            true,
            false,
            null,
            null,
            null,
            17,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[105],
            240,
            true,
            false,
            null,
            null,
            null,
            18,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[106],
            260,
            true,
            false,
            null,
            null,
            null,
            19,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[107],
            180,
            true,
            false,
            null,
            null,
            null,
            20,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[108],
            160,
            true,
            false,
            null,
            null,
            null,
            21,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[109],
            140,
            true,
            false,
            null,
            null,
            null,
            22,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[4],
            $itemIds[110],
            40,
            true,
            false,
            null,
            null,
            null,
            23,
            -1,
            $customizationOptions[20]
        ];
        
        // Japanese Branch Menu
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[111],
            280,
            true,
            true,
            null,
            null,
            null,
            1,
            -1,
            $customizationOptions[16]
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[112],
            320,
            true,
            false,
            null,
            null,
            null,
            2,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[113],
            300,
            true,
            false,
            null,
            null,
            null,
            3,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[114],
            350,
            true,
            false,
            null,
            null,
            null,
            4,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[115],
            320,
            true,
            false,
            null,
            null,
            null,
            5,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[116],
            400,
            true,
            false,
            null,
            null,
            null,
            6,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[117],
            250,
            true,
            true,
            null,
            null,
            null,
            7,
            -1,
            $customizationOptions[17]
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[118],
            280,
            true,
            false,
            null,
            null,
            null,
            8,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[119],
            240,
            true,
            false,
            null,
            null,
            null,
            9,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[120],
            220,
            true,
            false,
            null,
            null,
            null,
            10,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[121],
            200,
            true,
            true,
            null,
            null,
            null,
            11,
            -1,
            $customizationOptions[18]
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[122],
            240,
            true,
            false,
            null,
            null,
            null,
            12,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[123],
            220,
            true,
            false,
            null,
            null,
            null,
            13,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[124],
            260,
            true,
            false,
            null,
            null,
            null,
            14,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[125],
            240,
            true,
            false,
            null,
            null,
            null,
            15,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[126],
            280,
            true,
            false,
            null,
            null,
            null,
            16,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[127],
            260,
            true,
            false,
            null,
            null,
            null,
            17,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[128],
            160,
            true,
            false,
            null,
            null,
            null,
            18,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[129],
            80,
            true,
            false,
            null,
            null,
            null,
            19,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[130],
            120,
            true,
            false,
            null,
            null,
            null,
            20,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[131],
            100,
            true,
            false,
            null,
            null,
            null,
            21,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[132],
            320,
            true,
            false,
            null,
            null,
            null,
            22,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[133],
            360,
            true,
            false,
            null,
            null,
            null,
            23,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[134],
            280,
            true,
            false,
            null,
            null,
            null,
            24,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[135],
            140,
            true,
            false,
            null,
            null,
            null,
            25,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[136],
            120,
            true,
            false,
            null,
            null,
            null,
            26,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[137],
            160,
            true,
            false,
            null,
            null,
            null,
            27,
            -1,
            null
        ];
        $branchMenuItems[] = [
            $branchIds[5],
            $itemIds[138],
            40,
            true,
            false,
            null,
            null,
            null,
            28,
            -1,
            $customizationOptions[20]
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO BranchMenu (branch_id, item_id, price, is_available, is_featured, preparation_time_override, available_start_time, available_end_time, display_order, inventory_count, customization_options) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $branchMenuIds = [];
        foreach ($branchMenuItems as $menuItem) {
            $stmt->execute($menuItem);
            $branchMenuIds[] = $this->pdo->lastInsertId();
        }
        // Seed Promotions
        $promotions = [
            [
                'WEEKEND15', 
                'promo_weekend_discount', 
                'PERCENTAGE', 
                15.00, 
                500.00, 
                200.00, 
                100, 
                0, 
                date('Y-m-d H:i:s'), 
                date('Y-m-d H:i:s', strtotime('+30 days')), 
                true, 
                false, 
                true, 
                $userIds[1] // Created by Fast Food Manager
            ],
            [
                'FIRSTORDER', 
                'promo_first_order', 
                'FIXED_AMOUNT', 
                100.00, 
                300.00, 
                null, 
                50, 
                0, 
                date('Y-m-d H:i:s'), 
                date('Y-m-d H:i:s', strtotime('+60 days')), 
                true, 
                true, 
                true, 
                $userIds[2] // Created by Desi Manager
            ]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO Promotions (code, description_translation_key, type, value, min_order_value, max_discount_amount, usage_limit, usage_count, start_date, end_date, is_active, auto_apply, is_customer_visible, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $promoIds = [];
        foreach ($promotions as $promo) {
            $stmt->execute($promo);
            $promoIds[] = $this->pdo->lastInsertId();
        }
        // Seed Promotion Scopes
        // Weekend promotion applies to all branches and specific items
        $promotionBranches = [];
        foreach ($promoIds as $promoId) {
            foreach ($branchIds as $branchId) {
                $promotionBranches[] = [$promoId, $branchId];
            }
        }
        $stmt = $this->pdo->prepare("INSERT INTO Promotion_Branches (promo_id, branch_id) VALUES (?, ?)");
        foreach ($promotionBranches as $promoBranch) {
            $stmt->execute($promoBranch);
        }
        $promotionItems = [
            // Weekend promotion applies to main course items
            [$promoIds[0], $itemIds[18]], // Kacchi Biryani
            [$promoIds[0], $itemIds[19]], // Morog Polao
            [$promoIds[0], $itemIds[20]], // Tehari
            [$promoIds[0], $itemIds[21]], // Kacchi Gosht
            [$promoIds[0], $itemIds[22]], // Bhuna Khichuri
            [$promoIds[0], $itemIds[23]], // Chicken Rezala
            [$promoIds[0], $itemIds[24]], // Chicken Korma
            [$promoIds[0], $itemIds[25]], // Beef Bhuna
            [$promoIds[0], $itemIds[26]], // Mutton Curry
            [$promoIds[0], $itemIds[38]], // Chicken Fried Rice
            [$promoIds[0], $itemIds[40]], // Szechuan Fried Rice
            [$promoIds[0], $itemIds[42]], // Chicken Chowmein
            [$promoIds[0], $itemIds[52]], // Chili Chicken
            [$promoIds[0], $itemIds[53]], // Crispy Chicken
            [$promoIds[0], $itemIds[54]], // Garlic Chicken
            [$promoIds[0], $itemIds[64]], // Butter Chicken
            [$promoIds[0], $itemIds[65]], // Chicken Tikka Masala
            [$promoIds[0], $itemIds[66]], // Rogan Josh
            [$promoIds[0], $itemIds[75]], // Tandoori Chicken
            [$promoIds[0], $itemIds[88]], // Margherita Pizza
            [$promoIds[0], $itemIds[89]], // Pepperoni Pizza
            [$promoIds[0], $itemIds[90]], // Hawaiian Pizza
            [$promoIds[0], $itemIds[91]], // Veggie Pizza
            [$promoIds[0], $itemIds[92]], // BBQ Chicken Pizza
            [$promoIds[0], $itemIds[93]], // Spaghetti Bolognese
            [$promoIds[0], $itemIds[96]], // Lasagna
            [$promoIds[0], $itemIds[105]], // Chicken Marsala
            [$promoIds[0], $itemIds[106]], // Veal Scaloppine
            [$promoIds[0], $itemIds[111]], // California Roll
            [$promoIds[0], $itemIds[112]], // Salmon Roll
            [$promoIds[0], $itemIds[113]], // Tuna Roll
            [$promoIds[0], $itemIds[114]], // Rainbow Roll
            [$promoIds[0], $itemIds[115]], // Dragon Roll
            [$promoIds[0], $itemIds[117]], // Chicken Ramen
            [$promoIds[0], $itemIds[118]], // Tonkotsu Ramen
            [$promoIds[0], $itemIds[119]], // Beef Udon
            [$promoIds[0], $itemIds[125]], // Chicken Katsu
            [$promoIds[0], $itemIds[126]], // Teppanyaki Chicken
            [$promoIds[0], $itemIds[127]], // Beef Teriyaki
            [$promoIds[0], $itemIds[128]], // Salmon Teriyaki
            
            // First order promotion applies to all items
            [$promoIds[1], $itemIds[0]], // Beef Burger
            [$promoIds[1], $itemIds[1]], // Chicken Burger
            [$promoIds[1], $itemIds[2]], // Cheese Burger
            [$promoIds[1], $itemIds[3]], // Fish Burger
            [$promoIds[1], $itemIds[4]], // Veggie Burger
            [$promoIds[1], $itemIds[5]], // Chicken Sandwich
            [$promoIds[1], $itemIds[6]], // Club Sandwich
            [$promoIds[1], $itemIds[7]], // Tuna Sandwich
            [$promoIds[1], $itemIds[8]], // French Fries
            [$promoIds[1], $itemIds[9]], // Crispy Chicken
            [$promoIds[1], $itemIds[10]], // Onion Rings
            [$promoIds[1], $itemIds[11]], // Mozzarella Sticks
            [$promoIds[1], $itemIds[12]], // Hot Dog
            [$promoIds[1], $itemIds[13]], // Chili Dog
            [$promoIds[1], $itemIds[14]], // Chicken Caesar Wrap
            [$promoIds[1], $itemIds[15]], // Garden Veggie Wrap
            [$promoIds[1], $itemIds[16]], // Chocolate Shake
            [$promoIds[1], $itemIds[17]], // Vanilla Shake
            [$promoIds[1], $itemIds[18]], // Kacchi Biryani
            [$promoIds[1], $itemIds[19]], // Morog Polao
            [$promoIds[1], $itemIds[20]], // Tehari
            [$promoIds[1], $itemIds[21]], // Kacchi Gosht
            [$promoIds[1], $itemIds[22]], // Bhuna Khichuri
            [$promoIds[1], $itemIds[23]], // Chicken Rezala
            [$promoIds[1], $itemIds[24]], // Chicken Korma
            [$promoIds[1], $itemIds[25]], // Beef Bhuna
            [$promoIds[1], $itemIds[26]], // Mutton Curry
            [$promoIds[1], $itemIds[27]], // Seekh Kebab
            [$promoIds[1], $itemIds[28]], // Chapli Kebab
            [$promoIds[1], $itemIds[29]], // Shami Kebab
            [$promoIds[1], $itemIds[30]], // Paratha
            [$promoIds[1], $itemIds[31]], // Luchi
            [$promoIds[1], $itemIds[32]], // Ruti
            [$promoIds[1], $itemIds[33]], // Beguni
            [$promoIds[1], $itemIds[34]], // Aloor Chop
            [$promoIds[1], $itemIds[35]], // Singara
            [$promoIds[1], $itemIds[36]], // Fuchka
            [$promoIds[1], $itemIds[37]], // Rasmalai
            [$promoIds[1], $itemIds[38]], // Chicken Fried Rice
            [$promoIds[1], $itemIds[39]], // Veg Fried Rice
            [$promoIds[1], $itemIds[40]], // Szechuan Fried Rice
            [$promoIds[1], $itemIds[41]], // Egg Fried Rice
            [$promoIds[1], $itemIds[42]], // Chicken Chowmein
            [$promoIds[1], $itemIds[43]], // Hakka Noodles
            [$promoIds[1], $itemIds[44]], // Singapore Noodles
            [$promoIds[1], $itemIds[45]], // Schezwan Noodles
            [$promoIds[1], $itemIds[46]], // Hot and Sour Soup
            [$promoIds[1], $itemIds[47]], // Wonton Soup
            [$promoIds[1], $itemIds[48]], // Sweetcorn Soup
            [$promoIds[1], $itemIds[49]], // Tomato Soup
            [$promoIds[1], $itemIds[50]], // Spring Rolls
            [$promoIds[1], $itemIds[51]], // Dumplings
            [$promoIds[1], $itemIds[52]], // Chili Chicken
            [$promoIds[1], $itemIds[53]], // Crispy Chicken
            [$promoIds[1], $itemIds[54]], // Garlic Chicken
            [$promoIds[1], $itemIds[55]], // Manchurian
            [$promoIds[1], $itemIds[56]], // Paneer Chilli
            [$promoIds[1], $itemIds[57]], // Fried Ice Cream
            [$promoIds[1], $itemIds[58]], // Darsaan
            [$promoIds[1], $itemIds[60]], // Naan
            [$promoIds[1], $itemIds[61]], // Tandoori Roti
            [$promoIds[1], $itemIds[62]], // Garlic Naan
            [$promoIds[1], $itemIds[63]], // Lachha Paratha
            [$promoIds[1], $itemIds[64]], // Butter Chicken
            [$promoIds[1], $itemIds[65]], // Chicken Tikka Masala
            [$promoIds[1], $itemIds[66]], // Rogan Josh
            [$promoIds[1], $itemIds[67]], // Dal Makhani
            [$promoIds[1], $itemIds[68]], // Chana Masala
            [$promoIds[1], $itemIds[69]], // Malai Kofta
            [$promoIds[1], $itemIds[70]], // Aloo Gobi
            [$promoIds[1], $itemIds[71]], // Baingan Bharta
            [$promoIds[1], $itemIds[72]], // Palak Paneer
            [$promoIds[1], $itemIds[73]], // Pulao
            [$promoIds[1], $itemIds[74]], // Jeera Rice
            [$promoIds[1], $itemIds[75]], // Tandoori Chicken
            [$promoIds[1], $itemIds[76]], // Samosa
            [$promoIds[1], $itemIds[77]], // Pakora
            [$promoIds[1], $itemIds[78]], // Papad
            [$promoIds[1], $itemIds[79]], // Gulab Jamun
            [$promoIds[1], $itemIds[80]], // Kheer
            [$promoIds[1], $itemIds[81]], // Jalebi
            [$promoIds[1], $itemIds[82]], // Butter Naan
            [$promoIds[1], $itemIds[83]], // Tandoori Chicken
            [$promoIds[1], $itemIds[84]], // Palak Paneer
            [$promoIds[1], $itemIds[85]], // Biryani Rice
            [$promoIds[1], $itemIds[86]], // Samosa
            [$promoIds[1], $itemIds[87]], // Gulab Jamun
            [$promoIds[1], $itemIds[88]], // Margherita Pizza
            [$promoIds[1], $itemIds[89]], // Pepperoni Pizza
            [$promoIds[1], $itemIds[90]], // Hawaiian Pizza
            [$promoIds[1], $itemIds[91]], // Veggie Pizza
            [$promoIds[1], $itemIds[92]], // BBQ Chicken Pizza
            [$promoIds[1], $itemIds[93]], // Spaghetti Bolognese
            [$promoIds[1], $itemIds[94]], // Fettuccine Alfredo
            [$promoIds[1], $itemIds[95]], // Penne Arrabbiata
            [$promoIds[1], $itemIds[96]], // Lasagna
            [$promoIds[1], $itemIds[97]], // Ravioli
            [$promoIds[1], $itemIds[98]], // Caesar Salad
            [$promoIds[1], $itemIds[99]], // Greek Salad
            [$promoIds[1], $itemIds[100]], // Caprese Salad
            [$promoIds[1], $itemIds[101]], // Antonio Salad
            [$promoIds[1], $itemIds[102]], // Bruschetta
            [$promoIds[1], $itemIds[103]], // Calamari
            [$promoIds[1], $itemIds[104]], // Arancini
            [$promoIds[1], $itemIds[105]], // Chicken Marsala
            [$promoIds[1], $itemIds[106]], // Veal Scaloppine
            [$promoIds[1], $itemIds[107]], // Tiramisu
            [$promoIds[1], $itemIds[108]], // Panna Cotta
            [$promoIds[1], $itemIds[109]], // Cannoli
            [$promoIds[1], $itemIds[110]], // Gelato
            [$promoIds[1], $itemIds[111]], // California Roll
            [$promoIds[1], $itemIds[112]], // Salmon Roll
            [$promoIds[1], $itemIds[113]], // Tuna Roll
            [$promoIds[1], $itemIds[114]], // Rainbow Roll
            [$promoIds[1], $itemIds[115]], // Dragon Roll
            [$promoIds[1], $itemIds[116]], // Sashimi Platter
            [$promoIds[1], $itemIds[117]], // Chicken Ramen
            [$promoIds[1], $itemIds[118]], // Tonkotsu Ramen
            [$promoIds[1], $itemIds[119]], // Beef Udon
            [$promoIds[1], $itemIds[120]], // Yakisoba
            [$promoIds[1], $itemIds[121]], // Vegetable Tempura
            [$promoIds[1], $itemIds[122]], // Shrimp Tempura
            [$promoIds[1], $itemIds[123]], // Chicken Katsu
            [$promoIds[1], $itemIds[124]], // Teppanyaki Chicken
            [$promoIds[1], $itemIds[125]], // Beef Teriyaki
            [$promoIds[1], $itemIds[126]], // Salmon Teriyaki
            [$promoIds[1], $itemIds[127]], // Tofu Steak
            [$promoIds[1], $itemIds[128]], // Edamame
            [$promoIds[1], $itemIds[130]], // Miso Soup
            [$promoIds[1], $itemIds[131]], // Chicken Bento
            [$promoIds[1], $itemIds[132]], // Salmon Bento
            [$promoIds[1], $itemIds[133]], // Vegetable Bento
            [$promoIds[1], $itemIds[134]], // Mochi Ice Cream
            [$promoIds[1], $itemIds[135]], // Dorayaki
            [$promoIds[1], $itemIds[136]], // Matcha Cake
            [$promoIds[1], $itemIds[137]], // Mango Lassi
            [$promoIds[1], $itemIds[138]]  // Coca-Cola
        ];
        $stmt = $this->pdo->prepare("INSERT INTO Promotion_Items (promo_id, item_id) VALUES (?, ?)");
        foreach ($promotionItems as $promoItem) {
            $stmt->execute($promoItem);
        }
        // Seed Branch Banners
        $banners = [
            // Fast Food banners
            [
                $branchIds[0],
                'banner_new_menu',
                '/assets/images/banners/fastfood-new-menu.jpg',
                '/menu',
                '#FF5733',
                '#FFFFFF',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+15 days')),
                true, // is_active
                1
            ],
            [
                $branchIds[0],
                'banner_weekend_special',
                '/assets/images/banners/fastfood-weekend.jpg',
                '/promotions',
                '#33FF57',
                '#000000',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+7 days')),
                true, // is_active
                2
            ],
            
            // Desi Food banners
            [
                $branchIds[1],
                'banner_new_menu',
                '/assets/images/banners/desi-new-menu.jpg',
                '/menu',
                '#FF5733',
                '#FFFFFF',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+15 days')),
                true, // is_active
                1
            ],
            [
                $branchIds[1],
                'banner_weekend_special',
                '/assets/images/banners/desi-weekend.jpg',
                '/promotions',
                '#33FF57',
                '#000000',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+7 days')),
                true, // is_active
                2
            ],
            
            // Chinese Food banners
            [
                $branchIds[2],
                'banner_new_menu',
                '/assets/images/banners/chinese-new-menu.jpg',
                '/menu',
                '#FF5733',
                '#FFFFFF',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+15 days')),
                true, // is_active
                1
            ],
            [
                $branchIds[2],
                'banner_weekend_special',
                '/assets/images/banners/chinese-weekend.jpg',
                '/promotions',
                '#33FF57',
                '#000000',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+7 days')),
                true, // is_active
                2
            ],
            
            // Indian Food banners
            [
                $branchIds[3],
                'banner_new_menu',
                '/assets/images/banners/indian-new-menu.jpg',
                '/menu',
                '#FF5733',
                '#FFFFFF',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+15 days')),
                true, // is_active
                1
            ],
            [
                $branchIds[3],
                'banner_weekend_special',
                '/assets/images/banners/indian-weekend.jpg',
                '/promotions',
                '#33FF57',
                '#000000',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+7 days')),
                true, // is_active
                2
            ],
            
            // Italian banners
            [
                $branchIds[4],
                'banner_new_menu',
                '/assets/images/banners/italian-new-menu.jpg',
                '/menu',
                '#FF5733',
                '#FFFFFF',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+15 days')),
                true, // is_active
                1
            ],
            [
                $branchIds[4],
                'banner_weekend_special',
                '/assets/images/banners/italian-weekend.jpg',
                '/promotions',
                '#33FF57',
                '#000000',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+7 days')),
                true, // is_active
                2
            ],
            
            // Japanese banners
            [
                $branchIds[5],
                'banner_new_menu',
                '/assets/images/banners/japanese-new-menu.jpg',
                '/menu',
                '#FF5733',
                '#FFFFFF',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+15 days')),
                true, // is_active
                1
            ],
            [
                $branchIds[5],
                'banner_weekend_special',
                '/assets/images/banners/japanese-weekend.jpg',
                '/promotions',
                '#33FF57',
                '#000000',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+7 days')),
                true, // is_active
                2
            ]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO BranchBanners (branch_id, title_translation_key, image_url, target_url, background_color, text_color, banner_type, start_date, end_date, is_active, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($banners as $banner) {
            $stmt->execute($banner);
        }
        // Seed Orders
        $orders = [
            // Fast Food orders
            [
                'ORD-FF-1001',
                $branchIds[0],
                1, // Table 1
                $promoIds[0], // Weekend promotion
                null, // customer_id
                'dine-in',
                'completed',
                480.00, // items_subtotal
                72.00, // discount_amount
                408.00, // subtotal_after_discount
                40.80, // service_charge_amount
                61.20, // vat_amount
                50.00, // delivery_charge_amount
                560.00, // final_amount
                'paid',
                'card',
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[19], // staff_id (Fast Food Waiter 1)
                'Extra cheese please',
                'qr_code',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}',
                date('Y-m-d H:i:s', strtotime('+1 hour')) // pickup_time
            ],
            [
                'ORD-FF-1002',
                $branchIds[0],
                5, // Table 5
                null, // No promotion
                null, // customer_id
                'takeaway',
                'completed',
                350.00, // items_subtotal
                0.00, // discount_amount
                350.00, // subtotal_after_discount
                35.00, // service_charge_amount
                52.50, // vat_amount
                0.00, // delivery_charge_amount
                437.50, // final_amount
                'paid',
                'cash',
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[20], // staff_id (Fast Food Waiter 2)
                'No onions',
                'app',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}',
                date('Y-m-d H:i:s', strtotime('+45 minutes')) // pickup_time
            ],
            
            // Desi Food orders
            [
                'ORD-DS-1001',
                $branchIds[1],
                null, // No table (delivery)
                $promoIds[1], // First order promotion
                null, // customer_id
                'delivery',
                'completed',
                800.00, // items_subtotal
                100.00, // discount_amount
                700.00, // subtotal_after_discount
                70.00, // service_charge_amount
                105.00, // vat_amount
                70.00, // delivery_charge_amount
                945.00, // final_amount
                'paid',
                'mobile_payment',
                'House 25, Road 10, Gulshan-2, Dhaka',
                date('Y-m-d H:i:s', strtotime('+1 hour')),
                date('Y-m-d H:i:s', strtotime('+1 hour 30 minutes')),
                $userIds[21], // staff_id (Desi Waiter 1)
                'Call upon arrival',
                'website',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}',
                null // pickup_time (not applicable for delivery)
            ],
            [
                'ORD-DS-1002',
                $branchIds[1],
                3, // Table 3
                null, // No promotion
                null, // customer_id
                'dine-in',
                'preparing',
                1000.00, // items_subtotal
                0.00, // discount_amount
                1000.00, // subtotal_after_discount
                100.00, // service_charge_amount
                150.00, // vat_amount
                0.00, // delivery_charge_amount
                1250.00, // final_amount
                'unpaid',
                null,
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[22], // staff_id (Desi Waiter 2)
                'Birthday celebration',
                'qr_code',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}',
                null // pickup_time (not applicable for dine-in)
            ],
            
            // Chinese Food orders
            [
                'ORD-CN-1001',
                $branchIds[2],
                2, // Table 2
                $promoIds[0], // Weekend promotion
                null, // customer_id
                'dine-in',
                'completed',
                600.00, // items_subtotal
                90.00, // discount_amount
                510.00, // subtotal_after_discount
                51.00, // service_charge_amount
                76.50, // vat_amount
                0.00, // delivery_charge_amount
                637.50, // final_amount
                'paid',
                'card',
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[23], // staff_id (Chinese Waiter 1)
                'Extra spicy please',
                'qr_code',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}',
                null // pickup_time (not applicable for dine-in)
            ],
            
            // Indian Food orders
            [
                'ORD-IN-1001',
                $branchIds[3],
                null, // No table (takeaway)
                null, // No promotion
                null, // customer_id
                'takeaway',
                'completed',
                300.00, // items_subtotal
                0.00, // discount_amount
                300.00, // subtotal_after_discount
                30.00, // service_charge_amount
                45.00, // vat_amount
                0.00, // delivery_charge_amount
                375.00, // final_amount
                'paid',
                'cash',
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[25], // staff_id (Indian Waiter 1)
                'Less spicy',
                'app',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}',
                date('Y-m-d H:i:s', strtotime('+30 minutes')) // pickup_time
            ],
            
            // Italian orders
            [
                'ORD-IT-1001',
                $branchIds[4],
                4, // Table 4
                $promoIds[1], // First order promotion
                null, // customer_id
                'dine-in',
                'completed',
                700.00, // items_subtotal
                100.00, // discount_amount
                600.00, // subtotal_after_discount
                60.00, // service_charge_amount
                90.00, // vat_amount
                0.00, // delivery_charge_amount
                750.00, // final_amount
                'paid',
                'card',
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[27], // staff_id (Italian Waiter 1)
                'Anniversary dinner',
                'qr_code',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}',
                null // pickup_time (not applicable for dine-in)
            ],
            
            // Japanese orders
            [
                'ORD-JP-1001',
                $branchIds[5],
                null, // No table (takeaway)
                null, // No promotion
                null, // customer_id
                'takeaway',
                'completed',
                250.00, // items_subtotal
                0.00, // discount_amount
                250.00, // subtotal_after_discount
                25.00, // service_charge_amount
                37.50, // vat_amount
                0.00, // delivery_charge_amount
                312.50, // final_amount
                'paid',
                'cash',
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[29], // staff_id (Japanese Waiter 1)
                'Party order',
                'app',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}',
                date('Y-m-d H:i:s', strtotime('+45 minutes')) // pickup_time
            ]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO Orders (order_uid, branch_id, table_id, promo_id, customer_id, order_type, status, items_subtotal, discount_amount, subtotal_after_discount, service_charge_amount, vat_amount, delivery_charge_amount, final_amount, payment_status, payment_method, delivery_address, estimated_delivery_time, actual_delivery_time, staff_id, notes, order_source, applied_rates_snapshot, pickup_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $orderIds = [];
        foreach ($orders as $order) {
            $stmt->execute($order);
            $orderIds[] = $this->pdo->lastInsertId();
        }
        // Seed Order Items
        $orderItems = [
            // Order 1 (ORD-FF-1001)
            [$orderIds[0], $branchMenuIds[0], 2, 180.00, '{"cheese": ["cheddar"], "extras": ["bacon"]}', null, 'completed', null, null, 1, 360.00],
            [$orderIds[0], $branchMenuIds[9], 2, 60.00, '{"size": "large"}', null, 'completed', null, null, 2, 120.00],
            
            // Order 2 (ORD-FF-1002)
            [$orderIds[1], $branchMenuIds[1], 1, 150.00, '{"spice_level": "medium"}', null, 'completed', null, null, 1, 150.00],
            [$orderIds[1], $branchMenuIds[6], 1, 120.00, '{"sauce": "garlic_mayo"}', null, 'completed', null, null, 2, 120.00],
            [$orderIds[1], $branchMenuIds[17], 2, 40.00, '{"size": "medium"}', null, 'completed', null, null, 3, 80.00],
            
            // Order 3 (ORD-DS-1001)
            [$orderIds[2], $branchMenuIds[18], 1, 350.00, '{"serving_size": "double", "extras": ["extra_meat"]}', null, 'completed', null, null, 1, 350.00],
            [$orderIds[2], $branchMenuIds[23], 2, 220.00, '{"serving_size": "large"}', null, 'completed', null, null, 2, 440.00],
            [$orderIds[2], $branchMenuIds[37], 1, 30.00, '{}', null, 'completed', null, null, 3, 30.00],
            
            // Order 4 (ORD-DS-1002)
            [$orderIds[3], $branchMenuIds[18], 2, 350.00, '{"serving_size": "double"}', null, 'preparing', null, null, 1, 700.00],
            [$orderIds[3], $branchMenuIds[27], 1, 220.00, '{"serving_size": "six_pieces"}', null, 'pending', null, null, 2, 220.00],
            
            // Order 5 (ORD-CN-1001)
            [$orderIds[4], $branchMenuIds[38], 1, 180.00, '{"protein": "chicken", "spice_level": "hot"}', null, 'completed', null, null, 1, 180.00],
            [$orderIds[4], $branchMenuIds[42], 1, 150.00, '{"spice_level": "medium"}', null, 'completed', null, null, 2, 150.00],
            [$orderIds[4], $branchMenuIds[46], 1, 120.00, '{"serving_size": "large"}', null, 'completed', null, null, 3, 120.00],
            [$orderIds[4], $branchMenuIds[59], 1, 40.00, '{"size": "medium"}', null, 'completed', null, null, 4, 40.00],
            
            // Order 6 (ORD-IN-1001)
            [$orderIds[5], $branchMenuIds[64], 2, 220.00, '{"serving_size": "large"}', null, 'completed', null, null, 1, 440.00],
            [$orderIds[5], $branchMenuIds[62], 1, 120.00, '{"serving_size": "four_pieces"}', null, 'completed', null, null, 2, 120.00],
            
            // Order 7 (ORD-IT-1001)
            [$orderIds[6], $branchMenuIds[88], 1, 300.00, '{"size": "medium", "crust": "thin"}', null, 'completed', null, null, 1, 300.00],
            [$orderIds[6], $branchMenuIds[93], 1, 280.00, '{"serving_size": "regular", "cheese": "parmesan"}', null, 'completed', null, null, 2, 280.00],
            [$orderIds[6], $branchMenuIds[98], 1, 180.00, '{"protein": "chicken"}', null, 'completed', null, null, 3, 180.00],
            
            // Order 8 (ORD-JP-1001)
            [$orderIds[7], $branchMenuIds[111], 2, 280.00, '{"serving_size": "eight_pieces"}', null, 'completed', null, null, 1, 560.00],
            [$orderIds[7], $branchMenuIds[117], 1, 250.00, '{"spice_level": "medium", "toppings": ["egg"]}', null, 'completed', null, null, 2, 250.00],
            [$orderIds[7], $branchMenuIds[121], 1, 200.00, '{"serving_size": "medium"}', null, 'completed', null, null, 3, 200.00],
            [$orderIds[7], $branchMenuIds[124], 1, 260.00, '{"serving_size": "large", "sauce": "teriyaki"}', null, 'completed', null, null, 4, 260.00]
        ];
        $stmt = $this->pdo->prepare("INSERT INTO OrderItems (order_id, branch_menu_id, quantity, price_at_order, selected_modifiers, notes, status, preparation_start_time, preparation_end_time, sequence_number, item_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($orderItems as $orderItem) {
            $stmt->execute($orderItem);
        }
    }
}
// Initialize database
$initializer = new DatabaseInitializer();
?>