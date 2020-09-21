FROM node:11.13.0-alpine

RUN mkdir -p /app
WORKDIR /app

# copy the app, note .dockerignore
COPY frontend /app/
RUN npm install
RUN npm run build

# expose 5000 on container
EXPOSE 8080

# start the app
CMD [ "npm", "run", "serve"]