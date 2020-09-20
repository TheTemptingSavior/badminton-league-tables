FROM node:lts-alpine

# install simple http server for serving static content
RUN npm install -g http-server

# make the 'app' folder the current working directory
WORKDIR /app

# copy both 'package.json' and 'package-lock.json' (if available)
COPY package*.json ./

# install project dependencies
RUN npm install

# copy project files and folders to the current working directory (i.e. 'app' folder)
COPY fronted/ .

# build app for production with minification
RUN npm run build

EXPOSE 8080
CMD [ "http-server", "dist" ]


#FROM node:11.13.0-alpine
#
#RUN mkdir -p /app
#WORKDIR /app
#
## copy the app, note .dockerignore
#COPY frontend /app/
#RUN npm install
#RUN npm run build
#
## expose 5000 on container
#EXPOSE 5000
#ENV NUXT_HOST=0.0.0.0
#ENV NUXT_PORT=5000
#
## start the app
#CMD [ "npm", "start" ]