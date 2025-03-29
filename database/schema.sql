-- Users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    display_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    password_reset_required BOOLEAN DEFAULT 1,
    last_login DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Pages table
CREATE TABLE pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    is_landing_page BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Menus table
CREATE TABLE menus (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Menu items table
CREATE TABLE menu_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    menu_id INTEGER NOT NULL,
    parent_id INTEGER DEFAULT NULL,
    title VARCHAR(100) NOT NULL,
    page_id INTEGER DEFAULT NULL,
    external_url VARCHAR(255) DEFAULT NULL,
    order_index INTEGER NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL
);

-- Media table
CREATE TABLE media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INTEGER NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Settings table
CREATE TABLE settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key VARCHAR(100) NOT NULL UNIQUE,
    value TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default data
INSERT INTO pages (slug, title, content, is_landing_page) VALUES 
('home', 'Welcome to Huckabuild', '<div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white">
    <div class="container mx-auto px-4 py-20">
        <div class="flex flex-col lg:flex-row items-center justify-between">
            <div class="lg:w-1/2 mb-12 lg:mb-0">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Build Faster with <span class="text-[#ff7700]">Huckabuild</span>
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    A modern, lightweight PHP CMS powered by SQLite. Perfect for developers who want to build beautiful websites without the complexity.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/docs/getting-started" class="inline-flex items-center px-6 py-3 rounded-lg bg-[#ff7700] hover:bg-[#e66b00] text-white font-semibold transition-colors">
                        <i class="bi bi-rocket-takeoff mr-2"></i>Get Started
                    </a>
                    <a href="https://github.com/sam-huckaby/huckabuild" class="inline-flex items-center px-6 py-3 rounded-lg border border-white hover:bg-white/10 text-white font-semibold transition-colors">
                        <i class="bi bi-github mr-2"></i>View on GitHub
                    </a>
                </div>
            </div>
            <div class="lg:w-1/2 hidden lg:block text-center">
                <i class="bi bi-code-square text-[#ff7700] text-8xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Why Choose Huckabuild?</h2>
            <p class="text-xl text-gray-600">Everything you need to build modern websites, nothing you don''t.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <i class="bi bi-lightning-charge text-[#ff7700] text-5xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-3">Lightning Fast</h3>
                    <p class="text-gray-600">Built with performance in mind. SQLite database means no complex setup or configuration.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <i class="bi bi-shield-check text-[#ff7700] text-5xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-3">Secure by Default</h3>
                    <p class="text-gray-600">Modern security practices built-in. Protection against common vulnerabilities out of the box.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <i class="bi bi-brush text-[#ff7700] text-5xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-3">Beautiful Admin</h3>
                    <p class="text-gray-600">Intuitive admin interface that makes content management a breeze.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-20">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-gray-600 mb-8">Join developers who are building better websites with Huckabuild.</p>
        <a href="/docs/installation" class="inline-flex items-center px-8 py-4 rounded-lg bg-[#ff7700] hover:bg-[#e66b00] text-white font-semibold transition-colors">
            <i class="bi bi-download mr-2"></i>Install Huckabuild
        </a>
    </div>
</div>', 1);

INSERT INTO settings (key, value) VALUES
('theme_mode', 'light'),
('site_name', 'Huckabuild Site'); 