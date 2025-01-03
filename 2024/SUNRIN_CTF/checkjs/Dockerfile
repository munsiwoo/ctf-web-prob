FROM node:18-alpine

WORKDIR /usr/src/app

COPY ./app .
RUN chmod 444 /usr/src/app/flag

RUN npm init -y
RUN npm install fastify @fastify/cors @fastify/formbody axios

EXPOSE 3000
CMD ["node", "index.js"]