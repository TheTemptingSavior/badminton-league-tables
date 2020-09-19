FROM node:11.13.0-alpine

RUN mkdir -p /app
WORKDIR /app

# copy the app, note .dockerignore
COPY frontend /app/
RUN npm install
RUN npm run build

# expose 5000 on container
EXPOSE 5000
ENV NUXT_HOST=0.0.0.0
ENV NUXT_PORT=5000

# start the app
CMD [ "npm", "start" ]