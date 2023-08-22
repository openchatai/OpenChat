FROM node:18-alpine as builder
WORKDIR /my-space

COPY package.json yarn.lock ./
RUN yarn install
COPY . .
RUN npm run build

FROM node:18-alpine as runner
WORKDIR /my-space
COPY --from=builder /my-space/package.json .
# COPY --from=builder /my-space/package-lock.json .
COPY --from=builder /my-space/next.config.js ./
# COPY --from=builder /my-space/public ./public
COPY --from=builder /my-space/.next/standalone ./
COPY --from=builder /my-space/.next/static ./.next/static

EXPOSE 3000
CMD ["node", "server.js"]
