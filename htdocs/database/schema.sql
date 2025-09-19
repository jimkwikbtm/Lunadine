-- Enable foreign key constraints
PRAGMA foreign_keys = ON;

-- Create tables in dependency order

-- 1. Translations
CREATE TABLE Translations (
    translation_key VARCHAR(255) NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    translation_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (translation_key, language_code)
);

-- 2. Roles
CREATE TABLE Roles (
    role_id INTEGER PRIMARY KEY AUTOINCREMENT,
    role_name VARCHAR(50) UNIQUE NOT NULL,
    permissions JSON,
    description TEXT,
    hierarchy_level INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8. Users
CREATE TABLE Users (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    role_id INTEGER NOT NULL,
    branch_id INTEGER,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    preferred_language VARCHAR(10) DEFAULT 'en-US',
    last_login TIMESTAMP,
    password_reset_token VARCHAR(255),
    password_reset_expires TIMESTAMP,
    failed_login_attempts INTEGER DEFAULT 0,
    account_locked_until TIMESTAMP,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES Roles(role_id),
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id)
);

-- 3. MenuCategories
CREATE TABLE MenuCategories (
    category_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name_translation_key VARCHAR(255) NOT NULL,
    description_translation_key VARCHAR(255),
    display_order INTEGER DEFAULT 0,
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT true,
    parent_category_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	created_by INTEGER,
    updated_by INTEGER,
	FOREIGN KEY (parent_category_id) REFERENCES MenuCategories(category_id),
    FOREIGN KEY (created_by) REFERENCES Users(user_id),
    FOREIGN KEY (updated_by) REFERENCES Users(user_id)
);

-- 4. MenuItems_Global
CREATE TABLE MenuItems_Global (
    item_id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER NOT NULL,
    sku VARCHAR(50) UNIQUE NOT NULL,
    name_translation_key VARCHAR(255) NOT NULL,
    description_translation_key VARCHAR(255),
    image_url VARCHAR(255),
    tags JSON,
    allergen_information JSON,
    preparation_time_minutes INTEGER,
    dietary_restrictions JSON,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	created_by INTEGER,
    updated_by INTEGER,
	FOREIGN KEY (category_id) REFERENCES MenuCategories(category_id),
    FOREIGN KEY (created_by) REFERENCES Users(user_id),
    FOREIGN KEY (updated_by) REFERENCES Users(user_id)
);

-- 5. Branches
CREATE TABLE Branches (
    branch_id INTEGER PRIMARY KEY AUTOINCREMENT,
    internal_name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    timezone VARCHAR(50) DEFAULT 'UTC',
    contact_email VARCHAR(100),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	created_by INTEGER,
    updated_by INTEGER,
    FOREIGN KEY (created_by) REFERENCES Users(user_id),
    FOREIGN KEY (updated_by) REFERENCES Users(user_id)
);

-- 6. BranchSettings
CREATE TABLE BranchSettings (
    branch_id INTEGER PRIMARY KEY,
    display_name_translation_key VARCHAR(255) NOT NULL,
    logo_url VARCHAR(255),
    cover_photo_url VARCHAR(255),
    phone_number VARCHAR(20),
    vat_percentage DECIMAL(5, 2) DEFAULT 0.00,
    service_charge_percentage DECIMAL(5, 2) DEFAULT 0.00,
    delivery_fee_config JSON,
    operating_hours JSON,
    minimum_order_amount DECIMAL(10, 2) DEFAULT 0.00,
    delivery_radius_km DECIMAL(5, 2),
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id) ON DELETE CASCADE
);

-- 7. Tables
CREATE TABLE Tables (
    table_id INTEGER PRIMARY KEY AUTOINCREMENT,
    branch_id INTEGER NOT NULL,
    table_identifier VARCHAR(50) NOT NULL,
    qr_code_hash VARCHAR(255) UNIQUE NOT NULL,
    capacity INTEGER DEFAULT 4,
    table_type VARCHAR(20) DEFAULT 'table',
    is_active BOOLEAN DEFAULT true,
    location_description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	created_by INTEGER,
    updated_by INTEGER,
	FOREIGN KEY (branch_id) REFERENCES Branches(branch_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES Users(user_id),
    FOREIGN KEY (updated_by) REFERENCES Users(user_id)
);



-- 9. BranchMenu
CREATE TABLE BranchMenu (
    branch_menu_id INTEGER PRIMARY KEY AUTOINCREMENT,
    branch_id INTEGER NOT NULL,
    item_id INTEGER NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    is_available BOOLEAN DEFAULT true,
    is_featured BOOLEAN DEFAULT false,
    preparation_time_override INTEGER,
    available_start_time TIME,
    available_end_time TIME,
    display_order INTEGER DEFAULT 0,
	customization_options JSON,
    inventory_count INTEGER DEFAULT -1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES MenuItems_Global(item_id),
    UNIQUE (branch_id, item_id)
);

-- 10. Promotions
CREATE TABLE Promotions (
    promo_id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(50) UNIQUE,
    description_translation_key VARCHAR(255) NOT NULL,
    type TEXT CHECK(type IN ('PERCENTAGE', 'FIXED_AMOUNT')) NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    min_order_value DECIMAL(10, 2),
    max_discount_amount DECIMAL(10, 2),
    usage_limit INTEGER,
    usage_count INTEGER DEFAULT 0,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    is_active BOOLEAN DEFAULT true,
    auto_apply BOOLEAN DEFAULT false,
    is_customer_visible BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	created_by INTEGER,
	updated_by INTEGER,
    FOREIGN KEY (created_by) REFERENCES Users(user_id),
    FOREIGN KEY (updated_by) REFERENCES Users(user_id)
);

-- 11. Promotion_Branches
CREATE TABLE Promotion_Branches (
    promo_id INTEGER NOT NULL,
    branch_id INTEGER NOT NULL,
    PRIMARY KEY (promo_id, branch_id),
    FOREIGN KEY (promo_id) REFERENCES Promotions(promo_id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id) ON DELETE CASCADE
);

-- 12. Promotion_Items
CREATE TABLE Promotion_Items (
    promo_id INTEGER NOT NULL,
    item_id INTEGER NOT NULL,
    PRIMARY KEY (promo_id, item_id),
    FOREIGN KEY (promo_id) REFERENCES Promotions(promo_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES MenuItems_Global(item_id) ON DELETE CASCADE
);

-- 13. BranchBanners
CREATE TABLE BranchBanners (
    banner_id INTEGER PRIMARY KEY AUTOINCREMENT,
    branch_id INTEGER NOT NULL,
    title_translation_key VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    target_url VARCHAR(255),
    background_color VARCHAR(7) DEFAULT '#FFFFFF',
    text_color VARCHAR(7) DEFAULT '#000000',
    banner_type VARCHAR(20) DEFAULT 'promotion',
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    is_active BOOLEAN DEFAULT true,
    display_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id) ON DELETE CASCADE
);

-- 14. Orders
CREATE TABLE Orders (
    order_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_uid VARCHAR(20) UNIQUE NOT NULL,
    branch_id INTEGER NOT NULL,
    table_id INTEGER,
    promo_id INTEGER,
    customer_id INTEGER,
    order_type TEXT CHECK(order_type IN ('dine-in', 'takeaway', 'delivery')) NOT NULL,
    status TEXT CHECK(status IN ('pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled')) NOT NULL,
    items_subtotal DECIMAL(10, 2) NOT NULL,
    discount_amount DECIMAL(10, 2) DEFAULT 0.00,
    subtotal_after_discount DECIMAL(10, 2) NOT NULL,
    service_charge_amount DECIMAL(10, 2) NOT NULL,
    vat_amount DECIMAL(10, 2) NOT NULL,
    delivery_charge_amount DECIMAL(10, 2) DEFAULT 0.00,
    final_amount DECIMAL(10, 2) NOT NULL,
    payment_status TEXT CHECK(payment_status IN ('unpaid', 'paid', 'refunded')) NOT NULL,
    payment_method TEXT,
    delivery_address TEXT,
    estimated_delivery_time TIMESTAMP,
    actual_delivery_time TIMESTAMP,
	pickup_time TIMESTAMP,
    staff_id INTEGER,
    notes TEXT,
    order_source TEXT DEFAULT 'qr_code',
    applied_rates_snapshot JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	estimated_preparation_time INTEGER,
    actual_preparation_time TIMESTAMP,
    staff_notes TEXT,
    customer_phone VARCHAR(20),
    order_priority TEXT DEFAULT 'normal' CHECK(order_priority IN ('low', 'normal', 'high', 'urgent')),
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id),
    FOREIGN KEY (table_id) REFERENCES Tables(table_id),
    FOREIGN KEY (promo_id) REFERENCES Promotions(promo_id),
    FOREIGN KEY (staff_id) REFERENCES Users(user_id)
);

-- 15. OrderItems
CREATE TABLE OrderItems (
    order_item_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    branch_menu_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price_at_order DECIMAL(10, 2) NOT NULL,
    selected_modifiers JSON,
    notes TEXT,
    status TEXT DEFAULT 'pending',
    preparation_start_time TIMESTAMP,
    preparation_end_time TIMESTAMP,
	customizations JSON,
    sequence_number INTEGER,
    item_total DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (branch_menu_id) REFERENCES BranchMenu(branch_menu_id)
);

-- Create ServiceRequests table
CREATE TABLE ServiceRequests (
    request_id INTEGER PRIMARY KEY AUTOINCREMENT,
    branch_id INTEGER NOT NULL,
    table_id INTEGER NOT NULL,
    request_type TEXT NOT NULL CHECK(request_type IN ('CALL_WAITER', 'REQUEST_BILL', 'CLEANING_ASSISTANCE', 'OTHER')),
    status TEXT NOT NULL CHECK(status IN ('PENDING', 'ACKNOWLEDGED', 'RESOLVED', 'CANCELLED')),
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    acknowledged_at TIMESTAMP,
    resolved_at TIMESTAMP,
    resolved_by_user_id INTEGER,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id),
    FOREIGN KEY (table_id) REFERENCES Tables(table_id),
    FOREIGN KEY (resolved_by_user_id) REFERENCES Users(user_id)
);


-- Create Feedback table (without customer_id)
CREATE TABLE Feedback (
    feedback_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER,
    branch_id INTEGER NOT NULL,
    rating INTEGER NOT NULL CHECK(rating >= 1 AND rating <= 5),
    feedback_text TEXT,
    service_quality_rating INTEGER CHECK(service_quality_rating >= 1 AND service_quality_rating <= 5),
    food_quality_rating INTEGER CHECK(food_quality_rating >= 1 AND food_quality_rating <= 5),
    ambiance_rating INTEGER CHECK(ambiance_rating >= 1 AND ambiance_rating <= 5),
    value_for_money_rating INTEGER CHECK(value_for_money_rating >= 1 AND value_for_money_rating <= 5),
    would_recommend BOOLEAN,
    tags JSON,
    response_text TEXT,
    responded_by_user_id INTEGER,
    responded_at TIMESTAMP,
    status TEXT NOT NULL DEFAULT 'PENDING' CHECK(status IN ('PENDING', 'REVIEWED', 'RESPONDED', 'RESOLVED')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id),
    FOREIGN KEY (responded_by_user_id) REFERENCES Users(user_id)
);


-- Create RestaurantDetails table
CREATE TABLE RestaurantDetails (
    restaurant_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    legal_name VARCHAR(100),
    description TEXT,
    tagline VARCHAR(255),
    logo_url VARCHAR(255),
    cover_photo_url VARCHAR(255),
    website_url VARCHAR(255),
    support_email VARCHAR(100),
    support_phone VARCHAR(20),
    tax_id VARCHAR(50),
    currency_code VARCHAR(3) DEFAULT 'BDT',
    timezone VARCHAR(50) DEFAULT 'Asia/Dhaka',
    social_media_links JSON,
    business_hours JSON,
    address TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for performance
CREATE INDEX idx_restaurant_active ON RestaurantDetails(is_active);

-- Insert Luna Dine restaurant details
INSERT INTO RestaurantDetails (
    name, 
    legal_name, 
    description, 
    tagline, 
    logo_url, 
    cover_photo_url, 
    website_url, 
    support_email, 
    support_phone, 
    tax_id, 
    currency_code, 
    timezone, 
    social_media_links, 
    business_hours, 
    address, 
    latitude, 
    longitude
) VALUES (
    'Luna Dine',
    'Luna Dine Restaurant Ltd.',
    'Experience culinary excellence under the moonlight. Luna Dine offers a unique dining experience with carefully crafted dishes that blend tradition and innovation.',
    'Dine Under the Stars',
    '/images/restaurant/luna-dine-logo.png',
    '/images/restaurant/luna-dine-cover.jpg',
    'https://lunadine.com',
    'hello@lunadine.com',
    '+8801234567890',
    'LUNA-TAX-2023-001',
    'BDT',
    'Asia/Dhaka',
    '{"facebook": "https://facebook.com/lunadine", "instagram": "https://instagram.com/lunadine", "twitter": "https://twitter.com/lunadine"}',
    '{"monday": "11:00-23:00", "tuesday": "11:00-23:00", "wednesday": "11:00-23:00", "thursday": "11:00-23:00", "friday": "11:00-23:00", "saturday": "11:00-00:00", "sunday": "11:00-23:00"}',
    'House 15, Road 7, Dhanmondi, Dhaka-1205, Bangladesh',
    23.746466,
    90.376015
);

-- Create indexes for performance
CREATE INDEX idx_feedback_branch ON Feedback(branch_id);
CREATE INDEX idx_feedback_order ON Feedback(order_id);
CREATE INDEX idx_feedback_rating ON Feedback(rating);
CREATE INDEX idx_feedback_status ON Feedback(status);
CREATE INDEX idx_feedback_created_at ON Feedback(created_at);


-- Create indexes for performance
CREATE INDEX idx_service_requests_branch ON ServiceRequests(branch_id);
CREATE INDEX idx_service_requests_table ON ServiceRequests(table_id);
CREATE INDEX idx_service_requests_status ON ServiceRequests(status);
CREATE INDEX idx_service_requests_requested_at ON ServiceRequests(requested_at);
CREATE INDEX idx_service_requests_resolved_by ON ServiceRequests(resolved_by_user_id);

-- Create indexes for performance
CREATE INDEX idx_branches_active ON Branches(is_active);
CREATE INDEX idx_users_active ON Users(is_active);
CREATE INDEX idx_menu_items_active ON MenuItems_Global(is_active);
CREATE INDEX idx_branch_menu_available ON BranchMenu(branch_id, is_available);
CREATE INDEX idx_promotions_active ON Promotions(is_active);
CREATE INDEX idx_orders_status ON Orders(status);
CREATE INDEX idx_orders_created ON Orders(created_at);
CREATE INDEX idx_order_items_order ON OrderItems(order_id);


-- Admin sessions table
CREATE TABLE AdminSessions (
    session_id VARCHAR(64) PRIMARY KEY,
    user_id INTEGER NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Create indexes for performance
CREATE INDEX idx_admin_sessions_user ON AdminSessions(user_id);
CREATE INDEX idx_admin_sessions_expires ON AdminSessions(expires_at);
CREATE INDEX idx_users_role ON Users(role_id);
CREATE INDEX idx_users_branch ON Users(branch_id);


-- Additional indexes for performance
CREATE INDEX idx_translations_key ON Translations(translation_key);
CREATE INDEX idx_translations_language ON Translations(language_code);
CREATE INDEX idx_menu_categories_parent ON MenuCategories(parent_category_id);
CREATE INDEX idx_menu_items_category ON MenuItems_Global(category_id);
CREATE INDEX idx_branch_menu_item ON BranchMenu(item_id);
CREATE INDEX idx_promotion_branches_branch ON Promotion_Branches(branch_id);
CREATE INDEX idx_promotion_items_item ON Promotion_Items(item_id);
CREATE INDEX idx_branch_banners_branch ON BranchBanners(branch_id);
CREATE INDEX idx_branch_banners_type ON BranchBanners(banner_type);
CREATE INDEX idx_orders_customer ON Orders(customer_id);
CREATE INDEX idx_order_items_branch_menu ON OrderItems(branch_menu_id);
CREATE INDEX idx_menu_categories_created_by ON MenuCategories(created_by);
CREATE INDEX idx_menu_items_created_by ON MenuItems_Global(created_by);
CREATE INDEX idx_branches_created_by ON Branches(created_by);
CREATE INDEX idx_tables_created_by ON Tables(created_by);
CREATE INDEX idx_promotions_created_by ON Promotions(created_by);

-- Create OrderTimeline table for tracking order events
CREATE TABLE OrderTimeline (
    timeline_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    event_type TEXT NOT NULL CHECK(event_type IN (
        'ORDER_PLACED', 'ORDER_CONFIRMED', 'ORDER_PREPARING', 'ORDER_READY',
        'TABLE_ASSIGNED', 'SERVER_ASSIGNED', 'FOOD_SERVING', 'DINING_STARTED',
        'BILL_REQUESTED', 'BILL_PAID', 'ORDER_COMPLETED', 'ORDER_CANCELLED',
        'DRIVER_ASSIGNED', 'DRIVER_PICKED_UP', 'DRIVER_IN_TRANSIT', 'DRIVER_ARRIVING',
        'DRIVER_ARRIVED', 'ORDER_DELIVERED', 'PICKUP_READY', 'PICKUP_COMPLETED',
        'PAYMENT_RECEIVED', 'PAYMENT_FAILED', 'DELAY_NOTIFICATION', 'STAFF_NOTE'
    )),
    event_description TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    staff_id INTEGER,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES Users(user_id)
);

-- Create DeliveryTracking table for delivery order tracking
CREATE TABLE DeliveryTracking (
    tracking_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    driver_name VARCHAR(100),
    driver_phone VARCHAR(20),
    driver_vehicle TEXT,
    driver_vehicle_plate VARCHAR(20),
    current_latitude DECIMAL(10, 8),
    current_longitude DECIMAL(11, 8),
    estimated_arrival_time TIMESTAMP,
    pickup_time TIMESTAMP,
    delivery_status TEXT DEFAULT 'assigned' CHECK(delivery_status IN (
        'assigned', 'picked_up', 'in_transit', 'arriving', 'arrived', 'delivered', 'failed'
    )),
    driver_rating INTEGER CHECK(driver_rating >= 1 AND driver_rating <= 5),
    driver_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
);

-- Create TableServiceStatus table for dine-in order tracking
CREATE TABLE TableServiceStatus (
    service_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    table_id INTEGER NOT NULL,
    server_id INTEGER,
    service_status TEXT DEFAULT 'assigned' CHECK(service_status IN (
        'assigned', 'preparing', 'serving', 'dining', 'billing', 'completed'
    )),
    estimated_serving_time TIMESTAMP,
    actual_serving_time TIMESTAMP,
    dining_start_time TIMESTAMP,
    dining_end_time TIMESTAMP,
    service_requests_count INTEGER DEFAULT 0,
    service_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (table_id) REFERENCES Tables(table_id),
    FOREIGN KEY (server_id) REFERENCES Users(user_id)
);

-- Create PickupQueue table for takeaway order tracking
CREATE TABLE PickupQueue (
    queue_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    pickup_counter INTEGER DEFAULT 1,
    queue_position INTEGER,
    estimated_ready_time TIMESTAMP,
    actual_ready_time TIMESTAMP,
    pickup_time TIMESTAMP,
    pickup_status TEXT DEFAULT 'waiting' CHECK(pickup_status IN (
        'waiting', 'preparing', 'ready', 'awaiting_pickup', 'picked_up', 'expired'
    )),
    customer_notified BOOLEAN DEFAULT false,
    notification_method TEXT DEFAULT 'app' CHECK(notification_method IN ('app', 'sms', 'call', 'none')),
    pickup_instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
);

-- Create OrderNotifications table for tracking customer notifications
CREATE TABLE OrderNotifications (
    notification_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    notification_type TEXT NOT NULL CHECK(notification_type IN (
        'STATUS_UPDATE', 'DELAY_ALERT', 'DRIVER_ASSIGNED', 'DRIVER_ARRIVING',
        'ORDER_READY', 'PICKUP_READY', 'PAYMENT_CONFIRMED', 'FEEDBACK_REQUEST',
        'PROMOTIONAL', 'SERVICE_REQUEST', 'BILL_READY'
    )),
    notification_title TEXT NOT NULL,
    notification_message TEXT NOT NULL,
    notification_data JSON,
    delivery_method TEXT DEFAULT 'app' CHECK(delivery_method IN ('app', 'sms', 'email', 'push')),
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'sent', 'delivered', 'read', 'failed')),
    scheduled_time TIMESTAMP,
    sent_time TIMESTAMP,
    delivered_time TIMESTAMP,
    read_time TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
);

-- Create OrderTrackingAnalytics table for performance tracking
CREATE TABLE OrderTrackingAnalytics (
    analytics_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    tracking_session_id VARCHAR(100),
    customer_device_type TEXT,
    customer_browser_info TEXT,
    page_load_time INTEGER,
    time_to_first_update INTEGER,
    total_tracking_time INTEGER,
    interaction_events JSON,
    notification_views INTEGER DEFAULT 0,
    notification_clicks INTEGER DEFAULT 0,
    map_views INTEGER DEFAULT 0,
    driver_contacts INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
);

-- Create indexes for performance
CREATE INDEX idx_ordertimeline_order ON OrderTimeline(order_id);
CREATE INDEX idx_ordertimeline_type ON OrderTimeline(event_type);
CREATE INDEX idx_ordertimeline_timestamp ON OrderTimeline(timestamp);
CREATE INDEX idx_ordertimeline_staff ON OrderTimeline(staff_id);

CREATE INDEX idx_deliverytracking_order ON DeliveryTracking(order_id);
CREATE INDEX idx_deliverytracking_status ON DeliveryTracking(delivery_status);
CREATE INDEX idx_deliverytracking_driver ON DeliveryTracking(driver_name);
CREATE INDEX idx_deliverytracking_estimated ON DeliveryTracking(estimated_arrival_time);

CREATE INDEX idx_tableservice_order ON TableServiceStatus(order_id);
CREATE INDEX idx_tableservice_table ON TableServiceStatus(table_id);
CREATE INDEX idx_tableservice_server ON TableServiceStatus(server_id);
CREATE INDEX idx_tableservice_status ON TableServiceStatus(service_status);

CREATE INDEX idx_pickupqueue_order ON PickupQueue(order_id);
CREATE INDEX idx_pickupqueue_position ON PickupQueue(queue_position);
CREATE INDEX idx_pickupqueue_status ON PickupQueue(pickup_status);
CREATE INDEX idx_pickupqueue_ready ON PickupQueue(estimated_ready_time);

CREATE INDEX idx_ordernotifications_order ON OrderNotifications(order_id);
CREATE INDEX idx_ordernotifications_type ON OrderNotifications(notification_type);
CREATE INDEX idx_ordernotifications_status ON OrderNotifications(status);
CREATE INDEX idx_ordernotifications_scheduled ON OrderNotifications(scheduled_time);

CREATE INDEX idx_orderanalytics_order ON OrderTrackingAnalytics(order_id);
CREATE INDEX idx_orderanalytics_session ON OrderTrackingAnalytics(tracking_session_id);

-- Update Orders table status check constraint to include new statuses
-- Note: SQLite doesn't support DROP CONSTRAINT, so we need to recreate the table
-- This will be handled in the migration script



-- Create triggers for automatic timeline events
CREATE TRIGGER order_placed_trigger
AFTER INSERT ON Orders
FOR EACH ROW
BEGIN
    INSERT INTO OrderTimeline (order_id, event_type, event_description)
    VALUES (NEW.order_id, 'ORDER_PLACED', 'Order was placed by customer');
END;

CREATE TRIGGER order_status_change_trigger
AFTER UPDATE OF status ON Orders
FOR EACH ROW
WHEN OLD.status != NEW.status
BEGIN
    INSERT INTO OrderTimeline (order_id, event_type, event_description, metadata)
    VALUES (
        NEW.order_id, 
        'ORDER_' || UPPER(NEW.status),
        'Order status changed from ' || OLD.status || ' to ' || NEW.status,
        json_object('old_status', OLD.status, 'new_status', NEW.status)
    );
END;

-- Create trigger for delivery tracking
CREATE TRIGGER delivery_order_trigger
AFTER INSERT ON Orders
FOR EACH ROW
WHEN NEW.order_type = 'delivery'
BEGIN
    INSERT INTO DeliveryTracking (order_id, delivery_status)
    VALUES (NEW.order_id, 'assigned');
END;

-- Create trigger for table service tracking
CREATE TRIGGER dinein_order_trigger
AFTER INSERT ON Orders
FOR EACH ROW
WHEN NEW.order_type = 'dine-in' AND NEW.table_id IS NOT NULL
BEGIN
    INSERT INTO TableServiceStatus (order_id, table_id, service_status)
    VALUES (NEW.order_id, NEW.table_id, 'assigned');
END;

-- Create trigger for pickup queue tracking
CREATE TRIGGER takeaway_order_trigger
AFTER INSERT ON Orders
FOR EACH ROW
WHEN NEW.order_type = 'takeaway'
BEGIN
    INSERT INTO PickupQueue (order_id, pickup_status)
    VALUES (NEW.order_id, 'waiting');
END;