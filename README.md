#  Exam

Description of the project.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Installation

1. Clone the repository:
    ```bash
    https://github.com/protsykhome/Exam.git

2. Navigate to the project directory:
    ```bash
    cd projectname
    ```

3. Install dependencies:
    ```bash
    composer install
    ```

4. Set up the environment variables:
    - Copy the `.env.example` file to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Update the database connection details in the `.env` file according to your environment.

5. Start the Docker containers:
    ```bash
    docker-compose up --build
    ```

6. Access the application in your web browser at `http://localhost:8080`.

## Usage

In routes.yaml you can find all endpoints to test api.

## Contributing

Describe how others can contribute to the project.

## License

This project is licensed under the [License Name] License - see the [LICENSE.md](LICENSE.md) file for details.
