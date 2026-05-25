#!/bin/bash

echo "🚀 Memulai inisialisasi setup proyek..."

# 1. Menyiapkan Environment Variables
if [ ! -f .env ]; then
    echo "📝 Menyalin .env.example ke .env..."
    cp .env.example .env
else
    echo "✅ File .env sudah tersedia."
fi

# 2. Menginstal Dependensi Backend (PHP/Laravel)
echo "📦 Menginstal dependensi Composer..."
composer install --optimize-autoloader

# 3. Menghasilkan Application Key
echo "🔑 Men-generate Application Key..."
php artisan key:generate

# 4. Menginstal Dependensi Frontend (Hanya Install, tanpa Build)
echo "🎨 Menginstal dependensi NPM..."
npm install

# 5. Mengatur Permission (Krusial agar tidak error HTTP 500)
echo "🔐 Mengatur hak akses folder storage dan bootstrap/cache..."
chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache 2>/dev/null || true

# 6. Membersihkan Cache
echo "🧹 Membersihkan cache Laravel..."
php artisan optimize:clear

echo "✨ Setup awal selesai! Lanjutkan dengan menyalakan Docker."