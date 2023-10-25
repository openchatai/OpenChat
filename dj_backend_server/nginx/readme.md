# Generate private key
openssl genrsa -out nginx/ssl/privkey.pem 2048

# Generate certificate signing request
openssl req -new -key nginx/ssl/privkey.pem -out nginx/ssl/cert.csr

# Self-sign certificate 
openssl x509 -req -days 365 -in nginx/ssl/cert.csr -signkey nginx/ssl/privkey.pem -out nginx/ssl/cert.pem