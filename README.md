# Driving School

Complete system for driving school.

This project is being migrated from a legacy project made in 2011. Previously it was done in PHP, this new project is being done in TypeScript with the back-end in Node.js and the front-end with React.

In order to maintain the same database, it was necessary to maintain the same structure, so the name of the tables remains in Portuguese to maintain backward compatibility.

I thought about making the texts of the system already localized, with English and Portuguese, but as the system is very specific for the Brazilian market, I decided to keep it for now only with Portuguese, but we can change that if you want to help me adapt to other countries.

## Contributing

The project is divided into the backend and frontend packages, managed by [Lerna](https://lerna.js.org/). To start you run the follow command in project root dir:

```zsh
yarn bootstrap
```

### Back-end

To access the backend package:

```zsh
cd packages/backend
```

First, you need to configure the database. Using the Docker Compose, run the database with the command:

```zsh
docker-compose up
```

As the bank is going to be empty you need to perform the migration and populate with the seeds:

```zsh
yarn db:run:migrate
yarn db:run:seed
```

The default database is defined in `packages/backend/dev.sqlite3` and is not versioned by git.

Now just run the project:

```zsh
yarn start
```

### Front-end

To access the backend package:

```zsh
cd packages/frontend
```

Just run the project:

```zsh
yarn start
```

You can use the users:

| Login   | Password | Description                  |
| ------- | -------- | ---------------------------- |
| `admin` | `admin`  | User with all access granted |

### Troubleshooting

Fix invalid dates:

```sql
update alunos
set validadeprocesso = NULL
where '0000-00-00' = DATE_FORMAT(validadeprocesso,'%Y-%m-%d')
```
