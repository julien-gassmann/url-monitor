FROM node:lts

# Install pnpm
RUN npm install -g pnpm

# Copy entrypoint
COPY docker/node/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

WORKDIR /var/www/html/app

ENTRYPOINT ["/entrypoint.sh"]
