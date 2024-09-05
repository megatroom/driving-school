# Driving School

[![CircleCI](https://dl.circleci.com/status-badge/img/gh/megatroom/driving-school/tree/main.svg?style=svg)](https://dl.circleci.com/status-badge/redirect/gh/megatroom/driving-school/tree/main)

Complete system for driving school

## Getting Started

This project consists of 3 parts:

- **Legacy**: the old system. It is necessary to run it to upload the database, which will be used until the complete migration to the new system;
- **Storybook**: playground for the design system;
- **Next App**: New system built using the [Next.js](https://nextjs.org/) framework;

### Legacy

First create the configuration file in `legacy/sisautoescola/config.ini`:

```ini
[db]
host=legacy_database
database=sisautoescola
user=root
pwd=
```

Then run the project:

```bash
cd legacy
mkdir mysql_data
docker-compose up
```

Now you can access the URL: [http://localhost:5000/](http://localhost:5000/).

### Next App

First, install de dependencies:

```bash
npm install
```

Create the `.env` file with the following content:

```ini
SESSION_SECRET=
UNPLASH_ACCESS_KEY=
DB_DATABASE=sisautoescola
DB_HOST=localhost
DB_USER=root
DB_PASS=
```

To learn how to obtain these values, access the [Environment Variables](#environment-variables) topic.

Then run the development server:

```bash
npm run dev
```

Now you can access the URL: [http://localhost:3000/](http://localhost:3000/).

## Environment Variables

### `UNPLASH_ACCESS_KEY`

Access the [Unsplash Developers apps area](https://unsplash.com/oauth/applications), create or select the app for this project.

Go to `Keys` topic and get the `Access Key`.

### `SESSION_SECRET`

There are a few ways you can generate secret key to sign your session. For example, you may choose to use the openssl command in your terminal:

```bash
openssl rand -base64 32
```

This command generates a 32-character random string that you can use as your secret key.

## Deploying

To run th Next App in production you must run an optimized build:

```bash
npm run build
npm run start
```
