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
            ['app_name', 'en-US', 'Restaurant Manager'],
            ['app_name', 'bn-BD', 'রেস্টুরেন্ট ম্যানেজার'],
            
            // Category translations
            ['category_appetizers', 'en-US', 'Appetizers'],
            ['category_appetizers', 'bn-BD', 'অ্যাপিটাইজার'],
            ['category_main_course', 'en-US', 'Main Course'],
            ['category_main_course', 'bn-BD', 'মূল খাবার'],
            ['category_beverages', 'en-US', 'Beverages'],
            ['category_beverages', 'bn-BD', 'পানীয়'],
            ['category_desserts', 'en-US', 'Desserts'],
            ['category_desserts', 'bn-BD', 'মিষ্টি'],
            
            // Item translations
            ['item_samosa', 'en-US', 'Samosa'],
            ['item_samosa', 'bn-BD', 'সমোসা'],
            ['item_samosa_desc', 'en-US', 'Crispy pastry with spiced potatoes and peas'],
            ['item_samosa_desc', 'bn-BD', 'মসলাদার আলু এবং মটরশুঁটি দিয়ে তৈরি কুরকুরে পেস্ট্রি'],
            
            ['item_biryani', 'en-US', 'Chicken Biryani'],
            ['item_biryani', 'bn-BD', 'চিকেন বিরিয়ানি'],
            ['item_biryani_desc', 'en-US', 'Fragrant basmati rice with spiced chicken'],
            ['item_biryani_desc', 'bn-BD', 'মসলাদার মুরগির সাথে সুগন্ধি বাসমতি চাল'],
            
            ['item_coke', 'en-US', 'Coca-Cola'],
            ['item_coke', 'bn-BD', 'কোকা-কোলা'],
            ['item_coke_desc', 'en-US', 'Refreshing carbonated soft drink'],
            ['item_coke_desc', 'bn-BD', 'সতেজ কার্বনেটেড সফট ড্রিংক'],
            
            ['item_ice_cream', 'en-US', 'Vanilla Ice Cream'],
            ['item_ice_cream', 'bn-BD', 'ভ্যানিলা আইসক্রিম'],
            ['item_ice_cream_desc', 'en-US', 'Creamy vanilla ice cream with chocolate sauce'],
            ['item_ice_cream_desc', 'bn-BD', 'চকোলেট সস সহ ক্রিমি ভ্যানিলা আইসক্রিম'],
            
            // Branch translations
            ['branch_dhanmondi_name', 'en-US', 'Dhanmondi Branch'],
            ['branch_dhanmondi_name', 'bn-BD', 'ধানমন্ডি শাখা'],
            ['branch_gulshan_name', 'en-US', 'Gulshan Branch'],
            ['branch_gulshan_name', 'bn-BD', 'গুলশান শাখা'],
            
            // Promotion translations
            ['promo_weekend_discount', 'en-US', 'Weekend Special Discount'],
            ['promo_weekend_discount', 'bn-BD', 'সাপ্তাহিক বিশেষ ছাড়'],
            ['promo_weekend_discount_desc', 'en-US', '15% off on all items during weekends'],
            ['promo_weekend_discount_desc', 'bn-BD', 'সাপ্তাহিক ছুটিতে সমস্ত আইটেমে 15% ছাড়'],
            
            // Banner translations
            ['banner_new_menu', 'en-US', 'Try Our New Menu'],
            ['banner_new_menu', 'bn-BD', 'আমাদের নতুন মেনু চেষ্টা করুন'],
            ['banner_new_menu_desc', 'en-US', 'Exciting new dishes now available'],
            ['banner_new_menu_desc', 'bn-BD', 'এখন উত্তেজনাপূর্ণ নতুন খাবার পাওয়া যাচ্ছে'],
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
            ['category_appetizers', 'category_appetizers_desc', 1, '/images/categories/appetizers.jpg', null, true],
            ['category_main_course', 'category_main_course_desc', 2, '/images/categories/main-course.jpg', null, true],
            ['category_beverages', 'category_beverages_desc', 3, '/images/categories/beverages.jpg', null, true],
            ['category_desserts', 'category_desserts_desc', 4, '/images/categories/desserts.jpg', null, true]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO MenuCategories (name_translation_key, description_translation_key, display_order, image_url, parent_category_id, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $categoryIds = [];
        foreach ($categories as $category) {
            $stmt->execute($category);
            $categoryIds[] = $this->pdo->lastInsertId();
        }

        // Seed Menu Items
        $menuItems = [
            // Appetizers
            [$categoryIds[0], 'SAMOSA-001', 'item_samosa', 'item_samosa_desc', '/images/items/samosa.jpg', '["Vegetarian", "Spicy"]', '["Gluten"]', 10, '["Vegetarian"]', true],
            [$categoryIds[0], 'PANI-001', 'item_pani_puri', 'item_pani_puri_desc', '/images/items/pani-puri.jpg', '["Vegetarian", "Street Food"]', '["Gluten"]', 5, '["Vegetarian"]', true],
            
            // Main Course
            [$categoryIds[1], 'BIRYANI-001', 'item_biryani', 'item_biryani_desc', '/images/items/biryani.jpg', '["Spicy", "Rice Dish"]', '["Dairy", "Nuts"]', 25, '["Halal"]', true],
            [$categoryIds[1], 'CURRY-001', 'item_chicken_curry', 'item_chicken_curry_desc', '/images/items/chicken-curry.jpg', '["Spicy", "Curry"]', '["Dairy"]', 20, '["Halal"]', true],
            
            // Beverages
            [$categoryIds[2], 'COKE-330', 'item_coke', 'item_coke_desc', '/images/items/coke.jpg', '["Cold", "Soft Drink"]', null, 2, null, true],
            [$categoryIds[2], 'MANGO-001', 'item_mango_lassi', 'item_mango_lassi_desc', '/images/items/mango-lassi.jpg', '["Cold", "Dairy"]', '["Dairy"]', 3, '["Vegetarian"]', true],
            
            // Desserts
            [$categoryIds[3], 'ICECREAM-001', 'item_ice_cream', 'item_ice_cream_desc', '/images/items/ice-cream.jpg', '["Cold", "Sweet"]', '["Dairy"]', 5, '["Vegetarian"]', true],
            [$categoryIds[3], 'KHEER-001', 'item_rice_pudding', 'item_rice_pudding_desc', '/images/items/rice-pudding.jpg', '["Sweet", "Traditional"]', '["Dairy"]', 10, '["Vegetarian"]', true]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO MenuItems_Global (category_id, sku, name_translation_key, description_translation_key, image_url, tags, allergen_information, preparation_time_minutes, dietary_restrictions, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $itemIds = [];
        foreach ($menuItems as $item) {
            $stmt->execute($item);
            $itemIds[] = $this->pdo->lastInsertId();
        }

        // Seed Branches
        $branches = [
            ['Dhanmondi_01', 'House 12, Road 8, Dhanmondi, Dhaka', 23.746466, 90.376015, 'Asia/Dhaka', 'dhanmondi@restaurant.com', true],
            ['Gulshan_01', 'Plot 15, Block C, Gulshan-1, Dhaka', 23.781034, 90.414415, 'Asia/Dhaka', 'gulshan@restaurant.com', true]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO Branches (internal_name, address, latitude, longitude, timezone, contact_email, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $branchIds = [];
        foreach ($branches as $branch) {
            $stmt->execute($branch);
            $branchIds[] = $this->pdo->lastInsertId();
        }

        // Seed Branch Settings
        $branchSettings = [
            // Dhanmondi Branch
            [
                $branchIds[0], 
                'branch_dhanmondi_name', 
                '/images/logos/dhanmondi-logo.jpg', 
                '/images/covers/dhanmondi-cover.jpg',
                '+8801234567890', 
                15.00, 
                10.00, 
                '{"type": "fixed", "amount": 60.00}', 
                '{"monday": "11:00-23:00", "tuesday": "11:00-23:00", "wednesday": "11:00-23:00", "thursday": "11:00-23:00", "friday": "11:00-23:00", "saturday": "11:00-00:00", "sunday": "11:00-23:00"}',
                200.00,
                5.0
            ],
            // Gulshan Branch
            [
                $branchIds[1], 
                'branch_gulshan_name', 
                '/images/logos/gulshan-logo.jpg', 
                '/images/covers/gulshan-cover.jpg',
                '+8801987654321', 
                15.00, 
                10.00, 
                '{"type": "distance", "base_amount": 40.00, "per_km": 10.00}', 
                '{"monday": "11:00-23:00", "tuesday": "11:00-23:00", "wednesday": "11:00-23:00", "thursday": "11:00-23:00", "friday": "11:00-23:00", "saturday": "11:00-00:00", "sunday": "11:00-23:00"}',
                300.00,
                7.0
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
            [3, $branchIds[0], 'Dhanmondi Manager', 'dhanmondi_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager.dhanmondi@restaurant.com', 'en-US', true],
            [3, $branchIds[1], 'Gulshan Manager', 'gulshan_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager.gulshan@restaurant.com', 'en-US', true],
            // Chefs
            [4, $branchIds[0], 'Dhanmondi Chef', 'dhanmondi_chef', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chef.dhanmondi@restaurant.com', 'en-US', true],
            [4, $branchIds[1], 'Gulshan Chef', 'gulshan_chef', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chef.gulshan@restaurant.com', 'en-US', true],
            // Cashiers
            [5, $branchIds[0], 'Dhanmondi Cashier', 'dhanmondi_cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier.dhanmondi@restaurant.com', 'en-US', true],
            [5, $branchIds[1], 'Gulshan Cashier', 'gulshan_cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier.gulshan@restaurant.com', 'en-US', true],
            // Waiters
            [6, $branchIds[0], 'Dhanmondi Waiter 1', 'dhanmondi_waiter1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter1.dhanmondi@restaurant.com', 'en-US', true],
            [6, $branchIds[0], 'Dhanmondi Waiter 2', 'dhanmondi_waiter2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter2.dhanmondi@restaurant.com', 'en-US', true],
            [6, $branchIds[1], 'Gulshan Waiter 1', 'gulshan_waiter1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter1.gulshan@restaurant.com', 'en-US', true],
            [6, $branchIds[1], 'Gulshan Waiter 2', 'gulshan_waiter2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'waiter2.gulshan@restaurant.com', 'en-US', true]
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
            // Samosa (Appetizer)
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
            
            // Pani Puri (Appetizer)
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
            
            // Biryani (Main Course)
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
            
            // Chicken Curry (Main Course)
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
            
            // Coke (Beverage)
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
            
            // Ice Cream (Dessert)
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
                                "id" => "single_scoop",
                                "name" => [
                                    "en-US" => "Single Scoop",
                                    "bn-BD" => "এক স্কুপ"
                                ],
                                "price_adjustment" => 0
                            ],
                            [
                                "id" => "double_scoop",
                                "name" => [
                                    "en-US" => "Double Scoop",
                                    "bn-BD" => "দুই স্কুপ"
                                ],
                                "price_adjustment" => 40
                            ]
                        ]
                    ],
                    [
                        "id" => "toppings_group",
                        "name" => [
                            "en-US" => "Toppings",
                            "bn-BD" => "টপিংস"
                        ],
                        "type" => "checkbox",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "chocolate_sauce",
                                "name" => [
                                    "en-US" => "Chocolate Sauce",
                                    "bn-BD" => "চকোলেট সস"
                                ],
                                "price_adjustment" => 10
                            ],
                            [
                                "id" => "nuts",
                                "name" => [
                                    "en-US" => "Nuts",
                                    "bn-BD" => "বাদাম"
                                ],
                                "price_adjustment" => 15
                            ],
                            [
                                "id" => "whipped_cream",
                                "name" => [
                                    "en-US" => "Whipped Cream",
                                    "bn-BD" => "হুইপড ক্রিম"
                                ],
                                "price_adjustment" => 10
                            ]
                        ]
                    ]
                ]
            ]),
            
            // Rice Pudding (Dessert)
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
                    ],
                    [
                        "id" => "toppings_group",
                        "name" => [
                            "en-US" => "Toppings",
                            "bn-BD" => "টপিংস"
                        ],
                        "type" => "checkbox",
                        "required" => false,
                        "options" => [
                            [
                                "id" => "nuts",
                                "name" => [
                                    "en-US" => "Nuts",
                                    "bn-BD" => "বাদাম"
                                ],
                                "price_adjustment" => 15
                            ],
                            [
                                "id" => "raisins",
                                "name" => [
                                    "en-US" => "Raisins",
                                    "bn-BD" => "কিশমিশ"
                                ],
                                "price_adjustment" => 10
                            ]
                        ]
                    ]
                ]
            ])
        ];
        
        foreach ($branchIds as $branchIndex => $branchId) {
            foreach ($itemIds as $itemIndex => $itemId) {
                // Different prices for different branches
                $price = ($branchIndex === 0) ? [50, 30, 250, 200, 40, 80, 100, 150][$itemIndex] : [55, 35, 280, 220, 45, 85, 110, 160][$itemIndex];
                
                // All items are available in all branches now
                $branchMenuItems[] = [
                    $branchId,
                    $itemId,
                    $price,
                    true, // is_available - set all to active
                    ($itemIndex < 2), // First two items are featured
                    null, // preparation_time_override
                    null, // available_start_time
                    null, // available_end_time
                    $itemIndex, // display_order
                    -1, // inventory_count (-1 means unlimited)
                    $customizationOptions[$itemIndex] // customization_options with correct JSON format
                ];
            }
        }

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
                $userIds[1] // Created by Dhanmondi Manager
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
                $userIds[2] // Created by Gulshan Manager
            ]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO Promotions (code, description_translation_key, type, value, min_order_value, max_discount_amount, usage_limit, usage_count, start_date, end_date, is_active, auto_apply, is_customer_visible, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $promoIds = [];
        foreach ($promotions as $promo) {
            $stmt->execute($promo);
            $promoIds[] = $this->pdo->lastInsertId();
        }

        // Seed Promotion Scopes
        // Weekend promotion applies to both branches and specific items
        $promotionBranches = [
            [$promoIds[0], $branchIds[0]],
            [$promoIds[0], $branchIds[1]],
            [$promoIds[1], $branchIds[0]],
            [$promoIds[1], $branchIds[1]]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO Promotion_Branches (promo_id, branch_id) VALUES (?, ?)");
        foreach ($promotionBranches as $promoBranch) {
            $stmt->execute($promoBranch);
        }

        $promotionItems = [
            // Weekend promotion applies to main course and desserts
            [$promoIds[0], $itemIds[2]], // Biryani
            [$promoIds[0], $itemIds[3]], // Chicken Curry
            [$promoIds[0], $itemIds[6]], // Ice Cream
            [$promoIds[0], $itemIds[7]], // Rice Pudding
            
            // First order promotion applies to all items
            [$promoIds[1], $itemIds[0]], // Samosa
            [$promoIds[1], $itemIds[1]], // Pani Puri
            [$promoIds[1], $itemIds[2]], // Biryani
            [$promoIds[1], $itemIds[3]], // Chicken Curry
            [$promoIds[1], $itemIds[4]], // Coke
            [$promoIds[1], $itemIds[5]], // Mango Lassi
            [$promoIds[1], $itemIds[6]], // Ice Cream
            [$promoIds[1], $itemIds[7]]  // Rice Pudding
        ];

        $stmt = $this->pdo->prepare("INSERT INTO Promotion_Items (promo_id, item_id) VALUES (?, ?)");
        foreach ($promotionItems as $promoItem) {
            $stmt->execute($promoItem);
        }

        // Seed Branch Banners
        $banners = [
            // Dhanmondi banners
            [
                $branchIds[0],
                'banner_new_menu',
                '/images/banners/dhanmondi-new-menu.jpg',
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
                '/images/banners/dhanmondi-weekend.jpg',
                '/promotions',
                '#33FF57',
                '#000000',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+7 days')),
                true, // is_active
                2
            ],
            
            // Gulshan banners
            [
                $branchIds[1],
                'banner_new_branch',
                '/images/banners/gulshan-opening.jpg',
                '/about',
                '#3357FF',
                '#FFFFFF',
                'announcement',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+30 days')),
                true, // is_active
                1
            ],
            [
                $branchIds[1],
                'banner_delivery_offer',
                '/images/banners/gulshan-delivery.jpg',
                '/delivery',
                '#F3FF33',
                '#000000',
                'promotion',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', strtotime('+10 days')),
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
            // Dhanmondi orders
            [
                'ORD-DHN-1001',
                $branchIds[0],
                1, // Table 1
                $promoIds[0], // Weekend promotion
                null, // customer_id
                'dine-in',
                'completed',
                680.00, // items_subtotal
                102.00, // discount_amount
                578.00, // subtotal_after_discount
                57.80, // service_charge_amount
                86.70, // vat_amount
                60.00, // delivery_charge_amount
                782.50, // final_amount
                'paid',
                'card',
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[7], // staff_id (Dhanmondi Waiter 1)
                'Extra spicy please',
                'qr_code',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}'
            ],
            [
                'ORD-DHN-1002',
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
                $userIds[8], // staff_id (Dhanmondi Waiter 2)
                'No onions',
                'app',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}'
            ],
            
            // Gulshan orders
            [
                'ORD-GUL-1001',
                $branchIds[1],
                null, // No table (delivery)
                $promoIds[1], // First order promotion
                null, // customer_id
                'delivery',
                'completed',
                500.00, // items_subtotal (corrected)
                100.00, // discount_amount
                400.00, // subtotal_after_discount (corrected)
                40.00, // service_charge_amount (corrected)
                60.00, // vat_amount (corrected)
                70.00, // delivery_charge_amount
                570.00, // final_amount (corrected)
                'paid',
                'mobile_payment',
                'House 25, Road 10, Gulshan-2, Dhaka',
                date('Y-m-d H:i:s', strtotime('+1 hour')),
                date('Y-m-d H:i:s', strtotime('+1 hour 30 minutes')),
                $userIds[9], // staff_id (Gulshan Waiter 1)
                'Call upon arrival',
                'website',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}'
            ],
            [
                'ORD-GUL-1002',
                $branchIds[1],
                3, // Table 3
                null, // No promotion
                null, // customer_id
                'dine-in',
                'preparing',
                870.00, // items_subtotal
                0.00, // discount_amount
                870.00, // subtotal_after_discount
                87.00, // service_charge_amount
                130.50, // vat_amount
                0.00, // delivery_charge_amount
                1087.50, // final_amount
                'unpaid',
                null,
                null, // delivery_address
                null, // estimated_delivery_time
                null, // actual_delivery_time
                $userIds[10], // staff_id (Gulshan Waiter 2)
                'Birthday celebration',
                'qr_code',
                '{"vat_pct": 15.0, "service_charge_pct": 10.0}'
            ]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO Orders (order_uid, branch_id, table_id, promo_id, customer_id, order_type, status, items_subtotal, discount_amount, subtotal_after_discount, service_charge_amount, vat_amount, delivery_charge_amount, final_amount, payment_status, payment_method, delivery_address, estimated_delivery_time, actual_delivery_time, staff_id, notes, order_source, applied_rates_snapshot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $orderIds = [];
        foreach ($orders as $order) {
            $stmt->execute($order);
            $orderIds[] = $this->pdo->lastInsertId();
        }

        // Seed Order Items
        $orderItems = [
            // Order 1 (ORD-DHN-1001)
            [$orderIds[0], $branchMenuIds[2], 2, 250.00, '{"spice_level": "extra"}', null, 'completed', null, null, 1, 500.00],
            [$orderIds[0], $branchMenuIds[3], 1, 200.00, '{}', null, 'completed', null, null, 2, 200.00],
            [$orderIds[0], $branchMenuIds[4], 2, 40.00, '{}', null, 'completed', null, null, 3, 80.00],
            [$orderIds[0], $branchMenuIds[6], 1, 100.00, '{}', null, 'completed', null, null, 4, 100.00],
            
            // Order 2 (ORD-DHN-1002)
            [$orderIds[1], $branchMenuIds[0], 2, 50.00, '{"no_onions": true}', null, 'completed', null, null, 1, 100.00],
            [$orderIds[1], $branchMenuIds[5], 2, 80.00, '{}', null, 'completed', null, null, 2, 160.00],
            [$orderIds[1], $branchMenuIds[7], 1, 150.00, '{}', null, 'completed', null, null, 3, 150.00],
            
            // Order 3 (ORD-GUL-1001) - Fixed indices
            [$orderIds[2], $branchMenuIds[8], 1, 55.00, '{}', null, 'completed', null, null, 1, 55.00],
            [$orderIds[2], $branchMenuIds[9], 2, 35.00, '{}', null, 'completed', null, null, 2, 70.00],
            [$orderIds[2], $branchMenuIds[10], 1, 220.00, '{}', null, 'completed', null, null, 3, 220.00], // Fixed: was 11
            [$orderIds[2], $branchMenuIds[13], 1, 110.00, '{}', null, 'completed', null, null, 4, 110.00],
            [$orderIds[2], $branchMenuIds[14], 1, 45.00, '{}', null, 'completed', null, null, 5, 45.00],
            
            // Order 4 (ORD-GUL-1002)
            [$orderIds[3], $branchMenuIds[8], 3, 55.00, '{}', null, 'preparing', null, null, 1, 165.00],
            [$orderIds[3], $branchMenuIds[10], 2, 220.00, '{}', null, 'pending', null, null, 2, 440.00], // Fixed: was 11
            [$orderIds[3], $branchMenuIds[13], 1, 110.00, '{}', null, 'pending', null, null, 3, 110.00],
            [$orderIds[3], $branchMenuIds[14], 1, 45.00, '{}', null, 'pending', null, null, 4, 45.00]
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