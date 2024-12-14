generate JWT token via SSH
```
openssl genrsa -out private.pem 2048
openssl rsa -in private.pem -pubout -out public.pem

cd ../../ && sudo chmod -R 777 config/jwt/

