FROM nginx:alpine

# Копируем конфиг внутрь контейнера
COPY nginx.conf /etc/nginx/nginx.conf

# Копируем статические страницы
COPY html/ /usr/share/nginx/html/

EXPOSE 80