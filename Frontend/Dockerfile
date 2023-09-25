FROM node:14

WORKDIR /app

RUN npm install -g create-react-app

CMD ["npm", "start", "&", "tail", "-f", "/dev/null"]