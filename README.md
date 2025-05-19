
```
composer install
./vendor/bin/sail build
./vendor/bin/sail up -d
npm i
npm run dev
```

Чтобы запустить scheduler:

./vendor/bin/sail exec laravel.test php artisan schedule:work
