# myWari_api
fixture : bin/console doctrine:fixtures:load
composer : composer install
doctrine : bin/console d:d:c
           bin/console d:s:u --force
           
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem