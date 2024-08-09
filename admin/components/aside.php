<aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
    <div class="p-6">
        <a href="/foodmarketbot/admin/"
           class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
    </div>
    <nav class="text-white text-base font-semibold pt-3">
        <a href="/foodmarketbot/admin/"
           class="<?= basename($_SERVER['SCRIPT_FILENAME']) === 'index.php' ? 'active-nav-link text-white' : 'text-white opacity-75 hover:opacity-100' ?> flex items-center py-4 pl-6 nav-item">
            Dashboard
        </a>
        <a href="/foodmarketbot/admin/pages/products.php"
           class="<?= basename($_SERVER['SCRIPT_FILENAME']) === 'products.php' ? 'active-nav-link text-white' : 'text-white opacity-75 hover:opacity-100' ?> flex items-center py-4 pl-6 nav-item">
            Products
        </a><a href="/foodmarketbot/admin/pages/categories.php"
               class="<?= basename($_SERVER['SCRIPT_FILENAME']) === 'categories.php' ? 'active-nav-link text-white' : 'text-white opacity-75 hover:opacity-100' ?> flex items-center py-4 pl-6 nav-item">
            Categories
        </a><a href="/foodmarketbot/admin/pages/orders.php"
               class="<?= basename($_SERVER['SCRIPT_FILENAME']) === 'orders.php' ? 'active-nav-link text-white' : 'text-white opacity-75 hover:opacity-100' ?> flex items-center py-4 pl-6 nav-item">
            Orders
        </a><a href="/foodmarketbot/admin/pages/users.php"
               class="<?= basename($_SERVER['SCRIPT_FILENAME']) === 'users.php' ? 'active-nav-link text-white' : 'text-white opacity-75 hover:opacity-100' ?> flex items-center py-4 pl-6 nav-item">
            Users
        </a><a href="/foodmarketbot/admin/pages/add_product_category.php"
               class="<?= basename($_SERVER['SCRIPT_FILENAME']) === 'add_product_category.php' ? 'active-nav-link text-white' : 'text-white opacity-75 hover:opacity-100' ?> flex items-center text-sm py-4 pl-6 nav-item">
            Add Product & Category
        </a>
    </nav>
</aside>