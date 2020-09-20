FROM nginx

COPY backend/ /usr/share/nginx/html
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/sites/default.conf /etc/nginx/sites-available/default.conf