FROM nginx:latest
WORKDIR /var/www/html

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY backend/ /var/www/html/api
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]