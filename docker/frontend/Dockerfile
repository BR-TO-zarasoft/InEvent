# ---- Build stage ----
FROM node:22-alpine AS build

WORKDIR /app

COPY frontend/package.json frontend/package-lock.json* /app/
RUN npm install

COPY frontend/ /app/
RUN npm run build

# ---- Runtime stage ----
FROM nginx:alpine

COPY docker/frontend/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build /app/dist /usr/share/nginx/html

EXPOSE 80
