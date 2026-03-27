web: exec supervisord -c supervisord.conf
worker: cd backend && php artisan queue:work --tries=3 --sleep=3 --timeout=60
workerInstant: cd backend && php artisan queue:work redis_instant --queue=instant --tries=1 --timeout=60 --sleep=0