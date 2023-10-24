FROM nginx
COPY uwsgi_nginx.conf /etc/nginx/conf.d/default.conf

#for dev only
RUN apt update && apt install -y curl joe mc