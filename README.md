# Driving School

Complete system for driving school

## Getting Started

This project consists of 3 parts:

- **Legacy**: the old system. It is necessary to run it to upload the database, which will be used until the complete migration to the new system;
- **Storybook**: playground for the design system;
- **Next App**: New system built using the [Next.js](https://nextjs.org/) framework;

### Legacy

To run the project:

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
UNPLASH_ACCESS_KEY=
DB_DATABASE=sisautoescola
DB_HOST=localhost
DB_USER=root
DB_PASS=
```

Then run the development server:

```bash
npm run dev
```

Now you can access the URL: [http://localhost:3000/](http://localhost:3000/).
